<template>
    <div id="activity-label-manage">
            <div id="activity-label-create">
                <div class="panel panel-default">
                    <div class="panel-heading">Manage Label</div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-condensed">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Manage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="label in labels">
                                        <td>{{ label.id }}</td>
                                        <td>{{ label.name }}</td>
                                        <td>
                                            <a v-on:click="destory(label.id)">Delete</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
            labels: []
        }
    },

    route: {

        data() {
            this.fetchLabels();
        }

    },

    methods: {

        fetchLabels() {
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
        },

        destory(id) {
            this.$api.activity.deleteLabel({
                id: id
            }).then(
                (response) => {
                    this.fetchLabels();

                    MessageHelper.success('The label deleted');
                },
                (response) => {
                    MessageHelper.error('Cannot delete label');
                }
            )
        }

    }

}
</script>
