import Base from './base'

export default class Bookmark extends Base {

    constructor(vue) {
        super();

        this.vue = vue;
    }

    create(data) {
        return this.vue.$http.post(this.apiUrl('/bookmark/create'), data);
    }

    all(params) {
        return this.vue.$http.get(this.apiUrl('/bookmark/all'), params);
    }

}
