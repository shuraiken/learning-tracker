export enum LearningSessionLogType {
    Start = 'start',
    Pause = 'pause',
    Resume = 'resume',
    Stop = 'stop',
}

export enum LearningSessionStatus {
    Active = 'active',
    Paused = 'paused',
    Completed = 'completed',
}

export type ApiResponse<T> = {
    data: T;
    message?: string;
};

export type PaginatedResponse<T> = {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
};

export type Learning = {
    id: number;
    name: string;
    description?: string;
    mastery_id?: number;
    group_id?: number;
    mastery?: Pick<Mastery, 'id' | 'name'>;
};

export type Mastery = {
    id: number;
    name: string;
};

export type LearningFilterParams = {
    search?: string;
    groupId?: number | string;
    tags?: string;
    masteryId?: number | string;
    column?: string;
    direction?: string;
    limit?: number;
    page?: number;
};

export type MasteryFilterParams = {
    search?: string;
    sort?: unknown;
    limit?: number;
    page?: number;
};

export type LearningSession = {
    id: number;
    learning_id: number;
    name?: string;
    started_at: Date;
    ended_at?: Date;
    status: 'active' | 'paused' | 'completed';
    note?: string;
    total_duration?: number; // seconds
    latest_log?: LearningSessionLog;
    created_at?: Date;
    updated_at?: Date;
};

export type RawSession = Omit<LearningSession, 'started_at' | 'ended_at' | 'created_at' | 'updated_at' | 'latest_log'> & {
    started_at: string;
    ended_at?: string;
    created_at?: string;
    updated_at?: string;
    latest_log?: RawSessionLog;
};

export type RawSessionLog = Omit<LearningSessionLog, 'occurred_at' | 'created_at' | 'updated_at'> & {
    occurred_at: string;
    created_at?: string;
    updated_at?: string;
};

export type LearningSessionLog = {
    id: number;
    learning_session_id: number;
    type: LearningSessionLogType;
    occurred_at: Date;
    created_at?: Date;
    updated_at?: Date;
};

export type DashboardOverview = {
    totalLearnings: number;
    totalTime: number;
    today: number;
    thisWeek: number;
};

export type DashboardSkillBreakdown = {
    id: number;
    name: string;
    total_duration: number;
    duration_formatted: string;
};

export type DashboardWeeklyActivity = {
    monday: number;
    tuesday: number;
    wednesday: number;
    thursday: number;
    friday: number;
    saturday: number;
    sunday: number;
};

export type DashboardRecentSession = {
    id: number;
    name: string | null;
    skill: string;
    date: string | null;
    duration: number;
    duration_formatted: string;
    status: LearningSessionStatus;
};
