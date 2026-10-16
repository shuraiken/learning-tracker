<script setup lang="ts">
import DashboardHeader from '@/components/dashboard/DashboardHeader.vue';
import DashboardOverview from '@/components/dashboard/DashboardOverview.vue';
import RecentSessions from '@/components/dashboard/RecentSessions.vue';
import SkillBreakdown from '@/components/dashboard/SkillBreakdown.vue';
import WeeklyActivityChart from '@/components/dashboard/WeeklyActivityChart.vue';
import { useDashboard } from '@/composables/useDashboard';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

// Data flows through the store via composable (docs/data-flow.md).
const { overview, skillBreakdown, weeklyActivity, recentSessions, loading, error, period, fetchAll, setPeriod } = useDashboard();

onMounted(async () => {
    await fetchAll();
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4 md:p-6">
            <DashboardHeader />
            <p v-if="error" role="alert" class="text-sm text-destructive">{{ error }}</p>
            <DashboardOverview :overview="overview" :loading="loading" />
            <div class="grid gap-4 lg:grid-cols-2">
                <WeeklyActivityChart :weekly-activity="weeklyActivity" :loading="loading" />
                <SkillBreakdown :skill-breakdown="skillBreakdown" :loading="loading" :period="period" @update:period="setPeriod" />
            </div>
            <RecentSessions :sessions="recentSessions" :loading="loading" />
        </div>
    </AppLayout>
</template>
