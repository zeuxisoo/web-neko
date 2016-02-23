<template>
    <div id="activity-label-create">
        <div class="panel panel-default" v-bind:class="{ 'shake': error, 'animated': error }">
            <div class="panel-heading">Create Label</div>
            <div class="panel-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input class="form-control" id="name" placeholder="Name" v-model="name">
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
import MessageHelper from '../../../helpers/message'

export default {

    data() {
        return {
            name : "",
            error: false
        }
    },

    methods: {

        create() {
            if (this.name === "") {
                this.shakeError("Please enter name");
            }else{
                this.$api.activity.createLabel({
                    name: this.name
                }).then(
                    (response) => {
                        MessageHelper.success('Activity label created');
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
        },

    }

}
</script>
