<script setup lang="ts">
import { Separator } from '@/components/base/separator';
import { SidebarInset, SidebarProvider, SidebarTrigger } from '@/components/base/sidebar';
import { AppBar, AppearanceSwitcher, ThemeSwitcher } from '@/components/sidebar';
import { useSettingsStore, useUserStore } from '@/stores';
import { WhoopsHandler } from '@/utils';
import { onMounted, ref } from 'vue';

const user = useUserStore();
const settings = useSettingsStore();
const isLoading = ref(false);

onMounted(async () => {
    try {
        isLoading.value = true;

        await user.fetch();
        await settings.fetchAll();
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error when fetch me action in app sidebar');
    } finally {
        isLoading.value = false;
    }
});
</script>

<template>
    <SidebarProvider style="--sidebar-width: 15rem; --sidebar-width-mobile: 17rem" v-if="!isLoading">
        <AppBar :user="user" />
        <SidebarInset>
            <header
                class="flex h-16 shrink-0 items-center gap-2 border-b transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12"
            >
                <div class="flex h-4 w-full items-center gap-2 px-3">
                    <SidebarTrigger class="-ml-1" />
                    <Separator orientation="vertical" class="mr-2 h-4" />
                    <h1 className="text-base font-medium">Yowl</h1>
                    <div className="ml-auto flex items-center gap-2">
                        <ThemeSwitcher />
                        <AppearanceSwitcher />
                    </div>
                </div>
            </header>
            <div class="flex flex-1 flex-col p-4">
                <RouterView />
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
