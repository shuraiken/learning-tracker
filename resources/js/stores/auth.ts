import { defineStore } from 'pinia';
import { computed, ref } from 'vue';

const useAuthStore = defineStore('auth', () => {
    const user = ref(null);

    const isAuthenticated = computed(() => user.value !== null);

    return {
        user,
        isAuthenticated,
    };
});

export default useAuthStore;
