<template>
   <div class="card child-card anmeldungen-front">
       <div class="card-header">
          <h1 class="card-header-title">Anmeldung Spielgruppe</h1>
           <p class="card-header-subtitle">Bitte melden Sie jedes Kind separat an!</p>
       </div>
       <div class="registration-period">
           <p>Diese Anmeldung gilt von <span class="registration-period-date">{{start_month}} {{ start_year}} bis {{end_month}} {{year}}</span></p>
       </div>
       <div class="form-wrapper">
           <div class="flex name">
               <div class="form-group  w-full pr-20">
                   <label class="input-title">Vorname Kind*</label>
                   <input required v-model="child_name" class="form-control w-full form-input-bordered"
                          type="text"/>
                   <label class="my-2 text-danger"
                          v-if="errors && errors['child_first_name']">{{
                           errors['child_first_name'][0]
                       }}</label>
               </div>
               <div class="form-group  w-full">
                   <label class="input-title">Nachname Kind *</label>
                   <input required v-model="child_lastname" class="form-control w-full form-input-bordered" type="text"/>
                   <label class="my-2 text-danger"
                          v-if="errors && errors['child_last_name']">{{
                           errors['child_last_name'][0]
                       }}</label>
               </div>
           </div>
           <div class="gender form-group">
               <label class="input-title">Geschlecht *</label>
               <div class="gender-selection flex">
                   <div class="flex items-center">
                       <input class="form-control" v-model.number="gender_id" type="radio" value="1"><span class="gender-title">Junge</span>
                   </div>
                   <div class="flex items-center ml-3">
                       <input class="form-control" v-model.number="gender_id" type="radio" value="2" ><span class="gender-title">Mädchen</span>
                   </div>
               </div>
               <label class="my-2 text-danger"
                      v-if="errors && errors['gender_id']">{{
                       errors['gender_id'][0]
                   }}</label>
           </div>
           <div class="flex">
               <div class="form-group  w-full pr-20">
                   <date-input
                       :required="true"
                       v-model="child_birthdate"
                       :error="errors && errors['child_birthday'] ?  errors['child_birthday'][0]:false"
                       label="Geburtsdatum Kind"
                   />
               </div>
               <div class="form-group  w-full">
                   <label class="input-title">Nationalität Kind *</label>
                   <select @change="getSelectedNationality" required v-model="selected_nationality" class="form-control w-full form-input-bordered">
                       <option :value="item.id" :key="item.id" v-for="item in nationalities">{{item.name}}</option>
                   </select>
                   <label class="my-2 text-danger"
                          v-if="errors && errors['nationality_id']">{{
                           errors['nationality_id'][0]
                       }}</label>
               </div>
           </div>
           <div class="form-group  w-full ">
               <label class="input-title">Muttersprache *</label>
               <select v-model="selected_language" class="form-control w-full form-input-bordered">
                   <option v-for="language in languages" :value="language.id" :key="language.id" >{{ language.name }}</option>
               </select>
               <label class="my-2 text-danger" v-if="errors && errors['mother_tongue_id']">{{errors['mother_tongue_id'][0]}}</label>
           </div>
           <div v-if="selected_language === 12" class="form-group  w-full">
               <input required v-model="other_language" class="form-control w-full form-input-bordered" type="text"/>
               <label class="my-2 text-danger"
                      v-if="errors && errors['other_mother_tongue']">{{
                       errors['other_mother_tongue'][0]
                   }}</label>
           </div>
           <div class="flex">
               <div class="form-group  w-full pr-20">
                   <label class="input-title">Vorname Mutter *</label>
                   <input required v-model="mother_name" class="form-control w-full form-input-bordered"
                          type="text"/>
                   <label class="my-2 text-danger"
                          v-if="errors && errors['mother_first_name']">{{
                           errors['mother_first_name'][0]
                       }}</label>
               </div>
               <div class="form-group  w-full">
                   <label class="input-title">Nachname Mutter *</label>
                   <input required v-model="mother_lastname" class="form-control w-full form-input-bordered" type="text"/>
                   <label class="my-2 text-danger"
                          v-if="errors && errors['mother_last_name']">{{
                           errors['mother_last_name'][0]
                       }}</label>
               </div>
           </div>
           <div class="flex">
               <div class="form-group  w-full pr-20">
                   <label class="input-title">Vorname Vater *</label>
                   <input required v-model="father_name" class="form-control w-full form-input-bordered"
                          type="text"/>
                   <label class="my-2 text-danger"
                          v-if="errors && errors['father_first_name']">{{
                           errors['father_first_name'][0]
                       }}</label>
               </div>
               <div class="form-group  w-full">
                   <label class="input-title">Nachname Vater *</label>
                   <input required v-model="father_lastname" class="form-control w-full form-input-bordered" type="text"/>
                   <label class="my-2 text-danger"
                          v-if="errors && errors['father_last_name']">{{
                           errors['father_last_name'][0]
                       }}</label>
               </div>
           </div>
           <div class="flex">
               <div class="form-group  w-full pr-20">
                   <label class="input-title">Strasse *</label>
                   <vue-google-autocomplete
                       id="map"
                       classname="form-control form-input-bordered"
                       v-on:placechanged="getFromAddress"
                       placeholder=""
                       v-on:change="getNewVal"
                       ref="address"
                   >
                   </vue-google-autocomplete>
                   <label class="my-2 text-danger"
                          v-if="errors && errors['street']">{{
                           errors['street'][0]
                       }}</label>
               </div>
               <div class="form-group  w-full">
                   <label class="input-title">Hausnummer *</label>
                   <input required v-model="street_number" class="form-control w-full form-input-bordered" type="text"/>
                   <label class="my-2 text-danger"
                          v-if="errors && errors['street_number']">{{
                           errors['street_number'][0]
                       }}</label>
               </div>
           </div>
           <div class="flex">
               <div class="form-group  w-full pr-20">
                   <label class="input-title">Postleitzahl *</label>
                   <input required v-model="postal_code" class="form-control w-full form-input-bordered"
                          type="text"/>
                   <label class="my-2 text-danger"
                          v-if="errors && errors['postal_code']">{{
                           errors['postal_code'][0]
                       }}</label>
               </div>
               <div class="form-group  w-full">
                   <label class="input-title">Ort *</label>
                   <input required v-model="place" class="form-control w-full form-input-bordered" type="text"/>
                   <label class="my-2 text-danger"
                          v-if="errors && errors['place']">{{
                           errors['place'][0]
                       }}</label>
               </div>
           </div>
           <div class="form-group  w-full">
               <label class="input-title">Email *</label>
               <input required v-model="email" class="form-control w-full form-input-bordered"
                      type="email"/>
               <label class="my-2 text-danger"
                      v-if="errors && errors['email']">{{
                       errors['email'][0]
                   }}</label>
           </div>
           <div class="flex">
               <div class="input-group">
                   <div class="form-group  w-full pr-20">
                       <label class="input-title">Telefonnummer *</label>
                           <div class="phone">
                               <input @input="phoneNumberFormater(phone_number)" v-mask="'## ### ## ##'" maxlength="12" style="padding-left: 62px" v-model="phone_number" type="tel" class="form-control w-full form-input-bordered" required>
                               <span class="phone-label">+41</span>
                           </div>
                           <label class="my-2 text-danger"
                              v-if="errors && errors['phone_number']">{{
                               errors['phone_number'][0]
                           }}</label>
                   </div>
               </div>
               <div class="input-group">
                   <div class="form-group  w-full">
                       <label class="input-title">Mobile Telefonnummer</label>
                       <div class="phone">
                           <input @input="phoneNumberFormater(cellphone_number)" v-mask="'## ### ## ##'" maxlength="12" style="padding-left: 62px" v-model="cellphone_number" type="tel" class="form-control w-full form-input-bordered" required>
                           <span class="phone-label">+41</span>
                       </div>
                       <label class="my-2 text-danger"
                              v-if="errors && errors['mobile_phone_number']">{{
                               errors['mobile_phone_number'][0]
                           }}</label>
                   </div>
               </div>
           </div>
           <div class="form-group  w-full">
               <label class="input-title">Heutige Spielgruppe *</label>
               <select required v-model="playgroup_location" class="form-control w-full form-input-bordered">
                   <option v-for="spielgruppen in spielgruppens" :key="spielgruppen.id" :value=spielgruppen.id>{{ spielgruppen.name }}</option>
                   <option value="keine">Keine</option>
               </select>
               <label class="my-2 text-danger"
                      v-if="errors && errors['spielgruppen_id']">{{
                       errors['spielgruppen_id'][0]
                   }}</label>
           </div>
           <p class="strong-text">Wir möchten unser Kind wie folgt für die Spielgruppe anmelden (zutreffendes bitte ankreuzen): * </p>
           <div class="checkbox-form">
               <input v-model="semester_id" value=1  type="radio" class="form-control"/>
               <label class="checkbox-label">1 x wöchentlich 2 ½ h à CHF 360.- pro Semester</label>
           </div>
           <div class="checkbox-form">
               <input v-model="semester_id" value=2 type="radio" class="form-control"/>
               <label class="checkbox-label">2 x wöchentlich 2 ½ h à CHF 720.- pro Semester</label>
           </div>
           <div class="checkbox-form">
               <input v-model="semester_id" value=3 type="radio" class="form-control"/>
               <label class="checkbox-label">3 x wöchentlich 2 ½ h à CHF 1’080.- pro Semester</label>
           </div>
           <label class="my-2 text-danger"
                  v-if="errors && errors['semester_id']">{{
                   errors['semester_id'][0]
               }}</label>
           <p class="strong-text">Folgende Spielgruppen-Halbtage sind möglich für uns (bitte ALLE möglichen Varianten ankreuzen, nicht nur die präferiertesten) *</p>
           <div class="table-wrapper" :class="{'red-error':(errors && errors['openingHours'])}">
               <table class="weekday-table">
                   <thead class="weekday-table-head">
                   <tr>
                       <th colspan="2" scope="col"></th>
                       <th align="center" scope="col">Montag</th>
                       <th align="center" scope="col">Dienstag</th>
                       <th align="center" scope="col">Mittwoch</th>
                       <th align="center" scope="col">Donnerstag</th>
                       <th align="center" scope="col">Freitag</th>
                   </tr>
                   </thead>
                   <tbody class="weekday-table-body">
                   <tr>
                       <td class="row-title" colspan="2" scope="row">Vormittag (8:45 - 11:15 Uhr)</td>
                       <td align="center">
                           <input type="checkbox" :value="1"  v-model="morning_selection"/>
                       </td>
                       <td align="center">
                           <input type="checkbox" :value="2" v-model="morning_selection"/>
                       </td>
                       <td align="center">
                           <input type="checkbox" :value="3" v-model="morning_selection"/>
                       </td>
                       <td align="center">
                           <input type="checkbox" :value="4" v-model="morning_selection"/>
                       </td>
                       <td align="center">
                           <input type="checkbox" :value="5" v-model="morning_selection"/>
                       </td>
                   </tr>
                   <tr>
                       <td class="row-title" colspan="2" scope="row">Nachmittag (14:00 - 16:30 Uhr)</td>
                       <td align="center">
                           <input type="checkbox" :value="1" v-model="evening_selection"/>
                       </td>
                       <td align="center">
                           <input type="checkbox" :value="2" v-model="evening_selection"/>
                       </td>
                       <td align="center">
                           <input type="checkbox" :value="3" v-model="evening_selection"/>
                       </td>
                       <td align="center">
                           <input type="checkbox" :value="4" v-model="evening_selection"/>
                       </td>
                       <td align="center">
                           <input type="checkbox" :value="5" v-model="evening_selection"/>
                       </td>
                   </tr>
                   </tbody>
               </table>
           </div>
           <label class="my-2 text-danger"
                  v-if="errors && errors['openingHours']">{{errors['openingHours'][0]}}</label>
           <p class="strong-text">Mitgliedschaft *</p>
           <div class="radio-form">
               <div class="checkbox-form">
                   <input v-model="membership_id" :value="1" type="radio" class="form-control"/>
                   <span class="checkbox-label">Ich trete dem Familienforum bei (CHF 30.-/Jahr)</span>
               </div>
               <div class="checkbox-form">
                   <input v-model="membership_id" :value="2" type="radio" class="form-control"/>
                   <span class="checkbox-label">Ich bin bereits Mitglied des Familien Forums</span>
               </div>
               <label class="my-2 text-danger"
                      v-if="errors && errors['membership_id']">{{
                       errors['membership_id'][0]
                   }}</label>
           </div>
           <p class="bemer">Bemerkungen</p>
           <textarea style="height: 100px" class="form-control notes-area" rows="8" v-model="notes"></textarea>
           <label class="text-danger"
                  v-if="errors && errors['note']">{{
                   errors['note'][0]
               }}</label>
           <div>
               <div class="checkbox-form">
                   <input v-model="division_class" true-value=1 false-value=0 type="checkbox" class="form-control"/>
                   <label class="checkbox-label-strong">Einteilungswünsche können nicht berücksichtigt werden *</label>
               </div>
               <p class="checkbox-label checkbox-paragraph">Wir werden uns bemühen, eine möglichst optimale Einteilung zu machen. Ihr Kind wird in den Spielgruppenstandort eingeteilt, welcher ihrem Wohnquartier am nächsten steht. Wünsche nach Spielgruppenstandort können aus logistischen Gründen nicht berücksichtigt werden.</p>
               <label style="margin-left: 25px" class="my-2 text-danger"
               v-if="errors && errors['division_class']">{{
                   errors['division_class'][0]
               }}</label>
           </div>
           <div class="mt-4">
               <div class="checkbox-form">
                   <input v-model="registration_conditions" true-value=1 false-value=0 type="checkbox" class="form-control"/>
                   <label class="checkbox-label-strong">Anmeldebedingungen annehmen *</label>
               </div>
               <p class="checkbox-label checkbox-paragraph">Die Anmeldung ist verbindlich und bis zu den Sommerferien {{year_of_the_end_date_phase}} gültig. Bei einem Rückzug der Anmeldung nach Anmeldeschluss {{end_date_of_the_registration_phase}} wird eine Gebühr von CHF 75 bis CHF 225.- (je nach Anzahl der Spielgruppenbesuche pro Woche) verrechnet.</p>
               <label style="margin-left: 25px" class="my-2 text-danger"
               v-if="errors && errors['registration_conditions']">{{
                   errors['registration_conditions'][0]
               }}</label>
           </div>
           <div class="mt-4">
               <div class="checkbox-form">
                   <input v-model="confirmation_age" true-value=1 false-value=0 type="checkbox" class="form-control"/>
                   <label class="checkbox-label-strong">Bestätigung Eintrittsalter *</label>
               </div>
               <p class="checkbox-label checkbox-paragraph">Die Spielgruppe kann ab einem Eintrittsalter von 2.5 Jahren besucht werden. Mein Kind ist vor oder am {{children_minimum_age}} (Stichtag) geboren.</p>
               <label style="margin-left: 25px" class="my-2  text-danger"
               v-if="errors && errors['confirmation_age']">{{
                   errors['confirmation_age'][0]
               }}</label>
           </div>
           <button @click="registerChild" class="submit-button btn btn-primary">Anmelden</button>
       </div>

   </div>
