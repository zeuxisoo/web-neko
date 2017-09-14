import './bootstrap'
import Vue from 'vue'
import VueRouter from 'vue-router'
import Axios from 'axios'
import App from './views/App'
import Api from './api'
import EventBus from './helpers/event-bus'
import MessageHelper from './helpers/message'
import filters from './filters'

window.Vue = Vue

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    base: __dirname,
    routes: [
        {
            path     : '/',
            name     : 'home',
            component: require('./views/Home.vue')
        },
        {
            path     : '/dashboard',
            name     : 'dashboard',
            component: require('./views/Dashboard.vue'),
            meta     : {
                auth: true,
            }
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
            let agent = Axios.create({
                timeout: 1000,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN'    : document.head.querySelector('meta[name="csrf-token"]').content
                }
            })

            agent.interceptors.response.use(
                response => {
                    return response
                },
                error => {
                    if (error.response && error.response.status === 401) {
                        MessageHelper.error('Session timeout, Please login again')

                        // Trigger logout event if got 403 auth problem
                        application.$eventBus.emit('logout')
                    }else{
                        return Promise.reject(error)
                    }
                }
            )

            return agent
        }
    },

    $api: {
        get() {
            return new Api(this).entries()
        }
    },

    $eventBus: {
        value: new EventBus()
    }
})

Object.keys(filters).forEach(key => {
    Vue.filter(key, filters[key])
})

const application = new Vue({
    el: '#app',
    router: router,
    template: '<App/>',
    components: {
        App
    }
})
