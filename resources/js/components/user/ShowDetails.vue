<template>
    <div class="benutzer mt-5 mb-30" v-if="user !== null">
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
                    <span class="header-text">Benutzer Details </span>
                    <div class="flex justify">
                        <div class="col-4 pr-2">
                            <div class="wrapper flex justify-start items-start">
                                <div class="img-container">
                                    <div
                                        class="image-placeholder"
                                        v-if="user.image === null"
                                    ></div>
                                    <img
                                        v-else
                                        :src="user.image"
                                        alt="user"
                                        class="user"
                                    />
                                </div>
                                <div class="date">
                                    <p class="prompt">Erstellungsdatum</p>
                                    <p class="details">
                                        {{ formatDate(user.created_at) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 pr-2">
                            <p class="prompt">Benutzer-ID</p>
                            <p class="details">{{user.prefix_id}}</p>
                        </div>
                        <div class="col-4 pr-2">
                            <p class="prompt">Status</p>
                            <p class="details">{{ user.status_name }}</p>
                        </div>
                    </div>
                    <div class="row flex mt-4">
                        <div class="col-4 pr-2">
                            <p class="prompt">Benutzertyp</p>

                            <p class="details">{{ user.role_name }}</p>
                        </div>
                        <div class="col-4 pr-2" style="padding-left: 0">
                            <p class="prompt">Name</p>
                            <p class="details">
                                {{ user.first_name }} {{ user.last_name }}
                            </p>
                        </div>
                        <div class="col-4 pr-2" style="padding-left: 0">
                            <p class="prompt">Adresse</p>
                            <p class="details">
                                <!-- {{ user.house_number }},{{
                                    user.street_name
                                }},{{ user.postal_code }},{{ user.location }} -->
                                {{ `${user.street_name} ${user.house_number}, ${user.postal_code} ${user.location}` }}
                            </p>
                        </div>
                    </div>

                    <div class="row flex mt-4">
                        <div class="col-4 pr-2">
                            <p class="prompt">E-Mail</p>

                            <p class="details">{{ user.email }}</p>
                        </div>

                        <div class="col-4 pr-2" style="padding-left: 0">
                            <p class="prompt">Telefonnummer</p>

                            <p class="details">
                                {{ user.prefix }} {{ user.phone_number }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row flex justify-start items-center mt-4">
                <button
                    @click="goToEditPage"
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
            <div class="row flex justify-end items-center"></div>
        </div>
    </div>
</template>

<script>
import axios from "../../axios";
import moment from "moment";

export default {
    name: "ShowDetails",
    data() {
        return {
            role: localStorage.getItem("role"),
            user: null,
        };
    },

    computed: {},
    mounted() {
        this.getDivision();
    },

    filters: {
        leadzero: (value) => {
            return String(value).padStart(5, "0");
        },
    },

    methods: {
        formatDate(value) {
            return moment(value).format("DD.MM.YYYY");
        },
        async getDivision() {
            let user_id = parseInt(this.$route.params.id);
            console.log("route params", user_id);
            await axios.get(`/api/benutzer/${user_id}`).then((res) => {
                this.user = res.data.data;
            });
        },
        goBack() {
            this.$router.push(`/user`);
        },
        goToEditPage() {
            let id = parseInt(this.$route.params.id);
            this.$router.push(`/user/edit/${id}`);
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

.user,
.image-placeholder {
    width: 50px;
    height: auto;
    margin-right: 15px;
}

.image-placeholder {
    height: 50px;
    background-color: #063f5f;
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
    margin-bottom: 10px;
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
</style>