</template>

<script>
import axios from '../../axios'
import {mask} from 'vue-the-mask'
import VueGoogleAutocomplete from 'vue-google-autocomplete'
import phoneNumberFormater from "../../mixins/phoneNumberFormaterMixin";
import getLanguages from "../../mixins/getLanguagesMixin";
import getNationalities from "../../mixins/getNationalitiesMixin";
import DateInput from "../../shared/DateInput";
import moment from "moment";
import flushMessagesMixin from "../../mixins/flushMessagesMixin";
export default {
    components: {
        VueGoogleAutocomplete,
        DateInput
    },
    mixins:[phoneNumberFormater,getLanguages,getNationalities,flushMessagesMixin],
    directives: {mask},
    name: "ChildRegistration",
    data(){
    return {
        company_id: null,
        child_name: '',
        child_lastname: '',
        errors: [],
        gender_id: 1,
        child_birthdate: null,
        mother_name:'',
        mother_lastname:'',
        father_name: '',
        father_lastname: '',
        street_address: '',
        street_number: '',
        postal_code: '',
        place: '',
        email: '',
        other_language: '',
        phone_number: null,
        cellphone_number: '',
        playgroup_location: null,
        morning_selection: [],
        evening_selection: [],
        notes: '',
        division_class: null,
        registration_conditions: null,
        confirmation_age: null,
        place_id: '',
        membership_id: null,
        semester_id: null,
        child_language: 4,
        morningOpeningHours: {},
        afternoonOpeningHours: {},
        spielgruppens: [],
        year_of_the_end_date: null,
        year_of_the_end_date_phase: null,
        end_date_of_the_registration_phase: null,
        children_minimum_age: null,
        parents_inform_date: null,
        registration_phase_start_date: null,
        year: null,
        start_month: null,
        end_month: null,
        start_year: null,
        registration_phase_to: null,
        registration_phase_from: null,
        registrationInterval:null
    }
    },
    created() {
       this.getLanguages()
       this.getNationalities()
       this.company_id = parseInt(this.$route.params.company_id)
        setTimeout(()=>{
            this.getSpielgruppen()
        },1000)
        this.getRegistrationPeriodInfo(this.company_id )

    },
    mounted() {
        this.registrationInterval = setInterval(() => this.getRegistrationPeriodInfo(this.company_id ), 5000)
    },
    watch: {
        morning_selection() {
            this.morningOpeningHours = [];
            this.morning_selection.forEach(item => {
                this.morningOpeningHours.push({
                    [item]: 1
                })
            })
        },
        evening_selection() {
            this.afternoonOpeningHours = [];
            this.evening_selection.forEach(item => {
                this.afternoonOpeningHours.push({
                    [item]: 2
                })
            })
        },
    },
    methods: {
        getNewVal(newLocationAddres){
            this.$refs.address.$data.autocompleteText = this.street_address
        },
        getFromAddress(from_address, placeResultData, id) {
            this.street_address = from_address.route;
            this.street_number = from_address.street_number;
            this.postal_code= from_address.postal_code;
            this.place= from_address.locality;
            this.place_id = placeResultData.place_id
        },

        getSpielgruppen() {
            axios.get(`/api/spielgruppens/${this.company_id}`).then(res=>{
                this.spielgruppens = res.data
            })
        },
        getRegistrationPeriodInfo() {
            axios.get(`/api/get-period-info/${this.company_id}`).then(res=>{
                this.year_of_the_end_date = res.data.year_of_the_end_date
                this.year_of_the_end_date_phase = res.data.year_of_the_end_date_register
                this.end_date_of_the_registration = res.data.end_date_of_the_registration
                this.end_date_of_the_registration_phase = res.data.end_date_of_the_registration_phase
                this.children_minimum_age = res.data.children_minimum_age
                this.parents_inform_date = res.data.parents_inform_date
                this.registration_phase_start_date = res.data.registration_phase_start_date
                this.start_month = res.data.registration_phase_start_date_month
                this.end_month = res.data.end_date_of_the_registration_month
                this.year = res.data.year_of_the_end_date
                this.registration_phase_to = res.data.registration_phase_to
                this.registration_phase_from = res.data.registration_phase_from
                if(res.data.year_of_the_start_date != res.data.year_of_the_end_date){
                    this.start_year = res.data.year_of_the_start_date
                }
            })
        },

        isOutOfRegistrationPeriod(){
            return moment()
                .isBetween(
                    moment(this.registration_phase_from, "DD.MM.YYYY"),
                    moment(this.registration_phase_to, "DD.MM.YYYY"),
                    'days',
                    '[]'
                )
        },
        registerChild() {
            if (!this.isOutOfRegistrationPeriod()){
                return this.$router.push({
                    name: 'out-of-registration-period',
                    params:{
                        registration_phase_start_date:this.registration_phase_from,
                        registration_phase_end_date:this.registration_phase_to,
                    }
                })
            }
            let hoursData = {}
            if (this.morningOpeningHours.length && this.afternoonOpeningHours.length) {
                hoursData = {
                    openingHours: JSON.stringify(this.morningOpeningHours.concat(this.afternoonOpeningHours))
                }
            }else if(this.morningOpeningHours.length){
                hoursData = {
                    openingHours: JSON.stringify(this.morningOpeningHours)
                }
            }
            else if(this.afternoonOpeningHours.length){
                hoursData = {
                    openingHours: JSON.stringify(this.afternoonOpeningHours)
                }
            }
            else {
                hoursData.openingHours = ''
            }

            const intPhoneNumber = this.phone_number ? this.phone_number.replace(/ /g, '') : ''
            const intMobileNumber = this.cellphone_number ? this.cellphone_number.replace(/ /g, '') : ''
            let data = {
                child_first_name: this.child_name,
                child_last_name: this.child_lastname,
                gender_id: this.gender_id,
                child_birthday: this.child_birthdate,
                mother_first_name: this.mother_name,
                mother_last_name: this.mother_lastname,
                mother_tongue_id: this.selected_language,
                father_first_name: this.father_name,
                father_last_name: this.father_lastname,
                street: this.street_address,
                street_number: this.street_number,
                email: this.email,
                phone_number_prefix: '+41',
                mobile_phone_number_prefix: '+41',
                postal_code: this.postal_code,
                place : this.place,
                phone_number: parseInt(intPhoneNumber),
                mobile_phone_number: parseInt(intMobileNumber),
                place_id: this.place_id,
                semester_id : this.semester_id,
                membership_id: this.membership_id,
                note: this.notes,
                nationality_id: this.selected_nationality,
                other_mother_tongue: this.other_language,
                openingHours: hoursData.openingHours,
                company_id: this.company_id,
                division_class: this.division_class,
                registration_conditions: this.confirmation_age,
                confirmation_age: this.confirmation_age,
            }
            if(this.playgroup_location != 'keine') {
                data.spielgruppen_id = this.playgroup_location
            }
            axios.post(`/api/${this.company_id}/parent-registry`, data).then(res=>{
                return this.$router.push({
                    name: 'confirmation-message',
                    params:{
                        parents_inform_date:this.parents_inform_date
                    }
                })
            }).catch(err=>{
                this.errorMessage(err.response.data.message)
                const backend_errors = err.response.data.errors
                this.errors = Object.assign({}, backend_errors)
            })
        },

        clearFields() {
            this.child_name = '';
            this.child_lastname = '';
            this.gender_id = 1;
            this.child_birthdate = null;
            this.mother_name = '';
            this.mother_lastname = '';
            this.selected_language = null;
            this.father_name = '';
            this.father_lastname = '';
            this.street_address = '';
            this.street_number = '';
            this.email = '';
            this.postal_code = '';
            this.place = '';
            this.phone_number = '';
            this.cellphone_number = '';
            this.playgroup_location = '';
            this.place_id = '';
            this.semester_id = null;
            this.membership_id = null;
            this.other_language = '';
            this.selected_language_id = null
            this.notes = '';
            this.selected_nationality = null
            this.confirmation_age = null
            this.division_class = null
            this.registration_conditions = null
            this.morning_selection = [];
            this.evening_selection = [];
            this.morningOpeningHours = [];
            this.afternoonOpeningHours = []
            document.getElementById('map').value = '';
            this.$refs.address.$data.autocompleteText=''
        }
    },
    beforeDestroy () {
        clearInterval(this.registrationInterval)
    }
}
</script>

