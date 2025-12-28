import { UseFetchReturn } from "@vueuse/core"
import useAgent from "./useAgent"

export default {

    login(payload: LoginPayload): UseFetchReturn<LoginResponse> & PromiseLike<UseFetchReturn<LoginResponse>> {
        return useAgent<LoginResponse>('auth/login').post(payload);
    }

}
