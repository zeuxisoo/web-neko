import './bootstrap'
import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './views/App'

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
});

const application = new Vue({
    el: '#app',
    router: router,
    template: '<App/>',
    components: {
        App
    }
})
