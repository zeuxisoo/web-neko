<script setup lang="ts">
import api from '@/api';
import { Button } from '@/components/base/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/base/card';
import { Input } from '@/components/base/input';
import { Label } from '@/components/base/label';
import { useSettingsStore } from '@/stores';
import { WhoopsHandler } from '@/utils';
import validator from '@/validators';
import { Loader } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import { toast } from 'vue-sonner';

const isLoading = ref(false);

const settingsStore = useSettingsStore();

const form = ref({
    max_size_kb: 8192,
    allowed_mimes: 'jpeg,jpg,png,webp,gif',
    max_files: 8,
    max_per_memo: 6,
});

onMounted(async () => {
    try {
        isLoading.value = true;

        const attachmentSettings = settingsStore.attachment;

        if (attachmentSettings) {
            form.value = {
                max_size_kb: attachmentSettings.max_size_kb,
                allowed_mimes: attachmentSettings.allowed_mimes.join(','),
                max_files: attachmentSettings.max_files,
                max_per_memo: attachmentSettings.max_per_memo,
            };
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error when fetch settings in settings page');
    } finally {
        isLoading.value = false;
    }
});

const handleSave = async () => {
    isLoading.value = true;

    try {
        const mimes = form.value.allowed_mimes
            .split(',')
            .map((m) => m.trim())
            .filter((m) => m.length > 0);

        const formData = validator.form('settings.attachment.update').validate({
            max_size_kb: form.value.max_size_kb,
            allowed_mimes: mimes,
            max_files: form.value.max_files,
            max_per_memo: form.value.max_per_memo,
        });

        const { data, error } = await api.settings.attachment
            .update(formData as SettingsAttachmentUpdatePayload)
            .json<SettingsAttachmentUpdateResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data && data.value) {
            const result = data.value;
            const attachmentSettings = result.data;

            settingsStore.updateAttachment(attachmentSettings);

            toast.info('Attachment settings updated');
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error when update settings in settings page');
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>Attachment Settings</CardTitle>
            <CardDescription> Configure the default settings for file uploads on your site. </CardDescription>
        </CardHeader>
        <CardContent class="space-y-4">
            <div class="grid gap-2">
                <Label for="max-size-Kb">Max File Size (KB)</Label>
                <Input id="max-size-Kb" v-model.number="form.max_size_kb" type="number" min="1" max="102400" :disabled="isLoading" />
                <p class="text-xs text-muted-foreground">Maximum size for each file in kilobytes (KB). Default is 8192 KB (8 MB).</p>
            </div>
            <div class="grid gap-2">
                <Label for="allowed-mimes">Allowed MIME Types</Label>
                <Input id="allowed-mimes" v-model="form.allowed_mimes" type="text" placeholder="jpeg,jpg,png,webp,gif" :disabled="isLoading" />
                <p class="text-xs text-muted-foreground">Comma-separated list of allowed MIME types. Example: jpeg,jpg,png,webp,gif</p>
            </div>
            <div class="grid gap-2">
                <Label for="max-files">Max Files Per Upload</Label>
                <Input id="max-files" v-model.number="form.max_files" type="number" min="1" max="100" :disabled="isLoading" />
                <p class="text-xs text-muted-foreground">Maximum number of files allowed in a single upload request.</p>
            </div>
            <div class="grid gap-2">
                <Label for="max-per-memo">Max Files Per Memo</Label>
                <Input id="max-per-memo" v-model.number="form.max_per_memo" type="number" min="1" max="50" :disabled="isLoading" />
                <p class="text-xs text-muted-foreground">Maximum number of attachments allowed per memo.</p>
            </div>
        </CardContent>
        <CardFooter>
            <Button :disabled="isLoading" @click="handleSave">
                <Loader v-if="isLoading" class="mr-2 h-4 w-4 animate-spin" />
                Save
            </Button>
        </CardFooter>
    </Card>
</template>
