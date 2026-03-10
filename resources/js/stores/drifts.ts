import api from '@/api';
import { WhoopsHandler } from '@/utils';
import { defineStore } from 'pinia';

const useDriftsStore = defineStore('drifts', {
    state: () => ({
        isLoading: false,
        drifts: null as DriftIndexResponse | null,
    }),
    actions: {
        async fetchList(page: number = 1) {
            this.isLoading = true;

            try {
                const { data, error } = await api.drift.main.index({ page }).json<DriftIndexResponse>();

                if (error.value) {
                    throw error.value;
                }

                if (data && data.value) {
                    this.drifts = data.value;
                } else {
                    throw error.value;
                }
            } catch (e: unknown) {
                WhoopsHandler.handleError(e, 'Unknown error when fetch drift list action in drift store');
            } finally {
                this.isLoading = false;
            }
        },
        prepend(drift: DriftStoreResponse['data']) {
            if (this.drifts) {
                this.drifts.data = [drift, ...this.drifts.data];
            }
        },
        update(drift: DriftStoreResponse['data']) {
            if (this.drifts) {
                const index = this.drifts.data.findIndex((d) => d.id === drift.id);

                if (index !== -1) {
                    this.drifts.data[index] = drift;
                }
            }
        },
        remove(driftId: number) {
            if (this.drifts) {
                const index = this.drifts.data.findIndex((d) => d.id === driftId);

                if (index !== -1) {
                    this.drifts.data.splice(index, 1);
                }
            }
        },
    },
});

export default useDriftsStore;