<style scoped lang="scss">
.card{
    position: relative;
    border: 1px solid #C2C5C6;
    border-radius: 15px;
    padding-left: 30px;
    padding-right: 30px;
    padding-bottom: 25px;
    &.child-card{
        max-width: 764px;
        width: 90%;
        margin: 0 auto 150px;
    }
    .card-header {
        text-align: center;
        font-family: 'Lato', sans-serif;
        .card-header-title {
            padding-top: 40px;
            font-style: normal;
            font-weight: 600;
            font-size: 42px;
            line-height: 50px;
            text-align: center;
            color: #42474A;
        }
        .card-header-subtitle {
            padding-top: 11px;
            padding-bottom: 18px;
            font-weight: 600;
            font-size: 16px;
            line-height: 19px;
            text-align: center;
            text-transform: uppercase;
            color: #42474A;
        }
    }

    .registration-period {
        font-weight: 400;
        font-size: 16px;
        line-height: 25px;
        padding: 7.5px 10px;
        text-align: center;
        color: #F8FBFA;
        background: #AEC90B;
        border-radius: 5px;
        margin-bottom: 30px;
        &-date {
            color: black;
        }
    }
    .form-wrapper {
        padding-bottom: 16px;
        .form-input-bordered{
            margin-top: 8px;
            background: #F8FBFA;
            border: 0.5px solid #727574;
            box-sizing: border-box;
            border-radius: 5px;
        }
        .form-group{
            margin-bottom: 25px;
            label{
                font-weight: 500;
                font-size: 12px;
                line-height: 14px;
                color: #42474A;
                &.text-danger{
                    color: #E62D4F;
                }
            }
        }
    }

}


