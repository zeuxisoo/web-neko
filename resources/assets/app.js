import Vue from 'vue'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
import Api from './api'
import StorageHelper from './helpers/storage'
import MessageHelper from './helpers/message'
import filters from './filters'

Vue.use(VueRouter)
Vue.use(VueResource)

var Router = new VueRouter({
    hashbang: false,
    history: true,
    saveScrollPosition: true
});

Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector("meta[name=csrf-token]").content;
Vue.http.interceptors.push({
    request: function (request) {
        return request;
    },

    response: function (response) {
        // if (response.status === 500) {
        //     MessageHelper.error('Unknown server errors');
        //     Router.app.logout();
        // }

        if (response.status === 401) {
            MessageHelper.error('Session timeout, Please login again');
            Router.app.logout();
        }

        return response;
    }
});

Router.map({
    '/': {
        name     : 'home',
        component: require('./views/home.vue')
    },

    '/dashboard': {
        auth     : true,
        name     : 'dashboard',
        component: require('./views/dashboard.vue')
    },

    'bookmark': {
        auth     : true,
        name     : 'bookmark',
        component: require('./views/bookmark.vue')
    },

    'activity': {
        auth     : true,
        name     : 'activity',
        component: require('./views/activity.vue'),
        subRoutes: {
            '/': {
                name     : 'activity_index',
                component: require('./views/activity/index.vue')
            },

            '/create': {
                name     : 'activity_create',
                component: require('./views/activity/create.vue')
            },

            '/label/create': {
                name     : 'activity_label_create',
                component: require('./views/activity/label/create.vue')
            },

            '/label/manage': {
                name     : 'activity_label_manage',
                component: require('./views/activity/label/manage.vue')
            }
        }
    },

    '*': {
        name     : 'any',
        component: require('./views/not-found.vue')
    }
});

Router.beforeEach((transition) => {
    let token = StorageHelper.get('_token');

    if (transition.to.auth) {
        if (!token) {
            transition.redirect('/');
        }
    }

    if (transition.to.guest) {
        if (token) {
            transition.redirect('/');
        }
    }

    transition.next();
});

Object.defineProperties(Vue.prototype, {
    $api: {
        get: function() {
            return new Api(this).entries();
        }
    },
});

for(let filter in filters) {
    Vue.filter(filter, filters[filter]);
}

Router.start(Vue.extend(require('./views/app.vue')), '#app');
