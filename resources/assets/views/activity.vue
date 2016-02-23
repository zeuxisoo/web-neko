<template>
    <div id="activity">
        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading">Activity</div>
                    <div class="panel-body">
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="#">All</a></li>
                            <li><a href="#">Create</a></li>
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
            <div class="col-md-9">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<style>
</style>

<script>
</script>
