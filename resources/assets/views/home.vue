<template>
    <div id="home">
        <div class="panel panel-default" v-bind:class="{ 'shake': error, 'animated': error }">
            <div class="panel-heading">Sign in</div>
            <div class="panel-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label for="account" class="col-sm-2 control-label">Account</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="account" placeholder="Account" v-model="account">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="password" placeholder="Password" v-model="password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button class="btn btn-default" v-on:click="submit">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
</style>

<script>
import MessageHelper from '../helpers/message'

export default {

    data() {
        return {
            error   : false,
            account : "",
            password: ""
        }
    },

    methods: {

        submit() {
            if (this.account === "") {
                this.shakeError("Please enter account");
            }else if (this.password === "") {
                this.shakeError("Please enter password");
            }else{
                this.$api.auth.login({
                    account : this.account,
                    password: this.password
                }).then(
                    (response) => {
                        let data = response.data;
                        let token = data.token;

                        if (token) {
                            this.$dispatch('tokenSaved', token);
                        }else{
                            this.shakeError('Not found token value');
                        }
                    },
                    (response) => {
                        let reason = response.data;

                        if (reason.message) {
                            this.shakeError(reason.message);
                        }else{
                            this.shakeError('Unknown error');
                        }
                    }
                );
            }
        },

        shakeError(message) {
            MessageHelper.error(message);

            this.error = true;
            setTimeout(function() {
              this.error = false;
            }.bind(this), 1000);
        }

    }

}
</script>
