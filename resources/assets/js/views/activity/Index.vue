<template>
    <div id="activity-index">
        <div class="panel panel-default">
            <div class="panel-heading">Activity Labels</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <span class="btn btn-xs btn-default label-space" v-for="label in labels">
                            <router-link :to="{ name: 'activity_index', query: { label: label.id } }">
                                {{ label.name }}
                            </router-link>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Activities</div>
            <div class="panel-body">
                <div class="row row-activity" v-for="activity in activities">
                    <div class="col-sm-12">
                        <label class="label label-default">{{ activity.activity_at }}</label>
                        <label class="label label-info">{{ activity.label.name }}</label>
                        <small class="text-muted">{{ activity.remark | byDefault('No remark') }}</small>
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

.row-activity:nth-child(n+2) {
    padding-top: 10px;
}
</style>

<script>
import MessageHelper from '../../helpers/message';

export default {
    name: 'activity-index',

    data() {
        return {
            labels    : [],
            activities: []
        }
    },

    created() {
        this.fetchLabels()
        this.fetchActivitiesByLabel()
    },

    watch: {
        $route() {
            this.fetchLabels()
            this.fetchActivitiesByLabel()
        }
    },

    methods: {
        fetchLabels() {
            this.$api.activity
            .allLabel()
            .then(response => {
                let data    = response.data
                let labels  = data.data

                this.labels = labels
            }).catch(error => {
                    MessageHelper.error('Cannot fetch activity label list')
            })
        },

        fetchActivitiesByLabel() {
            let label = 'label' in this.$route.query ? this.$route.query.label : ""

            this.fetchActivities(label)
        },

        fetchActivities(label) {
            this.$api.activity
            .all({
                label: label
            }).then(response => {
                let data       = response.data
                let activities = data.data

                this.activities = activities
            }).catch(error => {
                MessageHelper.error('Cannot fetch activity list')
            })
        }
    }

}
</script>
