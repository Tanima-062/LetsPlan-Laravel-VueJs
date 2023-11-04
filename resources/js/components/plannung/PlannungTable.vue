<template>
    <div class="table-wrapper">
        <table class="weekday-table">
            <thead class="weekday-table-head">
            <tr>
                <th colspan="2" scope="col"></th>
                <th :style="[smallItem ? {fontSize:'10px'} : '']" align="left" scope="col">8:45 - 11:15 Uhr</th>
                <th :style="[smallItem ? {fontSize:'10px'} : '']" align="left" scope="col">14:00 - 16:30 Uhr</th>
            </tr>
            </thead>
            <tbody class="weekday-table-body">
            <tr v-for="day in days" :key="day.id" :class="{'hasSlots':updatedSlotState(day)}">
                <td class="row-title" colspan="2" scope="row">
                    <a v-if="day" data-trigger="dropdown">
                        {{ day.name }}
                        <img @click="day.opened = !day.opened " width="12"
                             :src="day.opened ? '/images/arrowUp.png' : '/images/plannung-table-icon.png'"/>
                    </a>
                    <div v-if="day.opened">
                        <hr style="border: 1px solid black"/>
                        <small style="margin-bottom: 10px">Spielgruppenbetreuung</small>
                        <div v-if="day && day.slotMorningHours" class="openingHours">
                            <a data-trigger="dropdown">
                                8:45 - 11:15 Uhr
                                <img @click="day.openMorningHours = !day.openMorningHours " width="12"
                                     :src="day.openMorningHours ? '/images/arrowUp.png' : '/images/plannung-table-icon.png'"/>
                            </a>
                        </div>
                        <div v-if="day.openMorningHours && day.openingHours" v-for="item in day.openingHours">
                            <div v-if="item.hour_id === 1" >
                                <p style="margin-block:7px">Spielgruppenleiter/in</p>
                                <a class="employee-name">{{ item.lead.first_name }} {{ item.lead.last_name }}</a>
                                <p class="address-info">{{ item.lead.street_name }} {{ item.lead.house_number }}</p>
                                <p class="address-info">{{ item.lead.location }} {{ item.lead.postal_code }}</p>
                                <p class="address-info">{{ item.lead.prefix }} {{ item.lead.phone_number }}</p>
                                <p class="address-info email">{{ item.lead.email }}</p>
                                <hr style="border:1px solid lightgrey"/>
                                <p>Spielgruppen Assistent/in</p>
                                <a class="employee-name">{{ item.assistant.first_name }}
                                    {{ item.assistant.last_name }}</a>
                            </div>
                        </div>
                        <div v-if="day && day.slotEveningHours" class="openingHours">
                            <a data-trigger="dropdown">
                                14:00 - 16:30 Uhr
                                <img @click="day.openEveningHours = !day.openEveningHours " width="12"
                                     :src="day.openEveningHours ? '/images/arrowUp.png' : '/images/plannung-table-icon.png'"/>
                            </a>
                        </div>
                        <div v-if="day.openEveningHours && day.openingHours" v-for="item in day.openingHours">
                            <div v-if="item.hour_id === 2">
                                <p>Spielgruppenleiter/in</p>
                                <a class="employee-name">{{ item.lead.first_name}} {{ item.lead.last_name }}</a>
                                <p v-if="item.lead.house_number" class="address-info">{{ item.lead.street_name }}
                                    {{ item.lead.house_number }}</p>
                                <p class="address-info">{{ item.lead.location }} {{ item.lead.postal_code }}</p>
                                <p class="address-info">{{ item.lead.prefix }} {{ item.lead.phone_number }}</p>
                                <p class="address-info email">{{ item.lead.email }}</p>
                                <hr style="border:1px solid lightgrey"/>
                                <p>Spielgruppen Assistent/in</p>
                                <a class="employee-name">{{ item.assistant.first_name }}
                                    {{ item.assistant.last_name }}</a>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="slots slots-morning" style="vertical-align: top;width: 133px">
                    <PlannungAvailability :count-per-day="day.morningCountPerDay"
                                          :maximum-occupancy="day.dragMorningHours ? 0 :  plannung.maximum_occupancy" v-show="day.slotMorningHours"/>
                    <span v-show="day.opened" class="for-style">
                         <draggable
                             :class="`period-1 playgroup-${plannung.id} day-${day.id} allow-${day.dragMorningHours ? 1 : 0}`"
                             style="width:165px;min-height:30px; margin: auto"
                             v-model="day.morningHoursChildren"
                             :move="showMovedElement"
                             :group="day.slotMorningHours ? 'items' : 'blocked-items'"
                         >
                              <PlannungChildCard :key="index" v-for="(item, index) in day.morningHoursChildren" :child="item"/>
                         </draggable>
                    </span>
                </td>
                <td class="slots slots-evening" style="vertical-align: top;width: 133px">
                    <PlannungAvailability :count-per-day="day.eveningCountPerDay"
                                          :maximum-occupancy="day.dragEveningHours ? 0 : plannung.maximum_occupancy" v-show="day.slotEveningHours"/>
                    <span v-show="day.opened" class="for-style">
                        <draggable
                            :class="`period-2 playgroup-${plannung.id} day-${day.id} allow-${day.dragEveningHours ? 1 : 0}`"
                            style="width:165px; min-height:30px; margin: auto"
                            v-model="day.eveningHoursChildren"
                            :move="showMovedElement"
                            :group="day.slotEveningHours ? 'items' : 'blocked-items'"
                        >
                             <PlannungChildCard :key="index" v-for="(item,index) in day.eveningHoursChildren" :child="item"/>
                        </draggable>
                    </span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import PlannungAvailability from "./PlannungAvailability";
