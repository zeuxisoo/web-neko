<template>
    <div id="activity">
        <div class="row visible-xs-block visible-sm-block">
            <div class="col-xs-12 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Actions</div>
                    <div class="panel-body">
                        <div class="row row-action">
                            <div class="col-xs-3 col-sm-2 text-right">Activity:</div>
                            <div class="col-xs-9 col-sm-10 col-actions">
                                <router-link :to="{ name: 'activity_index' }" class="btn btn-xs btn-default">
                                    All
                                </router-link>
                                <router-link :to="{ name: 'activity_create' }" class="btn btn-xs btn-default">
                                    Create
                                </router-link>
                            </div>
                        </div>
                        <div class="row row-action">
                            <div class="col-xs-3 col-sm-2 text-right">Label:</div>
                            <div class="col-xs-9 col-sm-10 col-actions">
                                <router-link :to="{ name: 'activity_label_create' }" class="btn btn-xs btn-default">
                                    All
                                </router-link>
                                <router-link :to="{ name: 'activity_label_manage' }" class="btn btn-xs btn-default">
                                    Manage
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 hidden-xs hidden-sm">
                <div class="panel panel-default">
                    <div class="panel-heading">Activity</div>
                    <div class="panel-body">
                        <ul class="nav nav-pills nav-stacked">
                            <li>
                                <router-link :to="{ name: 'activity_index' }">
                                    All
                                </router-link>
                            </li>
                            <li>
                                <router-link :to="{ name: 'activity_create' }">
                                    Create
                                </router-link>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Label</div>
                    <div class="panel-body">
                        <ul class="nav nav-pills nav-stacked">
                            <li>
                                <router-link :to="{ name: 'activity_label_create' }">
                                    Create
                                </router-link>
                            </li>
                            <li>
                                <router-link :to="{ name: 'activity_label_manage' }">
                                    Manage
                                </router-link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-9">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<style>
.row-action:nth-child(n+2) {
    margin-top: 10px;
}

.col-actions {
    padding-left: 0px;
}
</style>

<script>
</script>
