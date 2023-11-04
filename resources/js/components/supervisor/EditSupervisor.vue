<template>
    <div class="benutzer pt-30 mb-30">
       <div class="card-panel-home supervisor-card-wrapper bg-white">
           <div class="row flex gap-2">
               <file-upload-field :error="(errors && errors['image']) ? errors['image'][0]:''" :image="user.image" @handleImageUpload="previewImage"/>
               <div class="user-form-registration">
                   <select-input
                       v-model="user.status_id"
                      >
                       <option v-for="(status,index) in statuses" :key="index" :value="status.status_id">
                           {{ status.status_name }}
                       </option>
                   </select-input>
                   <div class="form-group role-select">
                       <p>Benutzertyp<sup>*</sup></p>
                       <select v-model="user.role_id" class="form-control form-input-bordered">
                           <option value="2">Spielgruppenleiter/in</option>
                           <option value="3">Spielgruppen Assistent/in</option>
                       </select>
                       <label v-if="errors && errors['role_id']"
                              class="text-danger">{{ errors['role_id'][0] }}</label>
                   </div>
                   <div class="row flex mt-4">
                       <div class="col-4 pr-2">
                           <p>Strasse<sup>*</sup></p>
                           <vue-google-autocomplete
                               id="map"
                               classname="form-control form-input-bordered"
                               v-on:placechanged="getFromAddress"
                               placeholder="St. Gallerstrasse"
                               v-model="user.street_name"
                               v-on:change="getNewVal"
                               ref="address"
                           >
                           </vue-google-autocomplete>
                           <label class="text-danger"
                                  v-if="errors && errors['street_name']">{{ errors['street_name'][0] }}</label>
                       </div>
                       <div class="col-2 pr-2">
                           <p>Hausnummer<sup>*</sup></p>
                           <input v-model="user.house_number" class="form-control form-input-bordered" placeholder="1"/>
                           <label class="text-danger"
                                  v-if="errors && errors['house_number']">{{ errors['house_number'][0] }}</label>
                       </div>
                       <div class="col-2 pr-2">
                           <p>PLZ<sup>*</sup></p>
                           <input v-model="user.postal_code" class="form-control form-input-bordered" placeholder="7346"/>
                           <label class="text-danger"
                                  v-if="errors && errors['postal_code']">{{ errors['postal_code'][0] }}</label>
                       </div>
                       <div class="col-4">
                           <p>Ort<sup>*</sup></p>
                           <input v-model="user.location" class="form-control form-input-bordered" placeholder="Aach"/>
                           <label class="text-danger"
                                  v-if="errors && errors['location']">{{ errors['location'][0] }}</label>
                       </div>
                   </div>
                   <div class="row flex mt-4">
                       <div class="quarter">
                           <p>E-Mail<sup>*</sup></p>
                           <input class="form-input-bordered form-control" v-model="user.email"
                                  placeholder="exemple@email">
                           <label class="text-danger" v-if="errors && errors['email']">{{ errors['email'][0] }}</label>
                       </div>
                       <div class="quarter ml-2">
                           <p>Telefonnummer<sup>*</sup></p>
                           <div class="flex">
                               <select class="phone-select" v-model="user.prefix">
                                   <option :value="code" v-for="code in country_code">{{ code }}</option>
                               </select>
                               <input maxlength="12" @input="phoneNumberFormater(user.phone_number)" v-model="user.phone_number" class="form-input-bordered form-control phone-input"
                                      placeholder="00 000 00 00" v-mask="'## ### ## ##'"/>
                           </div>
                           <label class="text-danger"
                                  v-if="errors && errors['phone_number']">{{ errors['phone_number'][0] }}</label>
                       </div>
                   </div>
               </div>
           </div>
           <hr class="horizontal-line"/>
           <div class="row flex mt-1">
               <div class="text-content">
                   <h3 class="text-title"><a href="#" @click.prevent="sendInvitation">Einladung zur Registrierung</a></h3>
                   <a href="#" @click.prevent="sendInvitation" class="text-description">Einladungen erneut senden, wenn sich der Benutzer noch nicht registriert
                       hat.</a>
               </div>
               <div class="user-personal-info">
                   <div class="flex">
                       <div class="pr-16">
                           <p>Vorname<sup>*</sup></p>
                           <input v-model="user.first_name" class="form-control form-input-bordered" placeholder="Lukas"/>
                           <label class="text-danger"
                                  v-if="errors && errors['first_name']">{{ errors['first_name'][0] }}</label>
                       </div>
                       <div class="">
                           <p>Nachname<sup>*</sup></p>
                           <input v-model="user.last_name" class="form-control form-input-bordered" placeholder="Maier"/>
                           <label class="text-danger"
                                  v-if="errors && errors['last_name']">{{ errors['last_name'][0] }}</label>
                       </div>
                   </div>
                   <div class="flex items-center justify-between">
                       <button @click="$router.go(-1)" class="btn btn-danger btn-fixed">Abbrechen</button>
                       <button @click="updateUser" class="btn btn-primary btn-fixed ">
                           Aktualisieren</button>
                   </div>
               </div>
           </div>
       </div>
    </div>
