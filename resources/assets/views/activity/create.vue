<template>
    <div id="activity-create">
        <div class="panel panel-default">
            <div class="panel-heading">Create Activity</div>
            <div class="panel-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <select class="form-control" id="label" v-model="label">
                                <option v-for="label in labels" v-bind:value="label.id">{{ label.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input class="form-control" id="activity_at" placeholder="YYYY-mm-dd HH:ii:ss" v-model="activity_at">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <textarea class="form-control" id="remark" placeholder="Remark" rows="5" v-model="remark"></textarea>
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
import MessageHelper from '../../helpers/message'
import moment from 'moment'

export default {

    data() {
        return {
            label      : 1,
            labels     : [],
            activity_at: moment().format('YYYY-MM-DD HH:mm:ss'),
            remark     : ""
        }
    },

    route: {

        data() {
            this.fetchLables()
        }

    },

    methods: {

        create() {
            if (this.label === "") {
                this.shakeError("Please select label");
            }else if (this.activity_at === "") {
                this.shakeError("Please enter activity date and time");
            }else{
                this.$api.activity.create({
                    label_id   : this.label,
                    activity_at: this.activity_at,
                    remark     : this.remark
                }).then(
                    (response) => {
                        MessageHelper.success('The activity created');
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

        fetchLables() {
            this.$api.activity.allLabel().then(
                (response) => {
                    let data       = response.data;
                    let labels     = data.data;

                    this.labels     = labels;
                },
                (response) => {
                    MessageHelper.error('Cannot fetch activity label list');
                }
            );
        }

    }

}
</script>
