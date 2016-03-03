<template>
    <div id="app">
        <div class="navbar navbar-default navbar-fixed-top navbar-app" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand">Brand</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li v-if="!authenticated">
                            <a class="nav-link">
                                <i class="glyphicon glyphicon-home"></i> Home
                            </a>
                        </li>

                        <li v-if="authenticated">
                            <a class="nav-link" v-link="{ name: 'dashboard' }">
                                <i class="glyphicon glyphicon-dashboard"></i> Dashboard
                            </a>
                        </li>
                        <li v-if="authenticated">
                            <a class="nav-link" v-link="{ name: 'bookmark' }">
                                <i class="glyphicon glyphicon-bookmark"></i> Bookmark
                            </a>
                        </li>
                        <li v-if="authenticated">
                            <a class="nav-link" v-link="{ name: 'activity' }">
                                <i class="glyphicon glyphicon-stats"></i> Activity
                            </a>
                        </li>
                        <li v-if="authenticated">
                            <a class="nav-link" v-on:click="logout">
                                <i class="glyphicon glyphicon-log-out"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>

<style>
body {
    padding-top: 70px;
}
</style>

<script>
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.min'
import 'animate.css/animate.min.css'
import 'toastr/build/toastr.min.css'
import '../css/app.css'
import Vue from 'vue'
import StorageHelper from '../helpers/storage'
import MessageHelper from '../helpers/message'

export default {

    data() {
        return {
            user         : {},
            authenticated: false
        }
    },

    ready() {
        $('.navbar-collapse').click('li', function() {
            $('.navbar-collapse').collapse('hide');
        });

        this.$on('userLogout', () => {
            this.logout();
        });

        this.$on('tokenSaved', (token) => {
            StorageHelper.set('_token', token);

            Vue.http.headers.common['Authorization'] = "bearer " + token;

            this.$api.user
                .me()
                .then(
                    (response) => {
                        this.user          = response.user;
                        this.authenticated = true;

                        this.$route.router.go({
                            name: 'dashboard'
                        });
                    },
                    (response) => {
                        let reason = response.data;

                        if (reason.status_code === 401) {
                            MessageHelper.error(reason.message);
                        }else{
                            MessageHelper.error(response.statusText);
                        }

                        this.logout();
                    }
                )
        });

        // Re-activate login state when token also exists
        let token = StorageHelper.get('_token');

        if (token) {
            this.$emit('tokenSaved', token);
        }
    },

    methods: {
        logout() {
            this.user          = {};
            this.authenticated = false;

            StorageHelper.remove('_token');

            if (this.$route.auth) {
                this.$route.router.go({
                    name: 'home'
                });
            }
        }
    }

}
</script>