</template>

<script>
import {codes} from "../../../contryCodes";
import axios from '../../axios';
import FileUploadField from "../../shared/FileUploadField";
import SelectInput from "../../shared/SelectInput";
import VueGoogleAutocomplete from 'vue-google-autocomplete'
import {mask} from "vue-the-mask";
import phoneNumberFormater from "../../mixins/phoneNumberFormaterMixin";
import flushMessagesMixin from "../../mixins/flushMessagesMixin";
export default {
    name: "EditSupervisor",
    directives:{mask},
    mixins:[phoneNumberFormater,flushMessagesMixin],
    components:{
        FileUploadField,
        SelectInput,
        VueGoogleAutocomplete
    },
    data() {
        return {
            errors: [],
            country_code: [],
            prefix: '+41',
            user: {},
            statuses: []
        }
    },
    mounted() {
        this.getCountryCodes()
        this.getUser()
        this.getStatuses()
    },

    methods: {
        getNewVal(newLocationAddres){
            this.$refs.address.$data.autocompleteText =  this.user.street_name
        },
        getFromAddress(from_address, placeResultData, id) {
            this.user.street_name = from_address.route;
            this.user.house_number = from_address.street_number;
            this.user.postal_code = from_address.postal_code;
            this.user.location = from_address.locality;
        },
        previewImage(image) {
           this.user.image = image
        },

        getCountryCodes() {
            codes.map(item => {
                this.country_code.push(item.dial_code)
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
        async getUser() {
            await axios.get(`/api/benutzer/${this.$route.params.id}`).then(res => {
                this.user = res.data.data
            })
        },

        updateUser() {
            const formData = new FormData()
            formData.append('role_id', this.user.role_id)
            if (this.user.image !== null && typeof this.user.image.name !== "undefined" ){
                formData.append('image', this.user.image)
            }
            formData.append('status_id', this.user.status_id)
            formData.append('street_name', this.user.street_name)
            formData.append('house_number', this.user.house_number)
            formData.append('postal_code', this.user.postal_code)
            formData.append('location', this.user.location)
            formData.append('email', this.user.email)
            formData.append('phone_number', this.user.phone_number)
            formData.append('first_name', this.user.first_name)
            formData.append('last_name', this.user.last_name)
            formData.append('prefix', this.user.prefix)

            axios.post(`/api/benutzer/update/${this.$route.params.id}`, formData).then(res => {
                this.successMessage('Benutzer updated successfully')
                this.getUser()
                this.$router.push({
                    name:'benutzer'
                })
            }).catch(err => {

                this.errorMessage(err.response.data.message)
                const backend_errors = err.response.data.errors
                this.errors = Object.assign({}, backend_errors)
            })
        },
        sendInvitation(){
            axios.post(`/api/benutzer/send-inviatation/${this.$route.params.id}`).then(res => {
                this.successMessage('Invitation sent successfully')
                this.getUser()
            }).catch(err => {
                this.errorMessage('Failed to send invitation')
            })
        }
    }
}
</script>
<style scoped lang="scss">
@import "supervisor";
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
.text-description{
    text-decoration: none;
}
</style>

