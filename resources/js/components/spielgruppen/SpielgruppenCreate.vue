<template>
    <div
        class="card-panel-home supervisor-card-wrapper rounded-none"
        style="background-color: #e9f7fe; border: none; border-radius: 0px"
    >
        <div class="head">
            <h4 class="head-title mb-4">Neue Spielgruppe hinzufügen</h4>
        </div>
        <div class="flex row">
            <div class="col-4 pr-2">
                <file-upload-field
                    :image="image"
                    @handleImageUpload="previewImage"
                />
                <label style="color: #e97d32" id="image_error"></label>
            </div>
            <div class="col-4 pr-2">
                <p class="title">Spielgruppen-ID*</p>
                <p
                    style="
                        font-family: 'Nexa';
                        font-style: normal;
                        font-weight: 700;
                        font-size: 15px;
                        line-height: 22.74px;
                    "
                >
                    {{ latest_id }}
                </p>
            </div>
            <div class="col-4 pr-2">
                <p class="title">Name*</p>
                <input
                    v-model="name"
                    class="inputField"
                    placeholder="Namen eingeben"
                    @keyup="checkLength($event, 'name', 40)"
                    maxlength="41"
                    id="name"
                    style="padding-left: 15px"
                />
                <label class="text-danger" id="name_error"></label>
            </div>
        </div>
        <div class="user-form-registration">
            <div class="flex row mt-4" style="margin-bottom: 40px">
                <div class="col-4 pr-2">
                    <p
                        class="title"
                        style="
                            font-family: 'Nexa';
                            font-style: normal;
                            font-weight: 700;
                            font-size: 18px;
                            line-height: 27px;
                            color: #16a9b7;
                        "
                    >
                        Strasse*
                    </p>
                    <vue-google-autocomplete
                        id="street_name"
                        classname="inputField"
                        v-on:placechanged="getFromAddress"
                        placeholder="Strasse"
                        v-on:change="getNewVal"
                        ref="address"
                        @keyup="checkLength($event, 'street_name', 40)"
                        maxlength="41"
                    >
                    </vue-google-autocomplete>
                    <label class="text-danger" id="street_name_error"></label>
                </div>
                <div class="col-4 pr-2">
                    <p
                        class="title"
                        style="
                            font-family: 'Nexa';
                            font-style: normal;
                            font-weight: 700;
                            font-size: 18px;
                            line-height: 27px;
                            color: #16a9b7;
                        "
                    >
                        Hausnummer*
                    </p>
                    <input
                        v-model="street_number"
                        class="inputField"
                        placeholder="Hausnummer"
                        maxlength="41"
                        @keypress="isDigit($event)"
                        @keyup="checkLength($event, 'street_number', 40)"
                        id="street_number"
                        style="padding-left: 15px"
                    />
                    <label class="text-danger" id="street_number_error"></label>
                </div>
                <div class="col-4 pr-2">
                    <p
                        class="title"
                        style="
                            font-family: 'Nexa';
                            font-style: normal;
                            font-weight: 700;
                            font-size: 18px;
                            line-height: 27px;
                            color: #16a9b7;
                        "
                    >
                        Postleitzahl*
                    </p>
                    <input
                        v-model="postal_code"
                        class="inputField"
                        placeholder="0001"
                        @keypress="isDigit($event)"
                        @keyup="checkLength($event, 'postal_code', 10)"
                        maxlength="11"
                        id="postal_code"
                        style="padding-left: 15px"
                    />
                    <label class="text-danger" id="postal_code_error"></label>
                </div>
            </div>
            <div class="flex row">
                <div class="col-4 pr-2">
                    <p
                        class="title"
                        style="
                            font-family: 'Nexa';
                            font-style: normal;
                            font-weight: 700;
                            font-size: 18px;
                            line-height: 27px;
                            color: #16a9b7;
                        "
                    >
                        Ort*
                    </p>
                    <input
                        v-model="city"
                        class="inputField"
                        placeholder="Ort"
                        maxlength="41"
                        @keyup="checkLength($event, 'city', 40)"
                        id="city"
                        style="padding-left: 15px"
                    />
                    <label class="text-danger" id="city_error"></label>
                </div>
                <div class="col-4 pr-2">
                    <p
                        style="
                            font-family: 'Nexa';
                            font-style: normal;
                            font-weight: 700;
                            font-size: 18px;
                            line-height: 27px;
                            color: #16a9b7;
                        "
                    >
                        Mindestanzahl*
                    </p>
                    <input
                        v-model="minimum_occupancy"
                        class="inputField"
                        placeholder="0000"
                        maxlength="5"
                        @keypress="isDigit($event)"
                        @keyup="checkLength($event, 'minimum_occupancy', 4)"
                        id="minimum_occupancy"
                    />
                    <label class="text-danger" id="minimum_occupancy_error"></label>
                </div>
                <div class="col-4 pr-2">
                    <p
                        style="
                            font-family: 'Nexa';
                            font-style: normal;
                            font-weight: 700;
                            font-size: 18px;
                            line-height: 27px;
                            color: #16a9b7;
                        "
                    >
                        Maximal Belegung<sup>*</sup>
                    </p>
                    <input
                        v-model="maximum_occupancy"
                        class="inputField"
                        placeholder="0000"
                        maxlength="5"
                        @keypress="isDigit($event)"
                        @keyup="checkLength($event, 'maximum_occupancy', 4)"
                        id="maximum_occupancy"
                        style="padding-left: 15px"
                    />
                    <label class="text-danger" id="maximum_occupancy_error"></label>
                </div>
            </div>
        </div>
        <div class="row flex-grow">
            <div class="table-wrapper">
                <div class="head">
                    <h4 class="head-title">Öffnungszeiten</h4>
                </div>
                <div class="weekday-table mt-4" id="weekday-table">
                    <div class="bg-white flex h-10 mt-2" style="padding: 6px 0">
                        <div  style="width: 300px;"></div>
                        <div
                            v-for="day in days"
                            class="text-left title"
                            :key="day"
                            style="width:210px;"
                        >
                            {{ day }}
                        </div>
                    </div>

                    <div class="flex mt-2 bg-white pb-2">
                        <div class=" svg-container" id="morning_time_range"
                            style="width: 285px; margin-left: 15px;"
                        >
                            <div>
                                <svg
                                    width="34"
                                    height="34"
                                    viewBox="0 0 34 34"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    style="margin:0"
                                >
                                    <path
                                        d="M18.25 10.3333V9.08333H15.75V10.3333H18.25ZM17 17H15.75C15.75 17.3315 15.8817 17.6495 16.1161 17.8839L17 17ZM19.4494 21.2172L20.3333 22.1011L22.1011 20.3333L21.2172 19.4494L19.4494 21.2172ZM15.75 10.3333V17H18.25V10.3333H15.75ZM16.1161 17.8839L19.4494 21.2172L21.2172 19.4494L17.8839 16.1161L16.1161 17.8839ZM30.75 17C30.75 24.5939 24.5939 30.75 17 30.75V33.25C25.9746 33.25 33.25 25.9746 33.25 17H30.75ZM17 30.75C9.40608 30.75 3.25 24.5939 3.25 17H0.75C0.75 25.9746 8.02537 33.25 17 33.25V30.75ZM3.25 17C3.25 9.40608 9.40608 3.25 17 3.25V0.75C8.02537 0.75 0.75 8.02537 0.75 17H3.25ZM17 3.25C24.5939 3.25 30.75 9.40608 30.75 17H33.25C33.25 8.02537 25.9746 0.75 17 0.75V3.25Z"
                                        fill="#001A72"
                                    />
                                </svg>
                            </div>
                            <TimeRangePicker shift="morning" @changeTime="setTime" />
                        </div>
                        <div
                            style="width:210px;"
                            v-for="(day, index) in days"
                            :key="index"
                        >
                            <div class="checkbox-div">
                                <input
                                    type="checkbox"
                                    :value="index + 1"
                                    v-model="morning_selection"
                                    :id="'morning-checkbox-' + index"
                                    class="morning_checkbox"
                                    @click="
                                        isChecked(
                                            'morning-checkbox-' + index,
                                            'morning',
                                            index
                                        )
                                    "
                                />
                                <label :for="'morning-checkbox-' + index"></label>
                            </div>
                        </div>
                    </div>
                    <div
                        class="flex pt-2 pb-2 bg-white"
                        style="border: 1px solid #d3e3ec; border-left: none; border-right:none;"
                    >
                        <div
                            class="row-role-title mt-2"
                            id="morning_lead_title"
                            style="
                                width:285px; margin-left: 15px;
                                font-family: 'Nexa';
                                font-style: normal;
                                font-weight: bold;
                                font-size: 15px;
                                line-height: 23px;
                                color: #2c5b7d;
                                opacity: 0.5;
                            "
                        >
                            Spielgruppenleiter/in
                        </div>
                        <div
                            class="select-box-svg__inner"
                            v-for="(day, index) in days"
                            :key="index"
                             style="width:210px;"
                        >
                            <select
                                :id="'morning_lead_' + index"
                                style="width: 85%"
                                disabled
                                class="list-item-select"
                            >
                                <option value="" class="list-item-disabled">
                                    Auswählen
                                </option>
                                <option
                                    :value="lead.id"
                                    v-for="lead in leads"
                                    :key="lead.id"
                                    class="list-item"
                                >
                                    {{ lead.first_name }} {{ lead.last_name }}
                                </option>
                            </select>
                            <!-- <svg
                                class="icon icon--arrow"
                                width="22"
                                height="14"
                                viewBox="0 0 22 14"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M2.66797 2.66663L11.0013 11L19.3346 2.66663"
                                    stroke="#063F5F"
                                    stroke-width="3"
                                    stroke-linecap="square"
                                />
                            </svg> -->
                        </div>
                    </div>
                    <div class="flex pt-2 pb-2 bg-white">
                        <div
                            class="row-role-title mt-2"
                            id="morning_assistant_title"
                            style="
                                 width:285px; margin-left: 15px;
                                font-family: 'Nexa';
                                font-style: normal;
                                font-weight: bold;
                                font-size: 15px;
                                line-height: 23px;
                                color: #2c5b7d;
                                opacity: 0.5;
                            "
                        >
                            Spielgruppen Assistent/in
                        </div>
                        <div
                            class="select-box-svg__inner"
                            v-for="(day, index) in days"
                            :key="index"
                            style="width:210px;"
                        >
                            <select
                                :id="'morning_assistant_' + index"
                                style="width: 85%"
                                disabled
                                class="list-item-select"
                            >
                                <option value="" class="list-item-disabled">
                                    Auswählen
                                </option>
                                <option
                                    :value="assistant.id"
                                    v-for="assistant in assistants"
                                    :key="assistant.id"
                                    class="list-item"
                                >
                                    {{ assistant.first_name }}
                                    {{ assistant.last_name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="flex mt-2 bg-white pb-2">
                        <div class="svg-container"  id="afternoon_time_range" style="width: 285px; margin-left: 15px;">
                            <div>
                                <svg
                                    width="34"
                                    height="34"
                                    viewBox="0 0 34 34"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                    style="margin: 0;"
                                >
                                    <path
                                        d="M18.25 10.3333V9.08333H15.75V10.3333H18.25ZM17 17H15.75C15.75 17.3315 15.8817 17.6495 16.1161 17.8839L17 17ZM19.4494 21.2172L20.3333 22.1011L22.1011 20.3333L21.2172 19.4494L19.4494 21.2172ZM15.75 10.3333V17H18.25V10.3333H15.75ZM16.1161 17.8839L19.4494 21.2172L21.2172 19.4494L17.8839 16.1161L16.1161 17.8839ZM30.75 17C30.75 24.5939 24.5939 30.75 17 30.75V33.25C25.9746 33.25 33.25 25.9746 33.25 17H30.75ZM17 30.75C9.40608 30.75 3.25 24.5939 3.25 17H0.75C0.75 25.9746 8.02537 33.25 17 33.25V30.75ZM3.25 17C3.25 9.40608 9.40608 3.25 17 3.25V0.75C8.02537 0.75 0.75 8.02537 0.75 17H3.25ZM17 3.25C24.5939 3.25 30.75 9.40608 30.75 17H33.25C33.25 8.02537 25.9746 0.75 17 0.75V3.25Z"
                                        fill="#001A72"
                                    />
                                </svg>
                            </div>
                            <TimeRangePicker
                                shift="afternoon"
                                @changeTime="setTime"
                            />
                        </div>
                        <div
                            style="width:210px;"
                            v-for="(day, index) in days"
                            :key="index"
                        >
                            <div class="checkbox-div">
                                <input
                                    type="checkbox"
                                    :value="index + 1"
                                    v-model="afternoon_selection"
                                    :id="'afternoon-checkbox-' + index"
                                    class="afternoon_checkbox"
                                    @click="
                                        isChecked(
                                            'afternoon-checkbox-' + index,
                                            'afternoon',
                                            index
                                        )
                                    "
                                />
                                <label
                                    :for="'afternoon-checkbox-' + index"
                                ></label>
                            </div>
                        </div>
                    </div>
                    <div
                        class="flex pt-2 pb-2 bg-white"
                        style="border: 1px solid #d3e3ec; border-left: none;border-right:none;"
                    >
                        <div
                            class="row-role-title mt-2"
                            id="afternoon_lead_title"
                            style="
                                width:285px; margin-left: 15px;
                                font-family: 'Nexa';
                                font-style: normal;
                                font-weight: bold;
                                font-size: 15px;
                                line-height: 23px;
                                color: #2c5b7d;
                                opacity: 0.5;
                            "
                        >
                            Spielgruppenleiter/in
                        </div>
                        <div
                            class="select-box-svg__inner"
                            v-for="(day, index) in days"
                            :key="index"
                             style="width:210px;"
                        >
                            <select
                                :id="'afternoon_lead_' + index"
                                 style="width: 85%"
                                disabled
                                class="list-item-select"
                            >
                                <option value="" class="list-item-disabled">
                                    Auswählen
                                </option>
                                <option
                                    :value="lead.id"
                                    v-for="lead in leads"
                                    :key="lead.id"
                                    class="list-item"
                                >
                                    {{ lead.first_name }} {{ lead.last_name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="flex pt-2 pb-2 bg-white">
                        <div
                            class="row-role-title mt-2"
                            id="afternoon_assistant_title"
                            style="
                                width:285px; margin-left: 15px;
                                font-family: 'Nexa';
                                font-style: normal;
                                font-weight: bold;
                                font-size: 15px;
                                line-height: 23px;
                                color: #2c5b7d;
                                opacity: 0.5;
                            "
                        >
                            Spielgruppen Assistent/in
                        </div>
                        <div
                            class="w-1/3 select-box-svg__inner"
                            v-for="(day, index) in days"
                            :key="index"
                             style="width:210px;"
                        >
                            <select
                                :id="'afternoon_assistant_' + index"
                                style="width: 85%"
                                disabled
                                class="list-item-select"
                            >
                                <option value="" class="list-item-disabled">
                                    Auswählen
                                </option>
                                <option
                                    :value="assistant.id"
                                    v-for="assistant in assistants"
                                    :key="assistant.id"
                                    class="list-item"
                                >
                                    {{ assistant.first_name }}
                                    {{ assistant.last_name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div id="openingHours_error" style="color: #e97d32"></div>
        </div>
        <div class="flex row mt-4">
            <div class="col-4 pr-2">
                <button
                    @click="createSpielgruppen"
                    class="btn-primary button-save"
                    style="width: 100%"
                >
                    Speichern
                </button>
            </div>
            <div class="col-4 pr-2" style="padding-left: 0; margin-left: 25px">
                <button
                    @click="cancel"
                    class="btn-primary button-clear"
                    style="width: 100%"
                >
                    Abbrechen
                </button>
            </div>
        </div>
        <ConfirmationModal />
        <NotificationModal />
        <FieldMissing />
    </div>
</template>
<script>
import TimeRangePicker from "./components/TimeRangePicker.vue";
import FileUploadField from "../../shared/FileUploadField";
import SelectInput from "../../shared/SelectInput";
import VueGoogleAutocomplete from "vue-google-autocomplete";
import axios from "../../axios";
import { forEach } from "lodash";
import NotificationModal from "./components/Notification.vue";
import FieldMissing from "./components/FieldMissing.vue";
import ConfirmationModal from "./components/ConfirmationModal.vue";
import { integer } from "@vuelidate/validators";

export default {
    name: "spielgruppen_create",
    data() {
        return {
            days: ["Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag"],
            latest_id: "",
            latest_id_by_company: "",
            name: "",
            street_name: "",
            street_number: "",
            postal_code: "",
            place_id: "",
            city: "",
            minimum_occupancy: "",
            maximum_occupancy: "",
            status_id: 1,
            image: null,
            users: [],
            morning_selection: [],
            afternoon_selection: [],
            morning_time_range: "",
            afternoon_time_range: "",
            showTime: false,
        };
    },
    mounted() {
        this.getUsers();
        this.getNextId();
    },
    computed: {
        leads() {
            return this.users.filter((item) => {
                return (
                    item.role_id === 2 &&
                    (item.status_id === 1 || item.status_id === 3)
                );
            });
        },

        assistants() {
            return this.users.filter((item) => {
                return (
                    item.role_id === 3 &&
                    (item.status_id === 1 || item.status_id === 3)
                );
            });
        },
    },
    components: {
        FileUploadField,
        SelectInput,
        TimeRangePicker,
        VueGoogleAutocomplete,
        NotificationModal,
        FieldMissing,
        ConfirmationModal,
    },
    methods: {
        isChecked(id, shift, index) {
            if (document.getElementById(id).checked) {
                document.getElementById(
                    shift + "_lead_title"
                ).style.opacity = 1;
                document.getElementById(
                    shift + "_assistant_title"
                ).style.opacity = 1;
                document.getElementById(
                    shift + "_lead_" + index
                ).disabled = false;
                document.getElementById(
                    shift + "_assistant_" + index
                ).disabled = false;
            } else {
                document.getElementById(
                    shift + "_lead_" + index
                ).disabled = true;
                document.getElementById(
                    shift + "_assistant_" + index
                ).disabled = true;

                var checkboxes = document.getElementsByClassName(
                    shift + "_checkbox"
                );
                var flag = true;

                for (var i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i].checked) {
                        flag = false;
                    }
                }

                if (flag) {
                    document.getElementById(
                        shift + "_lead_title"
                    ).style.opacity = 0.5;
                    document.getElementById(
                        shift + "_assistant_title"
                    ).style.opacity = 0.5;
                }
            }
        },
        clearError(){
            document.getElementById("weekday-table").style.border = 'none';
            document.getElementById("morning_time_range").style.border = 'none';
            document.getElementById("morning_lead_0").style.border = 'none';
            document.getElementById("morning_lead_1").style.border = 'none';
            document.getElementById("morning_lead_2").style.border = 'none';
            document.getElementById("morning_lead_3").style.border = 'none';
            document.getElementById("morning_lead_4").style.border = 'none';


            document.getElementById("morning_assistant_0").style.border = 'none';
            document.getElementById("morning_assistant_1").style.border = 'none';
            document.getElementById("morning_assistant_2").style.border = 'none';
            document.getElementById("morning_assistant_3").style.border = 'none';
            document.getElementById("morning_assistant_4").style.border = 'none';



            document.getElementById("afternoon_time_range").style.border = 'none';
            document.getElementById("afternoon_lead_0").style.border = 'none';
            document.getElementById("afternoon_lead_1").style.border = 'none';
            document.getElementById("afternoon_lead_2").style.border = 'none';
            document.getElementById("afternoon_lead_3").style.border = 'none';
            document.getElementById("afternoon_lead_4").style.border = 'none';

            document.getElementById("afternoon_assistant_0").style.border = 'none';
            document.getElementById("afternoon_assistant_1").style.border = 'none';
            document.getElementById("afternoon_assistant_2").style.border = 'none';
            document.getElementById("afternoon_assistant_3").style.border = 'none';
            document.getElementById("afternoon_assistant_4").style.border = 'none';
        },
        checkEmptyField() {
            var flag = false;

            if(this.afternoon_selection.length == 0 && this.morning_selection.length == 0){
                // console.log(this.afternoon_selection, this.morning_selection)
                // alert(this.afternoon_selection)
                // alert(this.morning_selection)
                document.getElementById("weekday-table").style.border =
                    "2px solid #E97D32";
                flag = true;
            }


            if (this.name == "") {
                document.getElementById("name").style.border =
                    "2px solid #E97D32";
                flag = true;
            }
            if (this.street_name == "") {
                document.getElementById("street_name").style.border =
                    "2px solid #E97D32";
                flag = true;
            }
            if (this.street_number == "") {
                document.getElementById("street_number").style.border =
                    "2px solid #E97D32";
                flag = true;
            }
            if (this.city == "") {
                document.getElementById("city").style.border =
                    "2px solid #E97D32";
                flag = true;
            }
            if (this.minimum_occupancy == "") {
                document.getElementById("minimum_occupancy").style.border =
                    "2px solid #E97D32";
                flag = true;
            }
            if (this.maximum_occupancy == "") {
                document.getElementById("maximum_occupancy").style.border =
                    "2px solid #E97D32";
                flag = true;
            }
            if (this.postal_code == "") {
                document.getElementById("postal_code").style.border =
                    "2px solid #E97D32";
                flag = true;
            }
            var checkboxes = document.getElementsByClassName("morning_checkbox");

            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    if (this.morning_time_range == "") {
                        document.getElementById("morning_time_range").style.border ="2px solid #E97D32";
                        flag = true;
                        break;
                    }
                }
            }
            var checkboxes = document.getElementsByClassName("afternoon_checkbox");

            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    if (this.afternoon_time_range == "") {
                        document.getElementById("afternoon_time_range").style.border ="2px solid #E97D32";
                        flag = true;
                        break;
                    }
                }
            }
            return flag;
        },
        isDigit(evt) {
            evt = evt ? evt : window.event;
            var charCode = evt.which ? evt.which : evt.keyCode;
            if (charCode < 48 || charCode > 57) {
                evt.preventDefault();
            } else {
                return true;
            }
        },
        setTime(t) {
            if (t.shift == "morning") {
                this.morning_time_range = t.start + "-" + t.end;
            } else {
                this.afternoon_time_range = t.start + "-" + t.end;
            }
        },
        checkLength(e, fieldName, count) {
            if (e.target.value.length > count) {
                document.getElementById(fieldName).style.border =
                    "2px solid #E97D32";
                document.getElementById(fieldName + "_error").innerHTML =
                    "Maximal " + count + " Zeichen möglich";
            } else {
                document.getElementById(fieldName).style.border = "";
                document.getElementById(fieldName + "_error").innerHTML = "";
            }
        },
        getNextId() {
            axios
                .get("/api/spielgruppen-next-id")
                .then((res) => {
                    this.latest_id = res.data.prefix_id;
                    this.latest_id_by_company = res.data.id_by_company;
                })
                .catch((err) => {});
        },
        getUsers() {
            axios
                .get("/api/filter/benutzer")
                .then((res) => {
                    this.users = res.data.data;
                })
                .catch((err) => {});
        },
        previewImage(image) {
            document.getElementById("image_error").innerHTML = "";
            this.image = image;
        },

        getSelectedItem(shift, type, index) {
            if (
                document.getElementById(shift + "_" + type + "_" + index)
                    .value == ""
            ) {
                document.getElementById(
                    shift + "_" + type + "_" + index
                ).style.border = "2px solid #E97D32";
                return false;
            }
            return true;
        },

        createSpielgruppen() {
            this.clearError();

            let formData = new FormData();
            var morning_leads = [];
            var morning_assistants = [];
            var flag = false;

            this.morning_selection.forEach((item) => {
                var ind = item - 1;
                if (this.getSelectedItem("morning", "lead", ind) == true) {
                    var value = document.getElementById(
                        "morning_lead_" + ind
                    ).value;
                    morning_leads.push(value);
                }
                if (this.getSelectedItem("morning", "assistant", ind) == true) {
                    var value = document.getElementById(
                        "morning_assistant_" + ind
                    ).value;
                    morning_assistants.push(value);
                } else {
                    flag = true;
                }
            });

            var afternoon_leads = [];
            var afternoon_assistants = [];

            this.afternoon_selection.forEach((item) => {
                var ind = item - 1;
                if (this.getSelectedItem("afternoon", "lead", ind) == true) {
                    var value = document.getElementById(
                        "afternoon_lead_" + ind
                    ).value;
                    afternoon_leads.push(value);
                }
                if (
                    this.getSelectedItem("afternoon", "assistant", ind) == true
                ) {
                    var value = document.getElementById(
                        "afternoon_assistant_" + ind
                    ).value;
                    afternoon_assistants.push(value);
                } else {
                    flag = true;
                }
            });


            if (this.checkEmptyField() == true) {
                const title = "Fehlende oder ungültige Pflichtangaben";
                const description =
                    "Bitte füllen Sie alle erforderlichen Felder aus und/oder erfassen Sie für diese gültige Daten gemäss orangen Informationstexten.";
                EventBus.$emit("field-missing-modal:show", {
                    yesClick: () => EventBus.$emit("field-missing-modal:hide"),
                    title,
                    description,
                });

                return;
            }
            if (this.minimum_occupancy != "" && this.maximum_occupancy != "") {
                if (
                    parseInt(this.minimum_occupancy) >
                    parseInt(this.maximum_occupancy)
                ) {
                    document.getElementById("minimum_occupancy").style.border =
                        "2px solid #E97D32";
                    document.getElementById(
                        "minimum_occupancy_error"
                    ).innerHTML =
                        "<div>Mindestanzahl sollte kleiner sein als maximal belegung</div>";
                    return;
                }
            }

            if (
                flag == true ||
                this.name.length > 40 ||
                this.street_name.length > 40 ||
                this.street_number.length > 40 ||
                this.postal_code.length > 10 ||
                this.city.length > 40 ||
                this.minimum_occupancy.length > 4 ||
                this.maximum_occupancy.length > 4
            ) {
                return;
            }

            formData.append("prefix_id", this.latest_id);
            formData.append("id_by_company", this.latest_id_by_company);
            if (this.image) {
                formData.append("image", this.image);
            }
            formData.append("name", this.name);
            formData.append("street_name", this.street_name);
            formData.append("place_id", this.place_id);
            formData.append("street_number", this.street_number);
            formData.append("postal_code", this.postal_code);
            formData.append("location", this.city);
            formData.append("minimum_occupancy", this.minimum_occupancy);
            formData.append("maximum_occupancy", this.maximum_occupancy);
            formData.append("status_id", this.status_id);
            formData.append("morning_time_range", this.morning_time_range);
            formData.append("afternoon_time_range", this.afternoon_time_range);

            formData.append(
                "morning_days",
                JSON.stringify(this.morning_selection)
            );
            formData.append(
                "afternoon_days",
                JSON.stringify(this.afternoon_selection)
            );
            formData.append("morning_leads", JSON.stringify(morning_leads));
            formData.append(
                "morning_assistants",
                JSON.stringify(morning_assistants)
            );
            formData.append("afternoon_leads", JSON.stringify(afternoon_leads));
            formData.append(
                "afternoon_assistants",
                JSON.stringify(afternoon_assistants)
            );

            axios
                .post("/api/spielgruppen/store", formData)
                .then((res) => {
                    this.$router.back();
                    EventBus.$emit("notification-modal:show", res.data.message);
                })
                .catch((err) => {
                    var errors = err.response.data.errors;
                    if (errors.image.length) {
                        var error_msg = "";
                        errors.image.forEach((error) => {
                            error_msg += "<div>" + error + "</div>";
                        });
                        document.getElementById("image_error").innerHTML =
                            error_msg;
                    }
                    if (errors.minimum_occupancy.length) {
                        var error_msg = "";
                        errors.minimum_occupancy.forEach((error) => {
                            error_msg += "<div>" + error + "</div>";
                        });
                        document.getElementById(
                            "minimum_occupancy"
                        ).style.border = "2px solid #E97D32";
                        document.getElementById(
                            "minimum_occupancy_error"
                        ).innerHTML = error_msg;
                    }
                    if (errors.maximum_occupancy.length) {
                        var error_msg = "";
                        errors.maximum_occupancy.forEach((error) => {
                            error_msg += "<div>" + error + "</div>";
                        });
                        document.getElementById(
                            "maximum_occupancy"
                        ).style.border = "2px solid #E97D32";
                        document.getElementById(
                            "maximum_occupancy_error"
                        ).innerHTML = error_msg;
                    }
                    if (errors.street_number) {
                        var error_msg = "";
                        errors.street_number.forEach((error) => {
                            error_msg += "<div>" + error + "</div>";
                        });
                        document.getElementById("street_number").style.border =
                            "2px solid #E97D32";
                        document.getElementById(
                            "street_number_error"
                        ).innerHTML = error_msg;
                    }
                    if (errors.street_name) {
                        var error_msg = "";
                        errors.street_name.forEach((error) => {
                            error_msg += "<div>" + error + "</div>";
                        });
                        document.getElementById("street_name").style.border =
                            "2px solid #E97D32";
                        document.getElementById("street_name_error").innerHTML =
                            error_msg;
                    }
                    if (errors.postal_code) {
                        var error_msg = "";
                        errors.postal_code.forEach((error) => {
                            error_msg += "<div>" + error + "</div>";
                        });
                        document.getElementById("postal_code").style.border =
                            "2px solid #E97D32";
                        document.getElementById("postal_code_error").innerHTML =
                            error_msg;
                    }
                    if (errors.name) {
                        var error_msg = "";
                        errors.name.forEach((error) => {
                            error_msg += "<div>" + error + "</div>";
                        });
                        document.getElementById("name").style.border =
                            "2px solid #E97D32";
                        document.getElementById("name_error").innerHTML =
                            error_msg;
                    }
                    if (errors.location) {
                        var error_msg = "";
                        errors.location.forEach((error) => {
                            error_msg += "<div>" + error + "</div>";
                        });
                        document.getElementById("city").style.border =
                            "2px solid #E97D32";
                        document.getElementById("city_error").innerHTML =
                            error_msg;
                    }
                    if (errors.openingHours) {
                        var error_msg = "";
                        errors.openingHours.forEach((error) => {
                            error_msg += "<div>" + error + "</div>";
                        });
                        document.getElementById(
                            "openingHours_error"
                        ).innerHTML = error_msg;
                    }
                });
        },
        getNewVal(newLocationAddres) {
            if (newLocationAddres == "") {
                this.street_name = "";
                this.street_number = "";
                this.postal_code = "";
                this.place_id = "";
                this.city = "";
            }
            this.$refs.address.$data.autocompleteText = this.street_name;
        },
        getFromAddress(from_address, placeResultData, id) {
            this.street_name = from_address.route;
            this.street_number = from_address.street_number;
            this.postal_code = from_address.postal_code;
            this.city = from_address.locality;
            this.place_id = placeResultData.place_id;
            document.getElementById("street_name").style.border = "";
            document.getElementById("street_name_error").innerHTML = "";
            document.getElementById("city").style.border = "";
            document.getElementById("city_error").innerHTML = "";
            if (this.postal_code) {
                document.getElementById("postal_code").style.border = "";
                document.getElementById("postal_code_error").innerHTML = "";
            }
        },
        cancel() {
            const title = "Erstellung der Spielgruppe abbrechen?";
            const description =
                "Wenn Sie diesen Vorgang abbrechen, werden alle Daten verworfen. Sind Sie sicher, dass Sie die Erstellung dieser Spielgruppe wirklich abbrechen wollen?";
            EventBus.$emit("confirmation-modal:show", {
                title,
                description,
                noClick: () => EventBus.$emit("confirmation-modal:hide"),
                yesClick: () => {
                    EventBus.$emit("confirmation-modal:hide");
                    this.$router.back();
                },
            });
        },
    },
};
</script>
<style scoped lang="scss">
@import "../supervisor/supervisor";

