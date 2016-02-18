import Auth from './auth'

export default class Api {

    constructor(vue) {
        this.vue = vue;
    }

    entries() {
        return {
            auth: new Auth(this.vue)
        }
    }

}
