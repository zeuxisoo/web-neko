import api from '@/api';
import { WhoopsHandler } from '@/utils';
import { defineStore } from 'pinia';

const useMemosStore = defineStore('memos', {
    state: () => ({
        memos: null as PulseMemoIndexResponse | null,
    }),
    actions: {
        async fetchList(page: number = 1, tag: string = '') {
            try {
                const { data, error } = await api.pulse.memo.index({ page, tag }).json<PulseMemoIndexResponse>();

                if (error.value) {
                    throw error.value;
                }

                if (data && data.value) {
                    this.memos = data.value;
                } else {
                    throw error.value;
                }
            } catch (e: unknown) {
                WhoopsHandler.handleError(e, 'Unknown error when fetch memo list action in memo store');
            }
        },
        prepend(memo: Memo) {
            if (this.memos) {
                this.memos.data = [memo, ...this.memos.data];
            }
        },
        update(memo: Memo) {
            if (this.memos) {
                const index = this.memos.data.findIndex((m) => m.id === memo.id);

                if (index !== -1) {
                    this.memos.data[index] = memo;
                }
            }
        },
        remove(memoId: number) {
            if (this.memos) {
                const index = this.memos.data.findIndex((m) => m.id === memoId);

                if (index !== -1) {
                    this.memos.data.splice(index, 1);
                }
            }
        },
    },
});

export default useMemosStore;
