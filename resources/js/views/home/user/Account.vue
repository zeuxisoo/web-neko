<script setup lang="ts">
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/base/tabs';
import { Header } from '@/components/page';
import Profile from './Profile.vue';
</script>

<template>
    <div class="pulse grid gap-3">
        <Header>Account Settings</Header>
        <Tabs default-value="profile">
            <TabsList>
                <TabsTrigger value="profile"> Profile </TabsTrigger>
                <TabsTrigger value="security"> Security </TabsTrigger>
            </TabsList>
            <TabsContent value="profile">
                <Profile />
            </TabsContent>
            <TabsContent value="security"> </TabsContent>
        </Tabs>
    </div>
</template>