.text-danger{
    font-size: 12px;
}

.gender-title {
    margin-left: 5px;
    font-weight: 400;
    font-size: 14px;
    line-height: 17px;
    color: #727574;
}

.phone {
    position: relative;
    display: inline-block;
    width: 100%;
}

.phone-label {
    background: #E3E9EB !important;
    color: #42474A;
    border: 0.5px solid darkgray;
    border-top-left-radius: 5px;
    border-bottom-left-radius: 5px;
    padding: 8px 14px;
    position: absolute;
    top: 8px;
    left: -1px;
}

.strong-text {
    margin-top: 25px;
    font-weight: 700;
    font-size: 15px;
    line-height: 18px;
    color: #42474A;
}

.checkbox-form {
    display: flex;
    align-items: center;
}

.checkbox-label {
    margin-left: 10px;
    color: #727574;
    font-size: 14px;
}

.row-title {
    font-size: 12px;
}

.weekday-table {
    width: 100%;
    border-spacing: 0;
    border-collapse: collapse;
    border-style: hidden;
}

.weekday-table-head {
    width: 100%;
    border-radius: 8px;
    border: 1px solid #727574;
    padding: 10px
}

th {
    padding-top: 7px;
    padding-bottom: 6px;
    background: #E3E9EB;
    font-weight: 400;
    font-size: 14px;
    line-height: 17px;
    text-align: center;
    color: #42474A;
    border: 1px solid #727574;
}

