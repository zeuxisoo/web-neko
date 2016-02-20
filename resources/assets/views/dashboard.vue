<template>
    <div id="dashboard">
        <div class="panel panel-default" v-bind:class="{ 'shake': error, 'animated': error }">
            <div class="panel-heading">Dashboard</div>
            <div class="panel-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="subject" placeholder="Subject" v-model="subject">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <textarea class="form-control" id="content" placeholder="Content" rows="5" v-model="content"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-default" v-on:click="create">Create</button>
                        </div>
                    </div>
                </form>
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
            subject: "",
            content: "",
            error  : false
        }
    },

    methods: {

        create() {
            if (this.content === "") {
                this.shakeError("Please enter content");
            }else{
                this.$api.dashboard.create({
                    subject: this.subject,
                    content: this.content
                }).then(
                    (response) => {
                        MessageHelper.success('Dashboard message created');
                    },
                    (response) => {
                        let reason = response.data;
                        let errors = reason.errors;

                        if (errors) {
                            Object.keys(errors).forEach((key) => {
                                MessageHelper.error(errors[key].shift());
                                return;
                            });
                        }else{
                            let message = 'message' in reason ? reason.message : 'Unknown errors';

                            MessageHelper.error(message);
                        }
                    }
                )
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
