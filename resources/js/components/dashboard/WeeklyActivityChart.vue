<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Card, CardContent } from '@/components/ui/card';
import { Skeleton } from '@/components/ui/skeleton';
import type { DashboardWeeklyActivity } from '@/services';
import { BarController, BarElement, CategoryScale, Chart, LinearScale, Tooltip } from 'chart.js';
import { computed, nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { formatDuration } from './format';

Chart.register(BarController, BarElement, CategoryScale, LinearScale, Tooltip);

const props = defineProps<{
    weeklyActivity: DashboardWeeklyActivity | null;
    loading: boolean;
}>();

const DAYS = [
    { key: 'monday', short: 'Mon', full: 'Monday' },
    { key: 'tuesday', short: 'Tue', full: 'Tuesday' },
    { key: 'wednesday', short: 'Wed', full: 'Wednesday' },
    { key: 'thursday', short: 'Thu', full: 'Thursday' },
    { key: 'friday', short: 'Fri', full: 'Friday' },
    { key: 'saturday', short: 'Sat', full: 'Saturday' },
    { key: 'sunday', short: 'Sun', full: 'Sunday' },
] as const;

type DayKey = (typeof DAYS)[number]['key'];

const secondsByDay = computed<number[]>(() => {
    if (!props.weeklyActivity) return DAYS.map(() => 0);
    return DAYS.map((day) => props.weeklyActivity?.[day.key as DayKey] ?? 0);
});

const isEmpty = computed(() => secondsByDay.value.every((seconds) => seconds === 0));

const ariaSummary = computed(() => DAYS.map((day, index) => `${day.full}: ${formatDuration(secondsByDay.value[index] ?? 0)}`).join(', '));

const canvasRef = ref<HTMLCanvasElement | null>(null);
let chart: Chart<'bar'> | null = null;
let themeObserver: MutationObserver | null = null;
let chartCanvas: HTMLCanvasElement | null = null;

/** Canvas can't read Tailwind tokens directly — resolve the CSS vars so .dark flips work. */
const resolveColors = () => {
    const styles = getComputedStyle(document.documentElement);
    const pick = (name: string, fallback: string) => styles.getPropertyValue(name).trim() || fallback;
    return {
        bar: pick('--chart-2', '#0d9488'),
        grid: pick('--border', 'rgba(0, 0, 0, 0.08)'),
        tick: pick('--muted-foreground', '#737373'),
    };
};

const buildChart = () => {
    if (!canvasRef.value || props.loading) return;

    chart?.destroy();
    chart = null;

    const colors = resolveColors();

    chart = new Chart(canvasRef.value, {
        type: 'bar',
        data: {
            labels: DAYS.map((day) => day.short),
            datasets: [
                {
                    data: secondsByDay.value.map((seconds) => seconds / 3600),
                    backgroundColor: colors.bar,
                    borderRadius: 4,
                    borderSkipped: 'bottom',
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        title: (items) => DAYS[items[0]?.dataIndex ?? 0]?.full ?? '',
                        label: (item) => formatDuration(secondsByDay.value[item.dataIndex] ?? 0),
                    },
                },
            },
            scales: {
                x: { grid: { display: false }, ticks: { color: colors.tick } },
                y: {
                    beginAtZero: true,
                    grid: { color: colors.grid },
                    ticks: {
                        color: colors.tick,
                        maxTicksLimit: 4,
                        callback: function (tickValue) {
                            const hours = typeof tickValue === 'number' ? tickValue : Number(tickValue);
                            return formatDuration(hours * 3600);
                        },
                    },
                },
            },
        },
    });

    chartCanvas = canvasRef.value;
};

const refreshChart = () => {
    if (!chart) return;
    const colors = resolveColors();
    chart.data.datasets[0]!.data = secondsByDay.value.map((seconds) => seconds / 3600);
    chart.data.datasets[0]!.backgroundColor = colors.bar;
    if (chart.options.scales?.x?.ticks) chart.options.scales.x.ticks.color = colors.tick;
    if (chart.options.scales?.y?.ticks) chart.options.scales.y.ticks.color = colors.tick;
    if (chart.options.scales?.y?.grid && typeof chart.options.scales.y.grid === 'object') chart.options.scales.y.grid.color = colors.grid;
    chart.update();
};

const ensureChart = () => {
    if (!canvasRef.value || props.loading) return;
    if (!chart || chartCanvas !== canvasRef.value) buildChart();
    else refreshChart();
};

onMounted(() => {
    ensureChart();

    // Re-resolve token colors when the .dark class toggles (canvas doesn't inherit CSS).
    themeObserver = new MutationObserver(() => refreshChart());
    themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
});

watch([canvasRef, secondsByDay, () => props.loading], async () => {
    await nextTick();
    ensureChart();
});

onBeforeUnmount(() => {
    themeObserver?.disconnect();
    chart?.destroy();
    chart = null;
    chartCanvas = null;
});
</script>

<template>
    <Card>
        <CardContent class="space-y-4 p-4 md:p-6">
            <HeadingSmall title="Weekly Activity" description="Learning time per day." />
            <template v-if="loading">
                <Skeleton class="h-48 w-full" />
            </template>
            <template v-else>
                <div class="relative h-48">
                    <canvas ref="canvasRef" :aria-label="`Weekly learning activity. ${ariaSummary}`" role="img" />
                    <p v-if="isEmpty" class="pointer-events-none absolute inset-0 flex items-center justify-center text-sm text-muted-foreground">
                        No activity this week yet.
                    </p>
                </div>
                <!-- Screen-reader fallback: canvas pixels aren't readable. -->
                <table class="sr-only">
                    <caption>
                        Learning time per day this week
                    </caption>
                    <tbody>
                        <tr v-for="(day, index) in DAYS" :key="day.key">
                            <th scope="row">{{ day.full }}</th>
                            <td>{{ formatDuration(secondsByDay[index] ?? 0) }}</td>
                        </tr>
                    </tbody>
                </table>
            </template>
        </CardContent>
    </Card>
</template>
