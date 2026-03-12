<script setup lang="ts">
import api from '@/api';
import { Button } from '@/components/base/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/base/card';
import { Input } from '@/components/base/input';
import { Label } from '@/components/base/label';
import { useSettingsStore } from '@/stores';
import { WhoopsHandler } from '@/utils';
import validator from '@/validators';
import { Loader } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import { toast } from 'vue-sonner';

const isLoading = ref(false);

const settingsStore = useSettingsStore();

const form = ref({
    per_page_attachment: 2,
    per_page_bookmark: 8,
    per_page_comment: 8,
    per_page_link: 8,
    per_page_memo: 8,
});

onMounted(async () => {
    try {
        isLoading.value = true;

        const paginationSettings = settingsStore.pagination;

        if (paginationSettings) {
            form.value = {
                per_page_attachment: paginationSettings.per_page_attachment,
                per_page_bookmark: paginationSettings.per_page_bookmark,
                per_page_comment: paginationSettings.per_page_comment,
                per_page_link: paginationSettings.per_page_link,
                per_page_memo: paginationSettings.per_page_memo,
            };
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error when fetch pagination settings in general settings page');
    } finally {
        isLoading.value = false;
    }
});

const handleSave = async () => {
    isLoading.value = true;

    try {
        const formData = validator.form('settings.pagination.update').validate({
            per_page_attachment: form.value.per_page_attachment,
            per_page_bookmark: form.value.per_page_bookmark,
            per_page_comment: form.value.per_page_comment,
            per_page_link: form.value.per_page_link,
            per_page_memo: form.value.per_page_memo,
        });

        const { data, error } = await api.settings.pagination
            .update(formData as SettingsPaginationUpdatePayload)
            .json<SettingsPaginationUpdateResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data && data.value) {
            const result = data.value;
            const paginationSettings = result.data;

            settingsStore.updatePagination(paginationSettings);

            toast.info('Pagination settings updated');
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error when update pagination settings in general settings page');
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>Pagination Settings</CardTitle>
            <CardDescription> Configure the default per-page items for different sections. </CardDescription>
        </CardHeader>
        <CardContent class="space-y-4">
            <div class="grid gap-2">
                <Label for="per-page-attachment">Attachments Per Page</Label>
                <Input id="per-page-attachment" v-model.number="form.per_page_attachment" type="number" min="1" max="100" :disabled="isLoading" />
                <p class="text-xs text-muted-foreground">Number of attachment years to show per page.</p>
            </div>
            <div class="grid gap-2">
                <Label for="per-page-bookmark">Bookmarks Per Page</Label>
                <Input id="per-page-bookmark" v-model.number="form.per_page_bookmark" type="number" min="1" max="100" :disabled="isLoading" />
                <p class="text-xs text-muted-foreground">Number of bookmarks to show per page.</p>
            </div>
            <div class="grid gap-2">
                <Label for="per-page-comment">Comments Per Page</Label>
                <Input id="per-page-comment" v-model.number="form.per_page_comment" type="number" min="1" max="100" :disabled="isLoading" />
                <p class="text-xs text-muted-foreground">Number of comments to show per page.</p>
            </div>
            <div class="grid gap-2">
                <Label for="per-page-link">Links Per Page</Label>
                <Input id="per-page-link" v-model.number="form.per_page_link" type="number" min="1" max="100" :disabled="isLoading" />
                <p class="text-xs text-muted-foreground">Number of links to show per page.</p>
            </div>
            <div class="grid gap-2">
                <Label for="per-page-memo">Memos Per Page</Label>
                <Input id="per-page-memo" v-model.number="form.per_page_memo" type="number" min="1" max="100" :disabled="isLoading" />
                <p class="text-xs text-muted-foreground">Number of memos to show per page.</p>
            </div>
        </CardContent>
        <CardFooter>
            <Button :disabled="isLoading" @click="handleSave">
                <Loader v-if="isLoading" class="mr-2 h-4 w-4 animate-spin" />
                Save
            </Button>
        </CardFooter>
    </Card>
</template>
