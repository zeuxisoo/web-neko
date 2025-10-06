<script setup lang="ts">
import { Button } from "@/components/ui/button"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
</script>

<template>
    <Card class="w-full mx-auto max-w-sm">
        <CardHeader>
            <CardTitle class="text-2xl">
                Login
            </CardTitle>
            <CardDescription>
                Enter your account below to login
            </CardDescription>
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
                        <a href="javascript:alert('???')" class="ml-auto inline-block text-sm">
                            🔒
                        </a>
                    </div>
                    <Input id="password" type="password" required />
                </div>
                <Button type="button" class="w-full">
                    Login
                </Button>
            </div>
        </CardContent>
    </Card>
</template>
