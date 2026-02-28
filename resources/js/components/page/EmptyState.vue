<script setup lang="ts">
import { Card, CardContent } from '../base/card';

defineProps<{
    isLoading: boolean;
    message?: string;
}>();
</script>

<template>
    <Card>
        <CardContent>
            <div v-if="isLoading" class="flex h-64 items-center justify-center">
                <div class="h-8 w-8 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
            </div>

            <div v-else class="flex h-64 flex-col items-center justify-center rounded-lg border-2 border-dashed">
                <p class="text-sm text-muted-foreground">{{ message }}</p>
            </div>
        </CardContent>
    </Card>
</template>
