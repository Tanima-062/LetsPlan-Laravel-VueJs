<template>
    <div class="benutzer pt-30 mb-30">
        <statistic-card :color="'#F2A402'"/>
        <div class="flex justify-end">
            <div @click="openForm = !openForm" class="button-group">
                <span class="plus"><svg width="10" height="10" viewBox="0 0 10 10" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.75 0V9.5" stroke="#F8FBFA" stroke-width="1.5"/>
                    <path d="M0 4.75L9.5 4.75" stroke="#F8FBFA" stroke-width="1.5"/>
                    </svg>
                    </span>
                <div class="vertical-line"></div>
                <span class="btn-title-group">Benutzer</span>
            </div>
        </div>
        <div v-if="openForm" class="card-panel-home supervisor-card-wrapper bg-white">
            <div class="row flex gap-2">
                <file-upload-field :error="(errors && errors['image']) ? errors['image'][0]:''" :image="image" @handleImageUpload="previewImage"/>
                <div class="user-form-registration">
                    <select-input
                        v-model="status"
                         >
                        <option v-for="status in statuses" :key="status.status_id" :value="status.status_id">{{status.status_name}}</option>
                    </select-input>

                    <div class="form-group role-select mb-15">
                        <p>Benutzertyp<sup>*</sup></p>
                        <select v-model="role" class="form-control form-input-bordered form-width-fixed">
                            <option value="2">Spielgruppenleiter/in</option>
                            <option value="3">Spielgruppen Assistent/in</option>
                        </select>
                        <span v-if="errors && errors['role_id']" class="text-danger">{{ errors['role_id'][0] }}</span>
                    </div>
                    <div class="row flex mb-15">
                        <div class="col-4 pr-16">
                            <p>Strasse<sup>*</sup></p>
                            <vue-google-autocomplete
                                id="map"
                                classname="form-control form-input-bordered"
                                v-on:placechanged="getFromAddress"
                                v-on:change="getNewVal"
                                ref="address"
                                placeholder="St. Gallerstrasse"
                            >
                            </vue-google-autocomplete>
                            <span class="text-danger"
                                  v-if="errors && errors['street_name']">{{ errors['street_name'][0] }}</span>

                        </div>
                        <div class="col-2 pr-16">
                            <p>Hausnummer<sup>*</sup></p>
                            <input v-model="house_number" class="form-control form-input-bordered" placeholder="1"/>
                            <span class="text-danger"
                                  v-if="errors && errors['house_number']">{{ errors['house_number'][0] }}</span>
                        </div>
                        <div class="col-2 pr-16">
                            <p>PLZ<sup>*</sup></p>
                            <input v-model="plz" class="form-control form-input-bordered" placeholder="7346"/>
                            <span class="text-danger"
                                  v-if="errors && errors['postal_code']">{{ errors['postal_code'][0] }}</span>
                        </div>
                        <div class="col-4">
                            <p>Ort<sup>*</sup></p>
                            <input v-model="ort" class="form-control form-input-bordered form-width-fixed"
                                   placeholder="Aach"/>
                            <span class="text-danger"
                                  v-if="errors && errors['location']">{{ errors['location'][0] }}</span>
                        </div>
                    </div>
                    <div class="row flex mb-15">
                        <div class="quarter pr-16">
                            <p>E-Mail<sup>*</sup></p>
                            <input type="email" class="form-input-bordered form-control form-width-fixed"
                                   v-model="email" placeholder="exemple@email.com">
                            <span class="text-danger" v-if="errors && errors['email']">{{ errors['email'][0] }}</span>
                        </div>
                        <div class="quarter">
                            <p>Telefonnummer<sup>*</sup></p>
                            <div class="flex">
                                <select class="phone-select" v-model="code">
                                    <option :value="code" v-for="code in country_code">{{ code }}</option>
                                </select>
                                <input maxlength="12" @input="phoneNumberFormater(phone)" v-model="phone" class="form-input-bordered form-control phone-input"
                                       placeholder="00 000 00 00" v-mask="'## ### ## ##'"/>
                            </div>
                            <span class="text-danger"
                                  v-if="errors && errors['phone_number']">{{ errors['phone_number'][0] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="horizontal-line"/>
            <div class="row flex mt-1">
                <div class="text-content">
                    <h3 class="text-title">Einladung zur Registrierung</h3>
                    <p class="text-description">Einladungen erneut senden, wenn sich der Benutzer noch nicht registriert
                        hat.</p>
                </div>
                <div class="user-personal-info">
                    <div class="flex">
                        <div class="pr-16">
                            <p>Vorname<sup>*</sup></p>
                            <input v-model="name" class="form-control form-input-bordered" placeholder="Lukas"/>
                            <span class="text-danger"
                                  v-if="errors && errors['first_name']">{{ errors['first_name'][0] }}</span>
                        </div>
                        <div class="">
                            <p>Nachname<sup>*</sup></p>
                            <input v-model="lastname" class="form-control form-input-bordered" placeholder="Maier"/>
                            <span class="text-danger"
                                  v-if="errors && errors['last_name']">{{ errors['last_name'][0] }}</span>
                        </div>
                    </div>
                    <button @click="createUser()" class="btn btn-primary w-full flex items-center justify-center">
                        <img class="mr-3" src="/images/enveloper.svg"/>
                        <span>Einladung verschicken</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="w-full supervisor-section">
            <div v-for="item in users.data">
                <supervisor :key="item.id" @delete="deleteUser(item.id)" :supervisor="item" :statuses="statuses" />
            </div>
        </div>
        <div class="row">
           <div class="col-md-12">
               <pagination
                   :collection="users"
                   @loadResource="getUsers"
               ></pagination>
           </div>
        </div>
    </div>
</template>

<script>
import {codes} from '../../../contryCodes';
import axios from '../../axios';
import Pagination from '../../shared/Pagination'
import FileUploadField from "../../shared/FileUploadField";
import SelectInput from "../../shared/SelectInput";
import VueGoogleAutocomplete from 'vue-google-autocomplete'
import phoneNumberFormater from "../../mixins/phoneNumberFormaterMixin";
import {mask} from "vue-the-mask";
import flushMessagesMixin from "../../mixins/flushMessagesMixin";
export default {
    name: "SupervisorRegistration",
    mixins: [phoneNumberFormater,flushMessagesMixin],
    directives: {
        mask
    },
    components:{
        Pagination,
        FileUploadField,
        SelectInput,
        VueGoogleAutocomplete
    },
    data() {
        return {
            openForm: false,
            image: '',
            role: '',
            address: '',
            house_number: '',
            plz: '',
            ort: '',
            email: '',
            phone: '',
            code: "+41",
            country_code: [],
            name: '',
            lastname: '',
            status: 1,
            errors: [],
            users: [],
            benutzer: null,
            statuses: []
        }
    },

    mounted() {
        this.getCountryCodes()
        this.getUsers()
        this.getStatuses()
        this.getBenutzerType()

    },

    methods: {
        getNewVal(newLocationAddres){
            this.$refs.address.$data.autocompleteText = this.address
        },
        getFromAddress(from_address, placeResultData, id) {
            this.address = from_address.route;
            this.house_number = from_address.street_number;
            this.plz= from_address.postal_code;
            this.ort= from_address.locality;
        },
        previewImage(image) {
            this.image = image
        },

        getCountryCodes() {
            codes.map(item => {
                this.country_code.push(item.dial_code)
            })
        },
        getUsers(page = 1) {
            axios.get('/api/benutzer?page=' + page).then(res => {
                this.users = res.data
            }).catch(err => {
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

        getBenutzerType() {
            axios.get('/api/benutzer_types').then(res => {
                this.benutzer = res.data
            })
        },

        createUser() {
            const formData = new FormData()
            formData.append('role_id', this.role)
            if(this.image != '' && this.image != null){
                formData.append('image', this.image)
            }
            formData.append('status_id', this.status)
            formData.append('status_name', '')
            formData.append('street_name', this.address)
            formData.append('house_number', this.house_number)
            formData.append('postal_code', this.plz)
            formData.append('location', this.ort)
            formData.append('email', this.email)
            formData.append('phone_number', this.phone)
            formData.append('first_name', this.name)
            formData.append('last_name', this.lastname)
            formData.append('prefix', this.code)
            axios.post('/api/benutzer', formData).then(res => {
                this.successMessage(res.data)
                this.errors=[]
                this.clearFields()
                this.openForm = false
                this.getUsers()
            }).catch(err => {

                const previewContainer = document.getElementById('image-preview')
                const previewImage = previewContainer.querySelector('.image-preview__image')
                const previewDefaultText = previewContainer.querySelector('.image-preview__defailt-text')

                previewDefaultText.style.display = "block";
                previewImage.style.display = "none";
                previewImage.setAttribute("src", "")

                if (err.response != undefined){
                    this.errorMessage(err.response.data.message)
                    const backend_errors = err.response.data.errors
                    this.errors = Object.assign({}, backend_errors)
                }

            })
        },

        clearFields() {
            this.image = null
            this.role = null
            this.status = 1
            this.address = ''
            this.house_number = ''
            this.plz = ''
            this.ort = ''
            this.email = ''
            this.phone = ''
            this.name = ''
            this.lastname = ''
            this.code = '+41'
            this.$refs.address.$data.autocompleteText='';
            const previewContainer = document.getElementById('image-preview')
            const previewImage = previewContainer.querySelector('.image-preview__image')
            const previewDefaultText = previewContainer.querySelector('.image-preview__defailt-text')

            previewDefaultText.style.display = "block";
            previewImage.style.display = "none";
            previewImage.setAttribute("src", "")
        },

        deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                axios.delete(`/api/benutzer/${id}`).then(res => {
                    this.getUsers()
                })
            }
        },
    }
}
</script>

<style scoped lang="scss">
@import "supervisor";
</style>
