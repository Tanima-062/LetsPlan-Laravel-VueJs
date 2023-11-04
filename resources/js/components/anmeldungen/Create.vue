<template>
    <div class="view">
        <div class="head">
            <h4 class="view-title">Neue Anmeldung hinzufügen</h4>
        </div>
        <div class="content">
            <div
                class="field-wrapper"
                style="margin-bottom: 56px"
            >
                <div class="field">
                    <div class="label">Anmeldungs-ID*</div>
                    <div class="value">AM{{ latest_id | leadzero }}</div>
                </div>
                <div class="field">
                    <div class="label">Einteilung*</div>
                    <SingleSelect
                        placeholder="Auswählen"
                        :options="divisions"
                        v-model="form.division_id"
                        :error="errors.division_id"
                        valueKey="id"
                        labelKey="name"
                        @input="
                            () => {
                                delete errors.division_id;
                            }
                        "
                    />
                </div>
            </div>

            <h4
                class="field-title"
                style="margin-bottom: 32px"
            >Kind</h4>
            <div class="field-wrapper">
                <div class="field">
                    <TextInput
                        label="Vorname*"
                        placeholder="Vornamen eingeben"
                        maxLength="31"
                        v-model="form.child_first_name"
                        :errorMessage="
                            v$.form.child_first_name.$errors.length
                                ? v$.form.child_first_name.$errors[0].$message
                                : ''
                        "
                        :error="errors.child_first_name"
                        @input="
                            () => {
                                v$.form.child_first_name.$touch();
                                delete errors.child_first_name;
                            }
                        "
                    />
                </div>

                <div class="field">
                    <TextInput
                        label="Nachname*"
                        placeholder="Nachnamen eingeben"
                        maxLength="31"
                        v-model="form.child_last_name"
                        :errorMessage="
                            v$.form.child_last_name.$errors.length
                                ? v$.form.child_last_name.$errors[0].$message
                                : ''
                        "
                        :error="errors.child_last_name"
                        @input="
                            () => {
                                v$.form.child_last_name.$touch();
                                delete errors.child_last_name;
                            }
                        "
                    />
                </div>

                <div class="field">
                    <div class="label">Geschlecht*</div>
                    <SingleSelect
                        :options="[
                            { value: 1, label: 'Junge' },
                            { value: 2, label: 'Mädchen' },
                        ]"
                        v-model="form.gender_id"
                        placeholder="Auswählen"
                        :error="errors.gender_id"
                        @input="
                            () => {
                                delete errors.gender_id;
                            }
                        "
                    />
                </div>
            </div>

            <div
                class="field-wrapper"
                style="margin-top: 40px"
            >
                <div class="field">
                    <p class="label">Geburtsdatum*</p>
                    <SingleDatePicker
                        :placeholder="`TT.MM.JJJJ`"
                        @changeDate="setSingleDate"
                        :value="form.child_birthday"
                        :error="errors.child_birthday ? true : false"
                    />
                    <p
                        class="child-birthday-error"
                        v-if="errors.child_birthday && form.child_birthday"
                        style="display: block"
                    >
                        {{ errors.child_birthday[0] }}
                    </p>
                </div>

                <div class="field">
                    <div class="label">Nationalität*</div>

                    <SingleSelectWithSearch
                        :options="nationalities"
                        v-model="form.nationality_id"
                        placeholder="Nationalität auswählen"
                        valueKey="id"
                        labelKey="name"
                        :error="errors.nationality_id"
                        searchType="nationalities"
                        @setOptions="
                            (options) => {
                                nationalities = options;
                            }
                        "
                        @input="
                            () => {
                                delete errors.nationality_id;
                            }
                        "
                    />
                </div>

                <div class="field">
                    <div class="label">Muttersprache*</div>
                    <SingleSelectWithSearch
                        :options="languages"
                        v-model="form.mother_tongue_id"
                        placeholder="Muttersprache auswählen"
                        valueKey="id"
                        labelKey="name"
                        :error="errors.mother_tongue_id"
                        searchType="mother_tongues"
                        @setOptions="
                            (options) => {
                                languages = options;
                            }
                        "
                        @input="
                            () => {
                                delete errors.mother_tongue_id;
                            }
                        "
                    />
                </div>
            </div>

            <h4
                class="field-title"
                style="margin-bottom: 32px; margin-top: 56px"
            >
                Eltern
            </h4>

            <div class="field-wrapper">
                <div class="field">
                    <TextInput
                        label="Vorname Mutter*"
                        placeholder="Vornamen der Mutter eingeben"
                        maxLength="31"
                        v-model="form.mother_first_name"
                        :errorMessage="
                            v$.form.mother_first_name.$errors.length
                                ? v$.form.mother_first_name.$errors[0].$message
                                : ''
                        "
                        :error="errors.mother_first_name"
                        @input="
                            () => {
                                v$.form.mother_first_name.$touch();
                                delete errors.mother_first_name;
                            }
                        "
                    />
                </div>

                <div class="field">
                    <TextInput
                        label="Nachname Mutter* "
                        placeholder="Nachnamen der Mutter eingeben"
                        maxLength="31"
                        v-model="form.mother_last_name"
                        :errorMessage="
                            v$.form.mother_last_name.$errors.length
                                ? v$.form.mother_last_name.$errors[0].$message
                                : ''
                        "
                        :error="errors.mother_last_name"
                        @input="
                            () => {
                                v$.form.mother_last_name.$touch();
                                delete errors.mother_last_name;
                            }
                        "
                    />
                </div>
            </div>

            <div
                class="field-wrapper"
                style="margin-top: 40px"
            >
                <div class="field">
                    <TextInput
                        label="Vorname Vater*"
                        placeholder="Vornamen des Vaters eingeben"
                        maxLength="31"
                        v-model="form.father_first_name"
                        :errorMessage="
                            v$.form.father_first_name.$errors.length
                                ? v$.form.father_first_name.$errors[0].$message
                                : ''
                        "
                        :error="errors.father_first_name"
                        @input="
                            () => {
                                v$.form.father_first_name.$touch();
                                delete errors.father_first_name;
                            }
                        "
                    />
                </div>

                <div class="field">
                    <TextInput
                        label="Nachname Vater* "
                        placeholder="Nachnamen des Vaters eingeben"
                        maxLength="31"
                        v-model="form.father_last_name"
                        :errorMessage="
                            v$.form.father_last_name.$errors.length
                                ? v$.form.father_last_name.$errors[0].$message
                                : ''
                        "
                        :error="errors.father_last_name"
                        @input="
                            () => {
                                v$.form.father_last_name.$touch();
                                delete errors.father_last_name;
                            }
                        "
                    />
                </div>
            </div>

            <h4
                class="field-title"
                style="margin-bottom: 32px; margin-top: 56px"
            >
                Adresse
            </h4>

            <div class="field-wrapper">
                <div class="field">
                    <TextInput
                        label="Optionaler Adresszusatz"
                        placeholder="Adresszusatz eingeben"
                        maxLength="31"
                        v-model="form.optional_address"
                        :errorMessage="
                            v$.form.optional_address.$errors.length
                                ? v$.form.optional_address.$errors[0].$message
                                : ''
                        "
                        :error="errors.optional_address"
                        @input="
                            () => {
                                v$.form.optional_address.$touch();
                                delete errors.optional_address;
                            }
                        "
                    />
                </div>

                <div class="field">
                    <div class="input-wrapper">
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
                            id="street"
                            classname="inputField"
                            v-on:placechanged="getFromAddress"
                            placeholder="Strasse"
                            v-on:change="getNewVal"
                            ref="address"
                            @keyup="checkLength($event, 'street', 40)"
                            maxlength="41"
                        >
                        </vue-google-autocomplete>
                        <p
                            class="error-text"
                            id="street_error"
                        ></p>
                    </div>
                </div>

                <div class="field">
                    <TextInput
                        label="Hausnummer*"
                        placeholder="0000"
                        maxLength="31"
                        v-model="form.street_number"
                        :errorMessage="
                            v$.form.street_number.$errors.length
                                ? v$.form.street_number.$errors[0].$message
                                : ''
                        "
                        :error="errors.street_number"
                        @input="
                            () => {
                                v$.form.street_number.$touch();
                                delete errors.street_number;
                            }
                        "
                    />
                </div>
            </div>

            <div
                class="field-wrapper"
                style="margin-top: 40px"
            >
                <div class="field">
                    <TextInput
                        label="Postleitzahl*"
                        placeholder="0001"
                        maxLength="11"
                        v-model="form.postal_code"
                        :errorMessage="
                            v$.form.postal_code.$errors.length
                                ? v$.form.postal_code.$errors[0].$message
                                : ''
                        "
                        :error="errors.postal_code"
                        @input="
                            () => {
                                v$.form.postal_code.$touch();
                                delete errors.postal_code;
                            }
                        "
                    />
                </div>

                <div class="field">
                    <TextInput
                        label="Ort*"
                        placeholder="Ort"
                        maxLength="31"
                        v-model="form.place"
                        :errorMessage="
                            v$.form.place.$errors.length
                                ? v$.form.place.$errors[0].$message
                                : ''
                        "
                        :error="errors.place"
                        @input="
                            () => {
                                v$.form.place.$touch();
                                delete errors.place;
                            }
                        "
                    />
                </div>
            </div>

            <h4
                class="field-title"
                style="margin-bottom: 32px; margin-top: 56px"
            >
                Kontaktdaten
            </h4>

            <div class="field-wrapper">
                <div class="field">
                    <TextInput
                        label="E-Mail Adresse*"
                        placeholder="E-Mail"
                        type="email"
                        v-model="form.email"
                        :errorMessage="
                            v$.form.email.$errors.length
                                ? v$.form.email.$errors[0].$message
                                : ''
                        "
                        :error="errors.email"
                        @input="
                            () => {
                                v$.form.email.$touch();
                                delete errors.email;
                            }
                        "
                    />
                </div>

                <div class="field">
                    <div
                        class="label"
                        for="phone-number"
                    >Telefonnummer*</div>
                    <PhoneNumber
                        :dialCode="form.phone_number_prefix"
                        :phoneNumber="form.phone_number"
                        :error="
                            errors.phone_number_prefix || errors.phone_number
                                ? true
                                : false
                        "
                        @updateDialCode="
                            (value) => (form.phone_number_prefix = value)
                        "
                        @updatePhoneNumber="
                            (value) => (form.phone_number = value)
                        "
                        @update="
                            () => {
                                delete errors.phone_number_prefix;
                                delete errors.phone_number;
                            }
                        "
                    />
                </div>

                <div class="field">
                    <div
                        class="label"
                        for="phone-number"
                    >
                        Mobiltelefonnummer
                    </div>
                    <PhoneNumber
                        :dialCode="form.mobile_phone_number_prefix"
                        :phoneNumber="form.mobile_phone_number"
                        :error="
                            errors.mobile_phone_number_prefix ||
                                errors.mobile_phone_number
                                ? true
                                : false
                        "
                        @updateDialCode="
                            (value) => (form.mobile_phone_number_prefix = value)
                        "
                        @updatePhoneNumber="
                            (value) => (form.mobile_phone_number = value)
                        "
                        @update="
                            () => {
                                delete errors.mobile_phone_number_prefix;
                                delete errors.mobile_phone_number;
                            }
                        "
                    />
                </div>
            </div>

            <h4
                class="field-title"
                style="margin-bottom: 32px; margin-top: 56px"
            >
                Weitere Anmeldeinformationen
            </h4>

            <div
                class="field-wrapper"
                style="margin-bottom: 40px"
            >
                <div class="field">
                    <div class="label">Präferierte Spielgruppe*</div>
                    <SingleSelect
                        :options="playgroups"
                        valueKey="id"
                        labelKey="name"
                        v-model="form.spielgruppen_id"
                        placeholder="Spielgruppe auswählen"
                        :error="
                            errors.spielgruppen_id || errors.invalid_playgroup
                        "
                        :disabled="!form.division_id"
                        @input="
                            () => {
                                delete errors.spielgruppen_id;
                                delete errors.invalid_playgroup;
                                this.clearSlots()
                            }
                        "
                    />
                </div>

                <div class="field">
                    <div class="label">Anzahl Spielgruppenbesuche*</div>
                    <SingleSelect
                        :options="[
                            {
                                value: 1,
                                label: '1 x wöchentlich 2 ½ h à CHF 360.- pro Semester',
                            },
                            {
                                value: 2,
                                label: '2 x wöchentlich 2 ½ h à CHF 720.- pro Semester',
                            },
                            {
                                value: 3,
                                label: '3 x wöchentlich 2 ½ h à CHF 1’080.- pro Semester',
                            },
                        ]"
                        v-model="form.semester_id"
                        placeholder="Anzahl auswählen"
                        :error="errors.semester_id || errors.invalid_playgroup"
                        @input="
                            () => {
                                delete errors.semester_id;
                            }
                        "
                    />
                </div>

                <div class="field">
                    <div class="label">Mitgliedschaft</div>
                    <SingleSelect
                        :options="[
                            {
                                value: 1,
                                label: 'Ich trete dem Familienforum bei',
                            },
                            {
                                value: 2,
                                label: 'Ich bin bereits Mitglied des Familien Forums',
                            },
                        ]"
                        v-model="form.membership_id"
                        placeholder="Mitgliedschaft auswählen"
                        :error="errors.membership_id"
                        @input="
                            () => {
                                delete errors.membership_id;
                            }
                        "
                    />
                </div>
            </div>

            <div
                class="openings-table"
                style="margin-bottom: 40px"
            >
                <div class="days">
                    <div class="table-row">
                        <h5
                            class="column"
                            style="width: 25%"
                        ></h5>
                        <h5
                            class="column"
                            style="width: 15%"
                        >Montag</h5>
                        <h5
                            class="column"
                            style="width: 15%"
                        >Dienstag</h5>
                        <h5
                            class="column"
                            style="width: 15%"
                        >Mittwoch</h5>
                        <h5
                            class="column"
                            style="width: 15%"
                        >Donnerstag</h5>
                        <h5
                            class="column"
                            style="width: 15%"
                        >Freitag</h5>
                    </div>
                </div>
                <div
                    class="hours"
                    :class="{ error: errors.openingHours }"
                >
                    <div class="table-row">
                        <div
                            class="column"
                            style="width: 25%"
                        >
                            <template v-if="
                                form.spielgruppen_id &&
                                playgroup &&
                                playgroup.morning_time_range
                            ">
                                Vormittag ({{
                                        playgroup.morning_time_range
                                        | formatTimeRangePlaygroup
                                }}
                                Uhr)
                            </template>
                            <template v-else> Vormittag </template>
                            <!-- Vormittag (08:48 - 11:45 Uhr) -->
                        </div>
                        <div
                            class="column"
                            style="width: 15%"
                            v-for="day in 5"
                            :key="day"
                        >
                            <Checkbox
                                :checked="morning_selection.includes(day)"
                                :disabled="!isHourSelectable(day, 1)"
                                :value="day"
                                @toggle="
                                    () =>
                                        morning_selection.indexOf(day) == -1
                                            ? morning_selection.push(day)
                                            : morning_selection.splice(
                                                morning_selection.indexOf(
                                                    day
                                                ),
                                                1
                                            )
                                "
                            />
                        </div>
                    </div>
                    <div class="table-row">
                        <div
                            class="column"
                            style="width: 25%"
                        >
                            <template v-if="
                                form.spielgruppen_id &&
                                playgroup &&
                                playgroup.afternoon_time_range
                            ">
                                Nachmittag ({{
                                        playgroup.afternoon_time_range
                                        | formatTimeRangePlaygroup
                                }}
                                Uhr)
                            </template>
                            <template v-else> Nachmittag </template>
                            <!-- Nachmittag (14:00 - 16:30 Uhr) -->
                        </div>
                        <div
                            class="column"
                            style="width: 15%"
                            v-for="day in 5"
                            :key="day"
                        >
                            <Checkbox
                                :checked="evening_selection.includes(day)"
                                :disabled="!isHourSelectable(day, 2)"
                                :value="day"
                                @toggle="
                                    () =>
                                        evening_selection.indexOf(day) == -1
                                            ? evening_selection.push(day)
                                            : evening_selection.splice(
                                                evening_selection.indexOf(
                                                    day
                                                ),
                                                1
                                            )
                                "
                            />
                        </div>
                    </div>
                </div>
                <p
                    class="error-text"
                    v-if="errors.invalid_playgroup"
                    style="display: block; margin-left: auto"
                >
                    Für die ausgewählte Spielgruppe sind die gewählten möglichen
                    Halbtage leider ausgebucht.
                </p>
                <p
                    class="error-text"
                    v-if="errors.openingHours"
                    style="display: block; margin-left: auto"
                >
                    Bitte geben Sie mindestens so viele mögliche Halbtage an,
                    die der vorhin gewählten Anzahl Spielgruppenbesuche
                    entsprechen.
                </p>
            </div>

            <div
                class="field-wrapper"
                style="margin-bottom: 48px"
            >
                <div
                    class="field"
                    style="width: 66%"
                >
                    <TextInput
                        label="Bemerkungen Eltern"
                        placeholder="Bemerkungen erfassen"
                        v-model="form.note"
                    />
                </div>
            </div>

            <div class="control">
                <button
                    class="create"
                    @click.prevent="registerChild"
                    type="submit"
                    :disabled="processing"
                >
                    Speichern
                </button>
                <button
                    class="cancel"
                    @click="cancel"
                >Abbrechen</button>
            </div>
        </div>

        <ConfirmationModal />
        <FieldMissing />
    </div>
