export default class StorageHelper {

    static set(key, value) {
        if (Object.prototype.toString.call(value) === '[object Function]') {
            throw new TypeError('Cannot store function')
        }

        if (Object.prototype.toString.call(value) === '[object Object]') {
            value = JSON.stringify(value);
        }

        sessionStorage[key] = value
    }

    static get(key) {
        let value = sessionStorage[key]

        try{
            return JSON.parse(value);
        }catch(e) {
            return value;
        }
    }

    static remove(key) {
        delete sessionStorage[key]
    }

    static clear() {
        sessionStorage.clear()
    }

}
