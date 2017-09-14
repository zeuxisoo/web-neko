<template>
    <div id="home">
        <shake-error-panel>
            <div class="panel-heading">Sign in</div>
            <div class="panel-body">
                <div class="form-horizontal" v-on:keyup.enter="submit">
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
        </shake-error-panel>
    </div>
</template>

<script>
import ShakeErrorPanel from '../components/ShakeErrorPanel'

export default {
    name: 'home',

    mixins: [ShakeErrorPanel.mixin],

    components: {
        "shake-error-panel": ShakeErrorPanel.component
    },

    data() {
        return {
            account : "",
            password: ""
        }
    },

    methods: {
        submit() {
            if (this.account === "") {
                this.shakeError("Please enter account")
            }else if (this.password === "") {
                this.shakeError("Please enter password")
            }else{
                this.$api.auth.login({
                    account : this.account,
                    password: this.password
                }).then(response => {
                    let data = response.data
                    let token = data.token

                    if (token) {
                        this.$eventBus.emit('tokenSaved', token)
                    }else{
                        this.shakeError('Not found token value')
                    }
                }).catch(error => {
                    if (error.response) {
                        let data = error.response.data

                        if (data.message) {
                            this.shakeError(data.message)
                            return
                        }
                    }

                    console.log("Home.vue", error)
                })
            }
        }
    }
}
</script>
