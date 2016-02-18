import Base from './base'

export default class User extends Base {

    constructor(vue) {
        super();

        this.vue = vue;
    }

    login() {
        return this.vue.$http.post(this.apiUrl('/auth/login'));
    }

}
