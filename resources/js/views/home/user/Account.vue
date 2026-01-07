<script setup lang="ts">
import { Button } from '@/components/base/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/base/card';
import { Input } from '@/components/base/input';
import { Label } from '@/components/base/label';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/base/tabs';
import { Header } from '@/components/page';
import { useAuthUser } from '@/composables';
import { WhoopsHandler } from '@/utils';
import { onMounted, ref } from 'vue';

const user = ref<User>({
    username: '',
    email: '',
});
const isLoading = ref(false);

onMounted(async () => {
    try {
        isLoading.value = true;

        const authUser = await useAuthUser();

        user.value = authUser.user.value;
        isLoading.value = isLoading.value;
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error when fetch me action in account profile');
    } finally {
        isLoading.value = false;
    }
});
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
                <Card>
                    <CardHeader>
                        <CardTitle>Profile</CardTitle>
                        <CardDescription>Make changes to your profile here. After saving, you'll be logged out.</CardDescription>
                    </CardHeader>
                    <CardContent class="grid gap-6">
                        <div class="grid gap-3">
                            <Label for="tabs-demo-name">Username</Label>
                            <Input id="tabs-demo-name" placeholder="meow" v-model="user.username" />
                        </div>
                        <div class="grid gap-3">
                            <Label for="tabs-demo-username">Email</Label>
                            <Input id="tabs-demo-username" placeholder="meow@home.local" v-model="user.email" />
                        </div>
                    </CardContent>
                    <CardFooter>
                        <Button @click="">Save</Button>
                    </CardFooter>
                </Card>
            </TabsContent>
            <TabsContent value="security">
                <Card>
                    <CardHeader>
                        <CardTitle>Security</CardTitle>
                        <CardDescription>Change your security here. After saving, you'll be logged out.</CardDescription>
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
