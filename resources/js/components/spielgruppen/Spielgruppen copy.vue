<template>
    <div class="benutzer mt-5 mb-30">
        <div v-if="role === 'admin' && openForm == false" class="flex justify-end">
            <div @click="openForm = !openForm" class="button-group">
                <span class="plus">+</span>
                <div class="vertical-line"></div>
                <span class="btn-title-group">Standort hinzufügen</span>
            </div>
        </div>
        <div v-if="openForm" class="card-panel-home supervisor-card-wrapper rounded-none" style="background-color:#E9F7FE; border:none; border-radius: 0px;">
            <div class="flex w-full justify-between">
                <div class="flex w-1/5">
                    <file-upload-field :error="(errors && errors['image']) ? errors['image'][0]:''" :image="image" @handleImageUpload="previewImage"/>
                    <span class="title">Bild</span>
                </div>
                <div class="w-1/5">
                    <p class="title">Spielgruppen-ID*</p>
                    <p>SPG00001</p>
                </div>
                <div class="w-1/4 mr-2">
                    <p class="title">Name*</p>
                    <input v-model="name" class="inputField" placeholder="Remisli"/>
                    <label v-if="errors && errors['name']" class="text-danger">{{ errors['name'][0] }}</label>
                </div>
            </div>
                <div class="user-form-registration">
                    <div class="flex mt-4 justify-between">
                        <div class="w-1/4">
                            <p class="title" style="color:#16A9B7; font-size: medium; font-weight: 600;">Strasse*</p>
                            <vue-google-autocomplete
                                id="map"
                                classname="inputField"
                                v-on:placechanged="getFromAddress"
                                placeholder="St. Gallerstrasse"
                                v-on:change="getNewVal"
                                ref="address"
                            >
                            </vue-google-autocomplete>
                            <label class="text-danger"
                                   v-if="errors && errors['street_name']">{{ errors['street_name'][0] }}</label>
                        </div>
                        <div class="w-1/4">
                            <p class="title" style="color:#16A9B7; font-size: medium; font-weight: 600;">Hausnummer</p>
                            <input v-model="street_number" class="inputField" placeholder="1"/>
                            <label class="text-danger"
                                   v-if="errors && errors['street_number']">{{ errors['street_number'][0] }}</label>
                        </div>
                        <div class="w-1/4 mr-2">
                            <p class="title" style="color:#16A9B7; font-size: medium; font-weight: 600;">Postleitzahl*</p>
                            <input v-model="postal_code" class="inputField" placeholder="7346"/>
                            <label class="text-danger"
                                   v-if="errors && errors['postal_code']">{{ errors['postal_code'][0] }}</label>
                        </div>
                    </div>
                    <div class="flex mt-4 justify-between">
                        <div class="w-1/4">
                            <p class="title" style="color:#16A9B7; font-size: medium; font-weight: 600;">Ort*</p>
                            <input v-model="place" class="inputField" placeholder="Aach"/>
                            <label class="text-danger" v-if="errors && errors['location']">{{ errors['location'][0] }}</label>
                        </div>
                        <div class="w-1/4">
                            <p style="color:#16A9B7; font-size: medium; font-weight: 600;">Mindestanzahl*</p>
                            <input v-model="minimum_number" class="inputField" placeholder="5"/>
                            <label class="text-danger" v-if="errors && errors['minimum_occupancy']">{{ errors['minimum_occupancy'][0] }}</label>
                        </div>
                        <div class="w-1/4 mr-2">
                            <p style="color:#16A9B7; font-size: medium; font-weight: 600;">Maximal Belegung<sup>*</sup></p>
                            <input v-model="maximum_occupancy" class="inputField"
                                   placeholder="10"/>
                            <label class="text-danger" v-if="errors && errors['maximum_occupancy']">{{ errors['maximum_occupancy'][0] }}</label>
                        </div>
                    </div>
                </div>
            <div class="row flex-grow">
                <div class="table-wrapper">
                    Öffnungszeiten
                    <div class="weekday-table mt-4">
                        <div class="bg-white flex h-8">
                            <div style="font-size: 14px;font-weight: 400;" class="w-1/3"></div>
                            <div v-for="day in days" class="w-1/3 text-left title">{{ day }}</div>
                        </div>

                        <div class="flex mt-2 bg-white">
                            <div class="w-1/3"><Timepicker></Timepicker></div>
                            <div class="w-1/3" v-for="(day, index) in days">
                                <input type="checkbox" :value="index + 1" v-model="morning_selection" class="checkbox"/>
                            </div>
                        </div>
                        <div class="flex pt-2 pb-2 bg-white">
                            <div class="row-role-title w-1/3">Spielgruppenleiter/in</div>
                            <div class="w-1/3" v-for="(day, index) in days">
                                <select :disabled="!morning_selection.includes(index + 1)" v-model="morning_leads[index]" style="background-color:#16A9B7; width: 150px;">
                                    <option>select</option>
                                    <option :value="lead.id" v-for="lead in leads" :key="lead.id">{{lead.first_name}} {{lead.last_name}}</option>
                                </select>
                                <label class="text-danger" v-if="errors && morning_selection && morning_selection.includes(index + 1) && errors['openingHours.'+parseInt(index+1)+'-1.lead_id']">{{ errors['openingHours.'+parseInt(index+1)+'-1.lead_id'][0] }}</label>
                            </div>
                        </div>
                        <div class="flex pt-2 pb-2 bg-white">
                            <div class="row-role-title w-1/3">Spielgruppen Assistent/in</div>
                            <div class="w-1/3" v-for="(day, index) in days">
                                <select :disabled="!morning_selection.includes(index + 1)" v-model="morning_assistants[index]" style="background-color:#16A9B7; width: 150px;">
                                    <option :value="assistant.id" v-for="assistant in assistants" :key="assistant.id">{{assistant.first_name}} {{assistant.last_name}}</option>
                                </select>
                                <label class="text-danger" v-if="errors && morning_selection && morning_selection.includes(index + 1) && errors['openingHours.'+parseInt(index+1)+'-1.assistant_id']">{{ errors['openingHours.'+parseInt(index+1)+'-1.assistant_id'][0] }}</label>
                            </div>
                        </div>
                        <div class="flex mt-2 bg-white">
                            <div class="w-1/3"><Timepicker></Timepicker></div>
                            <div class="w-1/3" v-for="(day, index) in days">
                                <input type="checkbox" :value="index + 1" v-model="morning_selection" class="checkbox"/>
                            </div>
                        </div>
                        <div class="flex pt-2 pb-2 bg-white">
                            <div class="row-role-title w-1/3">Spielgruppenleiter/in</div>
                            <div class="w-1/3" v-for="(day, index) in days">
                                <select :disabled="!morning_selection.includes(index + 1)" v-model="morning_leads[index]" style="background-color:#16A9B7; width: 150px;">
                                    <option>select</option>
                                    <option :value="lead.id" v-for="lead in leads" :key="lead.id">{{lead.first_name}} {{lead.last_name}}</option>
                                </select>
                                <label class="text-danger" v-if="errors && morning_selection && morning_selection.includes(index + 1) && errors['openingHours.'+parseInt(index+1)+'-1.lead_id']">{{ errors['openingHours.'+parseInt(index+1)+'-1.lead_id'][0] }}</label>
                            </div>
                        </div>
                        <div class="flex pt-2 pb-2 bg-white">
                            <div class="row-role-title w-1/3">Spielgruppen Assistent/in</div>
                            <div class="w-1/3" v-for="(day, index) in days">
                                <select :disabled="!morning_selection.includes(index + 1)" v-model="morning_assistants[index]" style="background-color:#16A9B7; width: 150px;">
                                    <option :value="assistant.id" v-for="assistant in assistants" :key="assistant.id">{{assistant.first_name}} {{assistant.last_name}}</option>
                                </select>
                                <label class="text-danger" v-if="errors && morning_selection && morning_selection.includes(index + 1) && errors['openingHours.'+parseInt(index+1)+'-1.assistant_id']">{{ errors['openingHours.'+parseInt(index+1)+'-1.assistant_id'][0] }}</label>
                            </div>
                        </div>
                    </div>
                    <label class="text-danger" v-if="errors && errors['openingHours']">{{ errors['openingHours'][0] }}</label>
                </div>
            </div>
            <div class="row flex justify-start items-center">
                <button @click="createSpielgruppen" class="btn-primary ml-2" style="background-color:#E97D32; width:400px;">Speichern</button>
                <button  class="btn-primary ml-4" style="background-color:#F8D3BA; color:#2C5B7D; width:400px">Abbrechen</button>
            </div>
        </div>
        <div class="w-full supervisor-section" :class="{'disable-all':role !== 'admin'}" v-if="openForm==false">
            <div v-for="item in spielgruppen.data">
                <spielgruppen-item :key="item.id" @delete="deleteUser(item.id)" :supervisor="item" :statuses="statuses"></spielgruppen-item>
            </div>
        </div>
        <div class="row" v-if="openForm==false">
            <div class="col-md-12">
                <pagination
                    :collection="spielgruppen"
                    @loadResource="getSpielgruppen"
                ></pagination>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "../../axios";
