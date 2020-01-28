<template>
    <transition name="fade" mode="out-in" v-if="show">
        <div class="loader-wrap">
            <div class="loader">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" :height="height" :width="width" :fill="color">
                    <rect x="0" y="13" width="4" height="5">
                        <animate attributeName="height" attributeType="XML"
                                 values="5;21;5"
                                 begin="0s" dur="0.6s" repeatCount="indefinite"/>
                        <animate attributeName="y" attributeType="XML"
                                 values="13; 5; 13"
                                 begin="0s" dur="0.6s" repeatCount="indefinite"/>
                    </rect>
                    <rect x="10" y="13" width="4" height="5">
                        <animate attributeName="height" attributeType="XML"
                                 values="5;21;5"
                                 begin="0.15s" dur="0.6s" repeatCount="indefinite"/>
                        <animate attributeName="y" attributeType="XML"
                                 values="13; 5; 13"
                                 begin="0.15s" dur="0.6s" repeatCount="indefinite"/>
                    </rect>
                    <rect x="20" y="13" width="4" height="5">
                        <animate attributeName="height" attributeType="XML"
                                 values="5;21;5"
                                 begin="0.3s" dur="0.6s" repeatCount="indefinite"/>
                        <animate attributeName="y" attributeType="XML"
                                 values="13; 5; 13"
                                 begin="0.3s" dur="0.6s" repeatCount="indefinite"/>
                    </rect>
                </svg>
            </div>
        </div>
    </transition>
</template>

<script>

    export default {
        name: "Loader",
        props: {
            show: {
                type: Boolean,
                default: false
            },
            width:  {type: Number,  default: 50},
            height: {type: Number,  default: 50},
            color:  {type: String,  default: '#000'},
            full:   {type: Boolean, default: true}
        }
    }

</script>

<style lang="scss">
    .fade-leave-active {
        transition: opacity 0.6s;
    }
    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
        opacity: 0;
    }
    .loader-wrap {
        background-color: #fff;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 799;

        .loader {
            position: absolute;
            transform: translate(-50%, -50%);
            left: 50%;
            top: 50%;
        }
    }
</style>