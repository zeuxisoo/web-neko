import { useLocalStorage } from '@vueuse/core';

const useAuthStorage = (initialValue: AuthStorageValue) => {
    return useLocalStorage('auth', initialValue, {
        mergeDefaults: true,
    });
};

export default useAuthStorage;
