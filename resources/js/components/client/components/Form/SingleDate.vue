<template>
    <v-date-picker
        v-model="date"
        title-position="left"
        color="green"
        :masks="{ input: 'DD.MM.YYYY', title: 'MMM YYYY' }"
    >
        <template v-slot="{ inputValue, inputEvents }">
            <div
                class="date-input"
                :class="{ error: $attrs.error || $attrs.errorMessage }"
            >
                <input
                    :value="inputValue"
                    v-on="inputEvents"
                    :placeholder="'TT.MM.JJJJ'"
                />
                <svg
                    width="30"
                    height="34"
                    viewBox="0 0 30 34"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    style="pointer-events: none"
                >
                    <path
                        d="M1.66797 14.0833H0.417969V16.5833H1.66797V14.0833ZM21.2513 2V0.75H18.7513V2H21.2513ZM18.7513 8.66667V9.91667H21.2513V8.66667H18.7513ZM11.2513 2V0.75H8.7513V2H11.2513ZM8.7513 8.66667V9.91667H11.2513V8.66667H8.7513ZM28.3346 14.0833H1.66797V16.5833H28.3346V14.0833ZM2.91797 28.6667V8.66667H0.417969V28.6667H2.91797ZM5.0013 30.75C3.85071 30.75 2.91797 29.8173 2.91797 28.6667H0.417969C0.417969 31.198 2.47 33.25 5.0013 33.25V30.75ZM5.0013 4.08333C2.47 4.08333 0.417969 6.13536 0.417969 8.66667H2.91797C2.91797 7.51607 3.85071 6.58333 5.0013 6.58333V4.08333ZM29.5846 28.6667V15.3333H27.0846V28.6667H29.5846ZM29.5846 15.3333V8.66667H27.0846V15.3333H29.5846ZM25.0013 33.25C27.5326 33.25 29.5846 31.198 29.5846 28.6667H27.0846C27.0846 29.8173 26.1519 30.75 25.0013 30.75V33.25ZM25.0013 6.58333C26.1519 6.58333 27.0846 7.51607 27.0846 8.66667H29.5846C29.5846 6.13536 27.5326 4.08333 25.0013 4.08333V6.58333ZM25.0013 4.08333H5.0013V6.58333H25.0013V4.08333ZM25.0013 30.75H5.0013V33.25H25.0013V30.75ZM18.7513 2V8.66667H21.2513V2H18.7513ZM8.7513 2V8.66667H11.2513V2H8.7513Z"
                        fill="#063F5F"
                    />
                </svg>
            </div>
        </template>
    </v-date-picker>
</template>

<script>
import { debounce as _debounce } from "lodash";
import DownArrowIcon from "../DownArrowIcon.vue";
import dayjs from "dayjs";

export default {
    components: {
        DownArrowIcon,
    },

    emit: ["input"],

    computed: {
        date: {
            get: function () {
                return this.$attrs.value;
            },
            set: function (value) {
                if (value) {
                    value = dayjs(value).format("YYYY-MM-DD");
                }
                this.$emit("input", value);
            },
        },
    },
};
</script>

<style lang="scss" scoped>
.date-input {
    display: flex;
    background-color: white;
    position: relative;

    input {
        display: flex;
        width: 100%;
        height: 40px;
        padding: 6px 6px 6px 24px;
        font-family: "Nexa";
        font-style: normal;
        font-weight: 700;
        font-size: 15px;
        line-height: 23px;
        color: #2c5b7d;
    }

    svg {
        position: absolute;
        top: 2px;
        right: 6px;
    }
}

.error {
    border: 1px solid #e97d32;
}
</style>
