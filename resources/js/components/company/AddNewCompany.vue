<template>
    <div class="supervisor-card-wrapper new-card">
        <div class="row flex gap-2">
            <div class="user-form-registration">
                <span class="header-text">Neues Unternehmen hinzufügen</span>
                <div class="row flex">
                    <div class="col-4 pr-2">
                        <p class="prompt">ID*</p>
                        <p class="unique_id">{{ form.uniq_id }}</p>
                    </div>
                    <div
                        class="col-4 pr-2"
                        style="padding-left: 0"
                    >
                        <p class="prompt">Name*</p>
                        <input
                            v-model="form.name"
                            class="form-input-bordered form-control"
                            :class="{ error: (v$.form.name.$errors.length || errors.name) }"
                            maxlength="41"
                            placeholder="Unternehmensnamen eingeben"
                            id="name"
                            @input="() => {
                                v$.form.name.$touch()
                                delete errors.name
                            }"
                        />
                        <div
                            v-if="v$.form.name.$errors.length"
                            class="error-msg"
                        >
                            Maximal 40 Zeichen möglich
                        </div>
                    </div>
                </div>
                <span class="sub-header-text">Kontaktperson</span>
                <div class="flex row mt-4">
                    <div class="col-4 pr-2">
                        <p class="prompt">Vorname*</p>
                        <input
                            v-model="form.first_name"
                            class="form-control form-input-bordered"
                            :class="{ error: (v$.form.first_name.$errors.length || errors.first_name) }"
                            maxlength="31"
                            placeholder="Vornamen eingeben"
                            id="first_name"
                            @input="() => {
                                v$.form.first_name.$touch()
                                delete errors.first_name
                            }"
                        />
                        <div
                            v-if="v$.form.first_name.$errors.length"
                            class="error-msg"
                        >
                            Maximal 30 Zeichen möglich
                        </div>
                    </div>
                    <div
                        class="col-4 pr-2"
                        style="padding-left: 0"
                    >
                        <p class="prompt">Nachname*</p>
                        <input
                            v-model="form.last_name"
                            class="form-control form-input-bordered"
                            :class="{ error: (v$.form.last_name.$errors.length || errors.last_name) }"
                            maxlength="31"
                            placeholder="Nachnamen eingeben"
                            id="last_name"
                            @input="() => {
                                v$.form.last_name.$touch()
                                delete errors.last_name
                            }"
                        />
                        <div
                            v-if="v$.form.last_name.$errors.length"
                            class="error-msg"
                        >
                            Maximal 30 Zeichen möglich
                        </div>
                    </div>
                </div>

                <div class="flex row mt-4">
                    <div class="col-4 pr-2">
                        <p class="prompt">Telefonnummer</p>
                        <PhoneNumber
                            :dialCode="form.country_code"
                            :phoneNumber="form.telephone"
                            :error="
                                errors.country_code || errors.telephone
                                    ? true
                                    : false
                            "
                            @updateDialCode="
                                (value) => (form.country_code = value)
                            "
                            @updatePhoneNumber="
                                (value) => (form.telephone = value)
                            "
                            @update="
                                () => {
                                    delete errors.country_code;
                                    delete errors.telephone;
                                }
                            "
                        />
                    </div>

                    <div
                        class="col-4 pr-2"
                        style="padding-left: 0"
                    >
                        <p class="prompt">E-Mail Adresse*</p>
                        <input
                            v-model="form.email"
                            class="form-control form-input-bordered"
                            :class="{error: (errors.email || v$.form.email.$errors.length)}"
                            type="text"
                            placeholder="E-Mail eingeben"
                            id="email"
                            @blur="v$.form.email.$touch"
                            @input="() => {
                                delete errors.email
                            }"
                        />
                        <div
                            v-if="v$.form.email.$errors.length"
                            class="error-msg"
                        >
                            Ungültiges E-Mail Format
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row flex mt-4">
            <div class="col-4 pr-2">
                <button
                    @click="create"
                    class="btn-primary button-save"
                    style="width: 100%"
                >
                    Speichern
                </button>
            </div>
            <div
                class="col-4 pr-2"
                style="padding-left: 0"
            >
                <button
                    @click="showCancelModal = true"
                    class="btn-primary button-clear"
                    style="width: 100%"
                >
                    Abbrechen
                </button>
            </div>
        </div>
        <div
            class="row flex justify-start items-center"
            style="margin-top: 30px"
        >
            <modal
                :show="showCancelModal"
                @close="showCancelModal = false"
            >
                <template #header>
                    <h3 style="margin: auto">
                        Erstellung des Unternehmens abbrechen?
                    </h3>
                </template>
                <template #body>
                    <span class="cancel-text">Wenn Sie diesen Vorgang abbrechen, werden alle Daten
                        verworfen. Sind Sie sicher, dass Sie die Erstellung
                        dieses Unternehmens wirklich abbrechen wollen?</span>
                </template>
                <template #footer>
                    <button
                        class="modal-primary-button"
                        @click="goBack"
                    >
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

            <modal
                :show="showErrorModal"
                @close="showErrorModal = false"
            >
                <template #header>
                    <h3 style="margin: auto">
                        Fehlende oder ungültige Pflichtangaben
                    </h3>
                </template>
                <template #body>
                    <span class="cancel-text">Bitte füllen Sie alle erforderlichen Felder aus
                        und/oder erfassen Sie für diese gültige Daten gemäss
                        orangen Informationstexten.</span>
                </template>
                <template #footer>
                    <div class="d-grid gap-4">
                        <button
                            class="modal-primary-button"
                            style="margin-left: 120px"
                            @click="showErrorModal = false"
                        >
                            OK
                        </button>
                    </div>
                </template>
            </modal>

            <!-- show missing modal -->
            <modal :show="showMissingModal">
                <template #header>
                    <h3 style="margin: auto">
                        Fehlende oder ungültige Pflichtangaben
                    </h3>
                </template>
                <template #body>
                    <span class="cancel-text">Bitte füllen Sie alle erforderlichen Felder aus
                        und/oder erfassen Sie für diese gültige Daten gemäss
                        orangen Informationstexten.</span>
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
        <div class="row flex justify-end items-center"></div>
    </div>
