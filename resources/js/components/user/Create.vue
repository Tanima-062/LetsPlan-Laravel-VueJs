<template>
    <div class="view">
        <div class="head">
            <h4 class="view-title">Neuer Benutzer hinzufügen</h4>
        </div>
        <div class="content">
            <div class="field-wrapper" style="margin-bottom: 40px">
                <div class="field image-box">
                    <input
                        class="invisible"
                        style="width: 0px; height: 0px; position: absolute"
                        type="file"
                        accept=".jpg, .png"
                        ref="image_input"
                        @input="
                            (e) =>
                                e.target.files[0]
                                    ? (form.image = e.target.files[0])
                                    : null
                        "
                    />
                    <ImageBox
                        @uploadImage="$refs.image_input.click()"
                        v-if="!user_image"
                    />
                    <img
                        style="width: 104px; height: 104px"
                        :src="user_image"
                        v-if="user_image"
                    />
                    <div class="image-control">
                        <div
                            class="label"
                            style="margin-bottom: 0px; margin-top: -4px"
                        >
                            Profilbild
                        </div>
                        <button
                            v-if="user_image"
                            @click="$refs.image_input.click()"
                        >
                            <UploadIcon /> Bild ändern
                        </button>
                        <button v-if="user_image" @click="deleteImage">
                            <TrashIcon /> Bild entfernen
                        </button>
                    </div>
                </div>

                <div class="field">
                    <div class="label">Benutzer-ID*</div>
                    <div class="value">{{ form.latest_id }}</div>
                </div>
                <div class="field">
                    <div class="label">Benutzertyp*</div>
                    <SingleSelect
                        placeholder="Auswählen"
                        :options="[
                            { label: 'Spielgruppenleiter/in', value: 2 },
                            { label: 'Spielgruppen Assistent/in', value: 3 },
                        ]"
                        v-model="form.role_id"
                        :error="errors.role_id"
                        @input="
                            () => {
                                delete errors.role_id;
                            }
                        "
                    />
                </div>
            </div>

            <div class="field-wrapper" style="margin-bottom: 40px">
                <div class="field">
                    <TextInput
                        label="Vorname*"
                        placeholder="Vornamen eingeben"
                        maxLength="31"
                        v-model="form.first_name"
                        :errorMessage="
                            v$.form.first_name.$errors.length
                                ? v$.form.first_name.$errors[0].$message
                                : ''
                        "
                        :error="errors.first_name"
                        @input="
                            () => {
                                v$.form.first_name.$touch();
                                delete errors.first_name;
                            }
                        "
                    />
                </div>

                <div class="field">
                    <TextInput
                        label="Nachname*"
                        placeholder="Nachnamen eingeben"
                        maxLength="31"
                        v-model="form.last_name"
                        :errorMessage="
                            v$.form.last_name.$errors.length
                                ? v$.form.last_name.$errors[0].$message
                                : ''
                        "
                        :error="errors.last_name"
                        @input="
                            () => {
                                v$.form.last_name.$touch();
                                delete errors.last_name;
                            }
                        "
                    />
                </div>
            </div>

            <div class="field-wrapper" style="margin-bottom: 40px">
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
                        <label class="text-danger" id="street_error"></label>
                    </div>
                </div>

                <div class="field">
                    <TextInput
                        label="Hausnummer*"
                        placeholder="0000"
                        maxLength="31"
                        v-model="form.house_number"
                        :errorMessage="
                            v$.form.house_number.$errors.length
                                ? v$.form.house_number.$errors[0].$message
                                : ''
                        "
                        :error="errors.house_number"
                        @input="
                            () => {
                                v$.form.house_number.$touch();
                                delete errors.house_number;
                            }
                        "
                    />
                </div>

                <div class="field">
                    <TextInput
                        label="Postleitzahl*"
                        placeholder="0000"
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
            </div>

            <div class="field-wrapper" style="margin-bottom: 48px">
                <div class="field">
                    <TextInput
                        label="Ort*"
                        placeholder="Ort"
                        maxLength="31"
                        v-model="form.location"
                        :errorMessage="
                            v$.form.location.$errors.length
                                ? v$.form.location.$errors[0].$message
                                : ''
                        "
                        :error="errors.location"
                        @input="
                            () => {
                                v$.form.location.$touch();
                                delete errors.location;
                            }
                        "
                    />
                </div>

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
                    <div class="label" for="phone-number">Telefonnummer*</div>
                    <PhoneNumber
                        :dialCode="form.prefix"
                        :phoneNumber="form.phone_number"
                        :error="
                            errors.prefix || errors.phone_number ? true : false
                        "
                        @updateDialCode="(value) => (form.prefix = value)"
                        @updatePhoneNumber="
                            (value) => (form.phone_number = value)
                        "
                        @update="
                            () => {
                                delete errors.prefix;
                                delete errors.phone_number;
                            }
                        "
                    />
                </div>
            </div>

            <div class="control">
                <button class="create" @click.prevent="register" type="submit">
                    Speichern und Einladung senden
                </button>
                <button class="cancel" @click="cancel">Abbrechen</button>
            </div>
        </div>

        <ConfirmationModal />
        <FieldMissing />
    </div>
</template>

