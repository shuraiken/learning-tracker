import { api } from './api';
import { ApiResponse, Learning, LearningFilterParams } from './types';

export type CreateLearning = {
    name: string;
    masteryId?: number | string;
    groupId?: number | string;
};

export const getLearnings = async (params?: LearningFilterParams): Promise<Learning[]> => {
    const { data } = await api.get<ApiResponse<Learning[]>>('/server/learnings', { params });

    return data.data;
};

export const getLearning = async (id: number | string): Promise<Learning> => {
    const { data } = await api.get<ApiResponse<Learning>>(`/server/learnings/${id}`);

    return data.data;
};

export const createLearning = async (payload: CreateLearning): Promise<Learning> => {
    const { data } = await api.post<ApiResponse<Learning>>('/server/learnings', payload);

    return data.data;
};

export const updateLearning = async (id: number | string, payload: { name: string }): Promise<string> => {
    const { data } = await api.put<ApiResponse<string>>(`/server/learnings/${id}`, payload);

    return data.data;
};

export const deleteLearning = async (id: number | string): Promise<string> => {
    const { data } = await api.delete<ApiResponse<string>>(`/server/learnings/${id}`);

    return data.data;
};