import {codes} from "../../../contryCodes";
import VueGoogleAutocomplete from 'vue-google-autocomplete'
import Pagination from '../../shared/Pagination'
import FileUploadField from "../../shared/FileUploadField";
import SelectInput from "../../shared/SelectInput";
import flushMessagesMixin from "../../mixins/flushMessagesMixin";
import Timepicker from '../../components/spielgruppen/timepicker/Timepicker.vue'

export default {
    name: "spielgruppen",
    mixins: [flushMessagesMixin],
    components : {
        VueGoogleAutocomplete,
        Pagination,
        FileUploadField,
        SelectInput,
        Timepicker
    },
    data() {
        return {
            role : localStorage.getItem('role'),
            days: [
                'Montag',
                'Dienstag',
                'Mittwoch',
                'Donnerstag',
                'Freitag',
            ],
            errors: [],
            statuses: [],
            country_code: [],
            code: "+41",
            openForm: false,
            spielgruppen: [],
            users: [],
            lead_id: null,
            assistant_id: null,
            image: null,
            name: '',
            street_name: '',
            street_number: '',
            postal_code: '',
            place_id: '',
            place: '',
            minimum_number: '',
            maximum_occupancy: '',
            status_id: 1,
            morning_selection: [],
            morning_leads: {},
            morning_assistants: {},
            evening_selection: [],
            evening_leads: {},
            evening_assistants: {},
            morningOpeningHours: [],
            afternoonOpeningHours: [],
        }
    },
    mounted() {
        this.getStatuses()
        this.getCountryCodes()
        this.getSpielgruppen()
        this.getUsers()
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

    computed: {
        leads() {
            return this.users.filter(item=>{
                return item.role_id === 2
            })
        },

        assistants() {
            return this.users.filter(item=>{
                return item.role_id === 3
            })
        },
    },

    methods: {
        getUsers() {
           if(this.role === 'admin'){
               axios.get('/api/filter/benutzer').then(res=>{
                   this.users = res.data.data
               }).catch(err=>{
               })
           }
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

        getCountryCodes() {
            codes.map(item => {
                this.country_code.push(item.dial_code)
            })
        },

        getSpielgruppen(page = 1) {
            axios.get('/api/spielgruppens?page=' + page).then(res => {
                this.spielgruppen = res.data
            }).catch(err => {
                console.log(err)
            })
        },

        previewImage(image) {
            this.image = image
        },

        createSpielgruppen() {
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
            formData.append('image', this.image)
            formData.append('name', this.name)
            formData.append('street_name', this.street_name)
            formData.append('place_id',this.place_id)
            formData.append('street_number', this.street_number)
            formData.append('postal_code', this.postal_code)
            formData.append('location', this.place)
            formData.append('minimum_occupancy', this.minimum_number)
            formData.append('maximum_occupancy', this.maximum_occupancy)
            formData.append('status_id', this.status_id)
            formData.append('openingHours', data.openingHours)

            axios.post('/api/spielgruppen/store', formData).then(res => {
                this.successMessage(res.data)
                this.getSpielgruppen()
                this.clearFields()
                this.errors=[]
                this.openForm = false;
            }).catch(err => {
                this.errorMessage(err.response.data.message);
                const backend_errors = err.response.data.errors
                this.errors = Object.assign({}, backend_errors)
            })
        },
        getNewVal(newLocationAddres){
            this.$refs.address.$data.autocompleteText = this.street_name
        },
        getFromAddress(from_address, placeResultData, id) {
            this.street_name = from_address.route;
            this.street_number = from_address.street_number;
            this.postal_code= from_address.postal_code;
            this.place= from_address.locality;
            this.place_id = placeResultData.place_id
        },

        deleteUser(id) {
            if (confirm('Are you sure you want to delete this playgroup?')) {
                axios.delete(`/api/spielgruppen/${id}`).then(res => {
                    this.getSpielgruppen()
                })
            }
        },

        clearFields() {
            this.image = null,
            this.name = '',
            this.street_name = '',
            this.street_number = '',
            this.postal_code = '',
            this.place = '',
            this.minimum_number = '',
            this.maximum_occupancy = '',
            this.status_id = 1,
            this.morning_selection = [],
            this.evening_selection = [],
            this.morningOpeningHours = {},
            this.morning_leads = {},
            this.evening_leads = {},
            this.evening_assistants = {},
            this.morning_assistants = {},
            this.afternoonOpeningHours = {}
            this.$refs.address.$data.autocompleteText=''
            const previewContainer = document.getElementById('image-preview')
            const previewImage = previewContainer.querySelector('.image-preview__image')
            const previewDefaultText = previewContainer.querySelector('.image-preview__defailt-text')
            previewDefaultText.style.display = "block";
            previewImage.style.display = "none";
            previewImage.setAttribute("src", "")
        }
    }
}
</script>

<style scoped lang="scss">
@import "../supervisor/supervisor";

.inputField{
    height:35px;
    width:412px;
}
.title{
    color: #16A9B7;
    font-weight: 600;
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

</style>
