<template>
    <div class="table-row">
        <div class="td" style="width: 10%">
            <router-link class="td" :to="`/anmeldungen/show/${register.id}`">
                {{ register.child_first_name }} {{ register.child_last_name }}
            </router-link>
        </div>
        <div class="td" style="width: 18%">
            <a
                class="td"
                target="_blank"
                :href="`/spielgruppen/show/${register.spielgruppen_id}`"
            >
                {{ register.spielgruppen }}
            </a>
        </div>
        <div class="td" style="width: 22%">
            <a
                class="td"
                target="_blank"
                :href="`/spielgruppen/show/${register.closest_spielgruppen_id}`"
            >
                {{ register.closest_spielgruppen }}
            </a>
        </div>
        <div class="td" style="width: 18%">
            <a
                v-if="register.spielgruppens[0]"
                class="td"
                target="_blank"
                :href="`/spielgruppen/show/${register.spielgruppens[0].id}`"
            >
                {{ register.spielgruppens[0].name }}
            </a>
        </div>
        <div class="td" style="width: 12%">{{ register.note }}</div>
        <div class="td" style="width: 10%">
            <Status
                :status="register.status_id"
                :title="register.status_name"
            />
        </div>
        <div class="td marked" style="width: 5%">
            <svg
                style="cursor: pointer"
                width="28"
                height="22"
                viewBox="0 0 28 22"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                v-if="email_sent_time"
                @click="emailNotification()"
            >
                <path
                    d="M12.1144 11.5313L12.9983 10.6474L12.9983 10.6474L12.1144 11.5313ZM15.8856 11.5313L15.0017 10.6474L15.0017 10.6474L15.8856 11.5313ZM3.33333 2.66699H24.6667V0.166992H3.33333V2.66699ZM24.75 2.75033V18.7503H27.25V2.75033H24.75ZM24.6667 18.8337H3.33333V21.3337H24.6667V18.8337ZM3.25 18.7503V2.75033H0.75V18.7503H3.25ZM3.33333 18.8337C3.28731 18.8337 3.25 18.7963 3.25 18.7503H0.75C0.75 20.1771 1.9066 21.3337 3.33333 21.3337V18.8337ZM24.75 18.7503C24.75 18.7963 24.7127 18.8337 24.6667 18.8337V21.3337C26.0934 21.3337 27.25 20.1771 27.25 18.7503H24.75ZM3.33333 0.166992C2.62031 0.166992 1.97276 0.457511 1.50664 0.923633L3.27441 2.6914C3.28286 2.68295 3.29221 2.67686 3.30128 2.67303C3.30951 2.66955 3.31952 2.66699 3.33333 2.66699V0.166992ZM1.50664 0.923633C1.04052 1.38976 0.75 2.0373 0.75 2.75033H3.25C3.25 2.73651 3.25255 2.7265 3.25604 2.71827C3.25987 2.70921 3.26596 2.69985 3.27441 2.6914L1.50664 0.923633ZM24.6667 2.66699C24.6805 2.66699 24.6905 2.66955 24.6987 2.67303C24.7078 2.67686 24.7171 2.68295 24.7256 2.6914L26.4934 0.923635C26.0272 0.457508 25.3797 0.166992 24.6667 0.166992V2.66699ZM24.7256 2.6914C24.734 2.69985 24.7401 2.70921 24.744 2.71827C24.7474 2.7265 24.75 2.73651 24.75 2.75033H27.25C27.25 2.0373 26.9595 1.38976 26.4934 0.923635L24.7256 2.6914ZM11.2305 12.4152C12.7601 13.9447 15.2399 13.9448 16.7695 12.4152L15.0017 10.6474C14.4485 11.2007 13.5515 11.2007 12.9983 10.6474L11.2305 12.4152ZM1.50664 2.6914L11.2305 12.4152L12.9983 10.6474L3.27441 0.92363L1.50664 2.6914ZM16.7695 12.4152L26.4934 2.6914L24.7256 0.92363L15.0017 10.6474L16.7695 12.4152Z"
                    fill="#42BEAF"
                />
            </svg>
            <svg
                style="cursor: pointer"
                width="28"
                height="22"
                viewBox="0 0 28 22"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                v-else
                @click="emailNotification()"
            >
                <path
                    d="M12.1144 11.5313L12.9983 10.6474L12.9983 10.6474L12.1144 11.5313ZM15.8856 11.5313L15.0017 10.6474L15.0017 10.6474L15.8856 11.5313ZM3.33333 2.66699H24.6667V0.166992H3.33333V2.66699ZM24.75 2.75033V18.7503H27.25V2.75033H24.75ZM24.6667 18.8337H3.33333V21.3337H24.6667V18.8337ZM3.25 18.7503V2.75033H0.75V18.7503H3.25ZM3.33333 18.8337C3.28731 18.8337 3.25 18.7963 3.25 18.7503H0.75C0.75 20.1771 1.9066 21.3337 3.33333 21.3337V18.8337ZM24.75 18.7503C24.75 18.7963 24.7127 18.8337 24.6667 18.8337V21.3337C26.0934 21.3337 27.25 20.1771 27.25 18.7503H24.75ZM3.33333 0.166992C2.62031 0.166992 1.97276 0.457511 1.50664 0.923633L3.27441 2.6914C3.28286 2.68295 3.29221 2.67686 3.30128 2.67303C3.30951 2.66955 3.31952 2.66699 3.33333 2.66699V0.166992ZM1.50664 0.923633C1.04052 1.38976 0.75 2.0373 0.75 2.75033H3.25C3.25 2.73651 3.25255 2.7265 3.25604 2.71827C3.25987 2.70921 3.26596 2.69985 3.27441 2.6914L1.50664 0.923633ZM24.6667 2.66699C24.6805 2.66699 24.6905 2.66955 24.6987 2.67303C24.7078 2.67686 24.7171 2.68295 24.7256 2.6914L26.4934 0.923635C26.0272 0.457508 25.3797 0.166992 24.6667 0.166992V2.66699ZM24.7256 2.6914C24.734 2.69985 24.7401 2.70921 24.744 2.71827C24.7474 2.7265 24.75 2.73651 24.75 2.75033H27.25C27.25 2.0373 26.9595 1.38976 26.4934 0.923635L24.7256 2.6914ZM11.2305 12.4152C12.7601 13.9447 15.2399 13.9448 16.7695 12.4152L15.0017 10.6474C14.4485 11.2007 13.5515 11.2007 12.9983 10.6474L11.2305 12.4152ZM1.50664 2.6914L11.2305 12.4152L12.9983 10.6474L3.27441 0.92363L1.50664 2.6914ZM16.7695 12.4152L26.4934 2.6914L24.7256 0.92363L15.0017 10.6474L16.7695 12.4152Z"
                    fill="#E97D32"
                />
            </svg>
            <div class="letsplan-tooltip" @click.stop="">
                <ExclamatoryIcon />
                <div class="tooltip-text">
                    <span v-if="!email_sent_time"
                        >Elterninfo-E-Mail nicht gesendet</span
                    >
                    <span v-else
                        >Elterninfo-E-Mail gesendet am
                        {{ email_sent_time | dateTime }}</span
                    >
                </div>
            </div>
        </div>
        <div class="td" style="width: 5%">
            <div
                class="menu-bar"
                @click="menubar_is_opened = !menubar_is_opened"
                v-click-outside="() => (menubar_is_opened = false)"
            >
                <MenuIcon
                    style="cursor: pointer"
                    :color="menubar_is_opened ? '#4C85A7' : ''"
                />
                <div
                    class="options"
                    v-if="menubar_is_opened"
                    @click="menubar_is_opened = false"
                >
                    <button
                        class="option"
                        v-if="register.status_id != 2 && isEditable"
                        @click="updateStatus(2)"
                    >
                        Deaktivieren
                    </button>
                    <button
                        class="option"
                        v-if="register.status_id == 2 && isEditable"
                        @click="updateStatus(1)"
                    >
                        Aktivieren
                    </button>
                    <router-link
                        class="option"
                        :to="`anmeldungen/edit/${register.id}`"
                        v-if="isEditable"
                        >Bearbeiten</router-link
                    >
                    <button
                        class="option"
                        v-if="register.marked != 1 && isEditable"
                        @click="updateMarked(1)"
                    >
                        Markieren
                    </button>
                    <button
                        class="option"
                        v-if="register.marked == 1 && isEditable"
                        @click="updateMarked(0)"
                    >
                        Demarkieren
                    </button>
                    <router-link
                        class="option"
                        :to="`anmeldungen/show/${register.id}`"
                        >Details ansehen</router-link
                    >
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import ExclamatoryIcon from "./ExclamatoryIcon.vue";
import MenuIcon from "../MenuIcon.vue";
import Status from "./Status.vue";
import axios from "../../../axios";
import ConfirmationModal from "./ConfirmationModal.vue";
import EmailModal from "./EmailModal.vue";
import { mapGetters } from "vuex";
import dayjs from "dayjs";