.svg-container {
    display: flex;
    align-items: center;
    svg {
        width: 30px;
        height: 30px;
        margin: 5px 0px 0px 21px;
    }
}

.list-item,
.list-item-select {
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 15px;
    line-height: 23px;
    color: #2c5b7d;
    margin: 0;
    padding: 0;
    margin-top: 5px;

    -webkit-appearance: none;
    -moz-appearance: none;
    background: transparent;
    background-image: url("data:image/svg+xml;utf8,<svg fill='black' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/><path d='M0 0h24v24H0z' fill='none'/></svg>");

    background-position-x: 100%;
    background-position-y: 5px;

    background-image: url("data:image/svg+xml,%3Csvg width='22' height='14' viewBox='0 0 22 14' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M2.6665 2.6665L10.9998 10.9998L19.3332 2.6665' stroke='%23063F5F' stroke-width='3' stroke-linecap='square'/%3E%3C/svg%3E%0A");

    background-repeat: no-repeat;

}

.list-item-disabled {
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 15px;
    line-height: 23px;
    color: #87b1cb;
}

.text-danger {
    color: #e97d32 !important;
    float: right;
    margin-right: 10px;
    font-style: italic;
}
.head-title {
    font-family: "Ubuntu";
    font-style: normal;
    font-weight: 700;
    font-size: 22px;
    line-height: 25px;
    color: #063f5f;
    margin: 20px 0px 0px 0px;
}
input {
    width: 100%;
    height: 40px;
    background: #ffffff;
    padding: 15px 40px 14px 24px;
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 15pxx;
    outline: none;
    line-height: 23px;
    color: #2c5b7d;
}
input::placeholder {
    font-size: 15px !important;
}
.title {
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 18px;
    line-height: 27px;
    color: #16a9b7;
}
.btn-primary {
    width: 200px;
    height: 40px;
    font-family: "Nexa", sans-serif;
    font-size: 18px;
    line-height: 27px;
}

