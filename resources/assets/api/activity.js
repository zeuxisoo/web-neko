import Base from './base'

export default class Activity extends Base {

    constructor(vue) {
        super();

        this.vue = vue;
    }

    createLabel(data) {
        return this.vue.$http.post(this.apiUrl('/activity/label/create'), data);
    }

}
