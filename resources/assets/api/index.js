import Auth from './auth'
import User from './user'
import Dashboard from './dashboard'
import Bookmark from './bookmark'

export default class Api {

    constructor(vue) {
        this.vue = vue;
    }

    entries() {
        return {
            auth      : new Auth(this.vue),
            user      : new User(this.vue),
            dashboard : new Dashboard(this.vue),
            bookmark  : new Bookmark(this.vue),
        }
    }

}
