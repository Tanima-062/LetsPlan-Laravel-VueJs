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
                @click="deleteDivision"
                v-if="division.status_id == 1
                && user.role_id == 1
                "
            >
                Löschen
            </li>
            <li class="option" @click="showModal" v-if="user.role_id == 1">Bearbeiten</li>
            <!-- <router-link
                class="option"
                tag="li"
                :to="{ name: 'division_edit', params: { id: division.id } }"
                >Bearbeiten</router-link
            > -->
            <router-link
                class="option"
                tag="li"
                :to="{ name: 'division_details', params: { id: division.id } }"
                >Details ansehen</router-link
            >
        </ul>
    </div>
</template>

<script>
import MenuIcon from "../Icons/MenuIcon.vue";
import axios from "../../../../axios";
export default {
    props: {
        division: {
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
            status: this.division.status_id,
        };
    },
    computed: {
        bgColor() {
            return this.menubar_is_opened ? "#4C85A7" : "#8BC5E1";
        },
        user(){
            return this.$store.getters['user'];
        }
    },
    mounted() {
        // EventBus.$on(`update-status-${this.division.id}`, (e)=>{
        //     this.status = e
        // })
    },
    methods: {
        showModal() {
            if (this.division.status_id == 1) {
                this.$router.push({
                    name: "division_edit",
                    params: { id: this.division.id },
                });
            } else {
                EventBus.$emit("show-edit-modal", {
                    division: this.division,
                });
            }
        },
        deleteDivision() {
            console.log("Delete divisino");
            //
            EventBus.$emit("show-confirm-modal", {
                division: this.division,
            });
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
