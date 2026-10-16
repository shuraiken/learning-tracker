import { api } from './api';
import { ApiResponse, Mastery, MasteryFilterParams, PaginatedResponse } from './types';

export const getMasteries = async (params?: MasteryFilterParams): Promise<PaginatedResponse<Mastery>> => {
    const { data } = await api.get<ApiResponse<PaginatedResponse<Mastery>>>('/server/masteries', { params });

    return data.data;
};

export const createMastery = async (name: string): Promise<Mastery> => {
    const { data } = await api.post<ApiResponse<Mastery>>('/server/masteries', { name });

    return data.data;
};
