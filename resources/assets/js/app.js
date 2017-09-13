import './bootstrap'
import Vue from 'vue'
import VueRouter from 'vue-router'

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

const app = new Vue({
  router
}).$mount('#app')
