import { useDashboardStore } from '@/stores/dashboard';
import { storeToRefs } from 'pinia';

export function useDashboard() {
    const store = useDashboardStore();
    const { overview, skillBreakdown, weeklyActivity, recentSessions, loading, error, period } = storeToRefs(store);

    return {
        overview,
        skillBreakdown,
        weeklyActivity,
        recentSessions,
        loading,
        error,
        period,
        fetchAll: store.fetchAll,
        setPeriod: store.setPeriod,
    };
}
