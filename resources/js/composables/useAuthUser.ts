import api from '@/api';
import { ref } from 'vue';

const useAuthUser = async () => {
    const user = ref<User>({
        username: '',
        email: '',
    });
    const isLoading = ref(true);

    try {
        const { data, error } = await api.auth.me().json<MeResponse>();

        if (data.value && data.value.ok) {
            const result = data.value;
            const me = result.data;

            user.value.username = me.username;
            user.value.email = me.email;

            isLoading.value = false;
        } else {
            throw error.value;
        }
    } catch (e: unknown) {
        throw e;
    } finally {
        isLoading.value = false;
    }

    return {
        user,
        isLoading,
    };
};

export default useAuthUser;
