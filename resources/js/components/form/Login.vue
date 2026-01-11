<script setup lang="ts">
import api from '@/api';
import { Button } from '@/components/base/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/base/card';
import { Input } from '@/components/base/input';
import { Label } from '@/components/base/label';
import { PasswordInput } from '@/components/password-input';
import { useAuthStore } from '@/stores';
import { WhoopsHandler } from '@/utils';
import validator from '@/validators';
import { Loader, Lock } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

const account = ref('');
const password = ref('');
const isLoading = ref(false);

const handleLogin = async () => {
    isLoading.value = true;

    try {
        const formData = validator.form('auth.login').validate({
            account: account.value,
            password: password.value,
        });

        const { data, error } = await api.auth.login(formData as LoginPayload).json<LoginResponse>();

        if (data.value && data.value.ok) {
            const result = data.value;

            const authStore = useAuthStore();
            authStore.activateAuth(result.data);

            toast.success('Welcome back');
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error on handle login action');
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <div class="flex w-full flex-col gap-6">
        <a href="#" class="flex items-center gap-2 self-center font-medium"> 🐱 Neko </a>
        <Card class="mx-auto w-full max-w-sm">
            <CardHeader class="text-center">
                <CardTitle class="text-xl"> Login </CardTitle>
                <CardDescription> Enter your account below to login </CardDescription>
            </CardHeader>
            <CardContent>
                <div class="grid gap-4">
                    <div class="grid gap-2">
                        <Label for="account">Account</Label>
                        <Input v-model="account" id="account" type="text" placeholder="username / email" required />
                    </div>
                    <div class="grid gap-2">
                        <div class="flex items-center">
                            <Label for="password">Password</Label>
                            <a href="javascript:alert('Don\'t touch me 😡')" class="ml-auto inline-block text-sm">
                                <Lock :size="14" />
                            </a>
                        </div>
                        <PasswordInput v-model="password" :enable-password-toggle="true" id="password" type="password" />
                    </div>
                    <Button type="button" class="w-full" @click="handleLogin" :disabled="isLoading">
                        <Loader class="animate-spin" v-if="isLoading" />
                        <template v-else>Login</template>
                    </Button>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
