<script setup lang="ts">
import api from '@/api';
import { AccountSecurityResponse, AccountSecurityUpdatePasswordPayload } from '@/api/types';
import { Button } from '@/components/base/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/base/card';
import { Label } from '@/components/base/label';
import { PasswordInput } from '@/components/password-input';
import { useAuthStore } from '@/stores';
import { WhoopsHandler } from '@/utils';
import validator from '@/validators';
import { Loader } from 'lucide-vue-next';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { toast } from 'vue-sonner';

const old_password = ref('');
const new_password = ref('');
const new_password_confirmation = ref('');
const isLoading = ref(false);

const router = useRouter();

const handleAccountSecuritySave = async () => {
    isLoading.value = true;

    try {
        const formData = validator.form('account.security.update').validate({
            old_password: old_password.value,
            new_password: new_password.value,
            new_password_confirmation: new_password_confirmation.value,
        });

        const { data, error } = await api.account.security
            .updatePassword(formData as AccountSecurityUpdatePasswordPayload)
            .json<AccountSecurityResponse>();

        if (error.value) {
            throw error.value;
        }

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
        WhoopsHandler.handleError(e, 'Unknown error on handle account security save action');
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>Security</CardTitle>
            <CardDescription>Change your security here. After saving, you'll be logged out.</CardDescription>
        </CardHeader>
        <CardContent>
            <form>
                <div class="grid gap-6">
                    <div class="grid gap-3">
                        <Label for="old-password">Current password</Label>
                        <PasswordInput v-model="old_password" id="old-password" type="password" :enable-password-toggle="true" />
                    </div>
                    <div class="grid gap-3">
                        <Label for="new-password">New password</Label>
                        <PasswordInput v-model="new_password" id="new-password" type="password" :enable-password-toggle="true" />
                    </div>
                    <div class="grid gap-3">
                        <Label for="confirm-password">Confirm password</Label>
                        <PasswordInput v-model="new_password_confirmation" id="confirm-password" type="password" :enable-password-toggle="true" />
                    </div>
                </div>
            </form>
        </CardContent>
        <CardFooter>
            <Button @click="handleAccountSecuritySave">
                <Loader class="animate-spin" v-if="isLoading" />
                <template v-else>Save</template>
            </Button>
        </CardFooter>
    </Card>
</template>
