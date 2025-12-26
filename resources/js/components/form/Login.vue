<script setup lang="ts">
import { Button } from '@/components/base/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/base/card';
import { Input } from '@/components/base/input';
import { Label } from '@/components/base/label';
import validator from '@/validators';
import { Lock } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import PasswordInput from './PasswordInput.vue';
import { WhoopsHandler } from '@/helpers';

const account = ref('');
const password = ref('');
const isLoading = ref(false);

const handleLogin = async () => {
    try {
        const formData = validator.form('auth.login').validate({
            account: account.value,
            password: password.value,
        });

        console.log(formData);
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, "Unknown error on handle login action");
    }

    toast.info('TODO: Call backend api to handle login');
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
                        {{ isLoading ? '<Loader class="animate-spin" />' : "Login" }}
                    </Button>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
