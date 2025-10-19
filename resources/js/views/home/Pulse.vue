<script setup lang="ts">
import { Button } from '@/components/base/button';
import { Card, CardContent } from '@/components/base/card';
import { Input } from '@/components/base/input';
import { Textarea } from '@/components/base/textarea';
import { ImageUp, SendHorizontal } from 'lucide-vue-next';

</script>

<template>
    <div class="pulse">
        <Card>
            <CardContent>
                <div class="grid item-center w-full gap-4">
                    <div class="flex flex-col">
                        <Textarea name="content" class="min-h-[100px]" placeholder="Place whatever you want" />
                    </div>
                    <div class="flex w-full items-center gap-1.5">
                        <Input name="tags" type="text" placeholder="Tags" />
                        <Button size="icon">
                            <SendHorizontal />
                            <span class="sr-only">Submit</span>
                        </Button>
                        <Button size="icon">
                            <ImageUp />
                            <span class="sr-only">Images</span>
                        </Button>
                    </div>
                </div>
            </CardContent>
        </Card>
    </div>
</template>
