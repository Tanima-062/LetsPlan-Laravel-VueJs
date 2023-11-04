<template>
    <div class="py-30">
        <div class="card">
            <table style="width: 100%">
                <tbody v-if="child">
                <tr>
                    <td class="user-info-table-data">
                        <div class="kind">
                            <p class="user-title">Vorname</p>
                            <p class="user-name">{{ child.child_first_name }}</p>
                        </div>
                    </td>
                    <td class="user-info-table-data">
                        <div class="kind">
                            <p class="user-title">Nachname</p>
                            <p class="user-name">{{child.child_last_name}}</p>
                        </div>
                    </td>
                    <td class="user-info-table-data">
                        <div class="kind">
                            <p class="user-title">Geschlecht</p>
                            <p v-if="child.gender_id == 1" class="user-name">Junge</p>
                            <p v-if="child.gender_id == 2" class="user-name">Mädchen</p>
                        </div>
                    </td>
                    <td class="user-info-table-data">
                        <div class="kind">
                            <p class="user-title">Geburtsdatum Kind</p>
                            <p class="user-name">{{ birthday }}</p>
                        </div>
                    </td>
                    <td class="user-info-table-data">
                        <div class="kind">
                            <p class="user-title">Nationalität</p>
                            <p class="user-name">{{ child.nationality_name }}</p>
                        </div>
                    </td>
                    <td class="user-info-table-data">
                        <div class="kind">
                            <p class="user-title">Muttersprache</p>
                            <p class="user-name">{{ child.mother_tongue_name }}</p>
                        </div>
                    </td>
                    <td class="user-info-table-data">
                        <div class="kind">
                            <p class="user-title">Mitgliedschaft</p>
                            <p v-if="child.membership_id === 2" class="user-name">Mitglied</p>
                            <p v-if="child.membership_id === 1" class="user-name">Neu</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="user-info-table-data" colspan="7">
                        <hr class="horizontal-line"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="1" class="user-info-table-data vertical-top">
                        <div class="kind">
                            <p class="user-title">Heutige</p>
                            <p class="user-name">
                                <span>{{child.spielgruppen}}</span>
                            </p>
                        </div>
                    </td>
                    <td colspan="1" class="user-info-table-data vertical-top">
                        <div class="kind">
                            <p class="user-title">Spielgruppe</p>
                            <p v-if="child.semester_id == 1" class="user-name">1 x wöchentlich </p>
                            <p v-if="child.semester_id == 2" class="user-name">2 x wöchentlich </p>
                            <p v-if="child.semester_id == 3" class="user-name">3 x wöchentlich </p>
                        </div>
                    </td>
                    <td colspan="1" class="user-info-table-data vertical-top">
                        <div class="kind">
                            <p class="user-title">Naheliegendste Spielgruppe</p>
                            <p class="user-name" v-if="child.closest_spielgruppen">{{child.closest_spielgruppen}}</p>
                        </div>
                    </td>
                    <td class="user-info-table-data" colspan="4">
                        <div style="width: 100%">
                            <div class="table-wrapper">
                                <table class="weekday-table" :class="{'disable-all':role !== 'admin'}">
                                    <thead class="weekday-table-head">
                                    <tr>
                                        <th class="calendar-table-head" colspan="2" scope="col"></th>
                                        <th class="calendar-table-head" align="center" scope="col">Montag</th>
                                        <th class="calendar-table-head" align="center" scope="col">Dienstag</th>
                                        <th class="calendar-table-head" align="center" scope="col">Mittwoch</th>
                                        <th class="calendar-table-head" align="center" scope="col">Donnerstag</th>
                                        <th class="calendar-table-head" align="center" scope="col">Freitag</th>
                                    </tr>
                                    </thead>
                                    <tbody class="weekday-table-body">
                                    <tr>
                                        <td class="row-title calendar-table-data" colspan="2" scope="row">Vormittag (8:45 - 11:15 Uhr)</td>
                                        <td class="calendar-table-data" align="center">
                                            <input :disabled="role !=='admin'" type="checkbox" value=1 v-model="morning_selection"/>
                                        </td>
                                        <td class="calendar-table-data" align="center">
                                            <input :disabled="role !=='admin'" type="checkbox" value=2 v-model="morning_selection"/>
                                        </td>
                                        <td class="calendar-table-data" align="center">
                                            <input :disabled="role !=='admin'" type="checkbox" value=3 v-model="morning_selection"/>
                                        </td>
                                        <td class="calendar-table-data" align="center">
                                            <input :disabled="role !=='admin'" type="checkbox" value=4 v-model="morning_selection"/>
                                        </td>
                                        <td class="calendar-table-data" align="center">
                                            <input :disabled="role !=='admin'" type="checkbox" value=5 v-model="morning_selection"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="row-title calendar-table-data" colspan="2" scope="row">Nachmittag (14:00 - 16:30 Uhr)</td>
                                        <td class="calendar-table-data" align="center">
                                            <input :disabled="role !=='admin'" type="checkbox" value=1 v-model="evening_selection"/>
                                        </td>
                                        <td class="calendar-table-data" align="center">
                                            <input :disabled="role !=='admin'" type="checkbox" value=2 v-model="evening_selection"/>
                                        </td>
                                        <td class="calendar-table-data" align="center">
                                            <input :disabled="role !=='admin'" type="checkbox" value=3 v-model="evening_selection"/>
                                        </td>
                                        <td class="calendar-table-data" align="center">
                                            <input :disabled="role !=='admin'" type="checkbox" value=4 v-model="evening_selection"/>
                                        </td>
                                        <td class="calendar-table-data" align="center">
                                            <input :disabled="role !=='admin'" type="checkbox" value=5 v-model="evening_selection"/>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="7" class="user-info-table-data">
                        <hr class="horizontal-line"/>
                    </td>
                </tr>
                <tr>
                    <td class="user-info-table-data vertical-top">
                        <div class="kind">
                            <p class="user-title">Vorname Mutter</p>
                            <p class="user-name">{{child.mother_first_name}}</p>
                        </div>
                    </td>
                    <td class="user-info-table-data vertical-top">
                        <div class="kind">
                            <p class="user-title">Nachname Mutter</p>
                            <p class="user-name">{{child.mother_last_name}}</p>
                        </div>
                    </td>
                    <td class="user-info-table-data vertical-top">
                        <div class="kind">
                            <p class="user-title">Vorname Vater</p>
                            <p class="user-name">{{child.father_first_name}}</p>
                        </div>
                    </td>
                    <td class="user-info-table-data vertical-top">
                        <div class="kind">
                            <p class="user-title">Nachname Vater</p>
                            <p class="user-name">{{child.father_last_name}}</p>
                        </div>
                    </td>
                    <td class="user-info-table-data vertical-top" colspan="3">
                        <p class="user-title">Bemerkungen</p>
                        <p class="user-name description">{{ child.note }}</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="7" class="user-info-table-data">
                        <hr class="horizontal-line"/>
                    </td>
                </tr>
                <tr>
                    <td class="user-info-table-data vertical-top">
                        <div class="kind">
                            <p class="user-title">Strasse / Nummer</p>
                            <p class="user-name">{{ child.street }} / {{child.street_number}}</p>
                        </div>
                    </td>
                    <td class="user-info-table-data vertical-top">
                        <div class="kind">
                            <p class="user-title">Postleitzahl/Ort</p>
                            <p class="user-name">{{ child.postal_code }}/{{ child.place }}</p>
                        </div>
                    </td>
                    <td class="user-info-table-data vertical-top">
                        <div class="kind">
                            <p class="user-title">E-Mail</p>
                            <p class="user-name">{{child.email}}</p>
                        </div>
                    </td>
                    <td class="user-info-table-data vertical-top">
                        <div class="kind">
                            <p class="user-title">Telefonnummer</p>
                            <p class="user-name">{{child.phone_number_prefix}} <input class="user-name" v-mask="'## ### ## ##'" v-model="child.phone_number"></p>
                        </div>
                    </td>
                    <td class="user-info-table-data vertical-top">
                        <div class="kind">
                            <p class="user-title">Mobile Telefonnummer</p>
                            <p class="user-name" v-if="child.mobile_phone_number">{{child.mobile_phone_number_prefix}} <input class="user-name" v-mask="'## ### ## ##'" v-model="child.mobile_phone_number"></p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="7" class="user-info-table-data">
                        <hr class="horizontal-line"/>
                    </td>
                </tr>
                <tr>
                    <td class="user-info-table-data" colspan="7">
                        <button @click="$router.go(-1)" class="plannung-user-info-button">Zurück</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import axios from '../../axios'
