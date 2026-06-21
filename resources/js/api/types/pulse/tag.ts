import type { ApiResponse } from '../base';

export interface PulseTagIndexResponse extends ApiResponse {
    data: {
        id: number;
        name: string;
        order_column: number;
    }[];
}
