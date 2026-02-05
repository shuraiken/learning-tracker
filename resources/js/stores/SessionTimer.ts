import axios from 'axios';
import { defineStore } from 'pinia';
import { ref } from 'vue';
import { CreateLearningSession, LearningSession } from './types';

export const useSessionTimerStore = defineStore('SessionTimer', () => {
    const activeSession = ref<LearningSession | null>(null);

    const activeSessionExists = () => {
        return activeSession.value !== null;
    };

    const createSession = async (form: CreateLearningSession) => {
        const response = await axios.post('/api/learning-sessions');
        activeSession.value = response.data;
    };

    const fetchActiveSession = async (userId: number) => {
        const response = await axios.get(`/api/users/${userId}/sessions/active`);
        activeSession.value = response.data;
    };

    const pauseActiveSession = async (learningSessionId: number) => {
        await axios.put(`/api/learning-sessions/${learningSessionId}/pause`);
    };

    const pauseActiveSessionLog = async (learningSessionLogId: number) => {
        await axios.put(`/api/learning-session-logs/${learningSessionLogId}/pause`);
    };

    const endActiveSessionLog = async (learningSessionLogId: number) => {
        await axios.put(`/api/learning-session-logs/${learningSessionLogId}/end`);
    };

    const resumeRunningLog = async (learningSessionLogId: number) => {
        await axios.put(`/api/learning-session-logs/${learningSessionLogId}/resume`);
    };

    return {
        activeSession,
        activeSessionExists,
        createSession,
        fetchActiveSession,
        pauseActiveSession,
    };
});
