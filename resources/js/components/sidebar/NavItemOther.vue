<script setup lang="ts">
import { SidebarGroup, SidebarGroupContent, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/base/sidebar';
import { type NavItem as NavItemType } from '@/components/sidebar/types';
import { RouterLink } from 'vue-router';

const props = defineProps<{
    items: NavItemType[];
}>();
</script>

<template>
    <SidebarGroup>
        <SidebarGroupContent>
            <SidebarMenu>
                <SidebarMenuItem v-for="item in props.items" :key="item.title">
                    <SidebarMenuButton as-child size="sm" :tooltip="item.title">
                        <RouterLink :to="item.to">
                            <component :is="item.icon" v-if="item.icon" />
                            <span>{{ item.title }}</span>
                        </RouterLink>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarGroupContent>
    </SidebarGroup>
</template>
