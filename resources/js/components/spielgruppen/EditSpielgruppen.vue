<template>
    <div class="benutzer pt-30 mb-30">
        <div class="card-panel-home supervisor-card-wrapper bg-white">
            <div class="row flex gap-2 ">
                <file-upload-field :error="(errors && errors['image']) ? errors['image'][0]:''" :image="spielgruppen.image" @handleImageUpload="previewImage"/>
                <div class="user-form-registration ml-3">
                    <div class="flex justify-between">
                        <div>
                            <p>Name<sup>*</sup></p>
                            <input :disabled="role !=='admin'" v-model="spielgruppen.name" class="form-input-bordered form-control"/>
                            <label v-if="errors && errors['name']" class="text-danger">{{ errors['name'][0] }}</label>
                        </div>
                        <div>
                            <p>Status</p>
                            <select-input v-model="spielgruppen.status_id" :disabled="role !=='admin'" >
                                <option v-for="status in statuses" :key="status.status_id" :value="status.status_id">
                                    {{ status.status_name }}
                                </option>
                            </select-input>
                        </div>
                    </div>
                    <div class="row flex mt-4">
                        <div class="col-4 pr-2">
                            <p>Strasse<sup>*</sup></p>
                            <vue-google-autocomplete
                                :disabled="role !=='admin'"
                                id="map"
                                classname="form-control form-input-bordered"
                                v-on:placechanged="getFromAddress"
                                placeholder="St. Gallerstrasse"
                                v-on:change="getNewVal"
                                ref="address"
                                v-model="spielgruppen.street_name"
                            >
                            </vue-google-autocomplete>
                            <label class="text-danger"
                                   v-if="errors && errors['street_name']">{{ errors['street_name'][0] }}</label>
                        </div>
                        <div class="col-2 pr-2">
                            <p>Hausnummer<sup>*</sup></p>
                            <input :disabled="role !=='admin'" v-model="spielgruppen.street_number" class="form-control form-input-bordered"/>
                            <label class="text-danger"
                                   v-if="errors && errors['street_number']">{{ errors['street_number'][0] }}</label>
                        </div>
                        <div class="col-2 pr-2">
                            <p>PLZ<sup>*</sup></p>
                            <input :disabled="role !=='admin'" v-model="spielgruppen.postal_code" class="form-control form-input-bordered"/>
                            <label class="text-danger"
                                   v-if="errors && errors['postal_code']">{{ errors['postal_code'][0] }}</label>
                        </div>
                        <div class="col-4">
                            <p>Ort<sup>*</sup></p>
                            <input :disabled="role !=='admin'" v-model="spielgruppen.location" class="form-control form-input-bordered"/>
                            <label class="text-danger"
                                   v-if="errors && errors['location']">{{ errors['location'][0] }}</label>
                        </div>
                    </div>
                    <div class="row flex gap-2 mt-4">
                        <div class="form-group col-2">
                            <p>Mindestanzahl<sup>*</sup></p>
                            <input :disabled="role !=='admin'" v-model="spielgruppen.minimum_occupancy" class="form-input-bordered form-control"/>
                            <label class="text-danger" v-if="errors && errors['minimum_occupancy']">{{ errors['minimum_occupancy'][0] }}</label>
                        </div>
                        <div class="form-group pl-2 col-2 whitespace-no-wrap">
                            <p>Maximal Belegung<sup>*</sup></p>
                            <input :disabled="role !=='admin'" v-model="spielgruppen.maximum_occupancy" class="form-input-bordered form-control"/>
                            <label class="text-danger" v-if="errors && errors['maximum_occupancy']">{{ errors['maximum_occupancy'][0] }}</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row flex-grow">
                <div class="table-wrapper">
                    <table class="weekday-table">
                        <thead class="weekday-table-head">
                        <tr>
                            <th style="font-size: 14px;font-weight: 400" align="left" colspan="2" scope="col">Öffnungszeiten<sup>*</sup></th>
                            <th align="center" scope="col" v-for="day in days">{{ day }}</th>
                        </tr>
                        </thead>
                        <tbody class="weekday-table-body">
                        <tr>
                            <td class="row-title" colspan="2" scope="row">8:45 - 11:15 Uhr</td>
                            <td align="center" v-for="(day, index) in days">
                                <input :disabled="role !=='admin'" type="checkbox" :value="index + 1" v-model="morning_selection"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="row-role-title" colspan="2" scope="row">Spielgruppenleiter/in</td>
                            <td align="center" v-for="(day, index) in days">
                                <select :disabled="!morning_selection.includes(parseInt(index+1)) || role !=='admin'" v-model="morning_leads[index]" class="form-input-bordered form-control">
                                    <option :value="lead.id" v-for="lead in leads" :key="lead.id">{{lead.first_name}} {{lead.last_name}}</option>
                                </select>
                                <label class="text-danger" v-if="errors && morning_selection && morning_selection.includes(index + 1) && errors['openingHours.'+parseInt(index+1)+'-1.lead_id']">{{ errors['openingHours.'+parseInt(index+1)+'-1.lead_id'][0] }}</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="row-role-title" colspan="2" scope="row">Spielgruppen Assistent/in</td>
                            <td align="center" v-for="(day, index) in days">
                                <select :disabled="!morning_selection.includes(index + 1) || role !=='admin'" v-model="morning_assistants[index]" class="form-input-bordered form-control">
                                    <option :value="assistant.id" v-for="assistant in assistants" :key="assistant.id">{{assistant.first_name}} {{assistant.last_name}}</option>
                                </select>
                                <label class="text-danger" v-if="errors && morning_selection && morning_selection.includes(index + 1) && errors['openingHours.'+parseInt(index+1)+'-1.assistant_id']">{{ errors['openingHours.'+parseInt(index+1)+'-1.assistant_id'][0] }}</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="row-title" colspan="2" scope="row">14:00 - 16:30 Uhr</td>
                            <td align="center" v-for="(day, index) in days">
                                <input :disabled="role !=='admin'" type="checkbox" :value="index + 1" v-model="evening_selection"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="row-role-title" colspan="2" scope="row">Spielgruppenleiter/in</td>
                            <td align="center" v-for="(day, index) in days">
                                <select :disabled="!evening_selection.includes(index + 1) || role !=='admin'" v-model="evening_leads[index]" class="form-input-bordered form-control">
                                    <option :value="lead.id" v-for="lead in leads" :key="lead.id">{{lead.first_name}} {{lead.last_name}}</option>
                                </select>
                                <label class="text-danger" v-if="errors && evening_selection && evening_selection.includes(index + 1) && errors['openingHours.'+parseInt(index+1)+'-2.lead_id']">{{ errors['openingHours.'+parseInt(index+1)+'-2.lead_id'][0] }}</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="row-role-title" colspan="2" scope="row">Spielgruppen Assistent/in</td>
                            <td align="center" v-for="(day, index) in days">
                                <select :disabled="!evening_selection.includes(index + 1) || role !=='admin'" v-model="evening_assistants[index]" class="form-input-bordered form-control">
                                    <option :value="assistant.id" v-for="assistant in assistants" :key="assistant.id">{{assistant.first_name}} {{assistant.last_name}}</option>
                                </select>
                                <label class="text-danger" v-if="errors && evening_selection && evening_selection.includes(index + 1) && errors['openingHours.'+parseInt(index+1)+'-2.assistant_id']">{{ errors['openingHours.'+parseInt(index+1)+'-2.assistant_id'][0] }}</label>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <label class="text-danger" v-if="errors && errors['openingHours']">{{ errors['openingHours'][0] }}</label>

                </div>
            </div>
            <div v-if="role==='admin'" class="row flex justify-end items-center">
                <button @click="updateSpielgruppen" class="btn-primary btn-fixed">Speichern</button>
            </div>
        </div>
    </div>
