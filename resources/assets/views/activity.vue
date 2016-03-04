<template>
    <div id="activity">
        <div class="row">
            <div class="col-xs-12 col-sm-12 visible-xs-block visible-sm-block">
                <div class="panel panel-default">
                    <div class="panel-heading">Actions</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-3 col-sm-2 text-right">Activity:</div>
                            <div class="col-xs-9 col-sm-10 col-actions">
                                <a v-link="{ name: 'activity_index' }" class="btn btn-xs btn-default">All</a>
                                <a v-link="{ name: 'activity_create' }" class="btn btn-xs btn-default">Create</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-3 col-sm-2 text-right">Label:</div>
                            <div class="col-xs-9 col-sm-10 col-actions">
                                <a v-link="{ name: 'activity_label_create' }" class="btn btn-xs btn-default">All</a>
                                <a v-link="{ name: 'activity_label_manage' }" class="btn btn-xs btn-default">Manage</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 hidden-xs hidden-sm">
                <div class="panel panel-default">
                    <div class="panel-heading">Activity</div>
                    <div class="panel-body">
                        <ul class="nav nav-pills nav-stacked">
                            <li><a v-link="{ name: 'activity_index' }">All</a></li>
                            <li><a v-link="{ name: 'activity_create' }">Create</a></li>
                        </ul>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Label</div>
                    <div class="panel-body">
                        <ul class="nav nav-pills nav-stacked">
                            <li><a v-link="{ name: 'activity_label_create' }">Create</a></li>
                            <li><a v-link="{ name: 'activity_label_manage' }">Manage</a></li>
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
.col-actions {
    padding-left: 0px;
}
</style>

<script>
</script>
