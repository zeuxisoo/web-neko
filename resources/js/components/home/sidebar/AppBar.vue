<script setup lang="ts">
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarProps,
    SidebarRail,
} from '@/components/base/sidebar';
import NavItem from '@/components/home/sidebar/NavItem.vue';
import NavUser from '@/components/home/sidebar/NavUser.vue';
import { type NavItem as NavItemType } from '@/types/dashboard';
import { Box, Shrub } from 'lucide-vue-next';

const props = withDefaults(defineProps<SidebarProps>(), {
    collapsible: 'icon',
});

const data = {
    user: {
        name: 'username',
        email: 'm@example.com',
        avatar: '/avatars/username.png',
    },
    items: [
        {
            kind: 'group',
            title: 'Park',
            url: '#',
            icon: Shrub,
            isActive: true,
            items: [
                { title: 'Pulse', url: '#' },
                { title: 'Gallery', url: '#' },
            ],
        },
        {
            kind: 'single',
            title: 'Inventory',
            url: '#',
            icon: Box,
        },
    ] as NavItemType[],
};
</script>

<template>
    <Sidebar v-bind="props">
        <SidebarHeader>
            <SidebarMenuItem>
                <SidebarMenuButton size="lg" class="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground">
                    <div class="flex aspect-square size-8 items-center justify-center rounded-lg bg-sidebar-primary text-sidebar-primary-foreground">
                        😎
                    </div>
                    <div class="grid flex-1 text-left text-sm leading-tight">
                        <span class="truncate font-semibold">Neko</span>
                        <span class="truncate text-xs">meow meow</span>
                    </div>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarHeader>
        <SidebarContent>
            <NavItem :items="data.items" />
        </SidebarContent>
        <SidebarFooter>
            <NavUser :user="data.user" />
        </SidebarFooter>
        <SidebarRail />
    </Sidebar>
</template>
