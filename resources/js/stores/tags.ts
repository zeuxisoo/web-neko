import api from '@/api';
import { WhoopsHandler } from '@/utils';
import { defineStore } from 'pinia';

const useTagsStore = defineStore('tags', {
    state: () => ({
        tags: {} as TagOrderedList,
    }),
    actions: {
        async fetch() {
            try {
                const { data, error } = await api.pulse.tag.index().json<PulseTagIndexResponse>();

                if (error.value) {
                    throw error.value;
                }

                if (data && data.value) {
                    const resultTags = data.value.data;

                    // convert Tag[] `[{ id, name, order_column }]` to TagOrderedList `{ name: id }`
                    this.tags = resultTags.reduce<TagOrderedList>((acc, tag) => {
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
    },
});

export default useTagsStore;
