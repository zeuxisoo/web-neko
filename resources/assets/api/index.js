import Auth from './auth'
import User from './user'

export default class Api {

    constructor(vue) {
        this.vue = vue;
    }

    entries() {
        return {
            auth: new Auth(this.vue),
            user: new User(this.vue),
        }
    }

}
