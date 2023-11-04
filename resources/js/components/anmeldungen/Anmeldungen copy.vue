<template>
    <div>
    {{anmeldungens}}
    </div>
    <!-- <div class="anmeldungen">
     <div class="flex mb-15" v-if="role === 'admin'">
         <select v-model="selected_playgroup" class="first-selection form-control mr-3">
             <option :value="0">Nach Standort filtern</option>
             <option v-for="item in spielgruppens" :value="item.id" :key="item.id">{{item.name}}</option>
         </select>
         <select class="first-selection form-control">
             <option selected>August 2022</option>
         </select>
     </div>
     <div class="row flex">
         <div style="width: 69%;margin-right: 30px">
             <div v-if="role === 'admin'" class="card-panel-home form-card-wrapper bg-white">
                 <div class="flex justify-between flex-wrap items-center">
                     <p class="form-title">Anmeldung <span v-if="child_registration_form">#{{getLastRegisterdChildrenId}}</span></p>
                     <button @click="child_registration_form = !child_registration_form" class="btn btn-danger">Neu</button>
                 </div>
                 <div v-if="child_registration_form" class="child-registration-form">
                     <div class="flex justify-between flex-wrap">
                         <div class="form-group pr-2">
                             <p class="input-label">Vorname Kind<sup>*</sup></p>
                             <input v-model="child_name" class="form-input-bordered form-control" placeholder="Lucas"/>
                             <label class="text-danger" v-if="errors && errors['child_first_name']">{{ errors['child_first_name'][0] }}</label>
                         </div>
                         <div class="form-group pr-2">
                             <p class="input-label">Nachname Kind<sup>*</sup></p>
                             <input v-model="child_lastname" class="form-input-bordered form-control" placeholder="Maier"/>
                             <label class="text-danger" v-if="errors && errors['child_last_name']">{{ errors['child_last_name'][0] }}</label>
                         </div>
                         <div class="form-group pr-2">
                             <p class="input-label">Geschlecht<sup>*</sup></p>
                             <select v-model="gender" class="form-control form-input-bordered">
                                 <option :value="1">Junge</option>
                                 <option :value="2">Mädchen</option>
                             </select>
                             <label class="text-danger" v-if="errors && errors['gender_id']">{{ errors['gender_id'][0] }}</label>
                         </div>
                         <div class="form-group">
                             <date-input
                                 :required="true"
                                 v-model="child_birthday"
                                 :error="errors && errors['child_birthday'] ?  errors['child_birthday'][0]:false"
                                 label="Geburtsdatum Kind"
                             />
                         </div>
                     </div>
                     <div class="flex justify-between flex-wrap">
                         <div class="form-group ">
                             <p class="input-label">Mitgliedschaft<sup>*</sup></p>
                             <select v-model="membership" class="form-input-bordered form-control">
                                 <option :value="1">Ich trete dem Familienforum bei</option>
                                 <option :value="2">Ich bin bereits Mitglied des Familien Forums</option>
                             </select>
                             <label class="text-danger" v-if="errors && errors['membership_id']">{{ errors['membership_id'][0] }}</label>
                         </div>
                         <div class="form-group ">
                             <p class="input-label">Nationalität Kind<sup>*</sup></p>
                             <select v-model="selected_nationality" class="form-input-bordered form-control">
                                 <option v-for="nationality in nationalities" :value="nationality.id" :key="nationality.id">{{ nationality.name }}</option>
                             </select>
                             <label class="text-danger" v-if="errors && errors['nationality_id']">{{ errors['nationality_id'][0] }}</label>
                         </div>
                         <div class="form-group ">
                             <p class="input-label">Muttersprache<sup>*</sup></p>
                             <select v-model="selected_language" class="form-input-bordered form-control">
                                 <option v-for="language in languages" :value="language.id" :key="language.id" >{{ language.name }}</option>
                             </select>
                             <label class="text-danger" v-if="errors && errors['mother_tongue_id']">{{ errors['mother_tongue_id'][0] }}</label>
                         </div>
                         <div v-if="selected_language === 12" class="form-group ">
                             <p class="input-label">Other Muttersprache<sup>*</sup></p>
                             <input class="form-input-bordered form-control" v-model="other_language"/>
                         </div>
                         <div class="form-group">
                             <p class="input-label">Spielgruppe</p>
                             <select v-model="semester" class="form-input-bordered form-control">
                                 <option :value="1">1 x wöchentlich 2 ½ h à CHF 360.- pro Semester</option>
                                 <option :value="2">2 x wöchentlich 2 ½ h à CHF 720.- pro Semester</option>
                                 <option :value="3">3 x wöchentlich 2 ½ h à CHF 1’080.- pro Semester</option>
                             </select>
                             <label class="text-danger" v-if="errors && errors['semester_id']">{{ errors['semester_id'][0] }}</label>
                         </div>
                     </div>
                     <div class="flex">
                         <div class="form-group">
                             <p class="input-label">Heutige</p>
                             <select v-model="playgroup_location" class="form-input-bordered form-control">
                                 <option v-for="spielgruppen in spielgruppens" :key="spielgruppen.id" :value="spielgruppen.id">{{spielgruppen.name}}</option>
                                 <option value="keine">Keine</option>
                             </select>
                             <label class="text-danger" v-if="errors && errors['current_playgroup']">{{ errors['current_playgroup'][0] }}</label>
                         </div>
                     </div>
                     <div class="table-wrapper mb-15">
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
                                     <input type="checkbox" :value="1" v-model="morning_selection"/>
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
                         <label class="my-2 text-danger"
                                style="width: 100%"
                                v-if="errors && errors['openingHours']">{{
                                 errors['openingHours'][0]
                             }}</label>
                     </div>
                     <div class="flex justify-between flex-wrap">
                         <div class="form-group pr-2">
                             <p class="input-label">Vorname Mutter<sup>*</sup></p>
                             <input v-model="mother_name" class="form-input-bordered form-control" placeholder="Eva"/>
                             <label class="text-danger" v-if="errors && errors['mother_first_name']">{{ errors['mother_first_name'][0] }}</label>
                         </div>
                         <div class="form-group pr-2">
                             <p class="input-label">Nachname Mutter<sup>*</sup></p>
                             <input v-model="mother_lastname" placeholder="Maier" class="form-input-bordered form-control"/>
                             <label class="text-danger" v-if="errors && errors['mother_last_name']">{{ errors['mother_last_name'][0] }}</label>
                         </div>
                         <div class="form-group pr-2">
                             <p class="input-label">Vorname Vater<sup>*</sup></p>
                             <input v-model="father_name" class="form-input-bordered form-control" placeholder="Adam"/>
                             <label class="text-danger" v-if="errors && errors['father_first_name']">{{ errors['father_first_name'][0] }}</label>
                         </div>
                         <div class="form-group">
                             <p class="input-label">Nachname Vater<sup>*</sup></p>
                             <input v-model="father_lastname" class="form-input-bordered form-control" placeholder="Maier"/>
                             <label class="text-danger" v-if="errors && errors['father_last_name']">{{ errors['father_last_name'][0] }}</label>
                         </div>
                     </div>
                     <div class="flex justify-between flex-wrap ">
                         <div class="form-group pr-2">
                             <p class="input-label">Strasse<sup>*</sup></p>
                             <vue-google-autocomplete
                                 id="map"
                                 classname="form-control form-input-bordered"
                                 v-on:placechanged="getFromAddress"
                                 placeholder="St. Gallerstrasse"
                                 v-on:change="getNewVal"
                                 ref="address"
                             >
                             </vue-google-autocomplete>
                             <label class="text-danger" v-if="errors && errors['street']">{{ errors['street'][0] }}</label>
                         </div>
                         <div class="form-group pr-2">
                             <p class="input-label">Hausnummer<sup>*</sup></p>
                             <input v-model="street_number" placeholder="1" class="form-input-bordered form-control"/>
                             <label class="text-danger" v-if="errors && errors['street_number']">{{ errors['street_number'][0] }}</label>
                         </div>
                         <div class="form-group pr-2">
                             <p class="input-label">Postleitzahl<sup>*</sup></p>
                             <input v-model="postal_code" class="form-input-bordered form-control" placeholder="78267"/>
                             <label class="text-danger" v-if="errors && errors['postal_code']">{{ errors['postal_code'][0] }}</label>
                         </div>
                         <div class="form-group">
                             <p class="input-label">Ort<sup>*</sup></p>
                             <input v-model="place" class="form-input-bordered form-control" placeholder="Aach"/>
                             <label class="text-danger" v-if="errors && errors['place']">{{ errors['place'][0] }}</label>
                         </div>
                     </div>
                     <div class="flex flex-wrap">
                         <div class="form-group pr-2">
                             <p class="input-label">E-Mail<sup>*</sup></p>
                             <input type="email" v-model="email" class="form-input-bordered form-control" placeholder="exemple@email"/>
                             <label class="text-danger" v-if="errors && errors['email']">{{ errors['email'][0] }}</label>
                         </div>
                         <div class="form-group pr-2">
                             <p class="input-label">Telefonnummer<sup>*</sup></p>
                             <div class="flex">
                                 <select class="phone-select" v-model="phone_code">
                                     <option :value="code" v-for="code in country_code">{{ code }}</option>
                                 </select>
                                 <input @input="phoneNumberFormater(phone_number)" maxlength="12" v-mask="'## ### ## ##'" v-model="phone_number"
                                        class="form-input-bordered form-control phone-input" placeholder="00 000 00 00"/>
                             </div>
                             <label class="text-danger"
                                    v-if="errors && errors['phone_number']">{{
                                     errors['phone_number'][0]
                                 }}</label>
                         </div>
                         <div class="form-group ">
                             <p class="input-label">Mobile Telefonnummer</p>
                             <div class="flex">
                                 <select class="phone-select" v-model="cellphone_code">
                                     <option :value="code" v-for="code in country_code">{{ code }}</option>
                                 </select>
                                 <input @input="phoneNumberFormater(cellphone_number)" maxlength="12" v-mask="'## ### ## ##'" v-model="cellphone_number"
                                        class="form-input-bordered form-control phone-input" placeholder="00 000 00 00"/>
                             </div>
                             <label class="text-danger"
                                    v-if="errors && errors['mobile_phone_number']">{{
                                     errors['mobile_phone_number'][0]
                                 }}</label>
                         </div>
                     </div>
                     <textarea style="height: auto; resize:none" rows="5" placeholder="Lorem Ipsum is not simply random text." class="description-area form-control" v-model="description"/>
                     <label class="text-danger"
                            v-if="errors && errors['note']">{{
                             errors['note'][0]
                         }}</label>
                     <div class="flex justify-end">
                         <button @click="registerChild" class="btn btn-primary">Speichern</button>
                     </div>
                 </div>
             </div>
             <AnmeldungenTable v-if="anmeldungens.data && anmeldungens.data.length > 0" :anmeldungens="anmeldungens.data"/>
             <div class="row">
                 <div class="col-md-12">
                     <pagination
                         :collection="anmeldungens"
                         @loadResource="getAnmeldungens"
                     ></pagination>
                 </div>
             </div>
         </div>
         <div style="width:31%;max-width: 355px;">
             <play-group-planning class="mb-30"/>
             <latest-notifications class="mb-30"/>
         </div>
     </div>
 </div> -->