</template>

<script>
import { codes } from "../../../contryCodes";

import moment from "moment";
import Modal from "./Modal.vue";
import PhoneNumber from "./PhoneNumber.vue";
import axios from "../../axios";
import flushMessagesMixin from "../../mixins/flushMessagesMixin";
import { useVuelidate } from "@vuelidate/core";
import { required, maxLength, helpers, email } from "@vuelidate/validators";

export default {
    name: "CreateCompany",

    setup() {
        const v$ = useVuelidate();
        return { v$ };
    },

    components: {
        Modal,
        PhoneNumber,
    },
    mixins: [flushMessagesMixin],
    data() {
        return {
            form: {
                name: "",
                first_name: "",
                last_name: "",
                country_code: 41,
                telephone: "",
                email: "",
                uniq_id: "",
            },
            emailValidated: true,
            country_codes: [],
            errors: {},
            showCancelModal: false,
            showErrorModal: false,
            showMissingModal: false,
            firstNameValidated: true,
            lastNameValidated: true,
        };
    },
    mounted() {
        this.getCountryCodes();
    },
    async created() {
        try {
            const res = await axios.get(`/api/company-get-max`);
            this.form.uniq_id = res.data;
        } catch (error) {
            console.log(error);
        }
    },

    computed: {},

    methods: {

        getCountryCodes() {
            codes.map((item) => {
                this.country_codes.push(item.dial_code);
            });
        },
        create() {

            axios
                .post("/api/company", this.form)
                .then((res) => {
                    this.successMessage(res.data);
                    this.errors = [];
                    this.$router.push({ name: "company" });
                })
                .catch((err) => {
                    this.errors = err.response.data.errors;
                    this.showMissingModal = true;
                });
        },

        goBack() {
            this.$router.push("/company");
        },
    },

    validations() {
        return {
            form: {
                name: {
                    maxLength: helpers.withMessage(
                        "Maximal 40 Zeichen möglich",
                        maxLength(40)
                    ),
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
    margin-bottom: 40px;
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

.sub-header-text {
    font-family: "Ubuntu";
    font-style: normal;
    font-weight: 700;
    font-size: 18px;
    line-height: 21px;
    display: flex;
    align-items: center;
    color: #063f5f !important;
    margin-top: 50px;
    margin-bottom: 20px;
}

.form-input-bordered {
    border-radius: 0px !important;
    border: none;
    width: 100%;
    padding: 10px;
    outline: none;
    height: 50px;
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 15px;
    line-height: 23px;
    color: #2c5b7d;

    &:focus {
        outline: none;
        box-shadow: none;
    }
}

.form-input-bordered::placeholder {
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 15px;
    line-height: 23px;
    color: #87b1cb;
}

.new-card {
    background: #e9f7fe;
    border-radius: 0;
    margin-bottom: 80px;
    font-family: "Nexa";
}

.button-save:hover {
    background: #e97d32 !important;
    cursor: pointer;
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
    color: #e3e9eb;
    position: absolute;
    width: 248px;
    height: 43px;
    left: calc(50% - 248px / 2 - 140px);
    top: calc(50% - 43px / 2 + 66.5px);
    background: #16a9b7;
    /* Nexa Bold / 18 */

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
    gap: 10px;
    color: w#E3E9EB;
    position: absolute;
    width: 248px;
    height: 43px;
    left: calc(50% - 248px / 2 + 140px);
    top: calc(50% - 43px / 2 + 66.5px);
    background: #c6e9f2;
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 18px;
    line-height: 27px;
    text-align: center;

    /* 2/5 */

    color: #19849e;
}

// .user-form-registration {
//     padding-left: 25px;
// }
.user-form-registration .form-control {
    height: 40px;
    font-family: "Nexa", sans-serif;
    color: #2c5b7d;
    font-size: 15px;
    background: #fff !important;
}

#dropdownStatusButton {
    color: #063f5f;
    background: transparent;
    border: transparent;
}

.unique_id {
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 15px !important;
    line-height: 23px !important;
    color: #2c5b7d !important;
}

.row {
    --bs-gutter-x: 4.5rem;
}

.error {
    border: 1px solid rgb(233, 125, 50);
}
</style>
