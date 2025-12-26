class ValidateError extends Error {

    constructor(...params: any[]) {
        super(...params);

        this.name = "ValidateError";
    }

}

export default ValidateError;
