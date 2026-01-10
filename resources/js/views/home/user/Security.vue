<script setup lang="ts">
import { Button } from '@/components/base/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/base/card';
import { Label } from '@/components/base/label';
import { PasswordInput } from '@/components/form';
import { Loader } from 'lucide-vue-next';
import { ref } from 'vue';

const isLoading = ref(false);

const handleAccountSecuritySave = async () => {};
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>Security</CardTitle>
            <CardDescription>Change your security here. After saving, you'll be logged out.</CardDescription>
        </CardHeader>
        <CardContent class="grid gap-6">
            <div class="grid gap-3">
                <Label for="old-password">Current password</Label>
                <PasswordInput id="old-password" type="password" :enable-password-toggle="true" />
            </div>
            <div class="grid gap-3">
                <Label for="new-password">New password</Label>
                <PasswordInput id="new-password" type="password" :enable-password-toggle="true" />
            </div>
            <div class="grid gap-3">
                <Label for="confirm-password">Confirm password</Label>
                <PasswordInput id="confirm-password" type="password" :enable-password-toggle="true" />
            </div>
        </CardContent>
        <CardFooter>
            <Button @click="handleAccountSecuritySave">
                <Loader class="animate-spin" v-if="isLoading" />
                <template v-else>Save</template>
            </Button>
        </CardFooter>
    </Card>
</template>
