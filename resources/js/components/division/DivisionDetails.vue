<template>
    <div class="benutzer mt-5 mb-30">
        <div class="card-panel-home supervisor-card-wrapper new-card">
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
                    <span class="header-text">Einteilung Details </span>
                    <div class="flex justify">
                        <div class="col-4 pr-2">
                            <p class="prompt">Erstellungsdatum</p>
                            <p class="details">{{ created_at }}</p>
                        </div>
                        <div class="col-4 pr-2">
                            <p class="prompt">Einteilungs-ID</p>
                            <p class="details">{{ division.prefix_id }}</p>
                        </div>
                        <div class="col-4 pr-2">
                            <p class="prompt">Name</p>
                            <p class="details">{{ name }}</p>
                        </div>
                    </div>
                    <div class="row flex mt-4">
                        <div class="col-4 pr-2">
                            <p class="prompt">Anmeldefrist</p>

                            <p class="details">
                                {{ formatDate(registration_start_date) }} -
                                {{ formatDate(registration_end_date) }}
                            </p>
                        </div>
                        <div class="col-4 pr-2" style="padding-left: 0">
                            <p class="prompt">Spielgruppen Zeitraum</p>
                            <p class="details">
                                {{ formatDate(playgroup_start_date) }} -
                                {{ formatDate(playgroup_end_date) }}
                            </p>
                        </div>
                        <div class="col-4 pr-2" style="padding-left: 0">
                            <p class="prompt">Datum Elterninformation</p>
                            <p class="details">{{ formatDate(date_parent) }}</p>
                        </div>
                    </div>
                    <span class="header-text">Tag der offenen Türe</span>
                    <div class="row flex mt-4">
                        <div class="col-4 pr-2">
                            <p class="prompt">Datum</p>

                            <p class="details">
                                {{ formatDate(open_door_date) }}
                            </p>
                        </div>

                        <div class="col-4 pr-2" style="padding-left: 0">
                            <p class="prompt">Zeit</p>

                            <p class="details">
                                {{ open_door_start_time }} -
                                {{ open_door_end_time }}
                            </p>
                        </div>

                        <div
                            class="col-4 pr-2"
                            style="padding-left: 0; width: 436px"
                        >
                            <p class="prompt">Ort</p>
                            <p class="details">{{ place }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row flex justify-start items-center mt-4" v-if="user.role_id == 1">
                <button
                    @click="triggerModal"
                    class="btn-primary button-save"
                    style="width: 436px"
                >
                    <div style="text-align: left; width: 40%">
                        <svg
                            width="26"
                            height="26"
                            viewBox="0 0 26 26"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M1.33398 24.6667H0.0839844V25.9167H1.33398V24.6667ZM1.33398 19.3333L0.450101 18.4494L0.0839844 18.8156V19.3333H1.33398ZM18.6673 2L19.5512 1.11612L18.6673 0.232233L17.7834 1.11612L18.6673 2ZM24.0007 7.33333L24.8845 8.21722L25.7684 7.33333L24.8845 6.44945L24.0007 7.33333ZM6.66732 24.6667V25.9167H7.18508L7.5512 25.5505L6.66732 24.6667ZM2.58398 24.6667V19.3333H0.0839844V24.6667H2.58398ZM17.7834 2.88388L23.1168 8.21722L24.8845 6.44945L19.5512 1.11612L17.7834 2.88388ZM6.66732 23.4167H1.33398V25.9167H6.66732V23.4167ZM23.1168 6.44945L19.1168 10.4494L20.8845 12.2172L24.8845 8.21722L23.1168 6.44945ZM19.1168 10.4494L5.78343 23.7828L7.5512 25.5505L20.8845 12.2172L19.1168 10.4494ZM2.21787 20.2172L15.5512 6.88388L13.7834 5.11612L0.450101 18.4494L2.21787 20.2172ZM15.5512 6.88388L19.5512 2.88388L17.7834 1.11612L13.7834 5.11612L15.5512 6.88388ZM20.8845 10.4494L15.5512 5.11612L13.7834 6.88388L19.1168 12.2172L20.8845 10.4494Z"
                                fill="white"
                            />
                        </svg>
                    </div>
                    <div style="width: 60%; text-align: left">Bearbeiten</div>
                </button>
            </div>

            <div class="row flex justify-start items-center my-4 mx-0">
                <modal :show="showEditModal" @close="showEditModal = false">
                    <template #header>
                        <h3 style="margin: auto">Einteilung bearbeiten</h3>
                    </template>
                    <template #body>
                        <span class="cancel-text"
                            >Diese Einteilung ist oder war bereits aktiv. Sind
                            Sie sicher, dass Sie diese Einteilung trotzdem
                            bearbeiten möchten?</span
                        >
                    </template>
                    <template #footer>
                        <button
                            class="modal-primary-button"
                            @click="goToEditPage"
                        >
                            Ja
                        </button>
                        <button
                            class="modal-secondary-button"
                            @click="showEditModal = false"
                        >
                            Nein
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
import moment from "moment";
import Modal from "./Modal.vue";

export default {
    name: "DivisionDetails",
    components: {
        Modal,
    },
    data() {
        return {
            role: localStorage.getItem("role"),
            errors: [],
            statuses: [],
            length: 10,
            code: "+41",
            openForm: false,
            division: [],
            divisions: [],
            users: [],
            page: 1,
            per_page: 10,
            uniq_id: "",
            max_uniq_id: "",
            name: "",
            created_at: "",
            registration_start_date: "",
            registration_end_date: "",
            playgroup_start_date: "",
            playgroup_end_date: "",
            date_parent: "",
            open_door_date: "",
            open_door_start_time: "",
            open_door_end_time: "",
            place: "",
            filter: "",
            status_id: null,
            selectedCompany: "",
            selectedCompanyName: "",
            open: false,
            showEditModal: false,
        };
    },

    computed: {
        user(){
            return this.$store.getters['user'];
        }
    },
    mounted() {
        this.getDivision();
    },

    methods: {
        formatDate(value, format = "DD.MM.YYYY") {
            return moment(value).format(format);
        },
        async getDivision() {
            let division_id = parseInt(this.$route.params.id);
            console.log("route params", division_id);
            await axios.get(`/api/division/${division_id}`).then((res) => {
                console.log("response", res.data[0]);
                console.log(res.data[0]);
                this.division = res.data[0];
            });
            this.created_at = this.formatDate(this.division.created_at);
            this.uniq_id = this.division.uniq_id;
            this.name = this.division.name;
            this.registration_start_date =
                this.division.registration_start_date;
            this.registration_end_date = this.division.registration_end_date;
            this.playgroup_start_date = this.division.playgroup_start_date;
            this.playgroup_end_date = this.division.playgroup_end_date;
            this.date_parent = this.division.date_parent;
            this.open_door_date = this.division.open_door_date;
            this.open_door_start_time = this.division.open_door_start_time;
            this.open_door_end_time = this.division.open_door_end_time;
            this.place = this.division.place;
            this.status_id = this.division.status_id;
        },

        goBack() {
            this.$router.push(`/division`);
        },
        goToEditPage() {
            let id = parseInt(this.$route.params.id);
            this.$router.push(`/division/edit/${id}`);
        },
        triggerModal() {
            if (this.status_id !== 1) {
                this.showEditModal = true;
            } else {
                this.goToEditPage();
            }
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

.new-card {
    background: #e9f7fe;
    border-radius: 0;
    margin-bottom: 80px;
}

.button-save {
    background: #e97d32 !important;
    border-radius: 0;
    margin-left: 13px;
    font-family: "Nexa";
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.details {
    font-family: "Nexa";

    font-weight: 700;
    font-size: 15px !important;
    line-height: 23px !important;
    color: #2c5b7d !important;
}

.user-form-registration .form-control {
    height: 40px;
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

.card-panel-home {
    border: none;
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
</style>
