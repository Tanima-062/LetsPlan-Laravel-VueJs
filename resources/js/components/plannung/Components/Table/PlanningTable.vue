<template>
    <div class="planning__table">
        <div class="planning__table--header" style="height: 68px;">
            <div class="planning__table--header__row first"></div>
            <div class="planning__table--header__row middle">{{ plannung.morning_time_range  | formatTimeRange}} </div>
            <div class="planning__table--header__row last">{{ plannung.afternoon_time_range | formatTimeRange }} </div>
        </div>
        <div class="planning__table--body">
            <div class="table__row" v-for="(day, dayIndex) in days" :key="day.id" :class="{'hasSlots':updatedSlotState(day)}">
                <div class="column day">
                    <h4>
                        {{ day.name }}
                    </h4>
                    <span @click="day.opened = !day.opened ">
                        <DoubleDownArrowVue v-if="!day.opened"  class="expand"/>
                        <DoubleUpArrowVue v-if="day.opened"  class="expand"/>
                    </span>
                </div>
                <div class="column morning" @dragover="day.opened = true">
                    <div class="plan">
                        <PlanningAvailability
                            :count-per-day="day.morningCountPerDay"
                            :maximum-occupancy="day.dragMorningHours ? 0 :  plannung.maximum_occupancy"
                            :openingHours="day.openingHours"
                            type="morning"
                            v-show="day.slotMorningHours"
                        ></PlanningAvailability>
                        <div class="plan__body" v-show="day.opened">
                            <draggable
                             :class="`period-1 playgroup-${plannung.id} day-${day.id} allow-${day.dragMorningHours ? 1 : 0}`"

                             v-model="day.morningHoursChildren"
                             :move="showMovedElement"
                             :group="day.slotMorningHours ? 'items' : 'blocked-items'"
                         >
                              <PlanChild
                                v-for="(item, index) in day.morningHoursChildren"
                                :key="index"
                                :child="item"
                                :dayIndex="dayIndex"
                                :planning_id="plannung.id"
                                type="morningHoursChildren"
                                @update="updateChild"
                            />
                         </draggable>
                        </div>
                    </div>
                </div>
                <div class="column evening"  @dragover="day.opened = true">
                    <div class="plan">
                        <PlanningAvailability
                            :count-per-day="day.eveningCountPerDay"
                            :maximum-occupancy="day.dragEveningHours ? 0 : plannung.maximum_occupancy"
                            :openingHours="day.openingHours"
                            type="evening"
                            v-show="day.slotEveningHours"
                        ></PlanningAvailability>
                        <div class="plan__body" v-show="day.opened">
                            <draggable
                                :class="`period-2 playgroup-${plannung.id} day-${day.id} allow-${day.dragEveningHours ? 1 : 0}`"

                                v-model="day.eveningHoursChildren"
                                :move="showMovedElement"
                                :group="day.slotEveningHours ? 'items' : 'blocked-items'"
                            >
                                <PlanChild
                                    v-for="(item,index) in day.eveningHoursChildren"
                                    :key="index"
                                    :child="item"
                                    :dayIndex="dayIndex"
                                    :planning_id="plannung.id"
                                    type="eveningHoursChildren"
                                    @update="updateChild"
                                />
                            </draggable>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import pickBy from "lodash/pickBy";
    import throttle from 'lodash/throttle'
    import axios from '../../../../axios';

    import DoubleDownArrowVue from '../Icons/DoubleDownArrow.vue';
    import DoubleUpArrowVue from '../Icons/DoubleUpArrow.vue';


    import PlanChild from './PlanChild.vue'


    import PlanningAvailability from "./PlanningAvailability.vue";
    import draggable from 'vuedraggable'

    export default {
        components: {
            DoubleDownArrowVue,
            DoubleUpArrowVue,
            PlanChild,
            PlanningAvailability,
            draggable
        },
        props: {
            plannung: {
                type: Object,
                required: true
            }
        },
        filters:{
            formatTimeRange(value) {
                if(value) {
                    return value.replace(/-/g, ' - ') + ' Uhr'
                }
                return value;
            }
        },
        mounted() {
            this.addOpeningHours()

            EventBus.$on(`plan-child-removed-${this.plannung.id}`, (e)=> {
                const dayIndex = e.dayIndex
                const type = e.type
                const id = e.id
                // console.log(e)
                // console.log(this.days[dayIndex])
                // this.days[dayIndex]['morningHoursChildren'] = []
               this.days[dayIndex]['morningHoursChildren'] = this.days[dayIndex][type].filter(item=> item.id !== id)
            })

        },
        data() {
            return {
                role: localStorage.getItem('role'),
                isActive: false,
                days: [
                    {
                        id: 1,
                        name: 'Montag',
                        opened: false,
                        openMorningHours: false,
                        openEveningHours: false,
                        slotMorningHours: false,
                        slotEveningHours: false,
                        openingHours: [],
                        morningHoursChildren: [],
                        eveningHoursChildren: [],
                        morningCountPerDay: 0,
                        eveningCountPerDay: 0,
                    },
                    {
                        id: 2,
                        name: 'Dienstag',
                        opened: false,
                        openMorningHours: false,
                        openEveningHours: false,
                        slotMorningHours: false,
                        slotEveningHours: false,
                        openingHours: [],
                        morningHoursChildren: [],
                        eveningHoursChildren: [],
                        morningCountPerDay: 0,
                        eveningCountPerDay: 0
                    },
                    {
                        id: 3,
                        name: 'Mittwoch',
                        opened: false,
                        openMorningHours: false,
                        openEveningHours: false,
                        slotMorningHours: false,
                        slotEveningHours: false,
                        openingHours: [],
                        morningHoursChildren: [],
                        eveningHoursChildren: [],
                        morningCountPerDay: 0,
                        eveningCountPerDay: 0
                    },
                    {
                        id: 4,
                        name: 'Donnerstag',
                        opened: false,
                        openMorningHours: false,
                        openEveningHours: false,
                        slotMorningHours: false,
                        slotEveningHours: false,
                        openingHours: [],
                        morningHoursChildren: [],
                        eveningHoursChildren: [],
                        morningCountPerDay: 0,
                        eveningCountPerDay: 0
                    },
                    {
                        id: 5,
                        name: 'Freitag',
                        opened: false,
                        openMorningHours: false,
                        openEveningHours: false,
                        slotMorningHours: false,
                        slotEveningHours: false,
                        openingHours: [],
                        morningHoursChildren: [],
                        eveningHoursChildren: [],
                        morningCountPerDay: 0,
                        eveningCountPerDay: 0
                    },
                ],
            }
        },
        watch:{
            days: {
                deep: true,
                handler: throttle(function (newVal, oldVal) {
                    newVal.map(day => {
                        day.morningCountPerDay = day.morningHoursChildren.length
                        day.eveningCountPerDay = day.eveningHoursChildren.length
                    });

                }, 1000),
            },
        },
        methods: {
            updateChild(event){
                console.log(event.dragged)
            },
            showMovedElement(event) {
            if (parseInt(event.to.classList[3].split('-')[1]) == 1 || this.role !== 'admin'){
                return false
            }
            let period =  parseInt(event.to.classList[0].split('-')[1])
            let playgroup =  parseInt(event.to.classList[1].split('-')[1])
            let day =  parseInt(event.to.classList[2].split('-')[1])
            let children = event.draggedContext.element
            let index = event.draggedContext.futureIndex
            let slot = period == 1 ? 'morningHoursChildren':'eveningHoursChildren';

            if (this.days[day-1][slot].length){
                //just sort happened here
                var order_fun = throttle( () => {
                    const updatedOrders = this.days[day-1][slot].map((pl, index) => { return {id: pl.id, order: index} });
                    axios.post('api/plannung/children-order-inside-slot', pickBy(updatedOrders)).then(res => {
                    }).catch(err => {})
                }, 500)

                setTimeout(() =>order_fun(), 500);
            }

            // console.log(day, children)
            //children was moved in a different slot or playgroup
                var throt_fun = throttle( () => {
                    const updatedOrders = {
                        id: children.id,
                        order: index,
                        playgroup: playgroup,
                        period: period,
                        day: day,
                    }

                    EventBus.$emit(`changeBackground-${children.id}-${children.child_reg_id}`, '#ffffff')
                    axios.post(`api/plannung/changechildrenplaygorup`, pickBy(updatedOrders)).then(res => {
                    }).catch(err => {})
                }, 500)
                setTimeout(() =>throt_fun(), 1000);

            },
            updatedSlotState(day){
                if (day.openingHours.length === 0){
                    return true;
                }
                if (day.openingHours.length === 1){
                    if (day.openingHours[0].hour_id === 1){
                    return  day.slotMorningHours = true
                    }
                    return  day.slotEveningHours = true
                }
                day.slotMorningHours =  day.slotEveningHours  = true
            },
            addOpeningHours() {
                let hours = this.plannung.opening_hours.filter((item) => {
                    return this.days[item.day_id - 1].id === item.day_id
                })

                hours.forEach((hour) => {
                    this.days[hour.day_id - 1].openingHours.push({
                        lead: hour.lead,
                        hour_id: hour.hour_id,
                        assistant: hour.assistant,
                    });
                    if (hour.hour_id === 1 && hour.children && hour.children.length) {
                        this.days[hour.day_id - 1].morningHoursChildren.push(...hour.children);
                        this.days[hour.day_id - 1].morningCountPerDay = hour.countPerDay;
                    } else if(hour.hour_id === 2 && hour.children && hour.children.length) {
                        this.days[hour.day_id - 1].eveningHoursChildren.push(...hour.children);
                        this.days[hour.day_id - 1].eveningCountPerDay = hour.countPerDay;
                    }
                })
                this.days.forEach( day=>{
                    if(day.eveningHoursChildren.length === 0){
                        let filteredByDayAndHour2 = this.plannung.children_registrations.filter(
                            item => (item.pivot.day_id === day.id && item.pivot.hour_id === 2 && item.pivot.color === '#e62d4fb3')
                        )
                        if (filteredByDayAndHour2.length>0) {
                            filteredByDayAndHour2.map((child)=>{
                                day.eveningHoursChildren.push({
                                    child_reg_id:child.pivot.children_registration_id,
                                    color:child.pivot.color,
                                    full_name:child.child_first_name + ' '+child.child_last_name,
                                    id:child.pivot.id,
                                    spielgruppen_id:child.pivot.spielgruppen_id
                                })
                            })
                            day.eveningCountPerDay = day.eveningHoursChildren.length
                            day.slotEveningHours = true
                            day.dragEveningHours = true
                        }
                    }
                    if(day.morningHoursChildren.length === 0){
                        let filteredByDayAndHour = this.plannung.children_registrations.filter(
                            item => (item.pivot.day_id === day.id && item.pivot.hour_id === 1 && item.pivot.color === '#e62d4fb3')
                        )
                        if (filteredByDayAndHour.length>0) {
                            filteredByDayAndHour.map((child)=>{
                                day.morningHoursChildren.push({
                                    child_reg_id:child.pivot.children_registration_id,
                                    color:child.pivot.color,
                                    full_name:child.child_first_name + ' '+child.child_last_name,
                                    id:child.pivot.id,
                                    spielgruppen_id:child.pivot.spielgruppen_id
                                })
                            })
                            day.morningCountPerDay = day.morningHoursChildren.length
                            day.slotMorningHours = true
                            day.dragMorningHours = true
                        }
                    }


                })
            },
        }

    }
