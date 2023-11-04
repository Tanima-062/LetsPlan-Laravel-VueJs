<template>
    <div class="benutzer mt-5 mb-30">
        <div
            v-if="user && user.role === 'super_admin'"
            class="flex"
        ></div>
        <div
            v-if="user !== null"
            class="card-panel-home supervisor-card-wrapper new-card"
        >
            <div class="row flex gap-2">
                <span
                    class="prev-page-container flex"
                    style="cursor: pointer; width: 130px"
                    @click="goBackButton"
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
                    <span class="prev-page">Zurück</span></span>
                <div class="user-form-registration">
                    <span class="header-text">Admin bearbeiten</span>
                    <div class="row flex">
                        <div class="col-4 pr-2">
                            <p class="prompt">Erstellungsdatum*</p>
                            <p
                                class="data"
                                v-show="form.created_at !== undefined"
                            >
                                {{ formatDate(form.created_at) }}
                            </p>
                        </div>
                        <div
                            class="col-4 pr-2"
                            style="padding-left: 0"
                        >
                            <p class="prompt">ID*</p>
                            <p class="data">{{ form.prefix_id }}</p>
                        </div>
                        <div
                            class="col-4 pr-2"
                            style="padding-left: 10px"
                        >
                            <p class="prompt">Unternehmen*</p>
                            <Dropdown
                                :itemList="companyList"
                                v-model="form.company_id"
                                id="company"
                                :error="errors.company_id ? true : false"
                                @input="() => {
                                    delete errors.company_id
                                }"
                            />
                        </div>
                    </div>
                    <div class="row flex mt-4">
                        <div class="col-4 pr-2">
                            <p class="prompt">Vorname*</p>
                            <input
                                id="first_name"
                                v-model="form.first_name"
                                class="form-control form-input-bordered "
                                :class="{ error: (v$.form.first_name.$errors.length || errors.first_name) }"
                                style="outline: none;"
                                maxlength="31"
                                placeholder="Vornamen eingeben"
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
                                id="last_name"
                                v-model="form.last_name"
                                class="form-control form-input-bordered "
                                :class="{ error: (v$.form.last_name.$errors.length || errors.last_name) }"
                                style="outline: none;"
                                maxlength="31"
                                placeholder="Nachnamen eingeben"
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

                        <div
                            class="col-4 pr-2"
                            style="padding-left: 0"
                        >
                            <p class="prompt">E-Mail Adresse*</p>
                            <input
                                id="email"
                                v-model="form.email"
                                style="outline: none;"
                                class="form-control form-input-bordered"
                                :class="{ error: (errors.email || v$.form.email.$errors.length) }"
                                type="email"
                                placeholder="E-Mail eingeben"
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
                        @click="updateCompany"
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
            <div class="row flex justify-start items-center my-4 mx-0">
                <modal
                    :show="showConfirmModal"
                    @close="showConfirmModal = false"
                >
                    <template #header>
                        <h3>Erstellung des Unternehmens abbrechen?</h3>
                    </template>
                    <template #body>
                        <span class="cancel-text">Wenn Sie diesen Vorgang abbrechen, werden alle
                            Daten verworfen. Sind Sie sicher, dass Sie die
                            Erstellung dieses Unternehmens wirklich abbrechen
                            wollen?</span>
                    </template>
                    <template #footer>
                        <button
                            class="modal-default-button"
                            @click="showCancelModal = false"
                        >
                            OK
                        </button>
                    </template>
                </modal>

                <modal
                    :show="showCancelModal"
                    @close="showCancelModal = false"
                >
                    <template #header>
                        <h3 style="margin: auto">Änderungen verwerfen?</h3>
                    </template>
                    <template #body>
                        <span class="cancel-text">Wenn Sie zurückgehen oder abbrechen, ohne zu
                            speichern, werden alle Änderungen verworfen. Sind
                            Sie sicher, dass Sie die Änderungen wirklich
                            verwerfen wollen?</span>
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
                        <h3 style="margin-left: 100px">
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
                            style="margin-left: 120px"
                            @click="showErrorModal = false"
                        >
                            OK
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
    </div>
</template>

<script>
import axios from "../../axios";
import flushMessagesMixin from "../../mixins/flushMessagesMixin";
import moment from "moment";
import Modal from "./Modal.vue";
import Dropdown from "./Components/Dropdown.vue";
import { useVuelidate } from "@vuelidate/core";
import { required, maxLength, helpers, email } from "@vuelidate/validators";

