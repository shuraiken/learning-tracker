import axios, { AxiosError } from 'axios';

export const api = axios.create({
    baseURL: '',
    withCredentials: true,
    headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
    },
});

api.interceptors.response.use(
    (response) => response,
    (error: AxiosError<{ message?: string; errors?: Record<string, string[]> }>) => {
        const status = error.response?.status ?? 0;
        const data = error.response?.data;

        const normalized = {
            status,
            message: typeof data === 'object' && data?.message ? data.message : httpStatusMessage(status),
            errors: status == 422 && typeof data === 'object' ? data?.errors : undefined,
            original: error,
        };

        if (import.meta.env.DEV) {
            console.error(`[API] ${error.config?.method?.toUpperCase()} ${error.config?.url} -> ${status}`, normalized.message);
        }

        throw normalized;
    },
);

function httpStatusMessage(status: number): string {
    const messages: Record<number, string> = {
        401: 'Session expired. Please log in again.',
        419: 'Page expired. Please refresh.',
        403: 'You do not have permission.',
        404: 'Resource not found.',
        500: 'Server error. Please try again.',
    };

    return messages[status] ?? 'An error occurred.';
}
