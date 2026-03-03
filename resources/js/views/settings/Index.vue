<script setup lang="ts">
import { Alert, AlertDescription, AlertTitle } from '@/components/base/alert';
import { Card, CardContent } from '@/components/base/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/base/tabs';
import { Header } from '@/components/page';
import { useUserStore } from '@/stores';
import { Info } from 'lucide-vue-next';
import Attachment from './Attachment.vue';
import General from './General.vue';

const userStore = useUserStore();
</script>

<template>
    <div class="attachment grid gap-3">
        <Header #title>Site Settings</Header>

        <Card v-if="!userStore.isAdmin">
            <CardContent>
                <Alert>
                    <Info />
                    <AlertTitle>Oops!</AlertTitle>
                    <AlertDescription>You do not have permission to view or edit these settings. Please contact an administrator.</AlertDescription>
                </Alert>
            </CardContent>
        </Card>

        <Tabs default-value="general" v-else>
            <TabsList>
                <TabsTrigger value="general"> General </TabsTrigger>
                <TabsTrigger value="attachment"> Attachment </TabsTrigger>
            </TabsList>
            <TabsContent value="general">
                <General />
            </TabsContent>
            <TabsContent value="attachment">
                <Attachment />
            </TabsContent>
        </Tabs>
    </div>
</template>
