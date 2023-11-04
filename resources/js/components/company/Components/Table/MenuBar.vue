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
                v-if="status == 1"
                @click="updateStatus(0)"
            >
                Deaktivieren
            </li>
            <li
                class="option"
                v-if="status == 0"
                @click="updateStatus(1)"
            >
                Aktivieren
            </li>
            <router-link
                class="option"
                tag="li"
                :to="{ name: 'company_edit', params: { id: company.id } }"
                >Bearbeiten</router-link
            >
        </ul>
    </div>
</template>

<script>
import MenuIcon from "../Icons/MenuIcon.vue";
import axios from "../../../../axios";
export default {
    props: {
        company: {
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
            status: this.company.status
        };
    },
    computed: {
        bgColor() {
            return this.menubar_is_opened ? "#4C85A7" : "#8BC5E1";
        },
    },
    mounted(){
        EventBus.$on(`update-status-${this.company.id}`, (e)=>{
            this.status = e
        })
    },
    methods: {
        updateStatus(status) {
            EventBus.$emit("show-confirm-modal", {
                status: status,
                company: this.company,
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