</script>

<style lang="scss" scoped>
    .planning__table {

        &--header {
            border-top: 1px solid #87B1CB;
            display: flex;
            &__row {
                padding: 20px 24px;
                font-family: 'Nexa';
                font-style: normal;
                font-weight: 700;
                font-size: 18px;
                line-height: 27px;
                display: flex;
                align-items: center;
                color: #2C5B7D;
                // width: 33.33%;

                border-right:  1px solid #87B1CB;;

                &:last-child {
                    border: none;
                }
            }

            .first {
                width: 28%;
            }
            .middle {
                width: 36%;
            }

            .last {
                width: 36%;
            }
        }
    }
</style>



<style lang="scss" scoped>
    .table__row {
        border-top:  1px solid #87B1CB;;


        display: flex;
        .column{
            padding: 8px 7px;
            // width: 33.33%;
            border-right:  1px solid #87B1CB;
            &:last-child {
                border: none;
            }
        }

        .column.day {
            width: 28%;

            padding-top: 10px;
            padding-bottom: 10px;
            padding-left: 16px;
            padding-right: 32px;
            display: flex;
            justify-content: space-between;
            h4 {
                margin: 0;
                padding: 0;
                font-family: 'Nexa';
                font-style: normal;
                font-weight: 700;
                font-size: 18px;
                line-height: 27px;
                color: #16A9B7;
            }

            .expand {
                cursor: pointer;
            }
        }

        .column.morning {
            width: 36%;
        }
        .column.evening {
            width: 36%;
        }
    }

    .plan {
        position: relative;

        // &__header {
        //     padding: 8px;
        //     border-left: 8px solid blueviolet;
        //     background: #9CDAE5;;
        //     &--content {
        //         padding-left: 7px;
        //         padding-top: 12px;
        //         padding-bottom: 10px;
        //         display: flex;
        //         align-items: center;

        //         h4 {
        //             margin: 0;
        //             padding: 0;
        //             font-family: 'Ubuntu';
        //             font-style: normal;
        //             font-weight: 400;
        //             font-size: 15px;
        //             line-height: 17px;
        //             color: #2C5B7D;
        //             margin-right: 6px;
        //         }
        //     }
        // }
    }

    // .plan__child {
    //     margin-top: 8px;
    //     background: #F8E7A8;
    //     display: flex;
    //     justify-content: space-between;

    //     .left {
    //         padding: 9px 8px;
    //         display: flex;
    //         align-items: center;
    //         width:  80%;
    //         h4 {
    //             margin: 0;
    //             padding: 0;
    //             margin-left: 8px;

    //             font-family: 'Ubuntu';
    //             font-style: normal;
    //             font-weight: 400;
    //             font-size: 15px;
    //             line-height: 17px;
    //             color: #2C5B7D;
    //         }
    //         .document__icon {
    //             // justify-self: flex-end;
    //             margin-left: auto;
    //         }
    //     }

    //     .right {
    //         padding: 9px 8px;
    //         border-left: 2px solid white;
    //     }

    // }

    .row {
        --bs-gutter-x: 0;
    }
</style>
