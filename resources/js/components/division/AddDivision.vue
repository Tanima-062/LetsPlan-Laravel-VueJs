<template>
    <div class="benutzer mt-5 mb-30">
        <div class="supervisor-card-wrapper new-card">
            <div class="row flex gap-2">
                <div class="user-form-registration">
                    <span class="header-text">Neue Einteilung hinzufügen</span>
                    <div class="row flex">
                        <div class="col-4 pr-2">
                            <p class="prompt">Einteilungs-ID*</p>
                            <p class="unique_id">
                                {{ form.uniq_id }}
                            </p>
                            <label
                                v-if="errors && errors['created_at']"
                                class="text-danger"
                                >{{ errors["uniq_id"][0] }}</label
                            >
                        </div>

                        <div class="col-4 pr-2" style="padding-left: 0">
                            <p class="prompt">Name*</p>
                            <input
                                v-model="form.name"
                                class="form-input-bordered form-control"
                                placeholder="Namen eingeben"
                                id="name"
                                maxlength="41"
                            />
                            <div
                                v-show="form.name.length >= 41"
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

                            <div
                                v-if="error.playgroup_start_date_equal"
                                class="error-msg"
                            >
                                “Das “Von” Datum muss später als das “Bis” Datum
                                der Anmeldefrist sein.
                            </div>
                        </div>
                        <div class="col-4 pr-2" style="padding-left: 0">
                            <p class="prompt">Datum Elterninformation*</p>
                            <SingleDatePicker
                                :placeholder="`TT.MM.JJJJ`"
                                @changeDate="setSingleDate"
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

                        <div class="col-4 pr-2" style="padding-left: 0">
                            <p class="prompt">Ort*</p>
                            <input
                                v-model="form.place"
                                class="form-control form-input-bordered"
                                placeholder="Ort eingeben"
                                id="place"
                                maxlength="41"
                            />
                            <div
                                v-show="form.place.length >= 41"
                                class="error-msg"
                            >
                                Maximal 40 Zeichen möglich
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row flex mt-4">
                <div class="col-4 pr-2">
                    <button
                        @click="addDivision"
                        class="btn-primary button-save col-4 pr-2"
                        style="width: 100%"
                    >
                        Speichern
                    </button>
                </div>
                <div class="col-4 pr-2" style="padding-left: 0">
                    <button
                        @click="showCancelModal = true"
                        class="btn-primary button-clear col-4 pr-2"
                        style="width: 100%"
                    >
                        Abbrechen
                    </button>
                </div>
            </div>
            <div class="row flex justify-start items-center mt-4"></div>
            <div class="row flex justify-end items-center"></div>
            <!-- Discard changes modal -->
            <modal :show="showCancelModal" @close="showCancelModal = false">
                <template #header>
                    <h3 style="margin: auto">
                        Erstellung der Einteilung abbrechen?
                    </h3>
                </template>
                <template #body>
                    <span class="cancel-text"
                        >Wenn Sie diesen Vorgang abbrechen, werden alle Daten
                        verworfen. Sind Sie sicher, dass Sie die Erstellung
                        dieser Einteilung wirklich abbrechen wollen?</span
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
            <!-- Duplicate date modal -->
            <modal
                :show="showDuplicateDateModal"
                containerStyle="height: 300px; padding-bottom: 0"
            >
                <template #header>
                    <h3 style="margin: auto">Ungültige Anmeldefrist</h3>
                </template>
                <template #body>
                    <p class="cancel-text">
                        Für den eingegebenen Anmeldefrist Zeitraum besteht
                        bereits eine Einteilung für ein, mehrere oder alle Tage
                        des eingegebenen Zeitraums. Bitte prüfen Sie die
                        bestehenden Einteilungen und erfassen Sie einen
                        Anmeldefrist Zeitraum, welcher keine Daten enthält,
                        welche bereits Teil von einer anderen Einteilung sind.
                    </p>
                </template>
                <template #footer>
                    <button
                        class="modal-primary-button"
                        style="margin-left: 130px; margin-top: 20px"
                        @click="showDuplicateDateModal = false"
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
import Modal from "./Modal.vue";
import TimeRangePicker from "./TimeRangePicker.vue";
import SingleDatePicker from "./SingleDatePicker.vue";
import DatePicker from "./DatePicker.vue";

export default {
    name: "AddDivision",
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
                name: "",
                uniq_id: "",
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
            error: {
                name: null,
                registration_start_date: null,
                playgroup_start_date: null,
                date_parent: null,
                open_door_date: null,
                open_door_start_time: null,
                place: null,
                playgroup_start_date_equal: null,
            },
        };
    },

    mounted() {
        this.getMax();
    },

    methods: {
        setSingleDate(e) {
            console.log("parent", e.start);
            this.form.date_parent = e.start;
        },
        setTime(t) {
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
        checkInputLength() {
            let flag = false;
            console.log(this.form.name.length);
            if (this.form.name.length >= 41) {
                this.error.name = true;
                flag = true;
                document.getElementById("name").style.border =
                    "1px solid #E97D32";
            } else if (this.form.place.length >= 41) {
                this.error.place = true;
                flag = true;
                document.getElementById("place").style.border =
                    "1px solid #E97D32";
            } else {
                flag = false;
            }
            return flag;
        },

        addDivision() {
            this.clearError();
            if (this.checkEmptyField()) {
                this.showMissingModal = true;
                return;
            } else if (this.checkInputLength()) {
                this.showMissingModal = true;

                return;
            } else {
                axios
                    .post(`/api/division/`, this.form)
                    .then((res) => {
                        console.log(res.data);
                        this.successMessage(res.data);
                        this.$router.push({
                            name: "division",
                        });
                    })
                    .catch((err) => {
                        if (
                            err.response.data.errors
                                .registration_period_duplicate
                        ) {
                            this.showDuplicateDateModal = true;
                            this.form.registration_start_date = "";
                            this.form.registration_end_date = "";
                            this.error.registration_start_date = true;
                        } else if (
                            err.response.data.errors.playgroup_start_date
                        ) {
                            this.error.playgroup_start_date = true;
                            this.error.playgroup_start_date_equal = true;
                        } else {
                            const backend_errors = err.response.data.errors;
                            this.errors = Object.assign({}, backend_errors);
                        }
                    });
            }
        },

        goBack() {
            this.$router.push(`/division`);
        },

        async getMax() {
            const res = await axios.get(`/api/division-get-max`);
            this.form.uniq_id = res.data;
        },

        clearError() {
            document.getElementById("name").style.border = "none";
            document.getElementById("place").style.border = "none";
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
            return flag;
        },
    },
};
</script>

<style scoped lang="scss">
@import "../supervisor/supervisor";

.error-msg {
    color: #e97d32;
    margin-top: 5px;
    text-align: right;
    font-size: 12px;
    font-style: italic;
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
    border-radius: 0px;
    border: none;
    font-family: "Nexa";
    background: white;
    font-style: normal;
    font-weight: 700;
    font-size: 15px;
    line-height: 23px;
    color: #2c5b7d;
}

.form-input-bordered::placeholder {
    color: #87b1cb !important;
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

    font-family: "Nexa";
    font-size: 18px;
}

.button-clear {
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 18px;

    height: 40px;
    background: #f8d3ba !important;

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
    color: #87b1cb !important;
}

.row {
    --bs-gutter-x: 4.5rem;
}
</style>
