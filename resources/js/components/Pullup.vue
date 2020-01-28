<template>
    <div class="card pull-up" v-bind:class="[{ 'pull-up-hover' : options && options.hover }, type, {active: active}]">
        <div class="card-content">
            <div class="card-body p-1">
                <div class="media d-flex">
                    <div class="media-body text-left">
                        <h3 :class="type">{{ count ? count : "-" }}</h3>
                        <h6 class="mb-0">{{ title && title.length ? title : '-' }}</h6>
                    </div>
                    <div v-if="!miniature">
                        <i class="font-large-2 float-right" :class="[type, icon]"></i>
                    </div>
                </div>
                <!--<div class="progress progress-sm mt-1 mb-0 box-shadow-2">-->
                    <!--<div class="progress-bar" v-bind:class="'bg-gradient-x-' + type" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>-->
                <!--</div>-->
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Pullup",
        props: {
            options: Object,
            type: String,
            title: String,
            count: Number,
            active: {
                type: Boolean,
                default: false
            },
            miniature: {
                type: Boolean,
                default: false
            },
            icon: {
                type: String,
                default: 'icon-bulb'
            },
        }
    }
</script>

<style lang="scss"></style>