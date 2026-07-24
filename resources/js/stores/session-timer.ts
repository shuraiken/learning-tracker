import axios from 'axios';
import { defineStore } from 'pinia';
import { computed, ref } from 'vue';
import { LearningSession } from './types';

const useSessionTimerStore = defineStore('session-timer', () => {
    const activeLearningSession = ref<LearningSession | null>(null);
    const isActive = computed(() => activeLearningSession.value !== null);

    const getActiveSession = async () => {
        try {
            const { data } = await axios.get('/server/learning-sessions/active');

            activeLearningSession.value = data.data;
        } catch (error) {
            throw error;
        }
    };

    const activateSession = async (id: number) => {
        try {
            const response = await axios.get(`/server/learning-sessions/${id}/activate`);
            const { data } = response.data;
            activeLearningSession.value = data;
        } catch (error) {
            throw error;
        }
    };

    const activateSessionAndLog = async (id: number) => {
        try {
            const response = await axios.post(`/server/learning-sessions/${id}/activate-session-and-log`);
            const { data } = response.data;
            activeLearningSession.value = data;
        } catch (error) {
            throw error;
        }
    };

    const pauseSession = async (id: number) => {
        try {
            const response = await axios.post(`/server/learning-sessions/${id}/pause`);

            activeLearningSession.value = response.data.data;
        } catch (error) {
            throw error;
        }
    };

    const resumeSession = async (id: number) => {
        try {
            const response = await axios.post(`/server/learning-sessions/${id}/resume`);

            activeLearningSession.value = response.data.data;
        } catch (error) {
            throw error;
        }
    };

    const endSession = async (id: number) => {
        try {
            await axios.post(`/server/learning-sessions/${id}/end`);

            activeLearningSession.value = null;
        } catch (error) {
            throw error;
        }
    };

    const createStartLog = async (learningSessionId: number) => {
        try {
            const response = await axios.post(`/server/learning-sessions/${learningSessionId}/start-log`);

            activeLearningSession.value = response.data.data;
        } catch (error) {
            throw error;
        }
    };

    const createStopLog = async (learningSessionId: number) => {
        try {
            const response = await axios.post(`/server/learning-sessions/${learningSessionId}/stop-log`);

            activeLearningSession.value = response.data.data;
        } catch (error) {
            throw error;
        }
    };

    return {
        activeLearningSession,
        isActive,
        getActiveSession,
        activateSession,
        activateSessionAndLog,
        pauseSession,
        resumeSession,
        endSession,

        createStartLog,
        createStopLog,
    };
});

export default useSessionTimerStore;
