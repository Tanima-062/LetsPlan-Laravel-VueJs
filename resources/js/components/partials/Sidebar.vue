<template>
    <div class="flex-none space-top bg-sidebar-dark width-274 side-letsplay">
        <a href="/">
            <div
                class="absolute pin-t pin-l pin-r flex items-center h-header height-80"
                style="background: #e9f7fe"
            ></div>
        </a>
        <ul class="list-group flex flex-col align-content-center pl-0">
            <router-link
                exact
                active-class="router-link-active"
                v-if="role != 'super_admin'"
                to="/startseite"
                custom
                v-slot="{ navigate, isActive, isExactActive }"
            >
                <li
                    :class="[
                        isActive && 'router-link-active',
                        isExactActive && 'router-link-exact-active',
                    ]"
                    @click="navigate"
                    class="link-navigation"
                    role="link"
                >
                    <HomeIcon />
                    <span class="list-title">Dashboard</span>
                </li>
            </router-link>
            <router-link
                active-class="router-link-active"
                v-if="role != 'super_admin'"
                to="/division"
                custom
                v-slot="{ navigate, isActive, isExactActive }"
            >
                <li
                    :class="[
                        isActive && 'router-link-active',
                        isExactActive && 'router-link-exact-active',
                    ]"
                    @click="navigate"
                    class="link-navigation"
                    role="link"
                >
                    <DivisionIcon />
                    <span class="list-title">Einteilungen</span>
                </li>
            </router-link>
            <router-link
                v-if="role === 'super_admin'"
                to="/company"
                custom
                v-slot="{ navigate, isExactActive, isActive }"
            >
                <li
                    :class="[
                        isActive && 'router-link-active',
                        isExactActive && 'router-link-exact-active',
                    ]"
                    class="link-navigation"
                    @click="navigate"
                    role="link"
                >
                    <CompanyIcon />
                    <span class="list-title">Unternehmen</span>
                </li>
            </router-link>
            <router-link
                v-if="role === 'super_admin'"
                to="/adminuser"
                custom
                v-slot="{ navigate, isExactActive, isActive }"
            >
                <li
                    :class="[
                        isActive && 'router-link-active',
                        isExactActive && 'router-link-exact-active',
                    ]"
                    class="link-navigation"
                    @click="navigate"
                    role="link"
                >
                    <SpielgruppenAdminIcon />
                    <span class="list-title">Spielgruppen Admins</span>
                </li>
            </router-link>
            <router-link
                v-if="role != 'super_admin'"
                to="/spielgruppen"
                custom
                v-slot="{ navigate, isExactActive, isActive }"
            >
                <li
                    :class="[
                        isActive && 'router-link-active',
                        isExactActive && 'router-link-exact-active',
                    ]"
                    class="link-navigation"
                    @click="navigate"
                    role="link"
                >
                    <PlayGroupIcon />
                    <span class="list-title">Spielgruppen</span>
                </li>
            </router-link>
            <router-link
                v-if="role != 'super_admin'"
                to="/anmeldungen"
                custom
                v-slot="{ navigate, isExactActive, isActive }"
            >
                <li
                    :class="[
                        isActive && 'router-link-active',
                        isExactActive && 'router-link-exact-active',
                    ]"
                    class="link-navigation"
                    @click="navigate"
                    role="link"
                >
                    <RegistrationIcon />
                    <span class="list-title">Anmeldungen</span>
                </li>
            </router-link>
            <router-link
                v-if="role != 'super_admin'"
                active-class="router-link-active"
                to="/plannung"
                custom
                v-slot="{ navigate, isActive, isExactActive }"
            >
                <li
                    :class="[
                        isActive && 'router-link-active',
                        isExactActive && 'router-link-exact-active',
                    ]"
                    class="link-navigation"
                    @click="navigate"
                    role="link"
                >
                    <PlanningIcon />
                    <span class="list-title">Planung</span>
                </li>
            </router-link>
            <router-link
                v-if="role === 'admin'"
                active-class="router-link-active"
                to="/user"
                custom
                v-slot="{ navigate, isActive, isExactActive }"
            >
                <li
                    :class="[
                        isActive && 'router-link-active',
                        isExactActive && 'router-link-exact-active',
                    ]"
                    class="link-navigation"
                    @click="navigate"
                    role="link"
                >
                    <UserIcon />
                    <span class="list-title">Benutzer</span>
                </li>
            </router-link>
        </ul>
    </div>
</template>

<script>
import DivisionIcon from "./Icons/DivisionIcon.vue";
import HomeIcon from "./Icons/HomeIcon.vue";
import PlayGroupIcon from "./Icons/PlayGroupIcon.vue";
import RegistrationIcon from "./Icons/RegistrationIcon.vue";
import PlanningIcon from "./Icons/PlanningIcon.vue";
import UserIcon from "./Icons/UserIcon.vue";
import CompanyIcon from "./Icons/CompanyIcon.vue";
import SpielgruppenAdminIcon from "./Icons/SpielgruppenAdminIcon.vue";
export default {
    name: "Sidebar",
    data() {
        return {
            role: localStorage.getItem("role"),
        };
    },
    methods: {
        activePage(path) {
            console.log(path);
        },
    },
    components: {
        DivisionIcon,
        HomeIcon,
        PlayGroupIcon,
        RegistrationIcon,
        PlanningIcon,
        UserIcon,
        CompanyIcon,
        SpielgruppenAdminIcon,
    },
};
</script>

<style lang="scss" scoped>
.list-group {
    margin-top: 60px;
}
.link-navigation {
    cursor: pointer;
    list-style: none;
    width: 100%;
    padding: 15px 15px 15px 25px;
    display: flex;
    align-items: center;
    color: #bee7fb;
    font-family: "Ubuntu";
    font-style: normal;
    font-weight: 700;
    font-size: 22px;
    line-height: 25px;
    margin: 0;
    padding: 0;
    margin-left: 45px;
    margin-bottom: 20px;

    svg {
        width: 32px;
        margin-right: 20px;
    }

    span {
        margin: 0;
        padding: 0;
    }
}

.list-title {
    font-size: 18px;
    font-weight: 600;
    font-family: "Lato";
    margin-left: 14px;
    color: #bee7fb;
    line-height: 22px;
}

.router-link-active {
    color: #e97d32;
}
.router-link-active span {
    color: #e97d32;
}

.router-link-active svg path {
    /* stroke: #f2a402; */
}

.side-letsplay {
    background: #063f5f;
    width: 300px;
}
</style>
