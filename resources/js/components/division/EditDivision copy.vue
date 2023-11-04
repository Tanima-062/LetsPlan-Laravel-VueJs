<template>
    <div class="benutzer mt-5 mb-30">
        <div class="supervisor-card-wrapper new-card">
            <div class="row flex gap-2">
                <span
                    class="flex items-center"
                    style="cursor: pointer"
                    @click="goBack"
                    ><svg
                        width="26.67px"
                        height="20px"
                        viewBox="0 0 31 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        class="mr-3"
                    >
                        <path
                            d="M2.66602 12L29.3327 12M2.66602 12L12.666 2M2.66602 12L12.666 22"
                            stroke="#2C5B7D"
                            stroke-width="2.5"
                            stroke-linecap="square"
                        />
                    </svg>
                    <span class="prev-page">Zurück</span></span
                >
                <div class="user-form-registration">
                    <span class="header-text">Einteilung bearbeiten</span>
                    <div class="flex justify">
                        <div class="col-4 pr-2">
                            <p class="prompt">Erstellungsdatum</p>
                            <p class="unique_id">{{ form.created_at }}</p>
                        </div>
                        <div class="col-4 pr-2">
                            <p class="prompt">Einteilungs-ID*</p>
                            <p class="unique_id">{{ form.uniq_id }}</p>
                        </div>
                        <div class="col-4 pr-2">
                            <p class="prompt">Name*</p>
                            <input
                                v-model="form.name"
                                class="form-input-bordered form-control"
                                style="width: 436px"
                                maxlength="40"
                                placeholder="Namen eingeben"
                                id="name"
                            />
                            <div
                                v-if="form.name.length >= 40"
                                class="error-msg"
                            >
                                Maximal 40 Zeichen möglich
                            </div>
                        </div>
                    </div>
                    <div class="row flex mt-4">
                        <div class="col-4 pr-2">
                            <p class="prompt">Anmeldefrist*</p>

                            <DatePicker
                                :placeholder="`TT.MM.JJJJ - TT.MM.JJJJ`"
                                @changeDate="setRegDateValue"
                                :value="[
                                    form.registration_start_date,
                                    form.registration_end_date,
                                ]"
                                range
                                :error="error.registration_start_date"
                            />
                        </div>
                        <div class="col-4 pr-2" style="padding-left: 0">
                            <p class="prompt">Spielgruppen Zeitraum*</p>
                            <DatePicker
                                :placeholder="`TT.MM.JJJJ - TT.MM.JJJJ`"
                                @changeDate="setPlaygroupDateValue"
                                :value="[
                                    form.playgroup_start_date,
                                    form.playgroup_end_date,
                                ]"
                                range
                                :error="error.playgroup_start_date"
                            />
                        </div>
                        <div class="col-4 pr-2" style="padding-left: 0">
                            <p class="prompt">Datum Elterninformation*</p>
                            <SingleDatePicker
                                :placeholder="`TT.MM.JJJJ`"
                                @changeDate="
                                    (e) => (form.date_parent = e.start)
                                "
                                :value="form.date_parent"
                                :error="error.date_parent"
                            />
                        </div>
                    </div>
                    <span class="header-text">Tag der offenen Türe</span>
                    <div class="row flex mt-4">
                        <div class="col-4 pr-2">
                            <p class="prompt">Datum*</p>

                            <SingleDatePicker
                                :placeholder="`TT.MM.JJJJ`"
                                @changeDate="
                                    (e) => (form.open_door_date = e.start)
                                "
                                :value="form.open_door_date"
                                :error="error.open_door_date"
                            />
                        </div>

                        <div class="col-4 pr-2" style="padding-left: 0">
                            <p class="prompt">Zeit*</p>

                            <TimeRangePicker
                                @changeTime="setTime"
                                :value="[
                                    form.open_door_start_time,
                                    form.open_door_end_time,
                                ]"
                                :error="error.open_door_start_time"
                            />
                        </div>

                        <div
                            class="col-4 pr-2"
                            style="padding-left: 0; width: 436px"
                        >
                            <p class="prompt">Ort*</p>
                            <input
                                v-model="form.place"
                                class="form-control form-input-bordered"
                                placeholder="Ort eingeben"
                                style="width: 436px"
                                maxlength="40"
                                id="place"
                            />
                            <div
                                v-if="form.place.length >= 40"
                                class="error-msg"
                            >
                                Maximal 40 Zeichen möglich
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row flex justify-start items-center mt-4">
                <button
                    @click="updateDivision"
                    class="btn-primary button-save"
                    style="width: 436px"
                >
                    Speichern
                </button>
                <button
                    @click="showCancelModal = true"
                    class="btn-primary button-clear"
                    style="width: 436px"
                >
                    Abbrechen
                </button>
            </div>
            <div class="row flex justify-end items-center"></div>
            <!-- Discard changes modal -->
            <modal :show="showCancelModal" @close="showCancelModal = false">
                <template #header>
                    <h3 style="margin: auto">Änderungen verwerfen?</h3>
                </template>
                <template #body>
                    <span class="cancel-text"
                        >Wenn Sie zurückgehen oder abbrechen, ohne zu speichern,
                        werden alle Änderungen verworfen. Sind Sie sicher, dass
                        Sie die Änderungen wirklich verwerfen wollen?</span
                    >
                </template>
                <template #footer>
                    <button class="modal-primary-button" @click="goBack">
                        Ja
                    </button>
                    <button
                        class="modal-secondary-button"
                        @click="showCancelModal = false"
                    >
                        Nein
                    </button>
                </template>
            </modal>
            <!-- Missing field modal -->
            <modal :show="showMissingModal">
                <template #header>
                    <h3 style="margin: auto">
                        Fehlende oder ungültige Pflichtangaben
                    </h3>
                </template>
                <template #body>
                    <span class="cancel-text"
                        >Bitte füllen Sie alle erforderlichen Felder aus
                        und/oder erfassen Sie für diese gültige Daten gemäss
                        orangen Informationstexten.</span
                    >
                </template>
                <template #footer>
                    <button
                        class="modal-primary-button"
                        style="margin-left: 130px"
                        @click="showMissingModal = false"
                    >
                        OK
                    </button>
                </template>
            </modal>
        </div>
    </div>
