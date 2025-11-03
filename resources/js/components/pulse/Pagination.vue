<script setup lang="ts">
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { Card, CardContent } from '@/components/base/card';
import { Button } from '@/components/base/button';
</script>

<template>
    <Card class="py-3">
        <CardContent>
            <div class="flex w-full justify-between">
                <Button class="gap-1">
                    <ChevronLeft />Prev
                </Button>
                <Button class="gap-1">
                    Next<ChevronRight />
                </Button>
            </div>
        </CardContent>
    </Card>
</template>
