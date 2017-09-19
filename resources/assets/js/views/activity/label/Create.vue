<template>
    <div id="activity-label-create">
        <shake-error-panel>
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
        </shake-error-panel>
    </div>
</template>

<style>
</style>

<script>
import ShakeErrorPanel from '../../../components/ShakeErrorPanel'
import MessageHelper from '../../../helpers/message'

export default {
    name: 'activity-label-create',

    mixins: [ShakeErrorPanel.mixin],

    components: {
        "shake-error-panel": ShakeErrorPanel.component
    },

    data() {
        return {
            name : "",
        }
    },

    methods: {
        create() {
            if (this.name === "") {
                this.shakeError("Please enter name")
            }else{
                this.$api.activity
                .createLabel({
                    name: this.name
                }).then(response => {
                    MessageHelper.success('Activity label created')

                    this.name = ""
                }).catch(error => {
                    if (error.response) {
                        let data   = error.response.data
                        let errors = data.errors

                        if (errors) {
                            Object.keys(errors).forEach((key) => {
                                MessageHelper.error(errors[key].shift())
                                return
                            })
                        }else{
                            let message = 'message' in data ? data.message : 'Unknown errors'

                            MessageHelper.error(message)

                            return
                        }
                    }

                    console.log("Activity.Label.Create.vue", error)
                })
            }
        },
    }

}
</script>
