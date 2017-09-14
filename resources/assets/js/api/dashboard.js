import Base from './base'

export default class Dashboard extends Base {

    constructor(vue) {
        super();

        this.vue = vue;
    }

    create(data) {
        return this.vue.$http.post(this.apiUrl('/dashboard/create'), data);
    }

    all(params) {
        return this.vue.$http.get(this.apiUrl('/dashboard/all'), params);
    }

}
