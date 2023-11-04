<template>
    <div class="registration__view" v-if="show">
        <div class="registration__view--header">
            <div>
                <span @click="openRegistration">
                    <ExpandIcon  />
                </span>
            </div>
            <div>
                <span @click="show = false;">
                    <CloseIcon />
                </span>
            </div>
        </div>
        <!-- {{ child }} -->
        <div class="registration__view--body">
            <div class="registration__section default">
                <div class="registration__section--header">
                    <h4>Anmeldung Details</h4>
                    <!-- <span>
                        <ArrowDown />
                    </span> -->
                </div>
                <div class="registration__section--body">
                    <div class="registration__section--body__row">
                        <div class="registration__section--body__column">
                            <h4>Erstellungsdatum</h4>
                            <h5>{{ child.created_at | dateFormate }}</h5>
                        </div>
                        <div class="registration__section--body__column">
                            <h4>Anmeldungs-ID</h4>
                            <!-- <h5>AM{{ child.id | leadzero }}</h5> -->
                            <h5>{{ child.prefix_id }}</h5>
                        </div>
                        <div class="registration__section--body__column">
                            <h4>Einteilung </h4>
                            <h5>{{ child.division ? child.division.name : '' }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="registration__section" >
                <div class="registration__section--header">
                    <h4>Kind</h4>
                    <span @click="child_section_open = !child_section_open">
                        <ArrowDown />
                    </span>
                </div>
                <div class="registration__section--body" v-if="child_section_open">
                    <div class="registration__section--body__row">
                        <div class="registration__section--body__column">
                            <h4>Vorname</h4>
                            <h5>{{ child.child_first_name  }}</h5>
                        </div>
                        <div class="registration__section--body__column">
                            <h4>Nachname</h4>
                            <h5>{{ child.child_last_name  }}</h5>
                        </div>
                        <div class="registration__section--body__column">
                            <h4>Geschlecht </h4>
                            <h5>
                                <template v-if="child.gender_id == 1">Junge</template>
                                <template v-if="child.gender_id == 2">Mädchen</template>
                            </h5>
                        </div>
                    </div>
                    <div class="registration__section--body__row">
                        <div class="registration__section--body__column">
                            <h4>Geburtsdatum</h4>
                            <h5>{{ child.child_birthday | dateFormate }}</h5>
                        </div>
                        <div class="registration__section--body__column">
                            <h4>Nationalität</h4>
                            <h5>{{ child.nationality_name }}</h5>
                        </div>
                        <div class="registration__section--body__column">
                            <h4>Muttersprache</h4>
                            <h5>{{ child.mother_tongue_name }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="registration__section">
                <div class="registration__section--header">
                    <h4>Eltern</h4>
                    <span  @click="parent_section_open = !parent_section_open">
                        <ArrowDown />
                    </span>
                </div>
                <div class="registration__section--body"  v-if="parent_section_open">
                    <div class="registration__section--body__row">
                        <div class="registration__section--body__column">
                            <h4>Vorname Mutter</h4>
                            <h5>{{ child.mother_first_name }}</h5>
                        </div>
                        <div class="registration__section--body__column">
                            <h4>Nachname Mutter </h4>
                            <h5>{{ child.mother_last_name }}</h5>
                        </div>

                    </div>
                    <div class="registration__section--body__row">
                        <div class="registration__section--body__column">
                            <h4>Vorname Vater</h4>
                            <h5> {{child.father_first_name}} </h5>
                        </div>
                        <div class="registration__section--body__column">
                            <h4>Nachname Vater </h4>
                            <h5> {{child.father_last_name}} </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="registration__section" >
                <div class="registration__section--header">
                    <h4>Adresse</h4>
                    <span @click="address_section_open = !address_section_open">
                        <ArrowDown />
                    </span>
                </div>
                <div class="registration__section--body"  v-if="address_section_open">
                    <div class="registration__section--body__row">
                        <div class="registration__section--body__column">
                            <h4>Optionaler Adresszusatz</h4>
                            <h5>{{ child.optional_address }}</h5>
                        </div>
                        <div class="registration__section--body__column">
                            <h4>Strasse</h4>
                            <h5>{{ child.street }}</h5>
                        </div>
                        <div class="registration__section--body__column">
                            <h4>Hausnummer</h4>
                            <h5>{{child.street_number}}</h5>
                        </div>

                    </div>
                    <div class="registration__section--body__row">
                        <div class="registration__section--body__column">
                            <h4>Postleitzahl</h4>
                            <h5>{{ child.postal_code }}</h5>
                        </div>
                        <div class="registration__section--body__column">
                            <h4>Ort</h4>
                            <h5>{{ child.place }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="registration__section" >
                <div class="registration__section--header">
                    <h4>Kontaktdaten</h4>
                    <span @click="contact_persion_section_open = !contact_persion_section_open">
                        <ArrowDown />
                    </span>
                </div>
                <div class="registration__section--body"  v-if="contact_persion_section_open">
                    <div class="registration__section--body__row">
                        <div class="registration__section--body__column">
                            <h4>E-Mail Adresse</h4>
                            <h5>{{child.email}}</h5>
                        </div>
                        <div class="registration__section--body__column">
                            <h4>Telefonnummer</h4>
                            <h5>{{child.phone_number_prefix}}{{ child.phone_number }}</h5>
                        </div>
                        <div class="registration__section--body__column">
                            <h4>Mobiltelefonnummer</h4>
                            <h5>
                                <template v-if="child.mobile_phone_number">
                                    {{child.mobile_phone_number_prefix}}{{child.mobile_phone_number}}
                                </template>
                            </h5>
                        </div>

                    </div>
                </div>
            </div>
            <div class="registration__section" >
                <div class="registration__section--header">
                    <h4>Weitere Anmeldeinformationen</h4>
                    <span @click="other_registrationin_information_section_open = !other_registrationin_information_section_open">
                        <ArrowDown />
                    </span>
                </div>
                <div class="registration__section--body"  v-if="other_registrationin_information_section_open">
                    <div class="registration__section--body__row">
                        <div class="registration__section--body__column">
                            <h4>Präferierte Spielgruppe</h4>
                            <h5> {{ child.spielgruppen }} </h5>
                        </div>
                        <div class="registration__section--body__column">
                            <h4>Anzahl Spielgruppenbesuche</h4>
                            <h5>
                                <template v-if="child.semester_id == 1">
                                    1 x wöchentlich 2 ½ h à CHF 360.- pro Semester
                                </template>
                                <template v-if="child.semester_id == 2">
                                    2 x wöchentlich 2 ½ h à CHF 720.- pro Semester
                                </template>
                                <template v-if="child.semester_id == 3">
                                    3 x wöchentlich 2 ½ h à CHF 1’080.- pro Semester
                                </template>
                            </h5>
                        </div>
                        <div class="registration__section--body__column">
                            <h4>Mitgliedschaft</h4>
                            <h5>{{ child.membership_id == 1 ? 'Ich trete dem Familienforum bei' : 'Ich bin bereits Mitglied des Familien Forums' }}</h5>
                        </div>

                    </div>

                    <!-- Implementation required -->
                    <div class="registration__section--body__row">
                        <div class="registration__section--body__column" style="width:100% !important;">
                            <div class="registration__section--body__row--nested">
                                <div class="registration__section--body__row--nested--column" style="width:33.33%;">

                                </div>
                                <div class="registration__section--body__row--nested--column" style="width:13.33%;">
                                    <h4>Montag</h4>
                                </div>
                                <div class="registration__section--body__row--nested--column" style="width:13.33%;">
                                    <h4>Dienstag</h4>
                                </div>
                                <div class="registration__section--body__row--nested--column" style="width:13.33%;">
                                    <h4>Mittwoch</h4>
                                </div>
                                <div class="registration__section--body__row--nested--column" style="width:13.33%;">
                                    <h4>Donnerstag</h4>
                                </div>
                                <div class="registration__section--body__row--nested--column" style="width:13.33%;">
                                    <h4>Freitag</h4>
                                </div>
                            </div>
                            <div class="registration__section--body__row--nested">
                                <div class="registration__section--body__row--nested--column" style="width:33.33%;">
                                    <h5 v-if="child.selected_spielgruppen  && child.selected_spielgruppen.morning_time_range">
                                        <!-- {{ child.selected_spielgruppen.morning_time_range }} -->
                                        Vormittag ({{ child.selected_spielgruppen.morning_time_range | formatTimeRange}})
                                    </h5>
                                    <h5 v-else>Vormittag</h5>
                                </div>
                                <div class="registration__section--body__row--nested--column" style="width:13.33%;" v-html="getStatus('morning_selection', 1)">
                                </div>
                                <div class="registration__section--body__row--nested--column" style="width:13.33%;" v-html="getStatus('morning_selection', 2)">
                                </div>
                                <div class="registration__section--body__row--nested--column" style="width:13.33%;" v-html="getStatus('morning_selection', 3)">
                                </div>
                                <div class="registration__section--body__row--nested--column" style="width:13.33%;" v-html="getStatus('morning_selection', 4)">
                                </div>
                                <div class="registration__section--body__row--nested--column" style="width:13.33%;" v-html="getStatus('morning_selection', 5)">
                                </div>
                            </div>
                            <div class="registration__section--body__row--nested">
                                <div class="registration__section--body__row--nested--column" style="width:33.33%;">
                                    <!-- <h5>Vormittag (08:48 - 11:45 Uhr)</h5> -->
                                    <h5 v-if="child.selected_spielgruppen  && child.selected_spielgruppen.afternoon_time_range">
                                        Nachmittag ({{ child.selected_spielgruppen.afternoon_time_range | formatTimeRange}})
                                    </h5>
                                    <h5 v-else>Nachmittag</h5>
                                </div>
                                <div class="registration__section--body__row--nested--column" style="width:13.33%;" v-html="getStatus('evening_selection', 1)">
                                </div>
                                <div class="registration__section--body__row--nested--column" style="width:13.33%;" v-html="getStatus('evening_selection', 2)">
                                </div>
                                <div class="registration__section--body__row--nested--column" style="width:13.33%;" v-html="getStatus('evening_selection', 3)">
                                </div>
                                <div class="registration__section--body__row--nested--column" style="width:13.33%;" v-html="getStatus('evening_selection', 4)">
                                </div>
                                <div class="registration__section--body__row--nested--column" style="width:13.33%;" v-html="getStatus('evening_selection', 5)">
                                </div>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
            <div class="registration__section" >
                <div class="registration__section--header">
                    <h4>Weitere Anmeldeinformationen</h4>
                    <span @click="further_registrationin_information_section_open = !further_registrationin_information_section_open">
                        <ArrowDown />
                    </span>
                </div>
                <div class="registration__section--body"  v-if="further_registrationin_information_section_open">
                    <div class="registration__section--body__row">
                        <div class="registration__section--body__column" style="width: 100%">
                            <h4>Bemerkungen Eltern</h4>
                            <h5> {{ child.note }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="registration__section" >
                <div class="registration__section--header">
                    <h4>Einteilungsinformationen</h4>
                    <span @click="placement_information_section_open = !placement_information_section_open">
                        <ArrowDown />
                    </span>
                </div>
                <div class="registration__section--body"  v-if="placement_information_section_open">
                    <div class="registration__section--body__row">
                        <div class="registration__section--body__column">
                            <h4>Naheliegendste Spielgruppe</h4>
                            <router-link style="text-decoration: underline;cursor: pointer;" tag="h5" :to="{name: 'spielgruppen_show', params: {id: child.closest_spielgruppen_id}}">{{ child.closest_spielgruppen }}</router-link>
                            <!-- <h5 style="text-decoration: underline;">{{ child.closest_spielgruppen }}</h5> -->
                        </div>
                        <div class="registration__section--body__column">
                            <h4>Eingeteilte Spielgruppe</h4>
                            <!-- <h5 style="text-decoration: underline;">{{ child.closest_spielgruppen }}</h5> -->
                            <router-link style="text-decoration: underline;cursor: pointer;" tag="h5" :to="{name: 'spielgruppen_show', params: {id: child.closest_spielgruppen_id}}">{{ child.closest_spielgruppen }}</router-link>

                        </div>
                        <div class="registration__section--body__column">
                            <h4>Interne Bemerkungen</h4>
                            <h5>
                                {{ child.internal_comments }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="registration__view--footer" v-if="user.role_id == 1">
            <div class="registration__view--footer__button" @click="register">
                <router-link tag="button" :to="{name: 'anmeldungen-edit', params: {id: child.id}}">Bearbeiten</router-link>
            </div>
        </div>
    </div>
</template>

<script>
import ExpandIcon from "./Icons/ExpandIcon.vue";
import CloseIcon from "./Icons/CloseIcon.vue";
import PenIcon from "./Icons/PenIcon.vue";
import ArrowDown from "./Icons/ArrowDown.vue";
import CheckIcon from "./Icons/CheckIcon.vue";

import axios from '../../../axios'
import moment from "moment";
import {mask} from 'vue-the-mask'

export default {
    components: { ExpandIcon, CloseIcon, PenIcon, ArrowDown, CheckIcon },
    data() {
        return {
            show: false,
            child_section_open: true,
            parent_section_open: true,
            address_section_open: true,
            contact_persion_section_open: true,
            other_registrationin_information_section_open: true,
            further_registrationin_information_section_open: true,
            placement_information_section_open: true,


            //

            child : null,
            morning_selection: [],
            evening_selection : [],
            role: localStorage.getItem('role'),
        };
    },
    filters: {
        dateFormate(date){
            return moment(date).locale('de').format('DD.MM.YYYY')
        },
        leadzero: (value) => {
            return String(value).padStart(5, '0')
        },
        formatTimeRange(value) {
            if(value) {
                    return value.replace(/-/g, ' - ') + ' Uhr'
                }
                return value;
        }
    },
    mounted() {
        EventBus.$on("open-registration-view", (e) => {
            // this.show = true;
            this.getChildInfo(e.child_reg_id)
            console.log(e);
        });
    },
    computed: {
        // birthday() {
        //     return moment(this.child.child_birthday).locale('de').format('DD.MM.YYYY')
        // }
        user(){
            return this.$store.getters['user'];
        }
    },
    methods: {
        openRegistration(){
            // window.open(`/anmeldungen/show/${this.child.id}`, '_blank')
            window.open(`/anmeldungen/show/${this.child.id}`, `Registration_Detail-${new Date().toLocaleTimeString()}`, 'width=1920, height=1080')
            this.show = false
        },
        register(){
            // console.log('register')
            this.$router.push({name: 'anmeldungen-edit', params: {id: this.child.id}})
        },
        getChildInfo(id) {
            axios.get(`/api/childInformation/${id}`).then(res=>{
                this.child = res.data.data

                this.child.openingHours.map(item=>{
                    if(item.hour_id == 1) {
                        this.morning_selection.push(item.day_id)
                    }else{
                        this.evening_selection.push(item.day_id)
                    }

                })
                console.log(this.child)
                this.show = true;
            }).catch(err => {
                console.log(err)
            })
        },
        getStatus(type, dayId){
            const status = this[type].includes(dayId);
            if(status) {
                return `
                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16.8839 8.88437L17.7678 8.00049L16 6.23272L15.1161 7.1166L16.8839 8.88437ZM8.99998 15.0005L8.1161 15.8844C8.60426 16.3725 9.39571 16.3725 9.88386 15.8844L8.99998 15.0005ZM6.88382 11.1166L5.99993 10.2327L4.23217 12.0005L5.11606 12.8844L6.88382 11.1166ZM15.1161 7.1166L8.1161 14.1166L9.88386 15.8844L16.8839 8.88437L15.1161 7.1166ZM5.11606 12.8844L8.1161 15.8844L9.88386 14.1166L6.88382 11.1166L5.11606 12.8844ZM18.75 11.0005C18.75 15.2807 15.2802 18.7505 11 18.7505V21.2505C16.6609 21.2505 21.25 16.6614 21.25 11.0005H18.75ZM11 18.7505C6.71979 18.7505 3.25 15.2807 3.25 11.0005H0.75C0.75 16.6614 5.33908 21.2505 11 21.2505V18.7505ZM3.25 11.0005C3.25 6.72028 6.71979 3.25049 11 3.25049V0.750488C5.33908 0.750488 0.75 5.33957 0.75 11.0005H3.25ZM11 3.25049C15.2802 3.25049 18.75 6.72028 18.75 11.0005H21.25C21.25 5.33957 16.6609 0.750488 11 0.750488V3.25049Z" fill="#E97D32"/>
                    </svg>
                `
            }

            return `
                <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 9.75049H5.75V12.2505H7V9.75049ZM15 12.2505H16.25V9.75049H15V12.2505ZM7 12.2505H15V9.75049H7V12.2505ZM18.75 11.0005C18.75 15.2807 15.2802 18.7505 11 18.7505V21.2505C16.6609 21.2505 21.25 16.6614 21.25 11.0005H18.75ZM11 18.7505C6.71979 18.7505 3.25 15.2807 3.25 11.0005H0.75C0.75 16.6614 5.33908 21.2505 11 21.2505V18.7505ZM3.25 11.0005C3.25 6.72028 6.71979 3.25049 11 3.25049V0.750488C5.33908 0.750488 0.75 5.33957 0.75 11.0005H3.25ZM11 3.25049C15.2802 3.25049 18.75 6.72028 18.75 11.0005H21.25C21.25 5.33957 16.6609 0.750488 11 0.750488V3.25049Z" fill="#4C85A7"/>
                </svg>
            `
        }
    }
}
</script>

<style lang="scss" scoped>
    .registration__view {
        position: absolute;
        width: 930px;
        top: 40px;
        left: 50%;
        transform: translateX(-50%);
        background: #FAFCFF;
        box-shadow: 8px 8px 0px #D3E3EC;
        padding: 24px;
        padding-bottom: 30px;
        z-index: 99;

        &--header {
            display: flex;
            justify-content: space-between;
            align-items: center;

            svg {
                cursor: pointer;
            }
        }

        &--body {
            margin-top: 32px ;

            .registration__section {
                // margin-bottom: 56px;
                margin-bottom: 40px;


                &--header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    border-bottom: 1px solid #D3E3EC;
                    margin-bottom: 20px;
                    // padding-bottom: 10px;


                    h4 {
                        font-family: 'Ubuntu';
                        font-style: normal;
                        font-weight: 700;
                        font-size: 18px;
                        line-height: 21px;
                        color: #063F5F;
                        margin: 0;
                        padding: 0;
                    }

                    svg {
                        cursor: pointer;
                    }
                }

                &--body {
                    &__row {
                        display: flex;
                        margin-bottom: 40px;

                        &:last-child {
                            margin-bottom: 0;
                        }
                    }

                    &__column {
                        width: 33.33%;
                        h4, h5 {
                            margin: 0;
                            padding: 0;
                        }

                        h4 {
                            font-family: 'Nexa';
                            font-style: normal;
                            font-weight: 700;
                            font-size: 18px;
                            line-height: 27px;
                            color: #16A9B7;
                        }

                        h5 {
                            font-family: 'Nexa';
                            font-style: normal;
                            font-weight: 700;
                            font-size: 15px;
                            line-height: 23px;
                            color: #2C5B7D;
                        }
                    }

                    &__row {

                        &--nested {
                            display: flex;
                            padding: 8px 16px;

                            background: #FFFFFF;

                            &:last-child {
                                border-top: 1px solid #D3E3EC;
                            }


                            &:first-child {
                                margin-bottom: 10px;
                            }

                            &--column {

                            }
                        }
                    }
                }
            }
            .registration__section.default {
                .registration__section--header {
                    border: none;
                    margin-bottom: 40px;
                    padding: 0;
                    h4 {
                        font-family: 'Ubuntu';
                        font-style: normal;
                        font-weight: 700;
                        font-size: 22px;
                        line-height: 25px;
                        color: #063F5F;
                    }
                }
            }

        }

        &--footer {

            &__button {
                width: 435px;
                // height: 40px;
                padding: 8px 12px;
                background: #E97D32;
                display: flex;
                align-items: center;
                justify-content: center;

                background-image: url("data:image/svg+xml, %3Csvg width='26' height='26' viewBox='0 0 26 26' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.33594 24.6667H0.0859375V25.9167H1.33594V24.6667ZM1.33594 19.3333L0.452054 18.4494L0.0859375 18.8156V19.3333H1.33594ZM18.6693 2L19.5532 1.11612L18.6693 0.232233L17.7854 1.11612L18.6693 2ZM24.0026 7.33333L24.8865 8.21722L25.7704 7.33333L24.8865 6.44945L24.0026 7.33333ZM6.66927 24.6667V25.9167H7.18704L7.55315 25.5505L6.66927 24.6667ZM2.58594 24.6667V19.3333H0.0859375V24.6667H2.58594ZM17.7854 2.88388L23.1187 8.21722L24.8865 6.44945L19.5532 1.11612L17.7854 2.88388ZM6.66927 23.4167H1.33594V25.9167H6.66927V23.4167ZM23.1187 6.44945L19.1187 10.4494L20.8865 12.2172L24.8865 8.21722L23.1187 6.44945ZM19.1187 10.4494L5.78539 23.7828L7.55315 25.5505L20.8865 12.2172L19.1187 10.4494ZM2.21982 20.2172L15.5532 6.88388L13.7854 5.11612L0.452054 18.4494L2.21982 20.2172ZM15.5532 6.88388L19.5532 2.88388L17.7854 1.11612L13.7854 5.11612L15.5532 6.88388ZM20.8865 10.4494L15.5532 5.11612L13.7854 6.88388L19.1187 12.2172L20.8865 10.4494Z' fill='white'/%3E%3C/svg%3E%0A");
                background-repeat: no-repeat no-repeat;
                background-position: 12px center;
                // background-size: cover;

                cursor: pointer;

                button {
                    font-family: 'Nexa';
                    font-style: normal;
                    font-weight: 700;
                    font-size: 18px;
                    line-height: 27px;

                    text-align: center;

                    color: #FFFFFF;

                    svg {
                        // margin-right: 120px;
                        justify-self: flex-start;
                    }

                }
            }
        }
    }
</style>
