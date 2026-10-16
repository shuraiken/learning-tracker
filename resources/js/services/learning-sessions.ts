import { api } from './api';
import { ApiResponse, LearningSession, LearningSessionLog, RawSession, RawSessionLog } from './types';

export type CreateLearningSession = Pick<LearningSession, 'learning_id' | 'name'>;

export const createLearningSession = async (learningSession: CreateLearningSession): Promise<LearningSession> => {
    const { data } = await api.post<ApiResponse<RawSession>>('/server/learning-sessions', learningSession);

    return parseSession(data.data);
};

export const getActiveSession = async (): Promise<LearningSession | null> => {
    const { data } = await api.get<ApiResponse<RawSession | null>>('/server/learning-sessions/active');

    return data.data ? parseSession(data.data) : null;
};

export const destroySession = async (id: number): Promise<void> => {
    await api.delete(`/server/learning-sessions/${id}`);
};

export const createSessionAndLog = async (payload: CreateLearningSession): Promise<{ session: LearningSession; log: LearningSessionLog }> => {
    const { data } = await api.post<ApiResponse<{ learningSession: RawSession; learningSessionLog: RawSessionLog }>>(
        '/server/learning-sessions/activate-session-and-log',
        payload,
    );

    return {
        session: parseSession(data.data.learningSession),
        log: parseLog(data.data.learningSessionLog),
    };
};

export const activateSession = async (id: number): Promise<LearningSession> => {
    const { data } = await api.post<ApiResponse<RawSession>>(`/server/learning-sessions/${id}/activate`);

    return parseSession(data.data);
};

export const pauseSession = async (id: number): Promise<LearningSession> => {
    const { data } = await api.post<ApiResponse<RawSession>>(`/server/learning-sessions/${id}/pause`);

    return parseSession(data.data);
};

export const resumeSession = async (id: number): Promise<LearningSession> => {
    const { data } = await api.post<ApiResponse<RawSession>>(`/server/learning-sessions/${id}/resume`);

    return parseSession(data.data);
};

export const endSession = async (id: number): Promise<LearningSession> => {
    const { data } = await api.post<ApiResponse<RawSession>>(`/server/learning-sessions/${id}/end`);

    return parseSession(data.data);
};

export const createStartLog = async (id: number): Promise<LearningSession> => {
    const { data } = await api.post<ApiResponse<RawSession>>(`/server/learning-sessions/${id}/start-log`);

    return parseSession(data.data);
};

export const createStopLog = async (id: number): Promise<LearningSession> => {
    const { data } = await api.post<ApiResponse<RawSession>>(`/server/learning-sessions/${id}/stop-log`);

    return parseSession(data.data);
};

const parseSession = (raw: RawSession): LearningSession => {
    return {
        ...raw,
        started_at: new Date(raw.started_at),
        ended_at: raw.ended_at ? new Date(raw.ended_at) : undefined,
        created_at: raw.created_at ? new Date(raw.created_at) : undefined,
        updated_at: raw.updated_at ? new Date(raw.updated_at) : undefined,
        latest_log: raw.latest_log ? parseLog(raw.latest_log) : undefined,
    };
};

const parseLog = (raw: RawSessionLog): LearningSessionLog => {
    return {
        ...raw,
        occurred_at: new Date(raw.occurred_at),
        created_at: raw.created_at ? new Date(raw.created_at) : undefined,
        updated_at: raw.updated_at ? new Date(raw.updated_at) : undefined,
    };
};