</template>

<script>
import {codes} from "../../../contryCodes";
import axios from "../../axios";
import VueGoogleAutocomplete from 'vue-google-autocomplete'
import FileUploadField from "../../shared/FileUploadField";
import SelectInput from "../../shared/SelectInput";
import flushMessagesMixin from "../../mixins/flushMessagesMixin";
export default {
    name: "EditSpielgruppen",
    mixins: [flushMessagesMixin],
    components: {
        VueGoogleAutocomplete,
        FileUploadField,
        SelectInput
    },
    data() {
    return {
        days: [
            'Montag',
            'Dienstag',
            'Mittwoch',
            'Donnerstag',
            'Freitag',
        ],
        users:[],
        spielgruppen: [],
        statuses: [],
        country_code: [],
        code: "+41",
        lead_id: null,
        assistant_id: null,
        morning_selection: [],
        morning_leads: {},
        morning_assistants: {},
        evening_selection: [],
        evening_leads: {},
        evening_assistants: {},
        morningOpeningHours: [],
        afternoonOpeningHours: [],
        errors: [],
        role: localStorage.getItem('role')
    }
    },
    watch: {
        morning_selection() {
            this.morningOpeningHours = [];
            this.morning_selection.forEach(item => {
                this.morningOpeningHours.push({
                    day: item,
                    period: 1,
                })
            })
        },
        evening_selection() {
            this.afternoonOpeningHours = [];
            this.evening_selection.forEach(item => {
                this.afternoonOpeningHours.push({
                    day: item,
                    period: 2,
                })
            })
        },
    },

    created() {
        this.getUsers()
        this.getStatuses()
        this.getSpielgruppen()
        this.getCountryCodes()
    },
    methods: {
        previewImage(image) {
            this.spielgruppen.image = image
        },
        openImageSelection() {
            if(this.role === 'admin') {
                document.getElementById('inpFile').click()
            }
        },
        getCountryCodes() {
            codes.map(item => {
                this.country_code.push(item.dial_code)
            })
        },

        getUsers() {
            axios.get('/api/filter/benutzer').then(res=>{
                this.users = res.data.data
            }).catch(err=>{
                console.log(err)
            })
        },


        getStatuses() {
            axios.get('/api/status').then(res => {
                this.statuses = res.data.data.map(item => {
                    return {
                        status_id: item.id,
                        status_name: item.name
                    }
                })
            })
        },
        getNewVal(newLocationAddres){
            this.$refs.address.$data.autocompleteText = this.spielgruppen.street_name
        },
        getFromAddress(from_address, placeResultData, id) {
            this.spielgruppen.street_name = from_address.route;
            this.spielgruppen.street_number = from_address.street_number;
            this.spielgruppen.postal_code= from_address.postal_code;
            this.spielgruppen.location= from_address.locality;
            this.spielgruppen.place_id = placeResultData.place_id
        },

        async getSpielgruppen() {
            let userid = parseInt(this.$route.params.id)
            await axios.get(`/api/spielgruppen/${userid}`).then(res => {
                this.spielgruppen = res.data.data
            })

            this.spielgruppen.opening_hours.map((item)=>{
                if(item.hour_id === 1) {
                    this.morning_selection.push(item.day_id)
                    this.morning_leads[item.day_id - 1] = item.lead_id
                    this.morning_assistants[item.day_id - 1] = item.assistant_id
                    return
                }
                this.evening_selection.push(item.day_id)
                this.evening_leads[item.day_id - 1] = item.lead_id
                this.evening_assistants[item.day_id - 1] = item.assistant_id
            })
        },

        updateSpielgruppen() {
            let data = {
                openingHours: ''
            }
            if (this.morningOpeningHours.length || this.afternoonOpeningHours.length) {
                let newMorningOpeningHours = this.morningOpeningHours;
                let newAfternoonOpeningHours = this.afternoonOpeningHours

                if (this.morningOpeningHours.length) {
                    newMorningOpeningHours = newMorningOpeningHours.map(record => {
                        record.lead_id = this.morning_leads[record.day - 1]
                        record.assistant_id = this.morning_assistants[record.day - 1]
                        return record;
                    })
                }
                if (this.afternoonOpeningHours.length) {
                    newAfternoonOpeningHours = newAfternoonOpeningHours.map(record => {
                        record.lead_id = this.evening_leads[record.day - 1]
                        record.assistant_id = this.evening_assistants[record.day - 1]
                        return record;
                    })
                }
                data.openingHours = JSON.stringify(newMorningOpeningHours.concat(newAfternoonOpeningHours))
            }

            const formData = new FormData()
            formData.append('name', this.spielgruppen.name)
            if (typeof this.spielgruppen.image.name !== "undefined"){
                formData.append('image', this.spielgruppen.image)
            }
            formData.append('status_id', this.spielgruppen.status_id)
            formData.append('street_name', this.spielgruppen.street_name)
            formData.append('street_number', this.spielgruppen.street_number)
            formData.append('postal_code', this.spielgruppen.postal_code)
            formData.append('location', this.spielgruppen.location)
            formData.append('place_id', this.spielgruppen.place_id)
            formData.append('minimum_occupancy', this.spielgruppen.minimum_occupancy)
            formData.append('maximum_occupancy', this.spielgruppen.maximum_occupancy)
            formData.append('status_id', this.spielgruppen.status_id)
            formData.append('openingHours', data.openingHours)

            axios.post(`/api/spielgruppen/update/${this.$route.params.id}`, formData).then(res => {
                this.successMessage(res.data)
                this.getSpielgruppen()
                this.$router.push({
                    name:'spielgruppen'
                })
            }).catch(err => {
                this.errorMessage(err.response.data.message)
                const backend_errors = err.response.data.errors
                this.errors = Object.assign({}, backend_errors)
                if (err.response.status !== 422) {
                    this.errorMessage()
                }
            })
        },
    },
    computed: {
        backgroundColor() {
            if (this.spielgruppen.status_id == '1') {
                return '#0063A7'
            } else if (this.spielgruppen.status_id == '2') {
                return '#E62D4F'
            } else if(this.spielgruppen.status_id == '3') {
                return '#F2A402'
            } else {
                return '#AEC90B'
            }
        },
        leads() {
            return this.users.filter(item=>{
                return item.role_id === 2
            })
        },

        assistants() {
            return this.users.filter(item=>{
                return item.role_id === 3
            })
        }
    }
}
</script>

<style scoped lang="scss">
@import "../supervisor/supervisor";
.btn-fixed {
    width: 304px;
    height: 40px;
    border-radius: 5px;
    font-weight: 500;
    font-size: 15px;
    line-height: 24px;
    margin-top: 20px;
    padding: 8px 15px;
}
.weekday-table-head {
    width: 100%;
    border-radius: 8px;
    border: 1px solid #727574;
    padding: 10px
}

th {
    padding: 5px;
    background: #E3E9EB;
    border: 1px solid #727574;
}

td {
    border: 1px solid #727574;
    padding: 7px;
    background: #F8FBFA;
}

.table-wrapper {
    border: 1px solid #727574;
    border-radius: 5px;
    overflow: hidden;
    margin-top:10px;
}

.weekday-table {
    width: 100%;
    border-spacing: 0;
    border-collapse: collapse;
    border-style: hidden;
}
</style>
