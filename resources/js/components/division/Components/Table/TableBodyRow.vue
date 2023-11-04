<template>
    <div class="table-row">
        <div class="td" style="width: 15%">
            <router-link
                tag="span"
                style="cursor: pointer"
                :to="{ name: 'division_details', params: { id: division.id } }"
            >
                {{ division.name }}
            </router-link>
        </div>
        <div class="td" style="width: 22%">
            {{
                `${formatDate(division.registration_start_date)} - ${formatDate(
                    division.registration_end_date
                )}`
            }}
        </div>
        <div class="td" style="width: 23%">
            {{
                `${formatDate(division.playgroup_start_date)} - ${formatDate(
                    division.playgroup_end_date
                )}`
            }}
        </div>
        <div class="td" style="width: 28%">
            {{ `${formatDate(division.date_parent)}` }}
        </div>
        <div class="td" style="width: 10%">
            <Status :division="division" />
        </div>
        <div class="td" style="width: 2%">
            <MenuBar :division="division" />
        </div>
    </div>
</template>
<script>
// import ExclamatoryIcon from './ExclamatoryIcon.vue';
// import MenuIcon from '../MenuIcon.vue';
// import Status from './Status.vue';
// import axios from '../../../axios'
// import ConfirmationModal from './ConfirmationModal.vue';
import Status from "./Status.vue";
import MenuBar from "./MenuBar.vue";
import moment from "moment";
export default {
    props: {
        division: {
            type: Object,
            required: true,
        },
    },
    components: {
        Status,
        MenuBar,
    },
    data() {
        return {};
    },

    // components: { ExclamatoryIcon, MenuIcon, Status, ConfirmationModal },

    methods: {
        formatDate(value) {
            return moment(value).format("DD.MM.YYYY");
        },
        // updateStatus(status) {
        //     const title = status == 1 ? "Spielgruppe aktivieren?" : "Spielgruppe deaktivieren?"
        //     const description = status == 1 ?
        //         `Sind Sie sicher, dass Sie die Spielgruppe “${this.register.child_first_name} ${this.register.child_last_name}” wirklich aktivieren wollen?”`
        //         : `Sind Sie sicher, dass Sie die Spielgruppe “${this.register.child_first_name} ${this.register.child_last_name}” wirklich deaktivieren wollen?`

        //     EventBus.$emit('confirmation-modal:show', {
        //         title,
        //         description,
        //         noClick: () => EventBus.$emit('confirmation-modal:hide'),
        //         yesClick: () => {
        //             EventBus.$emit('confirmation-modal:hide')
        //             axios.put(`/api/anmeldungens/${this.register.id}/update-status`, {
        //                 status,
        //             }).then(res => {
        //                 EventBus.$emit('notification-modal:show', res.data.message)
        //                 this.$emit('updateResult')
        //             })
        //         },
        //     })

        // },

        // updateMarked(marked) {
        //     axios.put(`/api/anmeldungens/${this.register.id}/update-marked`, {
        //         marked,
        //     }).then(res => {
        //         EventBus.$emit('notification-modal:show', res.data.message)
        //         this.$emit('updateResult')
        //     })
        // }
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
