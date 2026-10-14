import { defineStore } from 'pinia';
import { learningSessions, type CreateLearningSession } from '@/services';
import useSessionTimerStore from './session-timer';

export const useLearningSessionStore = defineStore('learning-session', () => {
    const sessionTimerStore = useSessionTimerStore();

    const createLearningSession = async (learningSession: CreateLearningSession) => {
        sessionTimerStore.activeLearningSession = await learningSessions.createLearningSession(learningSession);
    };

    const createLearningSessionAndLog = async (learningSession: CreateLearningSession) => {
        const { session } = await learningSessions.createSessionAndLog(learningSession);

        sessionTimerStore.activeLearningSession = session;
    }

    const deleteLearningSession = async (id: number) => {
        await learningSessions.destroySession(id);
    };

    return { createLearningSession, createLearningSessionAndLog, deleteLearningSession };
});
