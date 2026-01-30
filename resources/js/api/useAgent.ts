import { useAuthStore } from '@/stores';
import { createFetch } from '@vueuse/core';
import { ApiError } from './error';

const useAgent = createFetch({
    baseUrl: '/api/v1',

    options: {
        updateDataOnError: true,
        beforeFetch: async ({ options }) => {
            const auth = useAuthStore();

            const headers = new Headers(options.headers);
            headers.set('Accept', 'application/json');

            const accessToken = auth.auth.access_token;
            if (accessToken) {
                headers.set('Authorization', `Bearer ${accessToken}`);
            }

            // use cookie auto refresh token for stateful spa
            const csrfToken = document.cookie
                .split('; ')
                .find((row) => row.startsWith('XSRF-TOKEN='))
                ?.split('=')[1];
            if (csrfToken) {
                headers.set('X-XSRF-TOKEN', decodeURIComponent(csrfToken));
            }

            options.headers = headers;
            options.credentials = 'include';

            return { options };
        },
        onFetchError: (ctx) => {
            const status = ctx.response?.status;
            const data = ctx.data;

            if (status === 401) {
                const authStore = useAuthStore();
                authStore.deactivateAuth();

                ctx.error = new ApiError(data.message);
            }

            if (status === 422) {
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
