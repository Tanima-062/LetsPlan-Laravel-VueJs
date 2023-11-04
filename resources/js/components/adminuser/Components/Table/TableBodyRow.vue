<template>
    <div class="table-row">
        <div class="td" style="width: 15%">
            {{ formatDate(adminuser.created_at) }}
        </div>
        <div class="td" style="width: 15%">{{ adminuser.prefix_id }}</div>
        <div class="td" style="width: 15%">{{ adminuser.company_name }}</div>
        <div class="td" style="width: 20%">
            {{ adminuser.first_name }} {{ adminuser.last_name }}
        </div>
        <div class="td" style="width: 25%;padding-right: 5px;">
            {{ adminuser.email }}
        </div>
        <div class="td" style="width: 8%;">
            <Status :user="adminuser"/>
        </div>
        <div class="td" style="width: 2%;">
            <!-- <div class="menu-bar" @click="menubar_is_opened = !menubar_is_opened"
                v-click-outside="() => menubar_is_opened = false">
                <MenuBar style="cursor: pointer" :color="menubar_is_opened ? '#4C85A7' : ''" />
                <div class="options" v-if="menubar_is_opened" @click="menubar_is_opened = false">
                    <button class="option" v-if="adminuser.status_id != 2" @click="updateStatus(2)">
                        Deaktivieren
                    </button>
                    <button class="option" v-if="adminuser.status_id == 2" @click="updateStatus(1)">
                        Aktivieren
                    </button>
                    <router-link class="option" :to="`/adminuser/edit/${adminuser.id}`">Bearbeiten</router-link>

                </div>
            </div> -->
            <MenuBar :user="adminuser" />
        </div>
        <!-- <ConfirmationModal /> -->
        <NotificationModal />
    </div>
</template>
<script>
import ConfirmationModal from "../ConfirmModal.vue";
import NotificationModal from "../Notification.vue";
import Status from "./Status.vue";
import MenuBar from "./MenuBar.vue";
import moment from "moment";
import axios from "../../../../axios";
export default {
    props: {
        adminuser: {
            type: Object,
            required: true,
        },
    },
    components: {
        Status,
        MenuBar,
        ConfirmationModal,
        NotificationModal
    },
    data() {
        return { menubar_is_opened: false};
    },

    // components: { ExclamatoryIcon, MenuIcon, Status, ConfirmationModal },

    methods: {
        formatDate(value) {
            return moment(value).format("DD.MM.YYYY");
        },
        // updateStatus(status) {
        //     const title = status == 1 ? "Spielgruppen Admin aktivieren?" : "Spielgruppen Admin deaktivieren?"
        //     const description = status == 1 ?
        //     `Sind Sie sicher, dass Sie die Spielgruppe Admin “${this.adminuser.first_name} ${this.adminuser.last_name}” wirklich aktivieren wollen?`
        //         : `Sind Sie sicher, dass Sie die Spielgruppe Admin “${this.adminuser.first_name} ${this.adminuser.last_name}” wirklich deaktivieren wollen?`

        //     EventBus.$emit('confirmation-modal:show', {
        //         title,
        //         description,
        //         noClick: () => EventBus.$emit('confirmation-modal:hide'),
        //         yesClick: () => {
        //             EventBus.$emit('confirmation-modal:hide')
        //             axios.put(`/api/adminuser/status/${this.adminuser.id}`, {
        //                 status,
        //             }).then(res => {
        //                 EventBus.$emit('notification-modal:show', res.data.message)
        //                 this.$emit('updateResult')
        //             })
        //         },
        //     })

        // },
    },
};
</script>

<style lang="scss" scoped>
.table-row {
    border-top: 1px solid #d3e3ec;
    background: #e9f7fe;
    min-height: 56px;
    display: flex;
    align-items: center;
    padding: 0px 22px 0px 25px;

    &:hover {
        background-color: #d2eefc;
    }

    .td {
        font-family: "Nexa";
        font-style: normal;
        font-weight: 400;
        font-size: 18px;
        line-height: 27px;
        color: #2c5b7d;

        .avatar {
            width: 40px;
            height: 40px;

            display: flex;
            align-items: center;
            justify-content: center;
            img {
                width: 100%;
                height: auto;
            }
        }
    }

    .marked {
        display: flex;
        gap: 10px;

        .letsplan-tooltip {
            display: flex;
            position: relative;
            align-items: flex-start;

            .tooltip-text {
                display: none;
                position: absolute;
                top: -10%;
                right: -5px;
                padding: 22px 16px 16px 15px;
                background-color: white;
                width: 161px;

                font-family: "Nexa";
                font-style: normal;
                font-weight: 400;
                font-size: 15px;
                line-height: 23px;
                color: #2c5b7d;
            }

            &:hover {
                svg {
                    z-index: 2;
                }

                .tooltip-text {
                    display: block;
                    z-index: 1;
                }
            }
        }
    }

    .menu-bar {
        position: relative;

        > .options {
            position: absolute;
            width: 151px;
            background-color: white;
            right: 0px;
            z-index: 10;

            > .option {
                font-family: "Nexa";
                font-style: normal;
                font-weight: 400;
                font-size: 15px;
                line-height: 20px;
                color: #135f84;
                padding: 11px 16px 11px 16px;
                border-top: 1px solid #fef1e7;
                width: 100%;
                text-align: left;
                text-decoration: none;
                display: flex;

                &:hover {
                    font-weight: 700;
                }
            }
        }
        .options > .option:first-child {
            border-top: none;
        }
    }
}
</style>
