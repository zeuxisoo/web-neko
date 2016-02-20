import Base from './base'

export default class Dashboard extends Base {

    constructor(vue) {
        super();

        this.vue = vue;
    }

    create(data) {
        return this.vue.$http.post(this.apiUrl('/dashboard/create'), data);
    }

}
