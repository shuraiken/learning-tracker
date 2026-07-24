enum LearningSessionLogType {
    Start = 'start',
    Pause = 'pause',
    Resume = 'resume',
    Stop = 'stop',
}

export type LearningSessionLog = {
    id: number;
    learning_session_id: number;
    type: LearningSessionLogType;
    occurred_at: Date;
    created_at?: Date;
    updated_at?: Date;
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

export type CreateLearningSession = Omit<LearningSession, 'id' | 'started_at' | 'ended_at' | 'status' | 'note' | 'created_at' | 'updated_at'>;