</template>

<script>
import axios from "../../axios";
import flushMessagesMixin from "../../mixins/flushMessagesMixin";
import moment from "moment";
import Modal from "./Modal.vue";
import TimeRangePicker from "./TimeRangePicker.vue";
import SingleDatePicker from "./SingleDatePicker.vue";
import DatePicker from "./DatePicker.vue";

export default {
    name: "EditDivision",
    mixins: [flushMessagesMixin],
    components: {
        Modal,
        TimeRangePicker,
        SingleDatePicker,
        DatePicker,
    },
    data() {
        return {
            errors: [],
            showMissingModal: false,
            showDuplicateDateModal: false,
            showCancelModal: false,
            form: {
                created_at: "",
                name: "",
                uniq_id: "",
                registration_start_date: "",
                registration_start_date: "",
                registration_end_date: "",
                playgroup_start_date: "",
                playgroup_end_date: "",
                date_parent: "",
                open_door_date: "",
                open_door_start_time: "",
                open_door_end_time: "",
                place: "",
            },
        };
    },

    computed: {},
    mounted() {
        this.getDivision();
    },

    methods: {
        setTime(t) {
            console.log(t);
            this.form.open_door_start_time = t.start;
            this.form.open_door_end_time = t.end;
        },
        setRegDateValue(date) {
            this.form.registration_start_date = date.start;
            this.form.registration_end_date = date.end;
        },

        setPlaygroupDateValue(date) {
            this.form.playgroup_start_date = date.start;
            this.form.playgroup_end_date = date.end;
        },
        formatDate(value) {
            return moment(value).format("DD.MM.YYYY");
        },

        async getDivision() {
            let division_id = parseInt(this.$route.params.id);
            console.log("route params", division_id);
            await axios.get(`/api/division/${division_id}`).then((res) => {
                // console.log('response', res)
                console.log(res.data[0]);
                this.division = res.data[0];
            });
            this.form.created_at = this.formatDate(this.division.created_at);
            this.form.uniq_id = this.division.uniq_id;
            this.form.name = this.division.name;
            this.form.registration_start_date =
                this.division.registration_start_date;
            this.form.registration_end_date =
                this.division.registration_end_date;
            this.form.playgroup_start_date = this.division.playgroup_start_date;
            this.form.playgroup_end_date = this.division.playgroup_end_date;
            this.form.date_parent = this.division.date_parent;
            this.form.open_door_date = this.division.open_door_date;
            this.form.open_door_start_time = this.division.open_door_start_time;
            this.form.open_door_end_time = this.division.open_door_end_time;
            this.form.place = this.division.place;
        },

        formatDate(value) {
            return moment(value).format("DD.MM.YYYY");
        },

        updateDivision() {
            this.clearError();
            if (this.checkEmptyField()) {
                this.showMissingModal = true;
                return;
            } else {
                console.log(this.form);
                axios
                    .post(
                        `/api/division/update/${this.$route.params.id}`,
                        this.form
                    )
                    .then((res) => {
                        console.log(res.data);
                        this.successMessage(res.data);
                        this.$router.push({
                            name: "division",
                        });
                    })
                    .catch((err) => {
                        this.showErrorModal = true;
                        this.errorMessage(err.response.data.message);
                        const backend_errors = err.response.data.errors;
                        this.errors = Object.assign({}, backend_errors);
                    });
            }
        },
        clearError() {
            document.getElementById("name").style.border = "none";
            document.getElementById("place").style.border = "none";

            // document.getElementById("registration_start_date").style.border =
            //     "none";
            // document.getElementById("playgroup_start_date").style.border =
            //     "none";
            // document.getElementById("date_parent").style.border = "none";
            // document.getElementById("open_door_date").style.border = "none";
            // document.getElementById("open_door_start_time").style.border =
            //     "none";

            this.error.registration_start_date = false;
            this.error.playgroup_start_date = false;
            this.error.date_parent = false;
            this.error.open_door_date = false;
            this.error.open_door_start_time = false;
        },
        checkEmptyField() {
            var flag = false;
            if (this.form.name == "") {
                document.getElementById("name").style.border =
                    "1px solid #E97D32";
                flag = true;
            }
            if (this.form.place == "") {
                document.getElementById("place").style.border =
                    "1px solid #E97D32";
                flag = true;
            }
            console.log(flag);
            if (this.form.registration_start_date == "") {
                flag = true;
                this.error.registration_start_date = true;
            }
            if (this.form.playgroup_start_date == "") {
                flag = true;
                this.error.playgroup_start_date = true;
            }
            if (this.form.date_parent == "") {
                flag = true;
                this.error.date_parent = true;
            }
            if (this.form.open_door_date == "") {
                flag = true;
                this.error.open_door_date = true;
            }
            if (this.form.open_door_start_time == "") {
                flag = true;
                this.error.open_door_start_time = true;
            }
            console.log(flag);

            return flag;
        },
        goBack() {
            this.$router.push(`/division`);
        },
    },
};
</script>

