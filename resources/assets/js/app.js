import './bootstrap'
import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './views/App'
import Api from './api'
import axios from 'axios'

window.Vue = Vue

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    base: __dirname,
    routes: [
        {
            path     : '/',
            name     : 'home',
            component: require('./views/home.vue')
        },
        {
            path    : '*',
            redirect: '/'
        }
    ]
})

Object.defineProperties(Vue.prototype, {
    $http: {
        get() {
            return axios.create({
                timeout: 1000,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN'    : document.head.querySelector('meta[name="csrf-token"]').content
                }
            })
        }
    },

    $api: {
        get() {
            return new Api(this).entries();
        }
    },
})

const application = new Vue({
    el: '#app',
    router: router,
    template: '<App/>',
    components: {
        App
    }
})