export default {
    name: "EditAdminuser",
    mixins: [flushMessagesMixin],

    setup() {
        const v$ = useVuelidate();
        return { v$ };
    },

    components: {
        Modal,
        Dropdown,
    },
    data() {
        return {
            form: {
                created_at: "",
                company_id: "",
                first_name: "",
                last_name: "",
                email: "",
                prefix_id: "",
            },
            emailValidated: "",
            user: null,
            showMissingModal: false,
            showConfirmModal: false,
            showErrorModal: false,
            showCancelModal: false,
            errors: {},
            initialForm: {},
            companyList: [],
            hasAnythingChanged: false,
            firstNameValidated: true,
            lastNameValidated: true,
        };
    },
    mounted() {
        this.getAdminuser();
        this.getCompanies();
    },

    methods: {
        formatDate(value) {
            return moment(value).format("DD.MM.YYYY");
        },

        getCompanies() {
            axios.get("/api/company-lists").then((res) => {
                this.companyList = res.data;
            });
        },

        async getAdminuser() {
            let user_id = parseInt(this.$route.params.id);
            await axios.get(`/api/adminuser/${user_id}`).then((res) => {
                this.user = res.data[0];
            });
            this.form.created_at = this.user.created_at;
            this.form.prefix_id = this.user.prefix_id;
            this.form.first_name = this.user.first_name;
            this.form.last_name = this.user.last_name;
            this.form.company_id = this.user.company_id;
            this.form.email = this.user.email;

            this.initialForm = structuredClone(this.form);
        },

        updateCompany() {
            axios
                .post(
                    `/api/adminuser/update/${this.$route.params.id}`,
                    this.form
                )
                .then((res) => {
                    console.log(res);
                    this.successMessage(res.data);

                    this.$router.push({
                        name: "adminuser",
                    });
                })
                .catch((err) => {
                    this.errors = err.response.data.errors;
                    this.showMissingModal = true
                });
        },

        goBack() {
            this.$router.push(`/adminuser`);
        },

        goBackButton() {
            if (this.hasAnythingChanged === true) {
                this.showCancelModal = true;
            } else {
                this.$router.push({
                    name: "company",
                });
            }
        },
    },

    watch: {
        form: {
            handler(curr) {
                curr = JSON.stringify(curr);
                const initial = JSON.stringify(this.initialForm);
                this.hasAnythingChanged = !(curr == initial);
            },
            deep: true,
        },

        initialForm: {
            handler(curr) {
                curr = JSON.stringify(curr);
                const initial = JSON.stringify(this.initialForm);
                this.hasAnythingChanged = !(curr == initial);
            },
            deep: true,
        },
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

.data {
    font-family: "Nexa" !important;
    font-style: normal !important;
    font-weight: 700 !important;
    font-size: 15px !important;
    line-height: 23px !important;
    color: #2c5b7d !important;
}

.btn-primary {
    width: 200px;
    height: 40px;
    font-family: "Nexa", sans-serif;
    font-size: 18px;
    line-height: 27px;
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
    text-align: left;
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

.new-card {
    background: #e9f7fe;
    border-radius: 0;
    margin-bottom: 80px;
}

.button-group,
.button-save {
    background: #e97d32 !important;
    border-radius: 0;
}

.prev-page {
    font-family: "Nexa", sans-serif;
    font-size: 18px;
    line-height: 27px;
    color: #2c5b7d;
    font-weight: 700;
}

.button-clear {
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 18px;

    height: 40px;
    background: #f8d3ba !important;
    // margin-left: 70px;
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
    position: absolute;
    width: 248px;
    height: 43px;
    left: calc(50% - 248px / 2 - 140px);
    top: calc(50% - 43px / 2 + 66.5px);
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 18px;
    line-height: 27px;
    text-align: center;
    color: #ffffff;
    background: #16a9b7;
}

.modal-secondary-button {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    padding: 8px 96px;
    gap: 10px;
    position: absolute;
    width: 248px;
    height: 43px;
    left: calc(50% - 248px / 2 + 140px);
    top: calc(50% - 43px / 2 + 66.5px);
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 18px;
    line-height: 27px;
    text-align: center;
    /* 2/5 */
    color: #19849e;
    background: #c6e9f2;
}

.user-form-registration .form-control {
    height: 40px;
    font-family: "Nexa", sans-serif;
    color: #2c5b7d;
    font-size: 15px;
}

.form-control::placeholder {
    font-family: "Nexa", sans-serif;
    color: #2c5b7d;
    font-size: 15px;
}

#dropdownStatusButton {
    color: #063f5f;
    background: transparent;
    border: transparent;
}

.modal-default-button {
    float: right;
}

.flex.row.mt-4 {
    -bs-gutter-x: 1.5rem;
    --bs-gutter-x: 4.5rem;
}

.form-control:focus {
    outline: 0;
    box-shadow: none !important;
}

.form-control {
    border: none;
    background: white !important;
    font-family: "Nexa";
    font-style: normal !important;
    font-weight: 700 !important;
    font-size: 15px !important;
    line-height: 23px !important;
    color: #2c5b7d !important;
}

.card-panel-home {
    border: none;
}

.header-text {
    // margin-top: 0;
    margin-bottom: 40px;
}
.form-input-bordered {
    border-radius: 0px !important;
    // border: none !important;
}
.error {
    border: 1px solid rgb(233, 125, 50);
}
</style>
