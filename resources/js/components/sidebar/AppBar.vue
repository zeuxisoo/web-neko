<script setup lang="ts">
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarProps,
    SidebarRail,
} from '@/components/base/sidebar';
import NavUser from '@/components/sidebar/NavUser.vue';
import { type NavItem as NavItemType } from '@/components/sidebar/types';
import { Box, Settings, Shrub } from 'lucide-vue-next';
import { useRouter } from 'vue-router';
import NavItemMain from './NavItemMain.vue';
import NavItemOther from './NavItemOther.vue';

interface AppBarProps extends SidebarProps {
    user: User;
}

const props = withDefaults(defineProps<AppBarProps>(), {
    collapsible: 'icon',
});

const router = useRouter();

const data = {
    main: [
        {
            kind: 'group',
            title: 'Park',
            to: { path: '#' },
            icon: Shrub,
            isActive: true,
            items: [
                {
                    title: 'Pulse',
                    to: { name: 'park.pulse' },
                },
                {
                    title: 'Attachment',
                    to: { name: 'park.attachment' },
                },
            ],
        },
        {
            kind: 'single',
            title: 'Inventory',
            to: { path: '#' },
            icon: Box,
        },
    ] as NavItemType[],
    other: [
        {
            kind: 'single',
            title: 'Settings',
            to: { path: '/settings/index' },
            icon: Settings,
            isActive: false,
        },
    ] as NavItemType[],
};

const handleSidebarHeader = () => {
    router.push({
        name: 'index',
    });
};
</script>

<template>
    <Sidebar v-bind="props">
        <SidebarHeader @click="handleSidebarHeader">
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <a href="#">
                            <div
                                class="flex aspect-square size-8 items-center justify-center rounded-lg bg-sidebar-primary text-sidebar-primary-foreground"
                            >
                                😎
                            </div>
                            <div class="flex flex-col gap-0.5 leading-none">
                                <span class="truncate font-semibold">Neko</span>
                                <span class="truncate text-xs">meow meow</span>
                            </div>
                        </a>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>
        <SidebarContent>
            <NavItemMain :items="data.main" />
            <NavItemOther :items="data.other" class="mt-auto" />
        </SidebarContent>
        <SidebarFooter>
            <NavUser :user="props.user" />
        </SidebarFooter>
        <SidebarRail />
    </Sidebar>
</template>
