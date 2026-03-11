<script setup lang="ts">
import api from '@/api';
import { DriftForm, DriftList } from '@/components/drift';
import { useDriftsStore } from '@/stores';
import { WhoopsHandler } from '@/utils';
import validator from '@/validators';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

const isSubmitting = ref(false);

const driftStore = useDriftsStore();

const handleSubmit = async (submitData: DriftFromSubmitData) => {
    try {
        isSubmitting.value = true;

        const formData = validator.form('drift.store').validate({
            subject: submitData.subject,
            content: submitData.content,
            tags: submitData.tags,
        });

        const { data, error } = await api.drift.main.store(formData as DriftStorePayload).json<DriftStoreResponse>();

        if (error.value) {
            throw error.value;
        }

        if (data && data.value) {
            const result = data.value;
            const drift = result.data;

            driftStore.prepend(drift);

            toast.info('Drift created');
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        WhoopsHandler.handleError(e, 'Unknown error on handle drift store action');
    } finally {
        isSubmitting.value = false;
    }
};
</script>

<template>
    <div class="drift grid gap-3">
        <DriftForm :is-loading="isSubmitting" @submit="handleSubmit" />
        <DriftList />
    </div>
</template>
