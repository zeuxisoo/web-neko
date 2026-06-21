import api from '@/api';
import type { PulseTagIndexResponse } from '@/api/types';
import { WhoopsHandler } from '@/utils';
import { defineStore } from 'pinia';
import { TagOrderedList } from './types';

const useTagsStore = defineStore('tags', {
    state: () => ({
        memoTags: {} as TagOrderedList,
        driftTags: {} as TagOrderedList,
    }),
    actions: {
        async fetchMemo() {
            try {
                const { data, error } = await api.pulse.tag.index().json<PulseTagIndexResponse>();

                if (error.value) {
                    throw error.value;
                }

                if (data && data.value) {
                    const resultTags = data.value.data;

                    // convert Tag[] `[{ id, name, order_column }]` to TagOrderedList `{ name: id }`
                    this.memoTags = resultTags.reduce<TagOrderedList>((acc, tag) => {
                        acc[tag.name] = tag.id;
                        return acc;
                    }, {});
                } else {
                    throw error.value;
                }
            } catch (e: unknown) {
                WhoopsHandler.handleError(e, 'Unknown error when fetch tag list action in tag store');
            }
        },

        async fetchDrift() {
            try {
                const { data, error } = await api.drift.tag.index().json<PulseTagIndexResponse>();

                if (error.value) {
                    throw error.value;
                }

                if (data && data.value) {
                    const resultTags = data.value.data;

                    // convert Tag[] `[{ id, name, order_column }]` to TagOrderedList `{ name: id }`
                    this.driftTags = resultTags.reduce<TagOrderedList>((acc, tag) => {
                        acc[tag.name] = tag.id;
                        return acc;
                    }, {});
                } else {
                    throw error.value;
                }
            } catch (e: unknown) {
                WhoopsHandler.handleError(e, 'Unknown error when fetch drift tag list action in tag store');
            }
        },
    },
});

export default useTagsStore;
