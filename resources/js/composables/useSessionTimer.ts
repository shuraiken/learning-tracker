import useSessionTimerStore from '@/stores/session-timer';
import { storeToRefs } from 'pinia';

export function useSessionTimer() {
    const sessionTimerStore = useSessionTimerStore();
    const { activeLearningSession, isActive } = storeToRefs(sessionTimerStore);

    return {
        activeSession: activeLearningSession,
        isActive,
        getActiveSession: sessionTimerStore.getActiveSession,
        activateSession: sessionTimerStore.activateSession,
        activateSessionAndLog: sessionTimerStore.activateSessionAndLog,
        pauseSession: sessionTimerStore.pauseSession,
        resumeSession: sessionTimerStore.resumeSession,
        endSession: sessionTimerStore.endSession,

        createStartLog: sessionTimerStore.createStartLog,
        createStopLog: sessionTimerStore.createStopLog,
    };
}
