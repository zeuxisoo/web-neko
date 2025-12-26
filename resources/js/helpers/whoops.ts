import { ValidateError } from '@/validators';
import { toast } from 'vue-sonner';

export default class WhoopsHandler {

    static handleError(error: unknown | ValidateError, fallbackMessage: string) {
        if (error instanceof ValidateError) {
            toast.error(error.message);
            return;
        }

        if (!fallbackMessage || fallbackMessage === '') {
            fallbackMessage = 'Unknown error on whoops handler';
        }

        toast.error(fallbackMessage);
    }

}
