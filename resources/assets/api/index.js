import User from './user'

export default class Api {

    constructor(vue) {
        this.vue = vue;
    }

    entries() {
        return {
            user: new User(this.vue)
        }
    }

}
