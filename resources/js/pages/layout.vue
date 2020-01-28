<template>
    <div class="content-wrapper">
        <div class="content-header row mb-1">
            <breadcrumbs/>
        </div>
        <div class="content-body">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    export default {
        name: "layout"
    }
</script>

<style scoped>

</style>