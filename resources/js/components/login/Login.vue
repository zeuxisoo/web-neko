<script setup lang="ts">
import { Button } from '@/components/base/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/base/card';
import { Input } from '@/components/base/input';
import { Label } from '@/components/base/label';
import { Lock } from 'lucide-vue-next';
</script>

<template>
    <div class="flex h-screen w-full items-center justify-center px-4">
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
                            <Input id="account" type="text" placeholder="username / email" required />
                        </div>
                        <div class="grid gap-2">
                            <div class="flex items-center">
                                <Label for="password">Password</Label>
                                <a href="javascript:alert('Don\'t touch me 😡')" class="ml-auto inline-block text-sm">
                                    <Lock :size="14" />
                                </a>
                            </div>
                            <Input id="password" type="password" required />
                        </div>
                        <Button type="button" class="w-full"> Login </Button>
                    </div>
                </CardContent>
            </Card>
        </div>
    </div>
</template>
