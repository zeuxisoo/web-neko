import api from '@/api';
import type { PulseCommentIndexResponse } from '@/api/types';
import { WhoopsHandler } from '@/utils';
import { defineStore } from 'pinia';
import { Comment } from './types';

const useCommentsStore = defineStore('comments', {
    state: () => ({
        comments: null as PulseCommentIndexResponse | null,
    }),
    actions: {
        async fetchList(memoId: number, page: number = 1) {
            try {
                const { data, error } = await api.pulse.comment.index({ memo_id: memoId, page }).json<PulseCommentIndexResponse>();

                if (error.value) {
                    throw error.value;
                }

                if (data && data.value) {
                    if (page === 1) {
                        this.comments = data.value;
                    } else {
                        // append new comments to existing list for pagination
                        // page is not full page reload just load more append
                        this.comments = {
                            ...data.value,
                            data: [...(this.comments?.data ?? []), ...data.value.data],
                        };
                    }
                } else {
                    throw error.value;
                }
            } catch (e: unknown) {
                WhoopsHandler.handleError(e, 'Unknown error when fetch comments list action in comments store');
            }
        },
        append(comment: Comment) {
            if (this.comments) {
                this.comments.data = [...this.comments.data, comment];
            }
        },
    },
});

export default useCommentsStore;
