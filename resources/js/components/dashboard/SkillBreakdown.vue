<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Skeleton } from '@/components/ui/skeleton';
import type { DashboardSkillBreakdown } from '@/services';
import type { DashboardPeriod } from '@/stores/dashboard';
import { computed } from 'vue';

const props = defineProps<{
    skillBreakdown: DashboardSkillBreakdown[];
    loading: boolean;
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

// Denominator for share-of-period bars (locked: honest proportional widths).
const total = computed(() => props.skillBreakdown.reduce((sum, skill) => sum + skill.total_duration, 0));

// Fraction 0-1; template converts to % width. Zero-guarded for empty periods.
const calcProgressBarWidth = (skill: DashboardSkillBreakdown): number => (total.value > 0 ? skill.total_duration / total.value : 0);

// Backend pre-sorts desc; cap the visible tail (locked: max 8 rows).
const MAX_VISIBLE_SKILLS = 8;
const visibleSkills = computed(() => props.skillBreakdown.slice(0, MAX_VISIBLE_SKILLS));
</script>

<template>
    <Card>
        <CardContent class="space-y-4 p-4 md:p-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <HeadingSmall title="Skill Breakdown" description="Share of learning time for the selected period." />
                <div
                    class="flex flex-wrap gap-1 self-start rounded-lg border border-border bg-card p-1 sm:self-auto"
                    role="group"
                    aria-label="Period selector"
                >
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
            <template v-if="loading">
                <div v-for="n in 4" :key="n" class="space-y-2">
                    <div class="flex justify-between">
                        <Skeleton class="h-4 w-28" />
                        <Skeleton class="h-4 w-16" />
                    </div>
                    <Skeleton class="h-1.5 w-full" />
                </div>
            </template>
            <p v-else-if="visibleSkills.length === 0" class="text-sm text-muted-foreground">No skills yet for this period.</p>
            <ul v-else class="space-y-4">
                <li v-for="skill in visibleSkills" :key="skill.id" class="space-y-1.5">
                    <div class="flex items-baseline justify-between gap-2">
                        <p class="truncate text-sm font-medium">{{ skill.name }}</p>
                        <p class="shrink-0 text-sm text-muted-foreground">{{ skill.duration_formatted }}</p>
                    </div>
                    <div
                        class="h-1.5 w-full overflow-hidden rounded-full bg-muted"
                        role="img"
                        :aria-label="`${skill.name}: ${skill.duration_formatted}`"
                    >
                        <div class="h-full rounded-full bg-chart-2" :style="{ width: `${calcProgressBarWidth(skill) * 100}%` }" />
                    </div>
                </li>
            </ul>
        </CardContent>
    </Card>
</template>
