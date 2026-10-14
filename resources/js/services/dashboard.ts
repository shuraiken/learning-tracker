import { api } from "./api";
import {
    ApiResponse,
    type DashboardOverview,
    type DashboardSkillBreakdown,
    type DashboardWeeklyActivity,
    type DashboardRecentSession,
} from './types';

export const getOverview = async (): Promise<DashboardOverview> => {
    const { data } = await api.get<ApiResponse<DashboardOverview>>('/server/dashboard/overview');

    return data.data;
}

export const getSkillBreakdown = async (): Promise<DashboardSkillBreakdown[]> => {
    const { data } = await api.get<ApiResponse<DashboardSkillBreakdown[]>>('/server/dashboard/skill-breakdown');

    return data.data;
}

export const getWeeklyActivity = async (): Promise<DashboardWeeklyActivity> => {
    const { data } = await api.get<ApiResponse<DashboardWeeklyActivity>>('/server/dashboard/weekly-activity');

    return data.data;
}

export const getRecentSessions = async (): Promise<DashboardRecentSession[]> => {
    const { data } = await api.get<ApiResponse<DashboardRecentSession[]>>('/server/dashboard/recent-sessions');

    return data.data;
}