export default {
    props: {
        register: {
            type: Object,
            required: true,
        },
    },

    filters: {
        dateTime(value) {
            return dayjs(value).format("DD.MM.YYYY HH:mm");
        },
    },

    data() {
        return {
            menubar_is_opened: false,
            email_sent_time: this.register.mail_sent_at,
        };
    },

    computed: {
        ...mapGetters(["hasRole"]),

        isEditable() {
            return this.hasRole([window.PLAYGROUP_ADMIN, window.SUPER_ADMIN]);
        },
    },

    components: {
        ExclamatoryIcon,
        MenuIcon,
        Status,
        ConfirmationModal,
        EmailModal,
    },

    methods: {
        emailNotification() {
            const title = "Elterninfo E-Mail senden?";
            const description = `Sind Sie sicher, dass Sie den Eltern für die Anmeldung von ${this.register.child_first_name} ${this.register.child_last_name} ein E-Mail mit folgenden PDF’s senden möchten?`;

            EventBus.$emit("email-modal:show", {
                title,
                description,
                noClick: () => EventBus.$emit("email-modal:hide"),
                yesClick: () => {
                    EventBus.$emit("email-modal:hide");
                    axios
                        .get(
                            `/api/anmeldungens-sendMail/${this.register.id}`,
                            {}
                        )
                        .then((res) => {
                            console.log(res);
                            this.email_sent_time = res.data.email_sent_at;
                            EventBus.$emit(
                                "notification-modal:show",
                                res.data.message
                            );
                            this.$emit("updateResult");
                        });
                },
            });
        },
        updateStatus(status) {
            const title =
                status == 1
                    ? "Anmeldung aktivieren?"
                    : "Anmeldung deaktivieren?";
            const description =
                status == 1
                    ? `Wird eine Anmeldung aktiviert, wird die Einteilung für das entsprechende Kind und entsprechende Einträge in der Planung vorgenommen. Sind Sie sicher, dass Sie die Anmeldung für “${this.register.child_first_name} ${this.register.child_last_name}” wirklich aktivieren wollen?`
                    : `Wird eine Anmeldung deaktiviert, werden die Einträge in der Planung für das entsprechende Kind gelöscht. Sind Sie sicher, dass Sie die Anmeldung für “${this.register.child_first_name} ${this.register.child_last_name}” wirklich deaktivieren wollen?`;

            EventBus.$emit("confirmation-modal:show", {
                title,
                description,
                noClick: () => EventBus.$emit("confirmation-modal:hide"),
                yesClick: () => {
                    EventBus.$emit("confirmation-modal:hide");
                    axios
                        .put(
                            `/api/anmeldungens/${this.register.id}/update-status`,
                            {
                                status,
                            }
                        )
                        .then((res) => {
                            EventBus.$emit(
                                "notification-modal:show",
                                res.data.message
                            );
                            this.$emit("updateResult");
                        });
                },
            });
        },

        updateMarked(marked) {
            axios
                .put(`/api/anmeldungens/${this.register.id}/update-marked`, {
                    marked,
                })
                .then((res) => {
                    EventBus.$emit("notification-modal:show", res.data.message);
                    this.$emit("updateResult");
                });
        },
    },
};
</script>

<style lang="scss" scoped>
.table-row {
    border-top: 1px solid #d3e3ec;
    background: #e9f7fe;
    min-height: 56px;
    display: flex;
    align-items: flex-start;
    padding-top: 13px;
    gap: 10px;

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
        text-decoration: none;
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
                width: 230px;

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
