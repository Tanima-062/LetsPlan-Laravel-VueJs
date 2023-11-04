<template>
    <div class="card-statistics bg-white form-card-wrapper mb-50" :class="{'disable-all':role !== 'admin'}">
        <h3>Herzlich willkommen</h3>
        <p class="mt-4">Die <strong>Anmeldefrist</strong> dauert</p>
        <div class="flex">
            <div class="form-group mt-2 w-full pr-20">
                <date-input
                    :required="true"
                    v-model="registration_from"
                    :error="errors && errors['registration_phase_start_date'] ?  errors['registration_phase_start_date'][0]:false"
                    label="von"
                />
            </div>
            <div class="form-group mt-2 w-full">
                <date-input
                    :required="true"
                    v-model="registration_to"
                    :error="errors && errors['registration_phase_end_date'] ?  errors['registration_phase_end_date'][0]:false"
                    label="bis am"
                />
            </div>
        </div>
        <hr class="input-divider"/>
        <p class="mt-4">und gilt für den <strong>Spielgruppen Zeitraum</strong></p>
        <div class="flex">
            <div class="form-group mt-2 w-full pr-20">
                <date-input
                    :required="true"
                    v-model="playgroups_period_from"
                    :error="errors && errors['playground_period_start_date'] ?  errors['playground_period_start_date'][0]:false"
                    label="von"
                />
            </div>
            <div class="form-group mt-2 w-full">
                <date-input
                    :required="true"
                    v-model="playgroups_period_to"
                    :error="errors && errors['playground_period_end_date'] ?  errors['playground_period_end_date'][0]:false"
                    label="bis am"
                />
            </div>
        </div>
        <hr class="input-divider"/>
        <p class="mt-4">Die Eltern werden spätestens über die <strong>Einteilung</strong> informiert am<sup>*</sup>:</p>
        <div class="form-group mt-2 w-full">
            <date-input
                v-model="classification"
                :error="errors && errors['parents_inform_date'] ?  errors['parents_inform_date'][0]:false"
            />
        </div>
        <hr class="input-divider"/>
        <p class="mt-4">Der Tag der <strong>offenen Türe</strong> findet statt am<sup>*</sup>:</p>
        <div class="form-group mt-2 w-full">
            <date-input
                v-model="open_day_date"
                :error="errors && errors['open_day_date'] ?  errors['open_day_date'][0]:false"
            />
        </div>
        <div class="flex">
            <div class="form-group mt-2 w-full pr-20">
                <time-input
                    :required="true"
                    v-model="open_day_time_from"
                    :error="errors && errors['open_day_time_from'] ?  errors['open_day_time_from'][0]:false"
                    label="von"
                />
            </div>
            <div class="form-group mt-2 w-full">
                <time-input
                    :required="true"
                    v-model="open_day_time_to"
                    :error="errors && errors['open_day_time_to'] ?  errors['open_day_time_to'][0]:false"
                    label="bis am"
                />
            </div>
        </div>
        <hr class="input-divider"/>
        <p class="mt-4">Bitte eingeben, wo der Tag der <strong>offenen Türe</strong> stattfindet</p>
        <div class="form-group mt-2 w-full">
            <input v-model="open_day_description" class="form-control w-full form-input-bordered" type="text"/>
        </div>
        <button @click="register" class="btn btn-primary w-full mt-2">Aktualisieren</button>

    </div>
</template>

<script>
import axios from "../../axios";
import moment from 'moment'
import flushMessagesMixin from "../../mixins/flushMessagesMixin";
import DateInput from "../../shared/DateInput";
import TimeInput from "../../shared/TimeInput";
export default {
    name: "registrationDateForm",
    mixins: [flushMessagesMixin],
    components:{
      DateInput,
      TimeInput
    },
    data() {
        return {
            registration_from: null,
            registration_to: null,
            playgroups_period_from: null,
            playgroups_period_to: null,
            classification: null,
            open_day_date: null,
            open_day_description: null,
            open_day_time_from: null,
            open_day_time_to: null,
            id: null,
            errors: [],
            role : localStorage.getItem('role'),
        }
    },
    created() {
        this.getRegistry()

    },
    methods: {

        register() {
            if (this.role !== 'admin'){
                return;
            }
            const data = {
                registration_phase_start_date:this.registration_from,
                registration_phase_end_date:this.registration_to,
                playground_period_start_date: this.playgroups_period_from,
                playground_period_end_date: this.playgroups_period_to,
                parents_inform_date: this.classification,
                open_day_date: this.open_day_date,
                open_day_time_from:  moment(this.open_day_time_from).format('HH:mm'),
                open_day_time_to: moment(this.open_day_time_to).format('HH:mm'),
                open_day_description: this.open_day_description,
                id: this.id
            }
            axios.post("api/registry", data).then(res => {
                this.successMessage(res.data)
                this.getRegistry()
            }).catch(err => {
                this.errorMessage(err.response.data.message)
                const backend_errors = err.response.data.errors
                this.errors = Object.assign({}, backend_errors)
            })
        },
        getRegistry(){
            axios.get("api/registry").then(response => {
                    let data = response.data.data
                    if (data != undefined){
                        this.registration_from = moment(data.registration_phase_start_date).format('DD.MM.YYYY')
                        this.registration_to = moment(data.registration_phase_end_date).format('DD.MM.YYYY')
                        this.playgroups_period_from = moment(data.playground_period_start_date).format('DD.MM.YYYY')
                        this.playgroups_period_to = moment(data.playground_period_end_date).format('DD.MM.YYYY')
                        this.classification = moment(data.parents_inform_date).format('DD.MM.YYYY')
                        this.open_day_date = moment(data.open_day_date).format('DD.MM.YYYY')
                        this.open_day_time_from = new Date(
                            moment(data.open_day_date)
                                .add(data.open_day_time_from.split(':')[1],'minutes')
                                .add( data.open_day_time_from.split(':')[0],'hours')
                                .format("YYYY-MM-DD HH:mm:ss")
                        )
                        this.open_day_time_to = new Date(
                            moment(data.open_day_date)
                                .add(data.open_day_time_to.split(':')[1],'minutes')
                                .add( data.open_day_time_to.split(':')[0],'hours')
                                .format("YYYY-MM-DD HH:mm:ss")
                        )
                        this.open_day_description = data.content
                        this.id=data.id
                    }

            }).catch(err => {
                this.errorMessage(err.response.data.message)
            })
        }
    }
}
</script>

<style scoped lang="scss">

.form-card-wrapper {
    padding: 30px !important;
    .input-divider{
        height: 1px;
        background: #C2C5C6;
        margin: 15px 1px;
    }
    h3,
    p,
    strong,
    label{
        color: #42474A;
    }
}

.input-title {
    font-size: 14px;
}

.form-input-bordered {
    margin-top: 5px;
    border: 0.5px solid #727574;
    box-sizing: border-box;
    border-radius: 5px;
    background: #F8FBFA;
    height: 40px;
}

.btn-primary {
    margin-top: 15px;
    padding: 8px;
    background: #AEC90B;
    font-weight: 500;
    font-size: 15px;
    line-height: 24px;
    border-radius: 5px;
}
</style>
