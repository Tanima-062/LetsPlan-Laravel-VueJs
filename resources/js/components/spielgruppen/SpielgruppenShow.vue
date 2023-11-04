<template>
    <div  class="card-panel-home supervisor-card-wrapper rounded-none" style="background-color:#E9F7FE; border:none; border-radius: 0px;">
            <div class="head">
                <Back />
                <h4 class="title mb-4">Spielgruppen Details</h4>
            </div>

            <div class="flex w-full justify-between">
                <div class="flex w-1/5">
                    <img v-if="spielgruppen" :src="spielgruppen.file_url" style="height:104px; width:104px"/>
                    <span class="ml-4"><p class="title">Erstellungsdatum</p>
                    <p v-if="spielgruppen" style="font-family: 'Nexa';font-style: normal;font-weight: 700;font-size: 15px;line-height: 22.74px;color: #2C5B7D;">{{spielgruppen.created_at}}</p>
                    </span>
                </div>
                <div class="w-1/5">
                    <p class="title">Spielgruppen-ID</p>
                    <p v-if="spielgruppen" style="font-family: 'Nexa';font-style: normal;font-weight: 700;font-size: 15px;line-height: 22.74px;color: #2C5B7D;">{{ spielgruppen.prefix_id}}</p>
                </div>
                <div class="w-1/4 mr-2">
                    <p class="title">Name</p>
                    <p v-if="spielgruppen" style="font-family: 'Nexa';font-style: normal;font-weight: 700;font-size: 15px;line-height: 22.74px;color: #2C5B7D;">{{spielgruppen.name}}</p>
                </div>
            </div>
            <div class="user-form-registration">
                <div class="flex mt-4 justify-between">
                    <div class="w-1/4">
                        <p class="title" style="font-family: 'Nexa';font-style: normal;font-weight: 700;font-size: 18px;line-height: 27.29px;color: #16A9B7;">Adresse</p>
                        <p v-if="spielgruppen" style="font-family: 'Nexa';font-style: normal;font-weight: 700;font-size: 15px;line-height: 22.74px;color: #2C5B7D;">{{spielgruppen.street_name}} {{spielgruppen.street_number}}, {{spielgruppen.postal_code}} {{spielgruppen.location}}</p>
                    </div>
                    <div class="w-1/4">
                        <p class="title" style="font-family: 'Nexa';font-style: normal;font-weight: 700;font-size: 18px;line-height: 27.29px;color: #16A9B7;">Mindestanzahl</p>
                        <p v-if="spielgruppen" style="font-family: 'Nexa';font-style: normal;font-weight: 700;font-size: 15px;line-height: 22.74px;color: #2C5B7D;">{{spielgruppen.minimum_occupancy}}</p>
                    </div>
                    <div class="w-1/4 mr-2">
                        <p class="title" style="font-family: 'Nexa';font-style: normal;font-weight: 700;font-size: 18px;line-height: 27.29px;color: #16A9B7;">Maximal Belegung</p>
                        <p v-if="spielgruppen" style="font-family: 'Nexa';font-style: normal;font-weight: 700;font-size: 15px;line-height: 22.74px;color: #2C5B7D;">{{spielgruppen.maximum_occupancy}}</p>
                    </div>
                </div>
            </div>
            <div class="row flex-grow">
                <div class="table-wrapper">
                    <div class="head"><h4 class="head-title" style="font-size:20px; font-weight: 700">Öffnungszeiten</h4></div>
                    <div class="weekday-table mt-4">
                        <div class="bg-white flex h-8">
                            <div  style="width: 300px;"></div>
                            <div v-for="day in days" style="width:190px;" class=" text-left title" :key="day">{{ day }}</div>
                        </div>

                        <div class="flex mt-2 bg-white" v-if="spielgruppen">
                            <div class=" mt-2 mb-2" style="width:285px; margin-left:15px; 'Nexa';font-style: normal;font-weight: 700;font-size: 15px;line-height: 22.74px; color: #2C5B7D;">{{spielgruppen.morning_time_range}}</div>
                            <div class=" mt-2 mb-2" style="width:190px;" v-for="(day, index) in days" :key="index">
                                <TickIcon v-if="isAvailable(spielgruppen.opening_hours, index+1, 1)"></TickIcon>
                                <MinusIcon v-else></MinusIcon>
                            </div>
                        </div>
                        <div class="flex pt-2 pb-2 bg-white" v-if="spielgruppen" style="border: 1px solid #D3E3EC; border-left: none;border-right:none;">
                            <div class="row-role-title" style="width: 285px; margin-left: 15px; font-family: 'Nexa';font-style: normal;font-weight: 700;font-size: 15px;line-height: 23px; color: #2C5B7D;">Spielgruppenleiter/in</div>
                            <div style="width:190px;" v-for="(day, index) in days" :key="index">
                                <p style="margin:0;font-family: 'Nexa';font-style: normal;font-weight: 700;font-size: 15px;line-height: 23px; color: #2C5B7D;">{{getLead(spielgruppen.opening_hours, index+1, 1)}}</p>
                            </div>
                        </div>
                        <div class="flex pt-2 pb-2 bg-white" v-if="spielgruppen">
                            <div class="row-role-title" style="width:285px; margin-left: 15px; font-family: 'Nexa';font-style: normal;font-weight: 700;font-size: 15px;line-height: 23px; color: #2C5B7D;">Spielgruppen Assistent/in</div>
                            <div style="width:190px;" v-for="(day, index) in days" :key="index">
                                <p style="margin:0;font-family: 'Nexa';font-style: normal;font-weight: 700;font-size: 15px;line-height: 23px; color: #2C5B7D;">{{getAssistant(spielgruppen.opening_hours, index+1, 1)}}</p>
                            </div>
                        </div>
                        <div class="flex mt-2 bg-white" v-if="spielgruppen">
                            <div class="mt-2 mb-2" style="width: 285px; margin-left: 15px; font-family: 'Ubuntu';font-style: normal; font-weight: bold; font-size:15px; color: #2C5B7D;">{{spielgruppen.afternoon_time_range}}</div>
                            <div class="mt-2 mb-2" style="width:190px;" v-for="(day, index) in days" :key="index">
                                <TickIcon v-if="isAvailable(spielgruppen.opening_hours, index+1, 2)"></TickIcon>
                                <MinusIcon v-else></MinusIcon>
                            </div>
                        </div>
                        <div class="flex pt-2 pb-2 bg-white" v-if="spielgruppen" style="border: 1px solid #D3E3EC; border-left: none;border-right:none;">
                            <div class="row-role-title" style="width: 285px; margin-left: 15px; font-family: 'Nexa';font-style: normal;font-weight: 700;font-size: 15px;line-height: 23px; color: #2C5B7D;">Spielgruppenleiter/in</div>
                            <div class="" style="width:190px;" v-for="(day, index) in days" :key="index">
                                <p style="margin:0;font-family: 'Nexa';font-style: normal;font-weight: 700;font-size: 15px;line-height: 23px; color: #2C5B7D;">{{getLead(spielgruppen.opening_hours, index+1, 2)}}</p>
                            </div>
                        </div>
                        <div class="flex pt-2 pb-2 bg-white" v-if="spielgruppen">
                            <div class="row-role-title" style="width: 285px; margin-left: 15px; font-family: 'Nexa';font-style: normal;font-weight: 700;font-size: 15px;line-height: 23px; color: #2C5B7D;">Spielgruppen Assistent/in</div>
                            <div class="" style="width:190px;" v-for="(day, index) in days" :key="index">
                                <p style="margin:0;font-family: 'Nexa';font-style: normal;font-weight: 700;font-size: 15px;line-height: 23px; color: #2C5B7D;">{{getAssistant(spielgruppen.opening_hours, index+1, 2)}}</p>
                            </div>
                        </div>
                        <div class="details-row mt-5"  v-if="user.role_id == 1">
                            <div class="details">
                                <router-link
                                    class="edit-button"
                                    :to="`/spielgruppen/edit/${spielgruppen.id}`"
                                >
                                    <svg
                                        width="26"
                                        height="26"
                                        viewBox="0 0 26 26"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M1.33203 24.6667H0.0820312V25.9167H1.33203V24.6667ZM1.33203 19.3333L0.448148 18.4494L0.0820312 18.8156V19.3333H1.33203ZM18.6654 2L19.5492 1.11612L18.6654 0.232233L17.7815 1.11612L18.6654 2ZM23.9987 7.33333L24.8826 8.21722L25.7665 7.33333L24.8826 6.44945L23.9987 7.33333ZM6.66536 24.6667V25.9167H7.18313L7.54925 25.5505L6.66536 24.6667ZM2.58203 24.6667V19.3333H0.0820312V24.6667H2.58203ZM17.7815 2.88388L23.1148 8.21722L24.8826 6.44945L19.5492 1.11612L17.7815 2.88388ZM6.66536 23.4167H1.33203V25.9167H6.66536V23.4167ZM23.1148 6.44945L19.1148 10.4494L20.8826 12.2172L24.8826 8.21722L23.1148 6.44945ZM19.1148 10.4494L5.78148 23.7828L7.54925 25.5505L20.8826 12.2172L19.1148 10.4494ZM2.21591 20.2172L15.5492 6.88388L13.7815 5.11612L0.448148 18.4494L2.21591 20.2172ZM15.5492 6.88388L19.5492 2.88388L17.7815 1.11612L13.7815 5.11612L15.5492 6.88388ZM20.8826 10.4494L15.5492 5.11612L13.7815 6.88388L19.1148 12.2172L20.8826 10.4494Z"
                                            fill="white"
                                        />
                                    </svg>
                                    <span>Bearbeiten</span>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</template>
