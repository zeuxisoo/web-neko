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
                            <router-link :to="{ name: 'home' }" class="nav-link">
                                <i class="glyphicon glyphicon-home"></i> Home
                            </router-link>
                        </li>
                        <li v-if="authenticated">
                            <router-link :to="{ name: 'dashboard' }" class="nav-link">
                                <i class="glyphicon glyphicon-dashboard"></i> Dashboard
                            </router-link>
                        </li>
                        <li v-if="authenticated">
                            <a href="javascript:void(0)" class="nav-link" v-on:click="logout">
                                <i class="glyphicon glyphicon-log-out"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container">
            <transition enter-active-class="animated bounceInUp" leave-active-class="animated bounceOutDown">
                <router-view></router-view>
            </transition>
        </div>
    </div>
</template>

<style>
body {
    padding-top: 70px;
}
</style>

<script>
import 'animate.css/animate.min.css'
import 'toastr/build/toastr.min.css'

import StorageHelper from '../helpers/storage'
import MessageHelper from '../helpers/message'

export default {
    name: 'app',

    data() {
        return {
            user         : {},
            authenticated: false
        }
    },

    created() {
        //
        this.$eventBus.on('logout', () => {
            this.logout()
        })

        //
        this.$eventBus.on('tokenSaved', token => {
            StorageHelper.set('_token', token)

            this.$http.defaults.headers.common['Authorization'] = "bearer " + token

            this.$api.user
                .me()
                .then(response => {
                    this.user          = response.user
                    this.authenticated = true

                    this.$router.push({
                        name: 'dashboard'
                    });
                }).catch(error => {
                    if (error.response) {
                        let data = error.response.data

                        if (data.status_code === 401) {
                            MessageHelper.error(data.message)
                        }else{
                            MessageHelper.error(error.statusText)
                        }

                        this.logout()

                        return
                    }

                    console.log("App.vue", error)
                })
        })

        // Re-activate login state when token also exists
        let token = StorageHelper.get('_token')

        if (token) {
            this.$eventBus.emit('tokenSaved', token)
        }
    },

    beforeDestroy() {
        this.$eventBus.off('logout')
        this.$eventBus.off('tokenSaved')
    },

    methods: {
        logout() {
            this.user          = {}
            this.authenticated = false

            StorageHelper.remove('_token')

            if (this.$route.meta.auth) {
                this.$router.push({
                    name: 'home'
                })
            }
        }
    }
}
</script>
