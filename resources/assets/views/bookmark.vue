<template>
    <div id="bookmark">
        <shake-error-panel>
            <div class="panel-heading">Bookmark</div>
            <div class="panel-body">
                <form class="form-horizontal">
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

        <div class="panel panel-default" v-for="bookmark in bookmarks">
            <div class="panel-heading">{{ bookmark.created_at }}</div>
            <div class="panel-body">
                {{ bookmark.content }}
            </div>
        </div>

        <div>
            <ul class="pager">
                <li class="previous" v-if="hasPrevious">
                    <a v-link="{ name: 'bookmark', query: { page: pagination.current_page - 1 } }">
                        <span aria-hidden="true">&larr;</span> Older
                    </a>
                </li>
                <li class="next" v-if="hasNext">
                    <a v-link="{ name: 'bookmark', query: { page: pagination.current_page + 1 } }">
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
            content: "",

            page      : 1,
            bookmarks : [],
            pagination: {}
        }
    },

    route: {

        data() {
            let page = 'page' in this.$route.query ? this.$route.query.page : 1;

            this.fetchBookmarks(page);
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
                this.$api.bookmark.create({
                    content: this.content
                }).then(
                    (response) => {
                        MessageHelper.success('The content was bookmarked');
                        this.fetchBookmarks(1);
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

        fetchBookmarks(page) {
            this.$api.bookmark
            .all({
                page: page
            })
            .then(
                (response) => {
                    let data       = response.data;
                    let bookmarks  = data.data;
                    let pagination = data.meta.pagination;

                    this.bookmarks  = bookmarks;
                    this.pagination = pagination;
                },
                (response) => {
                    MessageHelper.error('Cannot fetch bookmark');
                }
            )
        }

    }

}
</script>
