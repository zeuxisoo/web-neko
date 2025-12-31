<script setup lang="ts">
import api from '@/api';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/base/avatar';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/base/dropdown-menu';
import { SidebarMenu, SidebarMenuButton, SidebarMenuItem, useSidebar } from '@/components/base/sidebar';
import { useAuthStore } from '@/stores';
import { WhoopsHandler } from '@/utils';
import { ChevronsUpDown, LogOut, User } from 'lucide-vue-next';
import { toast } from 'vue-sonner';

const props = defineProps<{
    user: User;
}>();

const { isMobile } = useSidebar();

const handleProfile = async () => {
    console.log('profile clicked');
};

const handleLogout = async () => {
    try {
        const { data, error } = await api.auth.logout().json<LogoutResponse>();

        if (data.value && data.value.ok) {
            const authStore = useAuthStore();
            authStore.deactivateAuth();

            toast.success('Good Bye! See you later');
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error on handle logout action');
    }
};
</script>

<template>
    <SidebarMenu>
        <SidebarMenuItem>
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <SidebarMenuButton size="lg" class="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground">
                        <Avatar class="h-8 w-8 rounded-lg">
                            <AvatarImage :src="props.user.avatar" :alt="props.user.username" />
                            <AvatarFallback class="rounded-lg"> AV </AvatarFallback>
                        </Avatar>
                        <div class="grid flex-1 text-left text-sm leading-tight">
                            <span class="truncate font-semibold">{{ props.user.username }}</span>
                            <span class="truncate text-xs">{{ props.user.email }}</span>
                        </div>
                        <ChevronsUpDown class="ml-auto size-4" />
                    </SidebarMenuButton>
                </DropdownMenuTrigger>
                <DropdownMenuContent
                    class="w-[--reka-dropdown-menu-trigger-width] min-w-56 rounded-lg"
                    align="end"
                    :side="isMobile ? 'bottom' : 'right'"
                    :side-offset="4"
                >
                    <DropdownMenuLabel class="p-0 font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                            <Avatar class="h-8 w-8 rounded-lg">
                                <AvatarImage :src="props.user.avatar" :alt="props.user.username" />
                                <AvatarFallback class="rounded-lg"> AV </AvatarFallback>
                            </Avatar>
                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span class="truncate font-semibold">{{ props.user.username }}</span>
                                <span class="truncate text-xs">{{ props.user.email }}</span>
                            </div>
                        </div>
                    </DropdownMenuLabel>
                    <DropdownMenuSeparator />
                    <DropdownMenuGroup>
                        <DropdownMenuItem @click="handleProfile">
                            <User />
                            Profile
                        </DropdownMenuItem>
                    </DropdownMenuGroup>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem @click="handleLogout">
                        <LogOut />
                        Log out
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>
        </SidebarMenuItem>
    </SidebarMenu>
</template>
