<template>
    <div class="view">
        <div class="head">
            <div class="mb-4">
                <Back :showModal="isDirty" style="cursor: pointer" />
            </div>
            <h4 class="view-title">Benutzer bearbeiten</h4>
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
                    <div class="value">{{ latest_id }}</div>
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
                    <PlaceInput
                        label="Strasse*"
                        placeholder="Strasse"
                        :error="errors.street_name"
                        :value="form.street_name"
                        @placeChanged="updateAddress"
                    />
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
                                delete errors.email;
                            }
                        "
                        @blur="() => v$.form.email.$touch()"
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
                <button
                    class="create"
                    @click.prevent="register"
                    type="submit"
                    :disabled="processing"
                >
                    Speichern
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

//Additional Libraries
import { useVuelidate } from "@vuelidate/core";
import { required, maxLength, helpers, email } from "@vuelidate/validators";

//Api Methods and Libraries
import axios from "../../axios";
import getNationalities from "../../mixins/getNationalitiesMixin";
import ImageBox from "./Components/Icons/ImageBox.vue";
import UploadIcon from "./Components/Icons/UploadIcon.vue";
import TrashIcon from "./Components/Icons/TrashIcon.vue";
import Back from "./Components/Back.vue";
import PlaceInput from "./Components/Form/PlaceInput.vue";
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
            latest_id: 0,
            languages: [],
            proccessing: false,
            errors: {},
            user: {},
            initialForm: {},
            isDirty: false,
            processing: false,
            form: {
                first_name: "",
                last_name: "",
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
            hasAnythingChanged: false,
        };
    },

    computed: {
        user_image() {
            if (this.form.image && typeof this.form.image == "object") {
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
        getLanguages() {
            axios.get("/api/languages").then((res) => {
                this.languages = res.data.data;
            });
        },

        deleteImage() {
            if (this.form.image && typeof this.form.image == "object") {
                URL.revokeObjectURL(this.user_image);
                this.form.image = undefined;
            }

            if (
                typeof this.form.image == "string" &&
                this.form.image == this.user.image
            ) {
                axios.delete(`/api/benutzer/delete-image/${this.user.id}`);
                this.form.image = undefined;
            }
        },

        getUser() {
            axios.get(`/api/benutzer/${this.$route.params.id}`).then((res) => {
                const data = res.data.data;
                this.latest_id = data.prefix_id;
                this.form = {
                    first_name: data.first_name,
                    last_name: data.last_name,
                    street_name: data.street_name,
                    image: data.image,
                    postal_code: data.postal_code,
                    house_number: data.house_number,
                    location: data.location,
                    email: data.email,
                    phone_number: data.phone_number,
                    prefix: parseInt(data.prefix) || 41,
                    role_id: data.role_id,
                };
                this.user = data;
                this.initialForm = structuredClone(this.form);
            });
        },

        updateAddress(place) {
            this.form.street_name = place.route || '';
            this.form.postal_code = place.postal_code || "";
            this.form.location = place.locality || "";
            this.form.house_number = place.street_number || "";

            console.log(place)
        },

        register() {
            this.proccessing = true;
            const form = new FormData();
            for (const key in this.form) {
                if (
                    key == "image" &&
                    (typeof this.form[key] == "string" || !this.form[key])
                ) {
                    continue;
                }

                form.append(key, this.form[key]);
            }

            axios
                .post(`/api/benutzer/update/${this.user.id}`, form)
                .then((res) => {
                    this.$router.push("/user");
                    EventBus.$emit(
                        "notification-modal:show",
                        `Der Benutzer “${res.data.data.first_name} ${res.data.data.last_name}” wurde erfolgreich aktualisiert.`
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
                })
                .finally(() => (this.processing = false));
        },

        cancel() {
            const title = "Änderungen verwerfen?";
            const description =
                "Wenn Sie zurückgehen oder abbrechen, ohne zu speichern, werden alle Änderungen verworfen. Sind Sie sicher, dass Sie die Änderungen wirklich verwerfen wollen?";
            EventBus.$emit("confirmation-modal:show", {
                title,
                description,
                noClick: () => EventBus.$emit("confirmation-modal:hide"),
                yesClick: () => {
                    EventBus.$emit("confirmation-modal:hide");
                    this.$router.push("/user");
                },
            });
        },
        goBack() {
            this.$router.push(`/user`);
        },
    },
    mounted() {
        this.getLanguages();
        this.getUser();
    },

    watch: {
        form: {
            handler(curr) {
                curr = JSON.stringify(curr);
                const initial = JSON.stringify(this.initialForm);
                this.isDirty = !(curr == initial);
            },
            deep: true,
        },

        initialForm: {
            handler(curr) {
                curr = JSON.stringify(curr);
                const initial = JSON.stringify(this.initialForm);
                this.isDirty = !(curr == initial);
            },
            deep: true,
        },
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
        Back,
        PlaceInput,
    },

    validations() {
        return {
            form: {
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
.prev-page {
    font-family: "Nexa", sans-serif;
    font-size: 18px;
    line-height: 27px;
    color: #2c5b7d;
    font-weight: 700;
    margin-bottom: 25px;
}

.prev-page:hover {
    cursor: pointer;
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