.button-save {
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 18px;
    height: 40px;
    line-height: 27px;
    background: #e97d32;
    color: #ffffff;
    padding: 15px 33px;
    border-radius: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.button-clear {
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 18px;
    line-height: 27px;
    height: 40px;
    background: #f8d3ba !important;
    color: #063f5f;
    padding: 15px 33px;
    border-radius: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.button-save:hover {
    background: #e97d32 !important;
    border-radius: 0;
}
.weekday-table-head {
    width: 100%;
    border-radius: 8px;
    // border: 1px solid #727574;
    padding: 10px;
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

.checkbox-div {
    width: 28px;
    height: 28px;
    position: relative;
    margin-top: 10px;
    border: 2.5px solid #d3e3ec;
}
.checkbox-div label {
    width: 21px;
    height: 21px;
    position: absolute;
    top: 2px;
    left: 2px;
    cursor: pointer;
}
.checkbox-div label:after {
    content: "";
    width: 17px;
    height: 17px;
    position: absolute;
    top: 1px;
    left: 1px;
    background: #9fdcf8;
    opacity: 0;
}
.checkbox-div label:hover::after {
    opacity: 0.3;
}
.checkbox-div input[type="checkbox"] {
    visibility: hidden;
}
.checkbox-div input[type="checkbox"]:checked + label:after {
    opacity: 1;
}

.select-box-svg__inner {
    border: none;
    border-radius: 5px;
    margin: 0;
    overflow: hidden;
    position: relative;
    transition: background-color 0.4s ease-in-out;
}
.select-box-svg__inner:hover,
.select-box-svg__inner:focus,
.select-box-svg__inner:active {
    cursor: pointer;
}

.icon--arrow {
    // position: absolute;
    // right: 40%;
    // bottom: 30%;
    // display: block;
}

.select-box-svg__inner select option {
    display: block;
    border: none;
}
.select-box-svg__inner select:hover {
    cursor: pointer;
}
.select-box-svg__inner select {
    position: relative;
    z-index: 2;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-color: transparent;
    border-color: none;
    // background-image: none;
    border: none;
    box-shadow: none;
    display: block;
    transition: background 0.4s ease;
}

select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    padding: 9px;
}

*:focus {
    outline: none;
}
*:disabled {
    opacity: 0.5;
}
</style>
