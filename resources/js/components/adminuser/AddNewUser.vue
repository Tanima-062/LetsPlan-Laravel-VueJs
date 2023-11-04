<template>
    <div class="supervisor-card-wrapper new-card">
        <div class="row flex gap-2">
            <div class="user-form-registration">
                <span class="header-text">Neuer Admin hinzufügen</span>
                <div class="flex row" style="--bs-gutter-x: 4.5rem">
                    <div class="col-4 pr-2" style="padding-right: 0">
                        <p class="prompt">ID*</p>
                        <p class="unique_id">{{ form.prefix_id }}</p>
                        <label
                            v-if="errors && errors['uniq_id']"
                            class="text-danger"
                            >{{ errors["uniq_id"][0] }}</label
                        >
                    </div>
                    <div class="col-4 pr-2" style="padding-left: 0">
                        <p class="prompt">Unternehmen*</p>

                        <Dropdown
                            :itemList="companyList"
                            v-model="form.company_id"
                            id="company"
                        />
                        <label
                            v-if="errors && errors['company_id']"
                            class="text-danger"
                            >{{ errors["company_id"][0] }}</label
                        >
                    </div>
                </div>
                <div class="flex row mt-4">
                    <div class="col-4 pr-2">
                        <p class="prompt">Vorname*</p>
                        <input
                            v-model="form.first_name"
                            class="form-control form-input-bordered"
                            maxlength="31"
                            placeholder="Vornamen eingeben"
                            id="first_name"
                            @keyup="checkFieldLength('first_name')"
                        />
                        <div
                            v-if="form.first_name.length >= 31"
                            class="error-msg"
                        >
                            Maximal 30 Zeichen möglich
                        </div>
                    </div>
                    <div class="col-4 pr-2 pl-0" style="padding-left: 0">
                        <p class="prompt">Nachname*</p>
                        <input
                            v-model="form.last_name"
                            class="form-control form-input-bordered"
                            maxlength="31"
                            placeholder="Nachnamen eingeben"
                            id="last_name"
                            @keyup="checkFieldLength('last_name')"
                        />
                        <div
                            v-if="form.last_name.length >= 31"
                            class="error-msg"
                        >
                            Maximal 30 Zeichen möglich
                        </div>
                    </div>

                    <div class="col-4 pr-2" style="padding-left: 0">
                        <p class="prompt">E-Mail Adresse*</p>
                        <input
                            v-model="form.email"
                            class="form-control form-input-bordered"
                            type="email"
                            placeholder="E-Mail eingeben"
                            id="email"
                            @input="removeBorder('email')"
                        />
                        <div v-if="emailValidated === false" class="error-msg">
                            Ungültiges E-Mail Format
                        </div>
                    </div>
                </div>
                <div class="flex row mt-4">
                    <div class="col-4 pr-2">
                        <button
                            @click="createAdmin"
                            class="btn-primary button-save"
                            style="width: 100%"
                        >
                            Speichern und Einladung senden
                        </button>
                    </div>
                    <div class="col-4 pr-2" style="padding-left: 0">
                        <button
                            @click="showCancelModal = true"
                            class="btn-primary button-clear"
                            style="width: 100%"
                        >
                            Abbrechen
                        </button>
                    </div>
                </div>

                <!-- show missing modal -->
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

        <div
            class="row flex justify-start items-center"
            style="margin-top: 48px; padding-left: 25px"
        >
            <modal :show="showConfirmModal" @close="showConfirmModal = false">
                <template #header>
                    <h3 style="margin: auto">
                        Erstellung des Unternehmens abbrechen?
                    </h3>
                </template>
                <template #body>
                    <span class="cancel-text"
                        >Wenn Sie diesen Vorgang abbrechen, werden alle Daten
                        verworfen. Sind Sie sicher, dass Sie die Erstellung
                        dieses Unternehmens wirklich abbrechen wollen?</span
                    >
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

            <modal :show="showCancelModal" @close="showCancelModal = false">
                <template #header>
                    <h3 style="margin: auto">
                        Erstellung des Spielgruppen Admins abbrechen?
                    </h3>
                </template>
                <template #body>
                    <span class="cancel-text"
                        >Wenn Sie diesen Vorgang abbrechen, werden alle Daten
                        verworfen. Sind Sie sicher, dass Sie die Erstellung
                        dieses Spielgruppen Admins wirklich abbrechen
                        wollen?</span
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

            <modal :show="showErrorModal" @close="showErrorModal = false">
                <template #header>
                    <h3 style="margin-left: 100px">
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
                        style="margin-left: 120px"
                        @click="showErrorModal = false"
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
import axios from "../../axios";
import flushMessagesMixin from "../../mixins/flushMessagesMixin";
import moment from "moment";
import Modal from "./Modal.vue";
import Dropdown from "./Components/Dropdown.vue";
export default {
    name: "AddAdminuser",
    mixins: [flushMessagesMixin],
    components: {
        Modal,
        Dropdown,
    },
    data() {
        return {
            form: {
                company_id: "",
                first_name: "",
                last_name: "",
                email: "",
                prefix_id: "",
            },
            emailValidated: "",
            showMissingModal: false,
            showConfirmModal: false,
            showErrorModal: false,
            showCancelModal: false,
            errors: [],
            companyList: [],
            firstNameValidated: true,
            lastNameValidated: true,
        };
    },
    filters: {
        leadzero: (value) => {
            return String(value).padStart(5, "0");
        },
    },
    computed: {},
    created() {
        this.getAdminList();
        this.getMax();
    },
    methods: {
        checkFieldLength(id){
            if(document.getElementById(id).value.length > 30){
                document.getElementById(id).style.border = "1px solid #E97D32";
            }else{
                document.getElementById(id).style.border = "";
            }
        },
        onlyAlphabets(target, id) {
            let flag = false;
            if (/^[\p{L}\p{M}\p{Zs}\-]+$/u.test(target)) {
                flag = true;
            } else {
                flag = false;
                document.getElementById(id).style.border = "1px solid #E97D32";
            }
            return flag;
        },

        ValidateEmail() {
            let flag = false;
            if (
                /^\w+([\.+-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(
                    this.form.email
                )
            ) {
                document.getElementById("email").style.border =
                    "";
                flag = true;
            } else {
                flag = false;
                document.getElementById("email").style.border =
                    "1px solid #E97D32";
            }
            return flag;
        },
        createAdmin() {
            this.clearError();
            if (this.checkEmptyField()) {
                this.showMissingModal = true;
                return;
            }
            if (this.checkInputLength()) {
                this.showMissingModal = true;
                return;
            }
            if (!this.ValidateEmail()) {
                this.emailValidated = false;
            }
            if (!this.onlyAlphabets(this.form.first_name, "first_name")) {
                this.firstNameValidated = false;
            }
            if (!this.onlyAlphabets(this.form.last_name, "last_name")) {
                this.lastNameValidated = false;
            }

            if (
                !this.emailValidated ||
                !this.firstNameValidated ||
                !this.lastNameValidated
            ) {
                this.showMissingModal = true;
                return;
            }
            axios
                .post("/api/adminuser", this.form)
                .then((res) => {
                    this.successMessage(
                        "Der Spielgruppen Admin wurde erfolgreich erstellt und die E-Mail Einladung gesendet."
                    );

                    this.errors = [];
                    this.$router.push("/adminuser");
                })
                .catch((err) => {
                    const backend_errors = err.response.data.errors;
                    this.errors = Object.assign({}, backend_errors);
                });
        },

        checkInputLength() {
            let flag = false;
            if (this.form.first_name.length >= 31) {
                flag = true;
                document.getElementById("first_name").style.border =
                    "1px solid #E97D32";
            } else if (this.form.last_name.length >= 31) {
                flag = true;
                document.getElementById("last_name").style.border =
                    "1px solid #E97D32";
            } else {
                flag = false;
            }
            return flag;
        },

        getAdminList() {
            axios
                .get("/api/company-lists")
                .then((res) => {
                    console.log(res.data);
                    this.companyList = res.data;
                })
                .catch((err) => {
                    const backend_errors = err.response.data.errors;
                    this.errors = Object.assign({}, backend_errors);
                });
        },

        async getMax() {
            const res = await axios.get(`/api/admin-get-max`);

            this.form.prefix_id = res.data;
        },
        formatDate(value) {
            return moment(value).format("DD.MM.YYYY");
        },

        goBack() {
            this.$router.push(`/adminuser`);
        },
        clearError() {
            document.getElementById("company").style.border = "none";
            document.getElementById("first_name").style.border = "none";
            document.getElementById("last_name").style.border = "none";
            document.getElementById("email").style.border = "none";

            this.errors = [];
            this.emailValidated = true;
            this.firstNameValidated = true;
            this.lastNameValidated = true;

            console.log("clear error");
        },
        checkEmptyField() {
            var flag = false;
            if (this.form.company_id == "") {
                document.getElementById("company").style.border =
                    "1px solid #E97D32";
                flag = true;
            }
            if (this.form.first_name == "") {
                document.getElementById("first_name").style.border =
                    "1px solid #E97D32";
                flag = true;
            }
            if (this.form.last_name == "") {
                document.getElementById("last_name").style.border =
                    "1px solid #E97D32";
                flag = true;
            }
            if (this.form.email == "") {
                document.getElementById("email").style.border =
                    "1px solid #E97D32";
                flag = true;
            }
            console.log(flag);

            return flag;
        },
        removeBorder(id) {
            this.hasAnythingChanged = true;
            document.getElementById(id).style.border = "none";
        },
    },
};
</script>

<style scoped lang="scss">
@import "../supervisor/supervisor";
.unique_id {
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 15px !important;
    line-height: 23px !important;
    color: #2c5b7d !important;
}
.error-msg {
    color: #e97d32;
    margin-top: 5px;
    text-align: right;
    font-size: 12px;
    font-style: italic;
    font-family: "Nexa";
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
.form-input-bordered {
    border-radius: 0px !important;
    // border: none !important;
}
.new-card {
    background: #e9f7fe;
    border-radius: 0;
    margin-bottom: 80px;
    padding: 27px 24px;
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
.user-form-registration {
    padding-left: 25px;
}
.user-form-registration .form-control {
    height: 40px;
    font-family: "Nexa", sans-serif;
    color: #2c5b7d;
    font-size: 15px;
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
#dropdownStatusButton {
    color: #063f5f;
    background: transparent;
    border: transparent;
}
.modal-default-button {
    float: right;
}

.form-control:focus {
    outline: 0;
    box-shadow: none !important;
}
.form-control {
    border: none;
}

.flex.row.mt-4 {
    -bs-gutter-x: 1.5rem;
    --bs-gutter-x: 4.5rem;
}

.unique_id {
    font-family: "Nexa";
    font-style: normal;
    font-weight: 400;
    font-size: 15px;
    line-height: 23px;
    color: #2c5b7d;
}

.header-text {
    margin-top: 0;
    margin-bottom: 40px;
}

.new-card {
    padding-bottom: 0;
}
</style>
