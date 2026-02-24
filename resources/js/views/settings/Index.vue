<script setup lang="ts">
import { Alert, AlertDescription, AlertTitle } from '@/components/base/alert';
import { Card, CardContent } from '@/components/base/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/base/tabs';
import { useUserStore } from '@/stores';
import { Info } from 'lucide-vue-next';
import Attachment from './Attachment.vue';

const userStore = useUserStore();
</script>

<template>
    <div class="attachment grid gap-3">
        <Card class="py-3">
            <CardContent>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl font-semibold tracking-tight">Settings</h1>
                    </div>
                </div>
            </CardContent>
        </Card>

        <Card v-if="!userStore.isAdmin">
            <CardContent>
                <Alert>
                    <Info />
                    <AlertTitle>Oops!</AlertTitle>
                    <AlertDescription>You do not have permission to view or edit these settings. Please contact an administrator.</AlertDescription>
                </Alert>
            </CardContent>
        </Card>

        <Tabs default-value="attachment">
            <TabsList>
                <TabsTrigger value="attachment"> Attachment </TabsTrigger>
            </TabsList>
            <TabsContent value="attachment">
                <Attachment />
            </TabsContent>
        </Tabs>
    </div>
</template>