<script>
//Components
import TextInput from "./Components/Form/TextInput.vue";
import SingleSelect from "./Components/Form/SingleSelect.vue";
import PhoneNumber from "./Components/Form/PhoneNumber.vue";
import ConfirmationModal from "./Components/ConfirmationModal.vue";
import FieldMissing from "./Components/FieldMissing.vue";
import PlaceInput from "./Components/Form/PlaceInput.vue";
import VueGoogleAutocomplete from "vue-google-autocomplete";

//Additional Libraries
import { useVuelidate } from "@vuelidate/core";
import { required, maxLength, helpers, email } from "@vuelidate/validators";

//Api Methods and Libraries
import axios from "../../axios";
import getNationalities from "../../mixins/getNationalitiesMixin";
import ImageBox from "./Components/Icons/ImageBox.vue";
import UploadIcon from "./Components/Icons/UploadIcon.vue";
import TrashIcon from "./Components/Icons/TrashIcon.vue";

export default {
    setup() {
        const v$ = useVuelidate();
        return { v$ };
    },

    mixins: [getNationalities],

    computed: {
        user() {
            return this.$store.state.user;
        },
    },

    data() {
        return {
            languages: [],
            proccessing: false,
            errors: {},
            form: {
                latest_id: null,
                first_name: "",
                last_name: "",
                street: "",
                street_name: "",
                image: undefined,
                postal_code: "",
                house_number: "",
                location: "",
                email: "",
                phone_number: "",
                role_id: "",
                prefix: 41,
            },
        };
    },

    computed: {
        user_image() {
            if (typeof this.form.image == "object") {
                return URL.createObjectURL(this.form.image);
            }
            if (typeof this.form.image == "string") {
                return this.form.image;
            }
            return undefined;
        },
    },

    filters: {
        leadzero: (value) => {
            return String(value).padStart(5, "0");
        },
    },

    methods: {
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
        getNewVal(newLocationAddres) {
            if (newLocationAddres == "") {
                this.form.street = "";
                this.form.street_name = "";
                this.form.postal_code = "";
                this.form.place_id = "";
                this.form.city = "";
            }
            this.$refs.address.$data.autocompleteText = this.form.street;
        },

        getFromAddress(from_address, placeResultData, id) {
            console.log(from_address);
            this.form.street = from_address.route;
            this.form.street_name = from_address.route;
            this.form.postal_code = from_address.postal_code;
            this.form.location = from_address.locality;
            this.form.place_id = placeResultData.place_id;

            this.form.house_number = from_address.street_number
        },
        getLanguages() {
            axios.get("/api/languages").then((res) => {
                this.languages = res.data.data;
            });
        },

        deleteImage() {
            if (typeof this.form.image == "object") {
                URL.revokeObjectURL(this.user_image);
                this.form.image = undefined;
            }
        },

        getLatestId() {
            axios.get("/api/benutzer/last-user-id").then((res) => {
                this.form.latest_id = res.data;
            });
        },

        updateAddress(place) {
            this.form.street = place.address;
            this.form.postal_code = place.postal_code || "";
            this.form.location = place.locality || "";
        },

        register() {
            this.proccessing = true;

            const form = new FormData();
            for (const key in this.form) {
                if (key == "image" && !this.form[key]) {
                    continue;
                }
                form.append(key, this.form[key]);
            }

            axios
                .post("/api/benutzer", form)
                .then((res) => {
                    this.$router.push("/user");
                    EventBus.$emit(
                        "notification-modal:show",
                        "Der Benutzer wurde erfolgreich erstellt und die E-Mail Einladung gesendet."
                    );
                })
                .catch((err) => {
                    const title = "Fehlende oder ungültige Pflichtangaben";
                    const description =
                        "Bitte füllen Sie alle erforderlichen Felder aus und/oder erfassen Sie für diese gültige Daten gemäss orangen Informationstexten.";
                    EventBus.$emit("field-missing-modal:show", {
                        yesClick: () =>
                            EventBus.$emit("field-missing-modal:hide"),
                        title,
                        description,
                    });
                    if (err.response.status == 422) {
                        this.errors = err.response.data.errors;
                    }
                });
        },

        cancel() {
            const title = "Erstellung des Benutzers abbrechen?";
            const description =
                "Wenn Sie diesen Vorgang abbrechen, werden alle Daten verworfen. Sind Sie sicher, dass Sie die Erstellung dieses Benutzers wirklich abbrechen wollen?";
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
        this.getLanguages();
        this.getLatestId();
    },

    components: {
        TextInput,
        SingleSelect,
        PhoneNumber,
        ConfirmationModal,
        FieldMissing,
        ImageBox,
        UploadIcon,
        TrashIcon,
        PlaceInput,
        VueGoogleAutocomplete,
    },

    validations() {
        return {
            form: {
                division: {
                    required,
                },
                first_name: {
                    maxLength: helpers.withMessage(
                        "Maximal 30 Zeichen möglich",
                        maxLength(30)
                    ),
                },
                last_name: {
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
                house_number: {
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
                location: {
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

            > .image-box {
                display: flex;
                position: relative;

                > svg {
                    cursor: pointer;
                }

                > .image-control {
                    display: flex;
                    flex-direction: column;
                    gap: 18px;

                    > button {
                        display: flex;
                        gap: 20px;
                        font-family: "Nexa";
                        font-style: normal;
                        font-weight: 700;
                        font-size: 15px;
                        line-height: 23px;
                        color: #2c5b7d;
                    }
                }
            }

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
}
</style>
