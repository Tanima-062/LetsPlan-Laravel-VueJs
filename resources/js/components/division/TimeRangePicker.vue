<template>
    <div class="box">
        <section>
            <date-picker
                v-model="timeRange"
                type="datetime"
                placeholder="00:00 - 00:00"
                :show-time-panel="true"
                @close="handleRangeClose"
                range-separator=" - "
                :format="format"
                value-type="HH:mm"
                range
                :value="value"
                :key="componentKey"
                :style="error ? 'border: 1px solid #E97D32' : ''"
                title-format="HH:mm"
            >
                <template v-slot:icon-calendar>
                    <svg
                        width="34"
                        height="34"
                        viewBox="0 0 34 34"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M18.25 10.3333V9.08333H15.75V10.3333H18.25ZM17 17H15.75C15.75 17.3315 15.8817 17.6495 16.1161 17.8839L17 17ZM19.4494 21.2172L20.3333 22.1011L22.1011 20.3333L21.2172 19.4494L19.4494 21.2172ZM15.75 10.3333V17H18.25V10.3333H15.75ZM16.1161 17.8839L19.4494 21.2172L21.2172 19.4494L17.8839 16.1161L16.1161 17.8839ZM30.75 17C30.75 24.5939 24.5939 30.75 17 30.75V33.25C25.9746 33.25 33.25 25.9746 33.25 17H30.75ZM17 30.75C9.40608 30.75 3.25 24.5939 3.25 17H0.75C0.75 25.9746 8.02537 33.25 17 33.25V30.75ZM3.25 17C3.25 9.40608 9.40608 3.25 17 3.25V0.75C8.02537 0.75 0.75 8.02537 0.75 17H3.25ZM17 3.25C24.5939 3.25 30.75 9.40608 30.75 17H33.25C33.25 8.02537 25.9746 0.75 17 0.75V3.25Z"
                            fill="#001A72"
                        />
                    </svg>
                </template>
            </date-picker>
        </section>
    </div>
</template>

<script>
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import "vue2-datepicker/locale/de";
export default {
    components: {
        DatePicker,
    },
    data() {
        return {
            time: [],
            componentKey: 0,
        };
    },
    props: {
        format: {
            type: String,
            default() {
                return "HH:mm";
            },
        },
        value: {
            type: Array,
            default: () => [],
        },
        label: String,
        error: [String, Boolean],
    },

    methods: {
        handleOpenChange() {
            this.showTimePanel = false;
        },
        handleRangeClose() {
            this.showTimeRangePanel = false;
        },
    },

    computed: {
        timeRange: {
            get: function () {
                if (this.value[0] && this.value[1]) {
                    // console.log(this.value[0], this.value[1])
                    return [this.value[0], this.value[1]];
                }
                return [];
            },
            set: function (value) {
                //    console.log(value)
                let startTime = value[0];
                let endTime = value[1];
                if (startTime && endTime) {
                    startTime = startTime;
                    endTime = endTime;
                }
                this.$emit("changeTime", {
                    start: startTime,
                    end: endTime,
                });
            },
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
    background: #fff !important;
    font-family: "Nexa", sans-serif;
    border-radius: 0;
    padding: 15px 24px;
}

.mx-datepicker svg {
    width: 2rem;
    height: 2rem;
}

.mx-input::placeholder {
    font-family: "Nexa" !important;
    font-style: normal !important;
    font-weight: 700 !important;
    font-size: 15px !important;
    line-height: 23px !important;
    color: #87b1cb !important;
}

.mx-datepicker {
    border-radius: 0;
    margin-top: 0;
}

.form-input {
    border-width: 0;
}

.mx-time-list {
    flex-direction: column;
}

.mx-time-header {
    display: none;
}

.mx-input-wrapper {
    background: white;
}
</style>
