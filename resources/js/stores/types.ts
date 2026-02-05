export type LearningSession = {
    id: number;
    learning_id: number;
    started_at?: Date;
    ended_at?: Date;
    hours_spent?: number;
    note?: string;
    created_at?: Date;
    updated_at?: Date;
};

export type CreateLearningSession = Omit<LearningSession, 'id' | 'started_at' | 'ended_at' | 'hours_spent' | 'note' | 'created_at' | 'updated_at'>;
