export default {
    byDefault(value, defaultValue) {
        return value === "" || value === null ? defaultValue : value;
    }
}
