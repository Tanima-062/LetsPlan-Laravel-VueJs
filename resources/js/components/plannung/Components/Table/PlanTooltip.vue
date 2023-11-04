<template>
    <div class="plan_tooltip" v-if="lead && assistant" :style="positionStyle">
        <div class="tooltip__header" :style="iconPositionStyle">
            <TooltipIcon />
        </div>
        <div class="tooltip__body">
            <div class="tooltip__body--item">
                <span class="left">Leiter/in: </span>
                <span class="right">{{ lead.first_name }} {{ lead.last_name }}</span>
            </div>
            <div class="tooltip__body--item">
                <span class="left">Assistent/in: </span>
                <span class="right">{{ assistant.first_name }} {{ assistant.last_name }}</span>
            </div>
        </div>
    </div>
</template>

<script>
import TooltipIcon from '../Icons/TooltipIcon.vue';
    export default {
    components: { TooltipIcon },
    props: {
        lead: {
            type: Object,
            required: true
        },
        assistant: {
            type: Object,
            required: true
        },
        type: {
            type: String,
            default: 'evening'
        }
    },
    computed: {
        positionStyle(){
            if(this.type == 'morning'){
                return 'left: 0'
            }else {
                return 'right: 0';
            }
        },
        iconPositionStyle(){
            if(this.type == 'morning'){
                return 'justify-content: flex-start;'
            }else {
                return 'justify-content: flex-end;'
            }
        }
    }
}
</script>

<style lang="scss" scoped>
    .plan_tooltip {
        position: absolute;
        z-index: 9999;
        background: #FAFCFF;
        width: 300px;
        padding: 2px;
        top: 0;
        // left: 0;

        box-shadow: 9px 9px 34px -27px rgba(0,0,0,1);
        -webkit-box-shadow: 9px 9px 34px -27px rgba(0,0,0,1);
        -moz-box-shadow: 9px 9px 34px -27px rgba(0,0,0,1);

        .tooltip__header {
            height: 14px;
            display: flex;
            svg {
                line-height: initial;
                vertical-align: super;
            }
        }
        .tooltip__body {
            padding: 16px;
            padding-top: 5px;
            padding-bottom: 10px;

            // gap: 16px;

            &--item {
                display: flex;
                // justify-content: space-between;
                margin-bottom: 10px;

                .left {
                    width: 100px;
                    margin-right: 15px;
                }
                .right {
                    // width: 200px;
                }

                &:last-child {
                    margin-bottom: 0;
                }

                span {
                    font-family: 'Nexa';
                    font-style: normal;
                    font-weight: 400;
                    font-size: 16px;
                    line-height: 24px;
                    color: #2C5B7D;
                }
            }
        }

    }
</style>