import PlannungChildCard from "./PlannungChildCard";
import draggable from 'vuedraggable'
import axios from "../../axios";
import pickBy from "lodash/pickBy";
import throttle from 'lodash/throttle'
import plannung from "./Plannung";
export default {
    name: "PlannungTable",
    components: {PlannungChildCard, PlannungAvailability, draggable},
    props: ['smallItem', 'plannung'],
    mounted() {
        this.addOpeningHours()
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
          //children was moved in a different slot or playgroup
            var throt_fun = throttle( () => {
                const updatedOrders = {
                    id: children.id,
                    order: index,
                    playgroup: playgroup,
                    period: period,
                    day: day,
                }
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

<style scoped lang="scss">
.table-wrapper {
    background: #F8FBFA;
    border: 0.5px solid #42474A;
    border-radius: 0px;
    overflow: hidden;
    margin-top: 10px;
    width: 100%;
}
table,
tr,
td{
    border:1px solid #42474A;
    font-family: 'Lato';
    font-style: normal;
    font-weight: 600;
    font-size: 13px;
    line-height: 16px;
    color: #42474A;
}
th {
    font-size: 13px;
    padding: 10px;
    border: 1px solid #42474A;
}

.weekday-table {
    width: 100%;
    border-spacing: 0;
    border-collapse: collapse;
    border-style: hidden;
}
.row-title{
    width: 137px !important;
    padding: 10px;
    word-break: break-word;
}

td a img {
    float: right
}

.openingHours {
    border: 1px solid lightgrey;
    padding: 5px;
    margin-top: 10px;
    background: white;
}

.employee-name {
    font-size: 18px;
    text-decoration: underline;
    font-weight: 400;
    color: #0063A7;
}

.address-info {
    font-size: 16px;
    color: #727574;
    margin-top: 7px;
    font-weight: 400;
}
img{
    border-style:none !important;
}
.email {
    font-size: 14px;
}
.plannung-second-block{
    table,
    tr,
    td,
    th,
    .employee-name,
    .address-info,
    .email{
        font-size: 10px;
        line-height: 12px;
    }
   .for-style > div{
       width: 120px !important;
   }
}
.bottom-drags{
    .slots{
        width: 176px !important;
    }
}
</style>
