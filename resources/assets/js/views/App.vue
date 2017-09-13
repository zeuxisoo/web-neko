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
import Vue from 'vue'

export default {
    name: 'app',

    data() {
        return {
            authenticated: false
        }
    }
}
</script>
