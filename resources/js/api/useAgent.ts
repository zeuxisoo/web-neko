import { createFetch } from '@vueuse/core';
import ApiError from './error';

const useAgent = createFetch({
    baseUrl: '/api/v1',
    options: {
        updateDataOnError: true,
        beforeFetch: async ({ options }) => {
            const accessToken = window.localStorage.getItem('access-token');
            const headers = new Headers(options.headers);
            headers.set('Accept', 'application/json');

            if (accessToken) {
                headers.set('Authorization', `Bearer ${accessToken}`);
            }

            options.headers = headers;

            return { options };
        },
        onFetchError: (ctx) => {
            const status = ctx.response?.status;
            const data = ctx.data;

            if (status == 422) {
                ctx.error = new ApiError(data.message);
            }

            return ctx;
        },
    },
    fetchOptions: {
        mode: 'cors',
    },
});

export default useAgent;
