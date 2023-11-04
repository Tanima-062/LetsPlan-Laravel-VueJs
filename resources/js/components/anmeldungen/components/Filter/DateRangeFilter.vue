<template>
    <div
        class="datepicker"
        v-click-outside="() => opened = false"
    >
        <div
            class="label"
            @click.stop="opened = !opened"
            style="cursor: pointer"
        >
            <span>{{labelText}}</span>
            <DownArrowIcon />
        </div>

        <v-date-picker
            v-model="dateRange"
            is-range
            title-position="left"
            color="green"
            v-if="opened"
            :masks="{input: 'DD.MM.YYYY', title: 'MMM YYYY'}"
        >
            <template v-slot="{ inputValue, inputEvents }">
                <div class="filter-date-range-picker">
                    <input
                        id="date"
                        class="challo__input"
                        :value="inputValue.start"
                        v-on="inputEvents.start"
                        :placeholder="'TT.MM.JJJJ'"
                    />
                    <span class="">
                        <svg viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"
                            />
                        </svg>
                    </span>
                    <input
                        id="date"
                        class="challo__input"
                        :value="inputValue.end"
                        v-on="inputEvents.end"
                        :placeholder="'TT.MM.JJJJ'"
                    />

                    <button
                        class="clear-btn challo__btn"
                        @click.prevent="dateRange = null"
                    >Löschen</button>
                </div>
            </template>
        </v-date-picker>
    </div>
</template>

<script>
import { debounce as _debounce } from "lodash";
import DownArrowIcon from "../DownArrowIcon.vue";
import dayjs from 'dayjs'

export default {
    props: {
        label: {
            type: String,
            default: 'Datum'
        },
        columnKey: {
            type: String,
            default: null
        }
    },
    components: {
        DownArrowIcon
    },

    data() {
        const params = new URLSearchParams(location.search)

        const start_date = params.get(`${this.column || ''}start_date`)
        const end_date = params.get(`${this.column || ''}end_date`)

        const range = start_date && end_date ? { start: dayjs(start_date).toISOString(), end: dayjs(end_date).toISOString() } : null;

        console.log(range)

        return {
            opened: false,
            dateRange: range
        }
    },
    computed: {
        labelText() {
            return this.label
        }
    },

    methods: {
        setFilterToQueryString() {
            const params = new URLSearchParams(location.search)

            const start_date = this.dateRange?.start
            const end_date = this.dateRange?.end

            if (start_date && end_date) {
                params.append(`${this.column || ''}start_date`, dayjs(start_date).format('YYYY-MM-DD'))
                params.append(`${this.column || ''}end_date`, dayjs(end_date).format('YYYY-MM-DD'))
            } else {
                params.delete(`${this.column || ''}start_date`)
                params.delete(`${this.column || ''}end_date`)
            }

            if (this.selected == null) {
                this.$emit('updateFilterable')
            }

            this.$router.push({ name: 'anmeldungen', query: Object.fromEntries(params) })
            this.$emit('updateResult')
        }
    },

    watch: {
        dateRange() {
            this.setFilterToQueryString()
        }
    },
};
</script>

<style lang="scss" scoped>
.datepicker {
    position: relative;
    display: flex;
    flex-direction: column;

    .label {
        font-family: 'Nexa';
        font-style: normal;
        font-weight: 400;
        font-size: 16px;
        line-height: 24px;
        color: #4C85A7;
    }
}

.filter-date-range-picker {
    display: flex;
    justify-content: space-between;
}

.filter-date-range-picker {
    display: flex;
    justify-content: space-between;
    max-width: 450px;
    background-color: white;
    padding: 10px;
    position: absolute;
    top: 25px;
    border: 1px solid #FEF1E7;
    align-items: center;

    input {
        width: 100px;
        color: #4C85A7;
    }

    span {
        // width: 50px;
        margin-left: 5px;
        margin-right: 10px;
        color: currentColor;
        font-size: 36px;
        display: flex;
        align-items: center;

        svg {
            fill: currentColor;
            width: 18px;
            height: 18px;
            ;
            stroke: #787878;
            ;
        }
    }

    .clear-btn {
        outline: none;
        border: none;
        text-align: center;
        font-weight: 700;
        padding: 7px 25px;
        margin-left: 10px;
        font-family: 'Nexa';
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
        cursor: pointer;
    }
}
</style>