import moment from "moment";
import {mask} from 'vue-the-mask'
export default {
    name: "PlannungUserInfoCard",
    directives: {mask},
    mounted() {
        this.getChildInfo()
    },
    data() {
        return {
            child : null,
            morning_selection: [],
            evening_selection : [],
            role: localStorage.getItem('role'),
        }
    },
    methods: {
        getChildInfo() {
            axios.get(`/api/childInformation/${this.$route.params.id}`).then(res=>{
                this.child = res.data.data

                this.child.openingHours.map(item=>{
                    if(item.hour_id == 1) {
                        this.morning_selection.push(item.day_id)
                    }else{
                        this.evening_selection.push(item.day_id)
                    }

                })
            }).catch(err => {
                console.log(err)
            })
        }
    },

    computed: {
        birthday() {
            return moment(this.child.child_birthday).locale('de').format('DD.MM.YYYY')
        }
    }
}
</script>

<style scoped lang="scss">
.card{
    padding: 7px 25px 15px;
    .image-kid-wrapper{
        width: 48px;
        height: 48px;
        border-radius: 100px;
        display: inline-block;
        position: relative;
        overflow: hidden;
        .profile-picture {
            width: 48px;
            height: 48px;
            object-fit: cover;
        }
        .shape-image {
            position: absolute;
            background:#1b343dcc;
            width: 100%;
            height: 100%;
            z-index: 1;
            top: 33px;
            overflow: hidden;
        }
    }
    .user-title {
        font-weight: 600;
        font-size: 13px;
        line-height: 16px;
        color: #42474A;
    }
    .user-name {
        font-weight: 400;
        font-size: 12px;
        line-height: 14px;
        color: #727574;
        white-space: nowrap;
    }
}


