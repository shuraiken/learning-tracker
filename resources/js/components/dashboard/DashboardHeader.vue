<script setup lang="ts">
import { Button } from '@/components/ui/button';
import type { DashboardPeriod } from '@/stores/dashboard';

const props = defineProps<{
    period: DashboardPeriod;
}>();

const emit = defineEmits<{
    (e: 'update:period', value: DashboardPeriod): void;
}>();

const options: { value: DashboardPeriod; label: string }[] = [
    { value: 'today', label: 'Today' },
    { value: 'week', label: 'This Week' },
    { value: 'month', label: 'This Month' },
    { value: 'all', label: 'All Time' },
];

const select = (value: DashboardPeriod) => {
    if (value !== props.period) emit('update:period', value);
};
</script>

<template>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Dashboard</h1>
            <p class="text-sm text-muted-foreground">Your learning at a glance.</p>
        </div>
        <div class="flex flex-wrap gap-1 rounded-lg border border-border bg-card p-1" role="group" aria-label="Period selector">
            <Button
                v-for="option in options"
                :key="option.value"
                size="sm"
                :variant="option.value === period ? 'secondary' : 'ghost'"
                :aria-pressed="option.value === period"
                @click="select(option.value)"
            >
                {{ option.label }}
            </Button>
        </div>
    </div>
</template>
