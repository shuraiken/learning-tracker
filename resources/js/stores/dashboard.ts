import type { DashboardOverview, DashboardRecentSession, DashboardSkillBreakdown, DashboardWeeklyActivity } from '@/services';
import { dashboard, isApiError } from '@/services';
import { defineStore } from 'pinia';
import { ref } from 'vue';

export type DashboardPeriod = 'today' | 'week' | 'month' | 'all';

export const useDashboardStore = defineStore('dashboard', () => {
    const overview = ref<DashboardOverview | null>(null);
    const skillBreakdown = ref<DashboardSkillBreakdown[]>([]);
    const weeklyActivity = ref<DashboardWeeklyActivity | null>(null);
    const recentSessions = ref<DashboardRecentSession[]>([]);
    const loading = ref(false);
    const error = ref<string | null>(null);
    // UI-only until the backend supports ?period= (roadmap Phase 2).
    const period = ref<DashboardPeriod>('all');

    const fetchOverview = async () => {
        overview.value = await dashboard.getOverview();
    };

    const fetchSkillBreakdown = async () => {
        skillBreakdown.value = await dashboard.getSkillBreakdown();
    };

    const fetchWeeklyActivity = async () => {
        weeklyActivity.value = await dashboard.getWeeklyActivity();
    };

    const fetchRecentSessions = async () => {
        recentSessions.value = await dashboard.getRecentSessions();
    };

    const fetchAll = async () => {
        loading.value = true;
        error.value = null;
        try {
            await Promise.all([fetchOverview(), fetchSkillBreakdown(), fetchWeeklyActivity(), fetchRecentSessions()]);
        } catch (e: unknown) {
            error.value = isApiError(e) ? e.message : 'Failed to load dashboard data.';
        } finally {
            loading.value = false;
        }
    };

    const setPeriod = (value: DashboardPeriod) => {
        period.value = value;
    };

    return {
        overview,
        skillBreakdown,
        weeklyActivity,
        recentSessions,
        loading,
        error,
        period,
        fetchOverview,
        fetchSkillBreakdown,
        fetchWeeklyActivity,
        fetchRecentSessions,
        fetchAll,
        setPeriod,
    };
});
