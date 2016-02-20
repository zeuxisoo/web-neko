import Auth from './auth'
import User from './user'
import Dashboard from './dashboard'

export default class Api {

    constructor(vue) {
        this.vue = vue;
    }

    entries() {
        return {
            auth      : new Auth(this.vue),
            user      : new User(this.vue),
            dashboard : new Dashboard(this.vue),
        }
    }

}