</template>

<script>
import { codes } from "../../../contryCodes";
import { mask } from 'vue-the-mask'
import AnmeldungenTable from "./AnmeldungenTable";
import axios from '../../axios'
import VueGoogleAutocomplete from 'vue-google-autocomplete'
import PlayGroupPlanning from "../../shared/PlayGroupPlanning";
import LatestNotifications from "../../shared/LatestNotifications";
import Pagination from '../../shared/Pagination'
import phoneNumberFormater from "../../mixins/phoneNumberFormaterMixin";
import getLanguages from "../../mixins/getLanguagesMixin";
import getNationalities from "../../mixins/getNationalitiesMixin";
import flushMessagesMixin from "../../mixins/flushMessagesMixin";
import DateInput from "../../shared/DateInput";
import throttle from "lodash/throttle";
import { conformsTo } from "lodash";
export default {
    name: "Anmeldungen",
    directives: { mask },
    mixins: [phoneNumberFormater, getLanguages, getNationalities, flushMessagesMixin],
    components: {
        AnmeldungenTable,
        VueGoogleAutocomplete,
        PlayGroupPlanning,
        LatestNotifications,
        Pagination,
        DateInput
    },
    data() {
        return {
            role: localStorage.getItem('role'),
            selected_playgroup: 0,
            anmeldungens: [],
            child_registration_form: false,
            phone_code: '+41',
            cellphone_code: '+41',
            child_name: '',
            child_lastname: '',
            gender: 1,
            membership: null,
            child_birthday: null,
            other_language: '',
            semester: null,
            mother_name: '',
            mother_lastname: '',
            father_name: '',
            father_lastname: '',
            playgroup_location: null,
            spielgruppens: [],
            street_address: '',
            street_number: '',
            postal_code: '',
            place: '',
            place_id: '',
            phone_number: '',
            email: '',
            cellphone_number: '',
            country_code: [],
            morning_selection: [],
            evening_selection: [],
            morningOpeningHours: {},
            afternoonOpeningHours: {},
            description: '',
            errors: [],
            getLastRegisterdChildrenId: 0,
        }
    },
    created() {
        this.getCountryCodes()
        this.getLanguages()
        this.getNationalities(this.nationalities)
        this.getSpielgruppens()
        this.getAnmeldungens()
        this.getLastRegisterdChildren()
    },
    methods: {
        getCountryCodes() {
            codes.map(item => {
                this.country_code.push(item.dial_code)
            })
        },
        getNewVal(newLocationAddres) {
            this.$refs.address.$data.autocompleteText = this.street_address
        },
        getFromAddress(from_address, placeResultData, id) {
            this.street_address = from_address.route;
            this.street_number = from_address.street_number;
            this.postal_code = from_address.postal_code;
            this.place = from_address.locality;
            this.place_id = placeResultData.place_id
        },

        getSpielgruppens() {
            axios.get('/api/spielgruppens/1').then(res => {
                this.spielgruppens = res.data
            })
        },
        getLastRegisterdChildren() {
            axios.get('/api/last-inserted-children').then(res => {
                this.getLastRegisterdChildrenId = parseInt(res.data + 1)
            })
        },
        registerChild() {
            let hoursData = {}
            if (this.morningOpeningHours.length && this.afternoonOpeningHours.length) {
                hoursData = {
                    openingHours: JSON.stringify(this.morningOpeningHours.concat(this.afternoonOpeningHours))
                }
            } else if (this.morningOpeningHours.length) {
                hoursData = {
                    openingHours: JSON.stringify(this.morningOpeningHours)
                }
            }
            else if (this.afternoonOpeningHours.length) {
                hoursData = {
                    openingHours: JSON.stringify(this.afternoonOpeningHours)
                }
            }
            else {
                hoursData.openingHours = ''
            }
            const intPhoneNumber = this.phone_number.replace(/ /g, '')
            const intMobileNumber = this.cellphone_number.replace(/ /g, '')
            let data = {
                child_first_name: this.child_name,
                child_last_name: this.child_lastname,
                gender_id: this.gender,
                child_birthday: this.child_birthday,
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
                place: this.place,
                phone_number: parseInt(intPhoneNumber),
                mobile_phone_number: parseInt(intMobileNumber),
                place_id: this.place_id,
                semester_id: this.semester,
                membership_id: this.membership,
                note: this.description,
                nationality_id: this.selected_nationality,
                other_mother_tongue: this.other_language,
                openingHours: hoursData.openingHours,
                session_id: 1
            }
            if (this.playgroup_location != 'keine') {
                data.spielgruppen_id = this.playgroup_location
            }

            axios.post('/api/anmeldungen', data).then(res => {
                this.successMessage(res.data)
                this.getAnmeldungens()
                this.clearFields()
                this.child_registration_form = false;
                this.errors = []
                this.getLastRegisterdChildren()
            }).catch(err => {
                this.errorMessage(err.response.data.message)
                const backend_errors = err.response.data.errors
                this.errors = Object.assign({}, backend_errors)
            })
        },

        getAnmeldungens(page = 1) {
            axios.get('/api/anmeldungens?page=' + page + '&spielgruppen_id=' + this.selected_playgroup).then(res => {
                this.anmeldungens = res.data
            }).catch(err => {
                console.log(err)
            })
        },

        clearFields() {
            this.child_name = '';
            this.child_lastname = '';
            this.gender_id = 1;
            this.child_birthday = null;
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
            this.semester = null;
            this.membership = null;
            this.other_language = ''
            this.selected_language_id = null
            this.description = '';
            this.selected_nationality = null
            this.morning_selection = [];
            this.evening_selection = [];
            this.morningOpeningHours = [];
            this.afternoonOpeningHours = [];
            this.$refs.address.$data.autocompleteText = '';
        }
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
        selected_playgroup: {
            handler: throttle(function (newVal, oldVal) {
                this.getAnmeldungens(1)
            }, 1000),
        },
    }

}
</script>

