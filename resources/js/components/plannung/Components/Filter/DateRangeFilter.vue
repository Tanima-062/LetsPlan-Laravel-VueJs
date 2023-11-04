<template>
    <div class="datepicker" v-click-outside="() => opened = false">
        <div class="label" @click.stop="opened = !opened">
            <span>{{labelText}}</span>
            <DownArrowIcon />
        </div>

        <v-date-picker v-model="dateRange" is-range title-position="left" color="green" v-if="opened"
            :masks="{input: 'DD.MM.YYYY', title: 'MMM YYYY'}">
            <template v-slot="{ inputValue, inputEvents }">
                <div class="filter-date-range-picker">
                    <input id="date" class="challo__input" :value="inputValue.start" v-on="inputEvents.start"
                        :placeholder="'TT.MM.JJJJ'" />
                    <span class="">
                        <svg viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </span>
                    <input id="date" class="challo__input" :value="inputValue.end" v-on="inputEvents.end"
                        :placeholder="'TT.MM.JJJJ'" />

                    <button class="clear-btn challo__btn" @click.prevent="clearDate">Löschen</button>
                </div>
            </template>
        </v-date-picker>
    </div>
</template>

<script>
import { debounce as _debounce } from "lodash";
import DownArrowIcon from "../Icons/ArrowDown.vue";
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
        },
        routeName: {
            type: String,
            required: true,
        },
    },
    components: {
        DownArrowIcon
    },

    data() {
        return {
            opened: false,
            dateRange: null,

            // is_default: true,
            // date: {
            //     start: null,
            //     end: null,
            // },
        }
    },
    computed: {
        labelText() {
            return this.label
        },
        start_date_key(){
            return `${this.column || ''}start_date`;
        },
        end_date_key(){
            return `${this.column || ''}end_date`;
        }
    },
    mounted(){
        this.setDateFromUrl()
    },
    watch: {
        dateRange(cur, old) {
            console.log('calling', cur, old)
            const start_date = cur?.start
            const end_date = cur?.end
            const params = new URLSearchParams(location.search)
            if (start_date && end_date) {
                params.append(this.start_date_key, dayjs(start_date).format('YYYY-MM-DD'))
                params.append(this.end_date_key, dayjs(end_date).format('YYYY-MM-DD'))
            } else {
                params.delete(this.start_date_key)
                params.delete(this.end_date_key)
            }
            this.$router.push({ name: this.routeName, query: Object.fromEntries(params) })
            this.$emit('updateResult')
        },
        // 'date':{
        //     handler: _debounce(async function () {

        //     },500),
        //     deep: true
        // },
    },
    methods: {
        setDateFromUrl(){
             let searchParams = Object.fromEntries(
                new URLSearchParams(location.search)
            );

            let start = null;
            let end = null;

            if (searchParams.hasOwnProperty(this.start_date_key)) {
                start = searchParams[this.start_date_key];
            }
            if (searchParams.hasOwnProperty(this.end_date_key)) {
                end = searchParams[this.end_date_key];
            }

            console.log(start, end)

            const date = {}

            if (start && end) {
                date.start = start;
                date.end = end;
            }

            if (start && !end) {
                date.start = start;
                date.end = start;
            }

            if (end && !start) {
                date.end = end;
                date.start = end;
            }

            this.dateRange = date;
        },
        // buildQueryParams(){
        //     let searchParams = Object.fromEntries(
        //         new URLSearchParams(location.search)
        //     );

        //     if (!is_default) {
        //         searchParams[start_date_key.value] = formatDate(date.start);
        //         searchParams[end_date_key.value] = formatDate(date.end);
        //     }else {
        //         if(searchParams.hasOwnProperty(start_date_key.value)) {
        //             delete searchParams[start_date_key.value];
        //         }
        //         if(searchParams.hasOwnProperty(end_date_key.value)) {
        //             delete searchParams[end_date_key.value];
        //         }
        //     }

        //     if (searchParams.hasOwnProperty("page")) {
        //         delete searchParams["page"];
        //     }

        //     return searchParams;
        // },
        // clearDate(){
        //     compoenentKey.value++;
        //     is_default = true;
        // },
        // formatDate(date){
        //     return new Date(date).toISOString().slice(0, 10);
        // },
        clearDate(){
            const params = new URLSearchParams(location.search)

            params.delete(`${this.column || ''}start_date`)
            params.delete(`${this.column || ''}end_date`)

            this.dateRange = null

            this.$router.push({ name: this.routeName, query: Object.fromEntries(params) })
            this.$emit('updateResult')
        },

    }
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
    top: 40px;
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
