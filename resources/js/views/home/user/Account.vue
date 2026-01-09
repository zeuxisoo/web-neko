<script setup lang="ts">
import api from '@/api';
import { Button } from '@/components/base/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/base/card';
import { Input } from '@/components/base/input';
import { Label } from '@/components/base/label';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/base/tabs';
import { PasswordInput } from '@/components/form';
import { Header } from '@/components/page';
import { useAuthUser } from '@/composables';
import { useAuthStore } from '@/stores';
import { WhoopsHandler } from '@/utils';
import validator from '@/validators';
import { Loader } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { toast } from 'vue-sonner';

const user = ref<User>({
    username: '',
    email: '',
});
const isLoading = ref(false);

const router = useRouter();

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

const handleAccountProfileSave = async () => {
    isLoading.value = true;

    try {
        const formData = validator.form('account.profile.update').validate({
            username: user.value.username,
            email: user.value.email,
        });

        const { data, error } = await api.account.profile.update(formData as AccountProfileUpdatePayload).json<AccountProfileResponse>();

        if (data.value && data.value.ok) {
            const result = data.value;

            const authStore = useAuthStore();
            authStore.deactivateAuth();

            router.push({
                name: 'index',
                replace: true,
            });

            toast.success(result.message);
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error on handle account profile save action');
    } finally {
        isLoading.value = false;
    }
};
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
                        <Button @click="handleAccountProfileSave">
                            <Loader class="animate-spin" v-if="isLoading" />
                            <template v-else>Save</template>
                        </Button>
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
                            <Label for="old-password">Current password</Label>
                            <PasswordInput id="old-password" type="password" enable-password-toggle="true" />
                        </div>
                        <div class="grid gap-3">
                            <Label for="new-password">New password</Label>
                            <PasswordInput id="new-password" type="password" enable-password-toggle="true" />
                        </div>
                        <div class="grid gap-3">
                            <Label for="confirm-password">Confirm password</Label>
                            <PasswordInput id="confirm-password" type="password" enable-password-toggle="true" />
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
