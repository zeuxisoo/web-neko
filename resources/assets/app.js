import Vue from 'vue'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
import Api from './api'

Vue.use(VueRouter)
Vue.use(VueResource)

var Router = new VueRouter({
    hashbang: false,
    history: true,
    saveScrollPosition: true
});

Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector("meta[name=csrf-token]").content;

Router.map({
    '/': {
        name     : 'home',
        component: require('./views/home.vue')
    },

    '*': {
        name     : 'any',
        component: require('./views/not-found.vue')
    }
});

Object.defineProperties(Vue.prototype, {
    $api: {
        get: function() {
            return new Api(this).entries();
        }
    },
});

Router.start(Vue.extend(require('./views/app.vue')), '#app');
