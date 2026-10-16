<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card';
import { Skeleton } from '@/components/ui/skeleton';
import type { DashboardOverview } from '@/services';
import { CalendarDays, Clock, Hourglass, LibraryBig } from 'lucide-vue-next';
import { computed } from 'vue';
import { formatDuration } from './format';

const props = defineProps<{
    overview: DashboardOverview | null;
    loading: boolean;
}>();

const cards = computed(() => [
    {
        key: 'today',
        label: 'Learning Time Today',
        value: props.overview ? formatDuration(props.overview.today) : '—',
        icon: Clock,
    },
    {
        key: 'week',
        label: 'Learning Time This Week',
        value: props.overview ? formatDuration(props.overview.thisWeek) : '—',
        icon: CalendarDays,
    },
    {
        key: 'total-time',
        label: 'Total Learning Time',
        value: props.overview ? formatDuration(props.overview.totalTime) : '—',
        icon: Hourglass,
    },
    {
        key: 'total',
        label: 'Total Learnings',
        value: props.overview ? String(props.overview.totalLearnings) : '—',
        icon: LibraryBig,
    },
]);
</script>

<template>
    <div class="grid grid-cols-2 gap-4 xl:grid-cols-4">
        <template v-if="loading">
            <Card v-for="n in 4" :key="n">
                <CardContent class="space-y-2 p-4 md:p-6">
                    <Skeleton class="h-4 w-4" />
                    <Skeleton class="h-4 w-24" />
                    <Skeleton class="h-8 w-20" />
                </CardContent>
            </Card>
        </template>
        <template v-else>
            <Card v-for="card in cards" :key="card.key">
                <CardContent class="space-y-1 p-4 md:p-6">
                    <component :is="card.icon" class="size-4 text-muted-foreground" aria-hidden="true" />
                    <p class="text-sm text-muted-foreground">{{ card.label }}</p>
                    <p class="text-2xl font-semibold tracking-tight">{{ card.value }}</p>
                </CardContent>
            </Card>
        </template>
    </div>
</template>
