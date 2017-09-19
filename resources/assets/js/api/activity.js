import Base from './base'

export default class Activity extends Base {

    constructor(vue) {
        super()

        this.vue = vue
    }

    createLabel(data) {
        return this.vue.$http.post(this.apiUrl('/activity/label/create'), data)
    }

    allLabel() {
        return this.vue.$http.get(this.apiUrl('/activity/label/all'))
    }

    deleteLabel(data) {
        return this.vue.$http.post(this.apiUrl('/activity/label/delete'), data)
    }

    create(data) {
        return this.vue.$http.post(this.apiUrl('/activity/create'), data)
    }

    all(params) {
        return this.vue.$http.get(this.apiUrl('/activity/all'), {
            params: params
        })
    }

}
