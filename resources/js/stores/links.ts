import api from '@/api';
import type { PulseLinkDestroyResponse, PulseLinkUnsavedResponse } from '@/api/types';
import { WhoopsHandler } from '@/utils';
import { defineStore } from 'pinia';
import { onScopeDispose } from 'vue';
import { toast } from 'vue-sonner';
import { Link } from './types';

const useLinksStore = (id: string = 'default') => {
    const store = defineStore(`links-${id}`, {
        state: () => ({
            links: [] as Link[],
        }),
        actions: {
            async fetchUnsaved() {
                try {
                    const { data, error } = await api.pulse.link.unsaved().json<PulseLinkUnsavedResponse>();

                    if (error.value) {
                        throw error.value;
                    }

                    if (data && data.value) {
                        const result = data.value;
                        const linkList = result.data;

                        for (const link of linkList) {
                            this.links.push(link);
                        }
                    } else {
                        throw error.value;
                    }
                } catch (e: unknown) {
                    WhoopsHandler.handleError(e, 'Unknown error when fetch unsaved link action in link store');
                }
            },
            async removeLink(index: number) {
                try {
                    const link = this.links[index];

                    const { data, error } = await api.pulse.link.destroy(link.id).json<PulseLinkDestroyResponse>();

                    if (error.value) {
                        throw error.value;
                    }

                    if (data && data.value) {
                        const result = data.value;
                        const message = result.message;

                        toast.info(message);

                        this.links.splice(index, 1);
                    } else {
                        throw error.value;
                    }
                } catch (e: unknown) {
                    WhoopsHandler.handleError(e, 'Unknown error when remove link action in attachment store');
                }
            },
            onLinked(links: Link[]) {
                this.links = this.links.concat(links);
            },
        },
    });

    const instance = store();

    onScopeDispose(() => {
        instance.$dispose();
    });

    return instance;
};

export default useLinksStore;
