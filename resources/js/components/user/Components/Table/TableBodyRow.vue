<template>
    <div class="table-row">
        <div class="td" style="width: 13%;">
            <div class="avatar" :style="user.image ? '' : 'background: #063F5F;'  ">
                <img :src="user.image" alt="" v-if="user.image">
            </div>
        </div>
        <div class="td" style="width: 20%;">{{ user.first_name }} {{ user.last_name }} </div>
        <div class="td" style="width: 20%;">{{ user.role_name }}</div>
        <div class="td" style="width: 30%;word-break: break-all;">{{ user.email }}</div>
        <div class="td" style="width: 15%;"> {{ user.prefix }} {{ user.phone_number }} </div>
        <div class="td" style="width: 5%;">
            <UserStatus :user="user" />
        </div>
        <div class="td" style="width: 2%;">
            <MenuBar :user="user" />
        </div>
    </div>

</template>
<script >
// import ExclamatoryIcon from './ExclamatoryIcon.vue';
// import MenuIcon from '../MenuIcon.vue';
// import Status from './Status.vue';
// import axios from '../../../axios'
// import ConfirmationModal from './ConfirmationModal.vue';
import UserStatus from './UserStatus.vue'
import MenuBar from './MenuBar.vue'
export default {
    props: {
        user: {
            type: Object,
            required: true,
        }
    },
    components: {
        UserStatus,
        MenuBar
    },
    data() {
        return {

        }
    },

    // components: { ExclamatoryIcon, MenuIcon, Status, ConfirmationModal },

    methods: {
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
}
</script>

<style lang="scss" scoped>
.table-row {
    border-top: 1px solid #D3E3EC;
    background: #E9F7FE;
    min-height: 56px;
    display: flex;
    align-items: center;
    padding: 0px 22px 0px 25px;

    &:hover {
        background-color: #D2EEFC;
    }

    .td {
        font-family: 'Nexa';
        font-style: normal;
        font-weight: 400;
        font-size: 18px;
        line-height: 27px;
        color: #2C5B7D;

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

                font-family: 'Nexa';
                font-style: normal;
                font-weight: 400;
                font-size: 15px;
                line-height: 23px;
                color: #2C5B7D;


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
                font-family: 'Nexa';
                font-style: normal;
                font-weight: 400;
                font-size: 15px;
                line-height: 20px;
                color: #135F84;
                padding: 11px 16px 11px 16px;
                border-top: 1px solid #FEF1E7;
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
