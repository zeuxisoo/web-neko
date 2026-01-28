import { toast } from 'vue-sonner';

export default class WhoopsHandler {
    static handleError(error: unknown, fallbackMessage?: string) {
        if (error instanceof Error) {
            const name = error.name;

            if (['ValidateError', 'ApiError'].includes(name)) {
                toast.error(error.message);
            } else {
                toast.error(error.message);
                console.log(error);
            }

            return;
        }

        if (!fallbackMessage || fallbackMessage === '') {
            fallbackMessage = 'Unknown error on whoops handler';
        }

        toast.error(fallbackMessage);
        console.log(error);
    }
}
