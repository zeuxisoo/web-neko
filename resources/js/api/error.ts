class ApiError extends Error {
    constructor(...params: any[]) {
        super(...params);

        this.name = 'ApiError';
    }
}

export { ApiError };
