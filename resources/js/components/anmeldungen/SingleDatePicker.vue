<template>
    <div>
        <label v-if="label" class="input-title" :for="id"
            >{{ label }} <sup v-if="required">*</sup></label
        >
        <date-picker
            :id="id"
            v-model="dateRange"
            type="date"
            :format="format"
            :value-type="valueFormat"
            v-bind="$attrs"
            :placeholder="placeholder"
            :lang="lang"
            class="form-input pickdate"
            :class="{ error: error }"
            :value="value"
            range-separator="-"
            :style="error ? 'border: 1px solid #E97D32' : ''"
            :append-to-body="false"
            title-format="DD.MM.YYYY"
        >
            <template v-slot:icon-calendar>
                <svg
                    width="30"
                    height="34"
                    viewBox="0 0 30 34"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M1.66602 14.0833H0.416016V16.5833H1.66602V14.0833ZM21.2493 2V0.75H18.7493V2H21.2493ZM18.7493 8.66667V9.91667H21.2493V8.66667H18.7493ZM11.2493 2V0.75H8.74935V2H11.2493ZM8.74935 8.66667V9.91667H11.2493V8.66667H8.74935ZM28.3327 14.0833H1.66602V16.5833H28.3327V14.0833ZM2.91602 28.6667V8.66667H0.416016V28.6667H2.91602ZM4.99935 30.75C3.84876 30.75 2.91602 29.8173 2.91602 28.6667H0.416016C0.416016 31.198 2.46804 33.25 4.99935 33.25V30.75ZM4.99935 4.08333C2.46804 4.08333 0.416016 6.13536 0.416016 8.66667H2.91602C2.91602 7.51607 3.84876 6.58333 4.99935 6.58333V4.08333ZM29.5827 28.6667V15.3333H27.0827V28.6667H29.5827ZM29.5827 15.3333V8.66667H27.0827V15.3333H29.5827ZM24.9993 33.25C27.5307 33.25 29.5827 31.198 29.5827 28.6667H27.0827C27.0827 29.8173 26.1499 30.75 24.9993 30.75V33.25ZM24.9993 6.58333C26.1499 6.58333 27.0827 7.51607 27.0827 8.66667H29.5827C29.5827 6.13536 27.5307 4.08333 24.9993 4.08333V6.58333ZM24.9993 4.08333H4.99935V6.58333H24.9993V4.08333ZM24.9993 30.75H4.99935V33.25H24.9993V30.75ZM18.7493 2V8.66667H21.2493V2H18.7493ZM8.74935 2V8.66667H11.2493V2H8.74935Z"
                        fill="#063F5F"
                    />
                </svg>
            </template>
        </date-picker>
        <!-- <div v-if="error" class="my-2 text-danger">{{ error }}</div> -->
    </div>
</template>

<script>
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import "vue2-datepicker/locale/de";
import dayjs from "dayjs";

export default {
    components: { DatePicker },
    inheritAttrs: false,
    props: {
        id: {
            type: String,
            default() {
                return `text-input-${this._uid}`;
            },
        },
        required: {
            type: Boolean,
            default: false,
        },
        format: {
            type: String,
            default() {
                return "DD.MM.YYYY";
            },
        },
        valueFormat: {
            type: String,
            default() {
                return "YYYY-MM-DD";
            },
        },
        placeholder: {
            type: String,
            default: "",
        },
        label: String,
        error: [String, Boolean],
        value: null,
    },
    data() {
        return {
            lang: {
                monthFormat: "MMMM",
            },
        };
    },

    computed: {
        dateRange: {
            get: function () {
                if (this.value && this.value.start !== null) {
                    console.log("get", this.value);
                    return this.value;
                } else {
                    return null;
                }
            },
            set: function (value) {
                let startDate = value;

                if (startDate !== null) {
                    startDate = dayjs(startDate).format("YYYY-MM-DD");
                } else {
                    startDate = null;
                }
                this.$emit("changeDate", {
                    start: startDate,
                });
            },
        },
    },
    methods: {
        focus() {
            this.$refs.input.focus();
        },
        select() {
            this.$refs.input.select();
        },
    },
};
</script>
<style lang="scss">
.text-danger {
    font-weight: 500 !important;
    font-size: 12px !important;
    line-height: 14px !important;
}

.mx-datepicker .mx-input {
    height: 40px;
    border: 0;
    background: white;
    font-style: normal;
    font-weight: 700;
    font-size: 15px;
    line-height: 23px;
    color: #2c5b7d;
    border-radius: 0;
    padding: 15px 24px;
}

.mx-datepicker svg {
    width: 2rem;
    height: 2rem;
}

.mx-input::placeholder {
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 15px;
    line-height: 23px;
    color: #87b1cb;
}

.mx-datepicker {
    border-radius: 0;
    margin-top: 0;
}

.form-input {
    border-width: 0;
}

.pickdate .mx-datepicker-main {
    left: 46% !important;
}
</style>
