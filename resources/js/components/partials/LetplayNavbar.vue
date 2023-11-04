<template>
    <div
        class="flex items-center justify-between relative h-header height-80 z-20 px-view"
        style="background: #e9f7fe"
    >
        <img class="main-login" src="/images/new-logo.png" />
        <div class="notification-panel">
            <div @click="toggleSettings" class="profile-info">
                <img
                    :src="
                        profile && profile.image_url
                            ? profile.image_url
                            : '/images/user-avatar.png'
                    "
                    class="profile-image"
                />
                <div class="profile-details">
                    <p
                        v-if="profile"
                        class="profile-name font-bold"
                        style="word-break: break-word"
                    >
                        {{ profile.first_name }} {{ profile.last_name }}
                    </p>
                    <p v-if="profile" class="profile-role">
                        {{ profile.role_name_de }}
                    </p>
                </div>
                <div class="ml-2">
                    <svg
                        width="22"
                        height="14"
                        viewBox="0 0 22 14"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        class="down-arrow"
                    >
                        <path
                            d="M2.6665 2.66666L10.9998 11L19.3332 2.66666"
                            stroke="#063F5F"
                            stroke-width="3"
                            stroke-linecap="square"
                        />
                    </svg>
                </div>
            </div>
            <div
                v-click-outside="hideSettings"
                v-if="openSettings"
                class="settings-menu card"
            >
                <router-link class="profile-link" to="/profile"
                    >Profileinstellung ändern</router-link
                >
                <div class="horizontal-line"></div>
                <h5 class="logout" @click="logout">Abmelden</h5>
            </div>
        </div>
    </div>
</template>

<script>
import GlobalSearch from "./GlobalSearch";
import ClickOutside from "vue-click-outside";
import axios from "../../axios";
import { mapState } from "vuex";
export default {
    name: "LetsplayNavbar",
    directives: { ClickOutside },
    computed: {
        ...mapState({
            profile: (state) => state.user,
        }),
    },
    mounted() {
        this.popupItem = this.$el;
    },
    data() {
        return {
            openSettings: false,
            openNotification: false,
            role: localStorage.getItem("role"),
        };
    },
    methods: {
        logout() {
            axios
                .post("/api/logout")
                .then(() => {
                    localStorage.setItem("token", "");
                    location.reload();
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        exportChildrens() {
            axios({
                url: "/api/exports/childrens",
                method: "GET",
                responseType: "blob", // important
            }).then((response) => {
                const url = window.URL.createObjectURL(
                    new Blob([response.data])
                );
                const link = document.createElement("a");
                link.href = url;
                link.setAttribute("download", "childrens.xlsx"); //or any other extension
                document.body.appendChild(link);
                link.click();
            });
        },
        toggleSettings() {
            this.openSettings = !this.openSettings;
            this.openNotification = false;
        },

        hideSettings() {
            this.openSettings = false;
        },

        hideNotifications() {
            this.openNotification = false;
        },

        toggleNotification() {
            this.openSettings = false;
            this.openNotification = !this.openNotification;
        },
    },
};
</script>

<style scoped>
.down-arrow {
    width: 16.67px;
    height: 8.33px;
}
.notification-panel {
    display: flex;
    align-items: center;
    position: absolute;
    right: 35px;
}
.bell {
    width: 30.88px;
    height: 27.79px;
    margin-right: 25px;
    cursor: pointer;
}

.profile-info {
    display: flex;
    margin-left: -30px;
    justify-content: flex-start;
    align-items: center;
    cursor: pointer;
    position: relative;
}

.profile-image {
    border-radius: 7px;
    width: 45px;
    height: 45px;
    margin-right: 10px;
    object-fit: cover;
}

.horizontal-line {
    border-top: 1px solid #fef1e7;
}

.profile-name {
    /* Nexa Bold / 18 */

    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 18px;
    line-height: 27px;
    margin-bottom: 0;
    color: #063f5f;
}

.profile-role {
    font-family: "Ubuntu";
    font-style: normal;
    font-weight: 400;
    font-size: 15px;
    line-height: 17px;
    margin-bottom: 0;
    /* Primary / Astronaut Blue */

    color: #063f5f;
}

.notification-badge {
    position: relative;
}

.new-notification {
    width: 17px;
    height: 17px;
    border-radius: 50%;
    background-color: #e62d4f;
    display: flex;
    justify-content: center;
    align-items: center;
    position: absolute;
    top: -2px;
    right: 19px;
    color: white;
    font-weight: 400;
    font-size: 12px;
    line-height: 14px;
    outline: 3px solid #ffffff;
}

.settings-menu {
    position: absolute;
    top: 63px;
    right: 15px;
    min-width: 250px;
    width: 25%;
    z-index: 100;
    border-radius: 0;
    border: none;
}

/* .settings-menu:before {
    content: "";
    position: absolute;
    width: 20px;
    height: 20px;
    top: -10px;
    left: 50%;
    border: 1px solid #c2c5c6;
    border-bottom-color: transparent;
    border-right-color: transparent;
    transform: rotate(45deg);
    background: white;
} */

.notification-menu {
    min-width: 200px;
    padding: 20px;
    position: absolute;
    top: 70px;
    width: 380px;
    right: calc(6% + 2px);
}

.notification-menu:before {
    content: "";
    position: absolute;
    width: 20px;
    height: 20px;
    top: -10px;
    border: 1px solid #c2c5c6;
    border-bottom-color: transparent;
    border-right-color: transparent;
    transform: rotate(45deg);
    background: white;
    left: 50%;
}

.profile-link {
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 15px;
    line-height: 23px;
    padding: 10px 16px;
    color: #063f5f;
    text-decoration: none;
    cursor: pointer;
}

.logout {
    /* Nexa Bold / 15 */
    padding: 10px 16px;
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 15px;
    line-height: 23px;
    cursor: pointer;
    margin-bottom: 0;
    color: #e97d32;
}

.old-notification {
    color: #a8acad;
}

.old-notification.date {
    margin-top: 5px;
}

.new-notification-pointer {
    width: 11px;
    height: 7px;
    background: #e62d4f;
    border-radius: 50%;
    margin-right: 5px;
    margin-top: 5px;
}
.main-login {
    position: absolute;
    left: 600px;
}
</style>
