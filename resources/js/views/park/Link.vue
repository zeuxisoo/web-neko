<script setup lang="ts">
import api from '@/api';
import { Button } from '@/components/base/button';
import { Card, CardContent } from '@/components/base/card';
import { InputGroup, InputGroupAddon, InputGroupInput } from '@/components/base/input-group';
import { EmptyState, Header } from '@/components/page';
import { WhoopsHandler } from '@/utils';
import { ChevronLeft, ChevronRight, ExternalLink, Search, X } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const router = useRouter();
const route = useRoute();

const isLoading = ref(true);
const links = ref<PulseLinkIndexResponse>();
const keyword = ref('');

const fetchLinks = async () => {
    isLoading.value = true;

    try {
        const page = Number(router.currentRoute.value.query.page) || 1;
        const searchKeyword = keyword.value;

        const { data, error } = await api.pulse.link.index({ page, keyword: searchKeyword }).json<PulseLinkIndexResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data && data.value) {
            links.value = data.value;
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error when fetch link list action in link page');
    } finally {
        isLoading.value = false;
    }
};

const handlePrev = () => {
    if (!links.value?.links.prev || !links.value?.meta) return;

    const prevPage = (links.value.meta.current_page ?? 1) - 1;

    router.push({
        name: 'park.link',
        query: {
            ...router.currentRoute.value.query,
            page: prevPage,
        },
    });
};

const handleNext = () => {
    if (!links.value?.links.next || !links.value?.meta) return;

    const nextPage = (links.value.meta.current_page ?? 1) + 1;

    router.push({
        name: 'park.link',
        query: {
            ...router.currentRoute.value.query,
            page: nextPage,
        },
    });
};

const handleOpenLink = (url: string) => {
    window.open(url, '_blank');
};

const handleSearch = () => {
    router.push({
        name: 'park.link',
        query: {
            ...router.currentRoute.value.query,
            page: 1,
            keyword: keyword.value || undefined,
        },
    });
};

const handleSearchInput = (e: InputEvent) => {
    const target = e.target as HTMLInputElement;

    keyword.value = target.value;
};

const handleSearchClear = () => {
    keyword.value = '';

    router.push({
        name: 'park.link',
        query: {
            ...router.currentRoute.value.query,
            page: 1,
            keyword: undefined,
        },
    });
};

watch(
    [() => route.query.page, () => route.query.keyword],
    () => {
        keyword.value = route.query.keyword as string;

        fetchLinks();
    },
    { immediate: true },
);
</script>

<template>
    <div class="link grid gap-3">
        <template v-if="links">
            <Header #main>
                <h1 class="text-2xl font-semibold tracking-tight">Links</h1>
                <span
                    v-if="links.data.length > 0"
                    class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-primary"
                >
                    {{ links.data.length }}
                </span>
            </Header>

            <Card class="py-2">
                <CardContent>
                    <div class="flex items-center gap-2">
                        <InputGroup>
                            <InputGroupInput v-model="keyword" placeholder="Search links..." @input="handleSearchInput" @keyup.enter="handleSearch" />
                            <InputGroupAddon>
                                <Search />
                            </InputGroupAddon>
                            <InputGroupAddon align="inline-end" @click="handleSearchClear">
                                <X v-if="keyword" />
                            </InputGroupAddon>
                        </InputGroup>
                        <Button @click="handleSearch">
                            <Search class="h-4 w-4" />
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <template v-if="links.data.length > 0">
                <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <Card
                        v-for="link in links.data"
                        :key="link.id"
                        class="cursor-pointer rounded-md py-0 hover:border-primary/50 hover:bg-accent/35"
                        @click="handleOpenLink(link.url)"
                    >
                        <div v-if="link.image">
                            <img :src="link.image" :alt="link.title" class="aspect-video h-full w-full rounded-md object-cover" loading="lazy" />
                        </div>
                        <div v-else class="flex aspect-video w-full items-center justify-center rounded-md bg-muted">
                            <ExternalLink class="h-8 w-8 text-muted-foreground" />
                        </div>
                        <CardContent class="min-h-24">
                            <h3 class="line-clamp-1 text-sm font-semibold tracking-tight">
                                {{ link.title }}
                            </h3>
                            <p v-if="link.description" class="mt-1 line-clamp-2 text-xs text-muted-foreground">
                                {{ link.description }}
                            </p>
                            <p class="mt-2 line-clamp-1 text-xs text-muted-foreground">
                                {{ link.url }}
                            </p>
                        </CardContent>
                    </Card>
                </div>

                <Card class="py-3">
                    <CardContent>
                        <div class="flex w-full justify-between">
                            <Button class="cursor-pointer" :disabled="!links.links.prev" @click="handlePrev"> <ChevronLeft />Prev </Button>
                            <Button class="cursor-pointer" :disabled="!links.links.next" @click="handleNext"> Next<ChevronRight /> </Button>
                        </div>
                    </CardContent>
                </Card>
            </template>
        </template>

        <EmptyState :is-loading="isLoading" message="No links yet" />
    </div>
</template>