</template>

<script>
//Components
import TextInput from "./components/Form/TextInput.vue";
import SingleSelect from "./components/Form/SingleSelect.vue";
import SingleSelectWithSearch from "./components/Form/SingleSelectWithSearch.vue";
import PhoneNumber from "./components/Form/PhoneNumber.vue";
import SingleDate from "./components/Form/SingleDate.vue";
import Checkbox from "./components/Checkbox.vue";
import ConfirmationModal from "./components/ConfirmationModal.vue";
import FieldMissing from "./components/FieldMissing.vue";
import SingleDatePicker from "./SingleDatePicker.vue";
import VueGoogleAutocomplete from "vue-google-autocomplete";

//Additional Libraries
import { useVuelidate } from "@vuelidate/core";
import { required, maxLength, helpers, email } from "@vuelidate/validators";

//Api Methods and Libraries
import axios from "../../axios";
import getNationalities from "../../mixins/getNationalitiesMixin";
import { mapGetters } from "vuex";

export default {
    setup() {
        const v$ = useVuelidate();
        return { v$ };
    },

    mixins: [getNationalities],

    computed: {
        ...mapGetters({
            user: "user",
        }),
        playgroups() {
            if (this.playgroupsData.length) {
                return this.playgroupsData;
            } else if (this.user && !this.playgroupLoaded) {
                this.playgroupLoaded = true;
                this.getPlaygroups();
            }
            return [];
        },
    },

    data() {
        return {
            latest_id: 0,
            nationalities: [],
            languages: [],
            playgroupLoaded: false,
            playgroupsData: [],
            morning_selection: [],
            evening_selection: [],
            divisions: [],
            playgroup: null,
            processing: false,
            errors: {},
            form: {
                division: null,
                child_first_name: "",
                child_last_name: "",
                child_birthday: "",
                mother_first_name: "",
                mother_last_name: "",
                father_first_name: "",
                father_last_name: "",
                optional_address: "",
                street: "",
                postal_code: "",
                street_number: "",
                place: "",
                place_id: "",
                email: "",
                phone_number: "",
                phone_number_prefix: 41,
                mobile_phone_number: "",
                mobile_phone_number_prefix: 41,
                gender_id: "",
                nationality_id: "",
                spielgruppen_id: "",
                mother_tongue_id: "",
                semester_id: "",
                membership_id: "",
                division_id: "",
                note: "",
                morningOpeningHours: [],
                afternoonOpeningHours: [],
                session_id: 1,
            },
        };
    },

    filters: {
        formatTimeRangePlaygroup(value) {
            if (value) {
                return value.replace(/-/g, " - ");
            }
            return value;
        },

        leadzero: (value) => {
            return String(value).padStart(5, "0");
        },
    },

    methods: {
        isHourSelectable(day_id, hour_id) {
            if (this.form.spielgruppen_id == 'keine') {
                return true;
            } else if (this.form.spielgruppen_id == '') {
                return false;
            } else if (this.form.spielgruppen_id && this.playgroup) {
                const openingHour = this.playgroup.opening_hours.find(item => item.hour_id == hour_id && item.day_id == day_id)
                return openingHour ? true : false;
            }
            return false;
        },

        clearSlots() {
            this.morning_selection = []
            this.evening_selection = []
        },

        checkLength(e, fieldName, count) {
            delete this.errors.street
            delete this.errors.place_id
            this.$refs.address.$el.classList.remove('error-box')
            if (e.target.value.length > count) {
                this.$refs.address.$el.style.border = "2px solid #E97D32";
                document.getElementById(fieldName + "_error").innerHTML =
                    "Maximal " + count + " Zeichen möglich";
            } else {
                this.$refs.address.$el.style.border = "";
                document.getElementById(fieldName + "_error").innerHTML = "";
            }
        },
        getNewVal(newLocationAddres) {
            if (newLocationAddres == "") {
                this.form.street = "";
                this.form.street_number = "";
                this.form.postal_code = "";
                this.form.place_id = "";
                this.form.city = "";
            }
            this.$refs.address.$data.autocompleteText = this.form.street;
        },

        getFromAddress(from_address, placeResultData, id) {
            console.log(from_address);
            this.form.street = from_address.route;
            this.form.street_number = from_address.street_number;
            this.form.postal_code = from_address.postal_code;
            this.form.place = from_address.locality;
            this.form.place_id = placeResultData.place_id;
        },

        setSingleDate(e) {
            this.form.child_birthday = e.start;
            delete this.errors.child_birthday;
        },
        getLastRegisterdChildren() {
            axios.get("/api/last-inserted-children").then((res) => {
                this.latest_id = parseInt(res.data);
            });
        },

        getDivisions() {
            axios.get(`/api/all-division`).then((res) => {
                this.divisions = res.data;
            });
        },

        getPlaygroups() {
            const params = this.form.division_id
                ? `&division=${this.form.division_id}`
                : `&division=new_or_active`;
            axios
                .get(
                    `/api/spielgruppens/${this.user.company_id}?status=1${params}`
                )
                .then((res) => {
                    res.data.splice(0, 0, { id: "keine", name: "Keine" });
                    this.playgroupsData = res.data;
                });
        },

        getLanguages() {
            axios.get("/api/languages").then((res) => {
                this.languages = res.data.data;
            });
        },

        registerChild() {
            this.processing = true;
            let openingHours = {};
            if (
                this.form.morningOpeningHours.length &&
                this.form.afternoonOpeningHours.length
            ) {
                openingHours = JSON.stringify(
                    this.form.morningOpeningHours.concat(
                        this.form.afternoonOpeningHours
                    )
                );
            } else if (this.form.morningOpeningHours.length) {
                openingHours = JSON.stringify(this.form.morningOpeningHours);
            } else if (this.form.afternoonOpeningHours.length) {
                openingHours = JSON.stringify(this.form.afternoonOpeningHours);
            } else {
                openingHours = "";
            }

            axios
                .post("/api/anmeldungen", { ...this.form, openingHours })
                .then((res) => {
                    this.$router.push("/anmeldungen");
                    EventBus.$emit(
                        "notification-modal:show",
                        "Die Anmeldung wurde erfolgreich erstellt."
                    );
                })
                .catch((err) => {
                    let title = "Fehlende oder ungültige Pflichtangaben";
                    let description =
                        "Bitte füllen Sie alle erforderlichen Felder aus und/oder erfassen Sie für diese gültige Daten gemäss orangen Informationstexten.";

                    if (
                        err.response.data.errors.hasOwnProperty(
                            "invalid_playgroup"
                        )
                    ) {
                        title = undefined;
                        description =
                            "Leider hat es für die von Ihnen gewählte Spielgruppe für Ihre möglichen Halbtage und gewünschte Anzahl Spielgruppenbesuche pro Woche keinen Platz mehr. Bitte wählen Sie eine andere Spielgruppe aus oder wählen “Keine”. Bei “Keine”.";
                    }

                    EventBus.$emit("field-missing-modal:show", {
                        yesClick: () =>
                            EventBus.$emit("field-missing-modal:hide"),
                        title,
                        description,
                    });
                    if (err.response.status == 422) {
                        this.errors = err.response.data.errors;
                        if (this.errors.hasOwnProperty('street') || this.errors.hasOwnProperty('place_id')) {
                            this.$refs.address.$el.classList.add('error-box')
                        }
                    }
                })
                .finally(() => (this.processing = false));
        },

        cancel() {
            const title = "Erstellung der Anmeldung abbrechen?";
            const description =
                "Wenn Sie diesen Vorgang abbrechen, werden alle Daten verworfen. Sind Sie sicher, dass Sie die Erstellung dieser Anmeldung wirklich abbrechen wollen?";
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
    mounted() {
        this.getLastRegisterdChildren();
        this.getLanguages();
        this.getDivisions();
        this.getNationalities(this.nationalities);
    },

    watch: {
        morning_selection() {
            delete this.errors.openingHours;
            this.form.morningOpeningHours = [];
            this.morning_selection.forEach((item) => {
                this.form.morningOpeningHours.push({
                    [item]: 1,
                });
            });
        },

        "form.spielgruppen_id": function (value) {
            if (value !== "keine") {
                axios.get(`/api/spielgruppen/get/${value}`).then((res) => {
                    console.log("test", res.data);
                    this.playgroup = res.data;
                });
            } else {
                this.playgroup = null;
            }
        },
        user() {
            this.getPlaygroups();
        },
        "form.division_id"() {
            console.log(this.user.company_id);
            if (this.user.company_id) {
                this.getPlaygroups();
            }
        },

        evening_selection() {
            delete this.errors.openingHours;
            this.form.afternoonOpeningHours = [];
            this.evening_selection.forEach((item) => {
                this.form.afternoonOpeningHours.push({
                    [item]: 2,
                });
            });
        },
    },

    components: {
        TextInput,
        SingleSelect,
        PhoneNumber,
        SingleDate,
        Checkbox,
        ConfirmationModal,
        FieldMissing,
        SingleDatePicker,
        VueGoogleAutocomplete,
        SingleSelectWithSearch,
    },

    validations() {
        return {
            form: {
                division: {
                    required,
                },
                child_first_name: {
                    maxLength: helpers.withMessage(
                        "Maximal 30 Zeichen möglich",
                        maxLength(30)
                    ),
                },
                child_last_name: {
                    maxLength: helpers.withMessage(
                        "Maximal 30 Zeichen möglich",
                        maxLength(30)
                    ),
                },
                father_first_name: {
                    maxLength: helpers.withMessage(
                        "Maximal 30 Zeichen möglich",
                        maxLength(30)
                    ),
                },
                father_last_name: {
                    maxLength: helpers.withMessage(
                        "Maximal 30 Zeichen möglich",
                        maxLength(30)
                    ),
                },
                mother_first_name: {
                    maxLength: helpers.withMessage(
                        "Maximal 30 Zeichen möglich",
                        maxLength(30)
                    ),
                },
                mother_last_name: {
                    maxLength: helpers.withMessage(
                        "Maximal 30 Zeichen möglich",
                        maxLength(30)
                    ),
                },
                optional_address: {
                    maxLength: helpers.withMessage(
                        "Maximal 30 Zeichen möglich",
                        maxLength(30)
                    ),
                },
                street: {
                    maxLength: helpers.withMessage(
                        "Maximal 30 Zeichen möglich",
                        maxLength(30)
                    ),
                },
                street_number: {
                    maxLength: helpers.withMessage(
                        "Maximal 30 Zeichen möglich",
                        maxLength(30)
                    ),
                },
                postal_code: {
                    maxLength: helpers.withMessage(
                        "Maximal 10 Zeichen möglich",
                        maxLength(10)
                    ),
                },
                place: {
                    maxLength: helpers.withMessage(
                        "Maximal 30 Zeichen möglich",
                        maxLength(30)
                    ),
                },
                email: {
                    email: helpers.withMessage(
                        "Ungültiges E-Mail Format",
                        email
                    ),
                },
            },
        };
    },
};
</script>

<style lang="scss" scoped>
.view {
    margin-top: 40px;
    margin-right: 32px;

    .head {
        background-color: #e9f7fe;
        padding: 24px 24px 0px 24px;

        .view-title {
            font-family: "Ubuntu";
            font-style: normal;
            font-weight: 700;
            font-size: 22px;
            line-height: 25px;
            color: #063f5f;
            margin: 0px;
        }
    }

    .content {
        background-color: #e9f7fe;
        min-height: 100vh;
        padding: 40px 24px 24px 24px;

        .field-title {
            font-family: "Ubuntu";
            font-style: normal;
            font-weight: 700;
            font-size: 18px;
            line-height: 21px;
            color: #063f5f;
            margin: 0px;
        }

        .field-wrapper {
            display: flex;
            gap: 32px;

            .field {
                width: 32%;
                gap: 3%;

                .label {
                    font-family: "Nexa";
                    font-style: normal;
                    font-weight: 700;
                    font-size: 18px;
                    line-height: 27px;
                    color: #16a9b7;
                    margin-bottom: 16px;
                }

                .value {
                    font-family: "Nexa";
                    font-style: normal;
                    font-weight: 700;
                    font-size: 15px;
                    line-height: 23px;
                    color: #2c5b7d;
                    margin-top: 16px;
                }
            }
        }

        .openings-table {
            display: flex;
            flex-direction: column;
            gap: 8px;

            .table-row {
                display: flex;
                align-items: center;
                background-color: white;
                padding: 11px 16px;
            }

            >.days {
                width: 100%;

                .table-row {
                    .column {
                        font-family: "Nexa";
                        font-style: normal;
                        font-weight: 700;
                        font-size: 18px;
                        line-height: 27px;
                        display: flex;
                        align-items: center;
                        color: #16a9b7;
                        margin: 0px;
                    }
                }
            }

            >.hours {
                width: 100%;

                .table-row {
                    border-top: 1px solid #d3e3ec;

                    .column {
                        font-family: "Nexa";
                        font-style: normal;
                        font-weight: 700;
                        font-size: 15px;
                        line-height: 23px;
                        color: #2c5b7d;
                    }
                }

                >.table-row:first-child {
                    border-top: none;
                }
            }

            .error {
                border: 1px solid #e97d32;
            }

            .error-text {
                font-family: "Nexa";
                font-style: italic;
                font-weight: 400;
                font-size: 12px;
                line-height: 18px;
                color: #e97d32;
            }
        }

        .control {
            display: flex;
            gap: 32px;

            .create {
                width: 32%;
                height: 40px;
                background: #e97d32;
                font-family: "Nexa";
                font-style: normal;
                font-weight: 700;
                font-size: 18px;
                line-height: 27px;
                text-align: center;
                color: #ffffff;
            }

            .cancel {
                width: 32%;
                height: 40px;
                background: #f8d3ba;
                font-family: "Nexa";
                font-style: normal;
                font-weight: 700;
                font-size: 18px;
                line-height: 27px;
                text-align: center;
                color: #2c5b7d;
            }
        }
    }

    .inputField {
        width: 100%;
        height: 40px;
        background: #ffffff;
        padding: 15px 40px 14px 24px;
        font-family: "Nexa";
        font-style: normal;
        font-weight: 700;
        font-size: 15px;
        outline: none;
        line-height: 23px;
        color: #2c5b7d;
    }
}

.child-birthday-error {
    font-family: "Nexa";
    font-style: italic;
    font-weight: 400;
    font-size: 12px;
    line-height: 18px;
    text-align: right;

    color: #e97d32;
    margin-top: 5px;
}


.error-text {
    width: 100%;
    margin-top: 8px;
    margin-bottom: 0px;
    font-family: 'Nexa';
    font-style: italic;
    font-weight: 400;
    font-size: 12px;
    line-height: 18px;
    text-align: right;
    color: #E97D32;
}

.error-box {
    border: 1px solid #e97d32;
}
</style>