<style scoped lang="scss">
.anmeldungen {
    margin-top: 20px;
    margin-bottom: 150px;
    height: 100vh;
    overflow-y: auto;
}

.form-input-bordered {
    width: 162px;
    background-color: #f8fbfa;
}

.child-registration-form {
    padding-block: 25px;
}

.btn-danger {
    background: #E62D4F;
    font-weight: 500;
    border-radius: 5px;
    padding-inline: 20px;
    padding-block: 8px;
}

.btn-primary {
    background: #AEC90B;
    font-weight: 500;
    border-radius: 5px;
    min-height: 0;
    height: 37px;
    padding-inline: 15px;
    margin-top: 17px;
}

.right-info-card {
    border-radius: 12px;
}

.form-card-wrapper {
    padding: 30px !important;
}


.form-title {
    font-size: 22px;
    font-weight: 700;
    font-family: 'Lato', sans-serif;
    color: #42474A;
}

.input-label,
.input-title {
    font-size: 12px;
    font-weight: 500;
    color: #42474A;
    margin-bottom: 3px;
}


.weekday-table-head {
    width: 100%;
    border-radius: 8px;
    border: 1px solid #727574;
    padding: 10px
}

.form-group {
    margin-bottom: 25px;

    label {
        font-weight: 500;
        font-size: 12px;
        line-height: 14px;
        color: #42474A;

        &.text-danger {
            color: #E62D4F;
            display: block;
            width: 162px;
        }
    }
}

.text-danger {
    width: 162px;
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
    margin-top: 10px;
}

.weekday-table {
    width: 100%;
    border-spacing: 0;
    border-collapse: collapse;
    border-style: hidden;
}

.phone-input {
    border-top-left-radius: unset !important;
    border-bottom-left-radius: unset !important;
}

.phone-select {
    background: #E3E9EB;
    border-top-left-radius: 5px;
    border-bottom-left-radius: 5px;
    border: 1px solid #727574;
}

.description-area {
    width: 100%;
    border-radius: 5px;
    background-color: #F8FBFA;
    border: 1px solid #727574;
    margin-top: 35px;
}

.first-selection {
    border-radius: 5px;
    border: 1px solid #C2C5C6;
    padding-inline: 10px;
    width: 231px;
}
</style>
