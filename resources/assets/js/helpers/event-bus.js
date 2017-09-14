import Vue from 'vue'

export default class EventBus {

    constructor() {
        this.vue = new Vue()
    }

    emit(event, ...args) {
        this.vue.$emit(event, ...args)
    }

    on(event, callback) {
        this.vue.$on(event, callback)
    }

    off(event, callback) {
        this.vue.$off(event, callback)
    }

}
