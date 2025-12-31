<script setup lang="ts">
import api from '@/api';
import { Separator } from '@/components/base/separator';
import { SidebarInset, SidebarProvider, SidebarTrigger } from '@/components/base/sidebar';
import AppBar from '@/components/sidebar/AppBar.vue';
import AppearanceSwitcher from '@/components/sidebar/AppearanceSwitcher.vue';
import ThemeSwitcher from '@/components/sidebar/ThemeSwitcher.vue';
import { WhoopsHandler } from '@/utils';
import { ref } from 'vue';

const user = ref<User>({
    username: '',
    email: '',
    avatar: '',
});
const isLoading = ref(false);

(async () => {
    isLoading.value = true;

    try {
        const { data } = await api.auth.me().json<MeResponse>();

        if (data.value && data.value.ok) {
            const result = data.value;
            const me = result.data;

            user.value.username = me.username;
            user.value.email = me.email;

            isLoading.value = false;
        } else {
            throw new Error('Unexcepted error when fetch me response in app sidebar');
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error when fetch me action in app sidebar');
    } finally {
        isLoading.value = false;
    }
})();
</script>

<template>
    <SidebarProvider style="--sidebar-width: 15rem; --sidebar-width-mobile: 17rem" v-if="!isLoading">
        <AppBar :user="user" />
        <SidebarInset>
            <header
                class="flex h-16 shrink-0 items-center gap-2 border-b transition-[width,height] ease-linear group-has-[[data-collapsible=icon]]/sidebar-wrapper:h-12"
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
