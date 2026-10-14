import { defineStore } from 'pinia';
import { computed, ref } from 'vue';
import { learningSessions, type LearningSession } from '@/services';

const useSessionTimerStore = defineStore('session-timer', () => {
    const activeLearningSession = ref<LearningSession | null>(null);
    const isActive = computed(() => activeLearningSession.value !== null);

    const getActiveSession = async () => {
        activeLearningSession.value = await learningSessions.getActiveSession();
    };

    const activateSession = async (id: number) => {
        activeLearningSession.value = await learningSessions.activateSession(id);
    };

    const pauseSession = async (id: number) => {
        activeLearningSession.value = await learningSessions.pauseSession(id);
    };

    const resumeSession = async (id: number) => {
        activeLearningSession.value = await learningSessions.resumeSession(id);
    };

    const endSession = async (id: number) => {
        await learningSessions.endSession(id);

        activeLearningSession.value = null;
    };

    const createStartLog = async (learningSessionId: number) => {
        activeLearningSession.value = await learningSessions.createStartLog(learningSessionId);
    };

    const createStopLog = async (learningSessionId: number) => {
        activeLearningSession.value = await learningSessions.createStopLog(learningSessionId);
    };

    return {
        activeLearningSession,
        isActive,
        getActiveSession,
        activateSession,
        pauseSession,
        resumeSession,
        endSession,

        createStartLog,
        createStopLog,
    };
});

export default useSessionTimerStore;
