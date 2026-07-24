import axios from 'axios';
import { defineStore } from 'pinia';
import { CreateLearningSession } from './types';

import useSessionTimerStore from './session-timer';

export const useLearningSessionStore = defineStore('learning-session', () => {
    const sessionTimerStore = useSessionTimerStore();

    const createLearningSession = async (learningSession: CreateLearningSession) => {
        try {
            const response = await axios.post('/server/learning-sessions', learningSession);

            const { data } = response.data;

            sessionTimerStore.activeLearningSession = data;
        } catch (error) {
            throw error;
        }
    };

    const createLearningSessionAndLog = async (learningSession: CreateLearningSession) => {
        try {
            const response = await axios.post('/server/learning-sessions/activate-session-and-log', learningSession);

            const { data } = response.data;

            sessionTimerStore.activeLearningSession = data.learningSession;
        } catch (error) {
            throw error;
        }
    }

    const deleteLearningSession = async (id: number) => {
        try {
            await axios.delete(`/server/learning-sessions/${id}`);
        } catch (error) {
            throw error;
        }
    };

    return { createLearningSession, createLearningSessionAndLog, deleteLearningSession };
});
