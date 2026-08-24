<script setup lang="ts">
import { useSessionTimer } from '@/composables/useSessionTimer';
import { computed, onMounted, onUnmounted, ref } from 'vue';

const { activeSession, getActiveSession, pauseSession, resumeSession, endSession, createStartLog, createStopLog } = useSessionTimer();

const elapsedSeconds = ref<number>(0);
const now = ref<number>(Date.now());

const latestLog = computed(() => activeSession.value?.latest_log);
const showStart = computed(() => activeSession.value && (!latestLog.value || latestLog.value?.type === 'stop'));
const showPlayAndStop = computed(() => latestLog.value?.type === 'pause');
const showPause = computed(() => latestLog.value?.type === 'resume' || latestLog.value?.type === 'start');
const formattedTime = computed(() => {
    const hours = Math.floor(elapsedSeconds.value / 3600);
    const minutes = Math.floor((elapsedSeconds.value % 3600) / 60);
    const seconds = elapsedSeconds.value % 60;
    return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
});

let interval: ReturnType<typeof setInterval> | null = null;

const onStart = () => {
    createStartLog(activeSession.value.id);
};

const onPlay = () => {
    resumeSession(activeSession.value.id);
};

const onPause = () => {
    pauseSession(activeSession.value.id);
};

const onStop = () => {
    createStopLog(activeSession.value.id);
};

const onEnd = () => {
    endSession(activeSession.value.id);
};

onMounted(() => {
    getActiveSession();

    interval = setInterval(() => {
        now.value = Date.now();

        if (!activeSession.value) {
            elapsedSeconds.value = 0;
            return;
        }

        if (!latestLog.value || latestLog.value?.type === 'pause' || latestLog.value?.type === 'stop') {
            elapsedSeconds.value = activeSession.value.total_duration;
            return;
        }

        const segmentMilliseconds = now.value - Date.parse(latestLog.value.occurred_at);
        // const segmentMilliseconds = now.value - Date.parse(activeSession.value.started_at);
        const segmentSeconds = Math.floor(segmentMilliseconds / 1000);

        elapsedSeconds.value = activeSession.value.total_duration + segmentSeconds;
    }, 1000);
});

onUnmounted(() => {
    clearInterval(interval);
});
</script>

<template>
    <div class="mx-auto flex items-center gap-3">
        <!-- <span>{{ activeSession ? 'Session active' : 'No active session' }}</span> -->
        <template v-if="activeSession">
            <span>{{ activeSession?.name }}</span>
            <span class="font-bold">{{ formattedTime }}</span>
        </template>

        <div class="flex items-center gap-2">
            <button v-if="showPlayAndStop" type="button" @click="onPlay" class="session-timer-control-button cursor-pointer rounded-lg border border-white">Resume</button>

            <template v-if="showPause">
                <button type="button" @click="onPause" class="session-timer-control-button cursor-pointer rounded-lg border border-white">
                    Pause
                </button>
            </template>

            <template v-if="showPause || showPlayAndStop">
                <button type="button" @click="onStop" class="session-timer-control-button cursor-pointer rounded-lg border border-white">
                    Stop
                </button>
                <button type="button" @click="onEnd" class="session-timer-control-button cursor-pointer rounded-lg border border-white">
                    End
                </button>
            </template>

            <button v-if="showStart" type="button" @click="onStart" class="session-timer-control-button cursor-pointer rounded-lg border border-white">Start</button>
        </div>
    </div>
</template>

<style scoped>

.session-timer-control-button {
    padding-inline: 16px;
    padding-block: 4px;
}
</style>
