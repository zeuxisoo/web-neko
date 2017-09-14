import Base from './base'

export default class Auth extends Base {

    constructor(vue) {
        super();

        this.vue = vue
    }

    login(data) {
        return this.vue.$http.post(this.apiUrl('/auth/login'), data)
    }

}