td {
    border: 1px solid #727574;
    padding: 8px 15px 7px;
    background: #F8FBFA;
}

.table-wrapper {
    border: 1px solid #727574;
    border-radius: 5px;
    overflow: hidden;
    margin-top:10px;
}
.red-error{
    &.table-wrapper,
    .weekday-table-head,
    td,
    th{
        border: 1px solid #E62D4F;
        background: rgba(230, 45, 79, 0.05);
    }

}
.notes-area {
    width: 100%;
    background: #F8FBFA;
    border: 1px solid #727574;
    box-sizing: border-box;
    border-radius: 5px;
    color: #42474A;
    padding: 11px 20px;
    margin-bottom:20px;
}

.checkbox-label-strong {
    margin-left: 10px;
    font-weight: 700;
    font-size: 15px;
    line-height: 18px;
    color: #42474A;

}

.checkbox-paragraph {
    margin-left: 25px;
    margin-top: 0;
    font-weight: 400;
    font-size: 14px;
    line-height: 17px;
    color: #42474A;
}

.btn-primary {
    font-weight: 500;
    font-size: 15px;
    line-height: 40px;
    border-radius: 5px;
    background: #AEC90B;
    width: 150px;
    height: 40px;
    position: absolute;
    bottom: -41px;
    left: 30px;
}

.pac-target-input {
    width: 100%;
    border: 1px solid #bacad6;
    border-radius: .5rem;
    padding-inline: 0.75rem;
}
.bemer{
    margin-top: 20px;
    font-weight: 500;
    font-size: 14px;
    line-height: 17px;
    color: #42474A;
}
</style>
