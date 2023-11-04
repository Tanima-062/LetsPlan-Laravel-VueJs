<template>
    <div class="plan__header" :style="{backgroundColor: backgroundColor, borderLeft: border}" >
        <div class="plan__header--content">
            <h4>Anzahl Kinder ({{countPerDay}}/{{ availity ? maximumOccupancy : 0 }})</h4>
            <span @mouseover="tooltipShow = true" @mouseleave="tooltipShow = false" style="position:relative" class="plan-tooltip">
                <TooltipIconVue  />
                <!-- <template v-if="type == 'morning' && openingHours.length > 0   ">
                    <PlanTooltip type="morning" v-if="tooltipShow && openingHours[0].lead &&  openingHours[0].assistant  " :lead="openingHours[0].lead" :assistant="openingHours[0].assistant" />
                </template>
                <template v-if="type == 'evening' && openingHours.length > 1 ">
                    <PlanTooltip type="evening" v-if="tooltipShow && openingHours[1].lead && openingHours[1].assistant" :lead="openingHours[1].lead" :assistant="openingHours[1].assistant" />
                </template> -->
                <template v-if="openingHours.length  && openingHour ">
                    <PlanTooltip :type="openingHour.hour_id == 1 ?  'morning' : 'evening'" v-if="tooltipShow && openingHour.lead && openingHour.assistant" :lead="openingHour.lead" :assistant="openingHour.assistant" />
                </template>
            </span>
        </div>
    </div>
</template>

<script>

    import TooltipIconVue from '../Icons/TooltipIcon.vue';
    import PlanTooltip from './PlanTooltip.vue'
    export default {
        components: {
            TooltipIconVue,
            PlanTooltip,
        },
        props: ['smallItem', 'maximumOccupancy', 'countPerDay', 'openingHours', 'type'],
        data(){
            return {
                expand: false,
                tooltipShow: false,
            }
        },
        computed: {
            backgroundColor() {
                if (!this.availity){
                    return '#E6ECEF';
                }
                return this.countPerDay >= this.maximumOccupancy ?   '#FDF2EB' : '#ECF8F7'
            },
            borderColor(){
                if (!this.availity){
                    return '#000000';
                }

                return this.countPerDay >= this.maximumOccupancy ?   '#E97D32' : '#42BEAF'
            },
            availity(){
                if ( this.maximumOccupancy === 0){
                    return false
                }else {
                    if(this.openingHours.length == 2){
                        if(this.type == 'morning' && this.openingHours[0].lead == null) {
                            return false
                        }
                        if(this.type == 'evening' && this.openingHours[1].lead == null) {
                            return false
                        }
                    }
                    if(this.openingHours.length == 1){
                        if(this.type == 'morning' && this.openingHours[0].lead == null) {
                            return false
                        }
                        if(this.type == 'evening' && this.openingHours[0].lead == null) {
                            return false
                        }
                    }
                }

                return true;
            },
            border(){
                return `8px solid ${this.borderColor}`
            },
            openingHour(){
                if(this.type == 'morning'){
                    return this.openingHours.find(item=>item.hour_id == 1)
                }
                if(this.type == 'evening'){
                    return this.openingHours.find(item=>item.hour_id == 2)
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
     .plan {
        position: relative;

        &__header {
            padding: 8px;
            // border-left: 8px solid blueviolet;
            // background: #9CDAE5;;
            &--content {
                padding-left: 7px;
                // padding-top: 12px;
                // padding-bottom: 10px;
                display: flex;
                align-items: center;

                h4 {
                    margin: 0;
                    padding: 0;
                    font-family: 'Ubuntu';
                    font-style: normal;
                    font-weight: 400;
                    font-size: 15px;
                    line-height: 17px;
                    color: #2C5B7D;
                    margin: 0;
                    margin-right: 6px;
                }

                .plan-tooltip {
                    line-height: 8px;
                }
            }
        }
    }
</style>