.kind {
    padding-left: 5px;
    white-space: nowrap;
    flex: 1 1 0;
}

.horizontal-line {
    border-top: 1px solid #C2C5C6;
    opacity: 0.7;
}

.weekday-table-head {
    width: 100%;
    border-radius: 8px;
    border: 1px solid #727574;
    padding: 10px
}

.calendar-table-head {
    padding: 5px;
    border: 1px solid #727574;
    background: #E3E9EB;
    font-weight: 400;
    font-size: 12px;
    line-height: 14px;
    text-align: center;
    color: #42474A;
}

.calendar-table-data {
    border: 1px solid #727574;
    background: #F8FBFA;
    font-weight: 400;
    font-size: 12px;
    line-height: 14px;
    color: #42474A;

}

.table-wrapper {
    background: #E3E9EB;
    border: 1px solid #727574;
    overflow: hidden;
    margin-top: 10px;
    tbody.weekday-table-body tr td {
        padding: 7px 0px 7px 7px;
    }
}

.weekday-table {
    width: 100%;
    border-spacing: 0;
    border-collapse: collapse;
    border-style: hidden;
}

.user-info-table-data {
    border:0 !important;
    background: none !important;
    width: 20%;
}

.user-info-table-data:first-of-type {
    width: auto !important;
}

.vertical-top {
    vertical-align: top;
    padding-top: 15px;
    padding-bottom: 15px;
}

.description {
    width: 100%;
    padding-right: 10px;
    white-space: break-spaces;
    text-wrap:normal;
}

.plannung-user-info-button {
    background-color: #0063A7;
    padding: 8px 15px;
    border-radius: 5px;
    color: white;
    float:right
}
</style>
