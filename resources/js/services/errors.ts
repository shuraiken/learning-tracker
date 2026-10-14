export interface ApiError {
    status: number
    message: string
    errors?: Record<string, string[]>
    original?: unknown
}

export function isApiError(e: unknown): e is ApiError {
    return typeof e === 'object' && e !== null && 'status' in e;
}
