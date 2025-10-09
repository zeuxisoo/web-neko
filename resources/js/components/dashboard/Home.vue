<script setup lang="ts">
import { useColorMode } from '@vueuse/core';
import { Button } from '@/components/base/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/base/dropdown-menu';
import { Separator } from '@/components/base/separator';
import { SidebarInset, SidebarProvider, SidebarTrigger } from '@/components/base/sidebar';
import AppBar from '@/components/dashboard/home/AppBar.vue';

const mode = useColorMode();
</script>

<template>
    <SidebarProvider style="--sidebar-width: 15rem; --sidebar-width-mobile: 17rem">
        <AppBar />
        <SidebarInset>
            <header
                class="flex h-16 shrink-0 items-center gap-2 border-b transition-[width,height] ease-linear group-has-[[data-collapsible=icon]]/sidebar-wrapper:h-12"
            >
                <div class="flex w-full items-center gap-2 px-3 h-4">
                    <SidebarTrigger class="-ml-1" />
                    <Separator orientation="vertical" class="mr-2 h-4" />
                    <h1 className="text-base font-medium">Yowl</h1>
                    <div className="ml-auto flex items-center gap-2">
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <Button variant="ghost" size="sm"> Theme </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end">
                                <DropdownMenuItem @click="mode = 'light'"> Light </DropdownMenuItem>
                                <DropdownMenuItem @click="mode = 'dark'"> Dark </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                </div>
            </header>
            <div class="flex flex-1 flex-col gap-4 p-4">
                <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                    <div class="aspect-video rounded-xl bg-muted/50" />
                    <div class="aspect-video rounded-xl bg-muted/50" />
                    <div class="aspect-video rounded-xl bg-muted/50" />
                </div>
                <div class="min-h-[100vh] flex-1 rounded-xl bg-muted/50 md:min-h-min" />
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
