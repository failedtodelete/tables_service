<template>
    <div class="customizer border-left-blue-grey border-left-lighten-4 d-xl-block" v-bind:class="{ open: visible }">

        <!-- Иконка закрытия в верхней части компонента -->
        <a class="customizer-close" href="javascript:void(0);" v-if="title" v-on:click="$emit('toggle')"><i class="ft-x font-medium-3"></i></a>

        <!-- Toggle label -->
        <a class="customizer-toggle box-shadow-3" href="javascript:void(0);"
           :class="labelBackgroundColorClass"
           v-on:click="$emit('toggle')"><i :class="labelIconClass"></i>
        </a>

        <!-- Контент -->
        <div class="customizer-content-wrap p-2">
            <h4 class="text-uppercase mb-0" v-if="title">{{ title }}</h4>
            <hr v-if="title">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Customizer",
        props: {
            title: String,
            label: Object,
            visibleToggle: {
                type: Boolean,
                default: true
            },
            open: {
                type: Boolean,
                default: false
            }
        },
        data: function() {
            return {
                defaultLabelBackground: 'bg-danger',
                defaultLabelColor: 'white',
                defaultLabelIcon: 'ft-settings spinner',
                defaultLabelSize: 'font-medium-3'
            }
        },
        computed: {
            visible() {return this.open},
            labelIconClass() {
                if (this.label) {
                    let icon    = this.label.icon   ? this.label.icon   : this.defaultLabelIcon;
                    let color   = this.label.color  ? this.label.color  : this.defaultLabelColor;
                    let size    = this.label.size   ? this.label.size   : this.defaultLabelSize;
                    return `${icon} ${size} ${color}`;
                }
                return `${this.defaultLabelIcon} ${this.defaultLabelSize} ${this.defaultLabelColor}`;
            },
            labelBackgroundColorClass() {
                if (this.label) {
                    return this.label.bColor ? this.label.bColor : this.defaultLabelBackground;
                }
                return this.defaultLabelBackground;
            }
        }
    }
</script>

<style lang="scss">
    .customizer {
        z-index: 999;

        .customizer-content-wrap {
            position: relative;
            height: 100vh;
            overflow: hidden !important;

        }
        .customizer-content {
            overflow-y: scroll;
            position: relative;
            height: 100%;

            /* хром, сафари */
            &::-webkit-scrollbar { width: 0; }

            /* ie 10+ */
            -ms-overflow-style: none;

            /* фф (свойство больше не работает, других способов тоже нет)*/
            overflow: -moz-scrollbars-none;

            .customizer-section-header {
                margin-bottom: 15px;
                h5 {text-transform: uppercase;margin-bottom: 5px;}
                small {text-transform: uppercase;font-size: 0.8rem;display: block}
            }
        }
    }
</style>