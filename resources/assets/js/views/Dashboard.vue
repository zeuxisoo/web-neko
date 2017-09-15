<template>
    <div id="dashboard">
        <shake-error-panel>
            <div class="panel-heading">Dashboard</div>
            <div class="panel-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="subject" placeholder="Subject" v-model="subject">
                            <p class="help-block help-tips">Optional, Please keep it blank if you have not subject</p>
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
        </shake-error-panel>

        <div class="panel panel-default" v-for="message in messages">
            <div class="panel-heading">{{ message.subject | byDefault('N/A') }}</div>
            <div class="panel-body">
                {{ message.content }}
            </div>
        </div>

        <div>
            <ul class="pager">
                <li class="previous" v-if="hasPrevious">
                    <router-link :to="{ name: 'dashboard', query: { page: pagination.current_page - 1 } }">
                        <span aria-hidden="true">&larr;</span> Older
                    </router-link>
                </li>
                <li class="next" v-if="hasNext">
                    <router-link :to="{ name: 'dashboard', query: { page: pagination.current_page + 1 } }">
                        Newer <span aria-hidden="true">&rarr;</span></a>
                    </router-link>
                </li>
            </ul>
        </div>
    </div>
</template>

<style>
.help-tips {
    margin: 5px 0px 0px 10px;
    font-size: 12px;
}
</style>

<script>
import ShakeErrorPanel from '../components/ShakeErrorPanel'
import MessageHelper from '../helpers/message'

export default {
    name: 'dashboard',

    mixins: [ShakeErrorPanel.mixin],

    components: {
        "shake-error-panel": ShakeErrorPanel.component
    },

    data() {
        return {
            subject: "",
            content: "",

            page      : 1,
            messages  : [],
            pagination: {}
        }
    },

    created() {
        this.fetchMessagesByPageNo()
    },

    watch: {
        $route() {
            this.fetchMessagesByPageNo()
        }
    },

    computed: {
        hasNext() {
            return this.pagination.current_page < this.pagination.total_pages
        },

        hasPrevious() {
            return this.pagination.current_page - 1 > 0
        }
    },

    methods: {
        create() {
            if (this.content === "") {
                this.shakeError("Please enter content")
            }else{
                this.$api.dashboard.create({
                    subject: this.subject,
                    content: this.content
                }).then(response => {
                    MessageHelper.success('Dashboard message created')

                    this.subject = ""
                    this.content = ""

                    this.fetchMessages(1)
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

                    console.log("Dashboard.vue", error)
                })
            }
        },

        fetchMessagesByPageNo() {
            let page = 'page' in this.$route.query ? this.$route.query.page : 1

            this.fetchMessages(page)
        },

        fetchMessages(page) {
            this.$api.dashboard
            .all({
                page: page
            })
            .then(response => {
                let data       = response.data
                let messages   = data.data
                let pagination = data.meta.pagination

                this.messages   = messages
                this.pagination = pagination
            }).catch(response => {
                MessageHelper.error('Cannot fetch dashboard messages')
            })
        }
    }

}
</script>
