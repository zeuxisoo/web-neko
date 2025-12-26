import { ValidateError } from "@/validators";
import { toast } from "vue-sonner";

export default class WhoopsHandler {

    static handleError(error: unknown | ValidateError, fallbackMessage = "Unknown error on whoops handler") {
        if (error instanceof ValidateError) {
            toast.error(error.message);
            return;
        }

        toast.error(fallbackMessage);
    }

}
