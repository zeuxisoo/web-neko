<template>
    <div id="activity-index">
        <div class="panel panel-default">
            <div class="panel-heading">Activity Labels</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <span class="btn btn-xs btn-default label-space" v-for="label in labels">
                            <a >{{ label.name }}</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.label-space {
    margin: 0px 2px;
}

.label-space a {
    text-decoration: none;
}
</style>

<script>
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
                    let data    = response.data;
                    let labels  = data.data;

                    this.labels = labels;
                },
                (response) => {
                    MessageHelper.error('Cannot fetch activity label list');
                }
            );
        },

    }

}
</script>
