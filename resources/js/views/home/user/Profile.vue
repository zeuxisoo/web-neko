<script setup lang="ts">
import api from '@/api';
import AvatarUpload from '@/components/avatar-upload/AvatarUpload.vue';
import { Button } from '@/components/base/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/base/card';
import { Input } from '@/components/base/input';
import { Label } from '@/components/base/label';
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
    <Card>
        <CardHeader>
            <CardTitle>Profile</CardTitle>
            <CardDescription>Make changes to your profile here. After saving, you'll be logged out.</CardDescription>
        </CardHeader>
        <CardContent class="grid gap-6">
            <div class="grid gap-3">
                <Label for="tabs-name">Username</Label>
                <Input id="tabs-name" placeholder="meow" v-model="user.username" />
            </div>
            <div class="grid gap-3">
                <Label for="tabs-username">Email</Label>
                <Input id="tabs-username" placeholder="meow@home.local" v-model="user.email" />
            </div>
            <div class="grid gap-3">
                <Label for="tabs-avatar">Avatar</Label>
                <AvatarUpload />
            </div>
        </CardContent>
        <CardFooter>
            <Button @click="handleAccountProfileSave">
                <Loader class="animate-spin" v-if="isLoading" />
                <template v-else>Save</template>
            </Button>
        </CardFooter>
    </Card>
</template>