<script>

import axios from "../../axios";
import { forEach } from "lodash";
import TickIcon from "./components/TickIcon.vue";
import MinusIcon from "./components/MinusIcon.vue"
import dayjs from "dayjs";
import Back from './components/Back.vue';
import Pen from './components/Pen.vue';

export default ({
    name:"spielgruppen_show",
    data() {
        return {
            days: [
                'Montag',
                'Dienstag',
                'Mittwoch',
                'Donnerstag',
                'Freitag',
            ],
            spielgruppen: null
        }
    },
    components: {
        TickIcon,
        MinusIcon,
        Back,
        Pen
    },
    mounted() {
        this.getSpielgruppen()
    },
    computed: {
        user(){
            return this.$store.getters['user'];
        }
    },
    methods:{
        isAvailable(opening_hours, day_id, hour_id){
            var flag = 0;
            opening_hours.forEach(item => {
                if(item.day_id == day_id && item.hour_id == hour_id){
                    flag = 1;
                    return;
                }
            })

            if(flag == 1){
                return true
            }
            return false
        },

        getLead(opening_hours, day_id, hour_id){
            var name = '';
            opening_hours.forEach(item => {
                if(item.day_id == day_id && item.hour_id == hour_id){
                    name = item.lead.first_name+' '+item.lead.last_name;
                    return
                }
            })
            return name
        },

        getAssistant(opening_hours, day_id, hour_id){
            var name = '';
            opening_hours.forEach(item => {
                if(item.day_id == day_id && item.hour_id == hour_id){
                    name = item.assistant.first_name+' '+item.assistant.last_name;
                    return
                }
            })
            return name
        },

        getSpielgruppen() {

        axios.get(`/api/spielgruppen/show/${this.$route.params.id}`).then(res=>{
            this.spielgruppen = res.data
            this.spielgruppen.created_at = dayjs(this.spielgruppen.created_at).format('DD.MM.YYYY');
        }).catch(err=>{
        })

        }
    },
})
</script>
<style scoped lang="scss">
    @import "../supervisor/supervisor";



    .head {
        background-color: #E9F7FE;
        margin-top: 20px;

        .title {
            font-family: 'Ubuntu';
            font-style: normal;
            font-weight: 700;
            font-size: 22px;
            line-height: 25px;
            color: #063F5F;
            margin: 42px 0px 0px 0px;
        }
    }

    .inputField{
        height:35px;
        width:412px;
    }
    .title{
        color: #16A9B7;
        font-family: 'Nexa';font-style: normal;font-weight: 700;font-size: 18px;line-height: 27.29px;
    }
    .btn-primary{
        width: 200px;
        height: 40px;
    }
    .weekday-table-head {
        width: 100%;
        border-radius: 8px;
        // border: 1px solid #727574;
        padding: 10px
    }

    th {
        padding: 5px;
        background: white;
        // border: 1px solid #727574;
    }

    td {
        // border: 1px solid #727574;
        padding: 7px;
        background: white;
    }

    .row-title {
        font-size: 14px;
        font-weight: 600;
    }

    .row-role-title {
        font-size: 12px;
        font-weight: 300;
    }

    .table-wrapper {
        // border: 1px solid #727574;
        // border-radius: 5px;
        overflow: hidden;
        margin-top: 40px;
    }

    .weekday-table {
        width: 100%;
        border-spacing: 0;
        border-collapse: collapse;
        border-style: hidden;
    }

    .details {
        .label {
            font-family: 'Nexa';
            font-style: normal;
            font-weight: 700;
            font-size: 18px;
            line-height: 27px;
            color: #16A9B7;
            margin-bottom: 5px;
        }

        .value {
            font-family: 'Nexa';
            font-style: normal;
            font-weight: 700;
            font-size: 15px;
            line-height: 23px;
            color: #2C5B7D;
            margin: 0px;
        }

        .edit-button {
            display: flex;
            position: relative;
            width: 412px;
            height: 40px;
            background: #E97D32;
            text-decoration: none;
            align-items: center;
            justify-content: center;

            svg {
                position: absolute;
                top: 50%;
                left: 2%;
                transform: translateY(-50%);
                margin-left: 0px;
            }

            span {
                font-family: 'Nexa';
                font-style: normal;
                font-weight: 700;
                font-size: 18px;
                line-height: 27px;
                color: #FFFFFF;
                justify-self: center;
            }
        }
    }

    </style>


