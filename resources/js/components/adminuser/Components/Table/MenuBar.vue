<template>
    <div
        class="menu-bar"
        @click="menubar_is_opened = !menubar_is_opened"
        v-click-outside="() => (menubar_is_opened = false)"
    >
        <MenuIcon style="cursor: pointer" :fill="bgColor" />
        <ul
            class="options"
            v-if="menubar_is_opened"
            @click="menubar_is_opened = false"
        >
            <li
                class="option"
                v-if="status != 2"
                @click="updateStatus(2)"
            >
                Deaktivieren
            </li>
            <li
                class="option"
                v-if="status == 2"
                @click="updateStatus(3)"
            >
                Aktivieren
            </li>
            <router-link
                class="option"
                tag="li"
                :to="{ name: 'adminuser_edit', params: { id: user.id } }"
                >Bearbeiten</router-link
            >
            <li
                class="option"
                v-if="status == 3"
                @click="sendInvitation"
            >
                Einladung senden
            </li>
        </ul>
    </div>
</template>

<script>
import MenuIcon from "../Icons/MenuIcon.vue";
import axios from "../../../../axios";
export default {
    props: {
        user: {
            type: Object,
            required: true,
        },
    },
    components: {
        MenuIcon,
    },
    data() {
        return {
            menubar_is_opened: false,
            status: this.user.status_id
        };
    },
    computed: {
        bgColor() {
            return this.menubar_is_opened ? "#4C85A7" : "#8BC5E1";
        },
    },
    mounted(){
        EventBus.$on(`update-status-${this.user.id}`, (e)=>{
            this.status = e
        })
    },
    methods: {
        updateStatus(status) {
            EventBus.$emit("show-confirm-modal", {
                status_id: status,
                user: this.user,
            });
        },
        sendInvitation() {
            console.log("send invitation");

            //route url
            //benutzer/send-inviatation/{benutzer}

            axios.post(`/api/benutzer/send-inviatation/${this.user.id}`).then(res => {
                // this.successMessage('Invitation sent successfully')
                // this.getUser()
                const text = `Die E-Mail Einladung für "${this.user.first_name} ${this.user.last_name}" wurde erfolgreich gesendet.`
                EventBus.$emit('show-notification', text)
            }).catch(err => {
                this.errorMessage('Failed to send invitation')
            })
        },
        goToDetailsPage(id) {
            this.$router.push({name: 'user_details', params: {id: this.user.id}});
        },
    },
};
</script>

<style lang="scss" scoped>
.table-row .menu-bar {
    .options {
        position: absolute;
        min-width: 160px;
        background-color: white;
        z-index: 1;
        right: 0px;
        display: flex;
        flex-direction: column;
        list-style: none;
        margin: 0;
        padding: 0;
        align-items: flex-start;
        width: 160px;

        .option {
            margin: 0;
            padding: 0;
            font-family: "Nexa";
            font-style: normal;
            font-weight: 400;
            font-size: 15px;
            line-height: 20px;
            color: #135f84;
            padding: 10px 15px;
            border-bottom: 1px solid #fef1e7;
            border-top: none;
            width: 100%;

            cursor: pointer;

            &:last-child {
                border: none;
            }

            &:hover {
                background: #bee7fb;
            }
        }
    }
}

// .table-row .menu-bar > .options > .option {
//     // border-bottom: none;
// }
</style>
