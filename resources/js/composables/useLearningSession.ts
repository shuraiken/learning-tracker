import { useLearningSessionStore } from '@/stores/learning-session';

export const useLearningSession = () => {
    const store = useLearningSessionStore();

    return {
        createLearningSession: store.createLearningSession,
        createLearningSessionAndLog: store.createLearningSessionAndLog,
        deleteLearningSession: store.deleteLearningSession,
    };
};
