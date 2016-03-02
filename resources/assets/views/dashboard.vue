<template>
    <div id="dashboard">
        <shake-error-panel>
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
        <shake-error-panel>

        <div class="panel panel-default" v-for="message in messages">
            <div class="panel-heading">{{ message.subject | byDefault 'N/A' }}</div>
            <div class="panel-body">
                {{ message.content }}
            </div>
        </div>

        <div>
            <ul class="pager">
                <li class="previous" v-if="hasPrevious">
                    <a v-link="{ name: 'dashboard', query: { page: pagination.current_page - 1 } }">
                        <span aria-hidden="true">&larr;</span> Older
                    </a>
                </li>
                <li class="next" v-if="hasNext">
                    <a v-link="{ name: 'dashboard', query: { page: pagination.current_page + 1 } }">
                        Newer <span aria-hidden="true">&rarr;</span></a>
                    </li>
                </li>
            </ul>
        </div>
    </div>
</template>

<style>
</style>

<script>
import ShakeErrorPanel from '../components/shake-error-panel'
import MessageHelper from '../helpers/message'

export default {

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

    route: {

        data() {
            let page = 'page' in this.$route.query ? this.$route.query.page : 1;

            this.fetchMessages(page);
        }

    },

    computed: {

        hasNext() {
            return this.pagination.current_page < this.pagination.total_pages;
        },

        hasPrevious() {
            return this.pagination.current_page - 1 > 0;
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
                        this.fetchMessages(1);
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

        fetchMessages(page) {
            this.$api.dashboard
            .all({
                page: page
            })
            .then(
                (response) => {
                    let data       = response.data;
                    let messages   = data.data;
                    let pagination = data.meta.pagination;

                    this.messages   = messages;
                    this.pagination = pagination;
                },
                (response) => {
                    MessageHelper.error('Cannot fetch dashboard messages');
                }
            )
        }

    }

}
</script>
