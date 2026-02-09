<script setup lang="ts">
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/base/collapsible';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/base/dropdown-menu';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
    useSidebar,
} from '@/components/base/sidebar';
import { type NavItem as NavItemType } from '@/components/sidebar/types';
import { ChevronRight } from 'lucide-vue-next';

const props = defineProps<{
    items: NavItemType[];
}>();

const sidebar = useSidebar();
</script>

<template>
    <SidebarGroup>
        <SidebarGroupLabel>Menu</SidebarGroupLabel>
        <SidebarMenu>
            <template as-child v-for="item in items" :key="item.title">
                <template v-if="item.kind === 'group'">
                    <SidebarMenuItem v-if="sidebar.state.value === 'collapsed' && !sidebar.isMobile.value">
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <SidebarMenuButton :tooltip="item.title" :is-Active="item.isActive">
                                    <component :is="item.icon" v-if="item.icon" />
                                </SidebarMenuButton>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent side="right" align="start">
                                <DropdownMenuLabel> {{ item.title }} </DropdownMenuLabel>
                                <DropdownMenuSeparator />
                                <DropdownMenuGroup>
                                    <DropdownMenuItem v-for="subItem in item.items" :key="subItem.title">
                                        <RouterLink :to="subItem.to">
                                            <span>{{ subItem.title }}</span>
                                        </RouterLink>
                                    </DropdownMenuItem>
                                </DropdownMenuGroup>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </SidebarMenuItem>

                    <Collapsible class="group/collapsible" as-child :default-open="item.isActive" v-if="sidebar.state.value === 'expanded'">
                        <SidebarMenuItem>
                            <CollapsibleTrigger as-child>
                                <SidebarMenuButton :tooltip="item.title">
                                    <component :is="item.icon" v-if="item.icon" />
                                    <span>{{ item.title }}</span>
                                    <ChevronRight class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90" />
                                </SidebarMenuButton>
                            </CollapsibleTrigger>
                            <CollapsibleContent>
                                <SidebarMenuSub>
                                    <SidebarMenuSubItem v-for="subItem in item.items" :key="subItem.title">
                                        <SidebarMenuSubButton as-child>
                                            <RouterLink :to="subItem.to">
                                                <span>{{ subItem.title }}</span>
                                            </RouterLink>
                                        </SidebarMenuSubButton>
                                    </SidebarMenuSubItem>
                                </SidebarMenuSub>
                            </CollapsibleContent>
                        </SidebarMenuItem>
                    </Collapsible>
                </template>

                <SidebarMenuItem v-if="item.kind === 'single'">
                    <SidebarMenuButton as-child :tooltip="item.title">
                        <RouterLink :to="item.to">
                            <component :is="item.icon" v-if="item.icon" />
                            <span>{{ item.title }}</span>
                        </RouterLink>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </template>
        </SidebarMenu>
    </SidebarGroup>
</template>
