<script setup lang="ts">
import { Button } from '@/components/base/button';
import { CardDescription, CardFooter } from '@/components/base/card';
import Card from '@/components/base/card/Card.vue';
import CardContent from '@/components/base/card/CardContent.vue';
import CardHeader from '@/components/base/card/CardHeader.vue';
import CardTitle from '@/components/base/card/CardTitle.vue';
import { Input } from '@/components/base/input';
import { Label } from '@/components/base/label';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/base/tabs';
import Header from '@/components/page/Header.vue';
import { ref } from 'vue';

type TabType = 'base' | 'security';

const activeTab = ref<TabType>('base');
</script>

<template>
    <div class="pulse grid gap-3">
        <Header>Profile Settings</Header>
        <Tabs default-value="account">
            <TabsList>
                <TabsTrigger value="account"> Account </TabsTrigger>
                <TabsTrigger value="password"> Password </TabsTrigger>
            </TabsList>
            <TabsContent value="account">
                <Card>
                    <CardHeader>
                        <CardTitle>Account</CardTitle>
                        <CardDescription>Make changes to your account here. Click save when you're done.</CardDescription>
                    </CardHeader>
                    <CardContent class="grid gap-6">
                        <div class="grid gap-3">
                            <Label for="tabs-demo-name">Username</Label>
                            <Input id="tabs-demo-name" default-value="meow" />
                        </div>
                        <div class="grid gap-3">
                            <Label for="tabs-demo-username">Email</Label>
                            <Input id="tabs-demo-username" default-value="meow@home.local" />
                        </div>
                    </CardContent>
                    <CardFooter>
                        <Button>Save</Button>
                    </CardFooter>
                </Card>
            </TabsContent>
            <TabsContent value="password">
                <Card>
                    <CardHeader>
                        <CardTitle>Password</CardTitle>
                        <CardDescription>Change your password here. After saving, you'll be logged out.</CardDescription>
                    </CardHeader>
                    <CardContent class="grid gap-6">
                        <div class="grid gap-3">
                            <Label for="tabs-demo-current">Current password</Label>
                            <Input id="tabs-demo-current" type="password" />
                        </div>
                        <div class="grid gap-3">
                            <Label for="tabs-demo-new">New password</Label>
                            <Input id="tabs-demo-new" type="password" />
                        </div>
                    </CardContent>
                    <CardFooter>
                        <Button>Save</Button>
                    </CardFooter>
                </Card>
            </TabsContent>
        </Tabs>
    </div>
</template>
refvuemt-1 mb-6 mb-2 mb-2 font-semibold font-semibold