<style scoped lang="scss">
@import "../supervisor/supervisor";
.btn-primary {
    width: 200px;
    height: 40px;
}

.error-msg {
    color: #e97d32;
    margin-top: 5px;
    text-align: right;
    font-size: 12px;
    font-style: italic;
    width: 436px;
    font-family: "Nexa";
}

.unique_id {
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 15px !important;
    line-height: 23px !important;
    color: #2c5b7d !important;
}

.header-text {
    font-family: "Ubuntu";
    font-style: normal;
    font-weight: 700;
    font-size: 22px;
    line-height: 25px;
    display: flex;
    align-items: center;
    color: #063f5f;
    margin-top: 20px;
    margin-bottom: 20px;
}
.prompt {
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 18px !important;
    line-height: 27px !important;
    display: flex;
    align-items: center;
    color: #16a9b7 !important;
}

.form-input-bordered {
    border-radius: 0px !important;
    border: none !important;
    font-family: "Nexa";
    background: white;
    font-style: normal;
    font-weight: 700;
    font-size: 15px;
    line-height: 23px;
    color: #2c5b7d;
}

.form-input-bordered:focus {
    box-shadow: none;
}
.new-card {
    background: #e9f7fe;
    border-radius: 0;
    margin-bottom: 80px;
}
.button-group,
.button-save {
    background: #e97d32 !important;
    border-radius: 0;
    margin-left: 13px;
    font-family: "Nexa";
    font-size: 18px;
}

.button-clear {
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 18px;
    width: 436px;
    height: 40px;
    background: #f8d3ba !important;
    margin-left: 52px;
    color: #063f5f;
    border-radius: 0;
}

.cancel-text {
    font-family: "Nexa";
    font-style: normal;
    font-weight: 400;
    font-size: 16px;
    line-height: 24px;
    display: flex;
    align-items: center;
    text-align: center;
    color: #2c5b7d;
}
.modal-primary-button {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    padding: 8px 96px;
    gap: 10px;
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 18px;
    line-height: 27px;
    text-align: center;
    color: #ffffff;
}
.modal-secondary-button {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    padding: 8px 96px;
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 18px;
    line-height: 27px;
    text-align: center;

    color: #19849e;
}
.user-form-registration .form-control {
    height: 40px;
    background: #fff;
}

.form-control::placeholder {
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 15px;
    line-height: 23px;
    color: #87b1cb;
}

.prev-page {
    width: 80px;
    height: 13px;
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 18px;
    line-height: 27px;
    display: flex;
    align-items: center;
    color: #2c5b7d;
    margin-left: 10px;
    margin-top: 4px;
}
</style>
