<template>
    <div class="status__modal" v-if="showModal">
        <div class="status__modal--header">
            <h4>{{ title }}</h4>
        </div>
        <div class="status__modal--body">
            {{ body }}
        </div>
        <div class="status__modal--footer">
            <button class="button confirm" @click.prevent="changeStatus">
                Ja
            </button>
            <button class="button cancel" @click.prevent="showModal = false">
                Nein
            </button>
        </div>
    </div>
</template>

<script>
import axios from "../../../axios";
export default {
    props: {},
    data() {
        return {
            showModal: false,
            status_id: null,
            user: null,
        };
    },
    computed: {
        fullName() {
            return `${this.user.first_name} ${this.user.last_name}`;
        },
        title() {
            return this.status_id == 1
                ? "Benutzer aktivieren?"
                : "Benutzer deaktivieren?";
        },
        body() {
            return this.status_id == 1
                ? `Sind Sie sicher, dass Sie den Benutzer "${this.fullName}" wirklich aktivieren und eine E-Mail Einladung senden wollen?`
                : `Sind Sie sicher, dass Sie den Benutzer "${this.fullName}" wirklich deaktivieren wollen?`;
        },
    },
    mounted() {
        EventBus.$on("show-confirm-modal", (e) => {
            this.showModal = true;
            this.status_id = e.status_id;
            this.user = e.user;
        });
    },
    methods: {
        changeStatus() {
            axios
                .patch(`/api/benutzer/${this.user.id}/status`, {
                    status_id: this.status_id,
                })
                .then((res) => {
                    const text =
                        this.status_id == 1
                            ? `Der Benutzer "${this.fullName}" wurde erfolgreich aktiviert und die E-Mail Einladung gesendet.`
                            : `Der Benutzer "${this.fullName}" wurde erfolgreich deaktiviert.`;
                    this.showModal = false;
                    EventBus.$emit("show-notification", text);
                    EventBus.$emit(
                        `update-status-${this.user.id}`,
                        this.status_id
                    );
                })
                .catch((err) => {
                    console.log(err);
                });
        },
    },
};
</script>

<style lang="scss" scoped>
.status__modal {
    width: 600px;
    background: #ffffff;
    box-shadow: 8px 8px 0px #d3e3ec;
    padding: 24px 32px;
    position: absolute;
    z-index: 9;
    top: 400px;
    left: 50%;
    transform: translateX(-50%);

    &--header {
        text-align: center;
        margin-bottom: 32px;

        h4 {
            margin: 0;
            padding: 0;

            font-family: "Nexa";
            font-style: normal;
            font-weight: 700;
            font-size: 18px;
            line-height: 27px;

            color: #16a9b7;
        }
    }

    &--body {
        font-family: "Nexa";
        font-style: normal;
        font-weight: 400;
        font-size: 16px;
        line-height: 24px;
        text-align: center;
        color: #2c5b7d;
    }

    &--footer {
        margin-top: 32px;
        display: flex;
        justify-content: space-between;

        gap: 32px;
        .button {
            width: 50%;
            padding: 8px;

            /* Nexa Bold / 18 */

            font-family: "Nexa";
            font-style: normal;
            font-weight: 700;
            font-size: 18px;
            line-height: 27px;
            text-align: center;
        }

        .confirm {
            color: #ffffff;
            background: #16a9b7;
        }

        .cancel {
            background: #c6e9f2;
            color: #19849e;
        }
    }
}
</style>
