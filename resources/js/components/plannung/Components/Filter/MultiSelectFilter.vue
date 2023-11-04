<template>
    <div class="multi-select-filter" ref="multi_select_filter" v-click-outside="() => opened = false">
        <div class="label" @click="loadOptions">
            <span>{{ labelText }}</span>
            <span class="ml-[10px]">
                <DownArrowIcon />
            </span>
        </div>
        <ul class="options" v-show="opened">
            <li class="option" v-for="option in options" :key="option[valueKey]">
                <label>
                    <input type="checkbox" :value="option[valueKey]" v-model="selectedItems" class="option_checkbox" />
                    {{ option[nameKey] }}
                </label>
            </li>
        </ul>
    </div>

</template>

<script>
import DownArrowIcon from "../Icons/ArrowDown.vue";
import Checkbox from "./Checkbox.vue";
import { debounce as _debounce } from "lodash";
import axios from "../../../../axios";


export default {
    props: {
        label: {
            type: String,
            required: true,
        },
        valueKey: {
            type: String,
            default: "id"
        },
        nameKey: {
            type: String,
            default: "name"
        },
        column: {
            type: String,
            required: true,
        },
        routeName: {
            type: String,
            required: true,
        },
        apiPath: {
            type: String,
            required: true
        }
    },
    components: { DownArrowIcon, Checkbox },

    data() {
        return {
            opened: false,
            selectedItems: [],
            options: []
        }
    },

    computed: {
        labelText() {
            if (this.selectedItems.length && this.options.length) {
                const option = this.options.find(item => item[this.valueKey] == this.selectedItems[0])
                if (option) {
                    const length = this.selectedItems.length
                    const text = `${option[this.nameKey]} ${length > 1 ? (', +' + String(length - 1)) : ''}`

                    return text;
                }

                return this.label
            }

            return this.label
        }
    },
    watch: {
        selectedItems: {
            handler: _debounce(async function (newValue, oldValue) {
                const query = { ...this.$route.query }
                query[this.column] = this.selectedItems.join(',')
                this.$router.push({ name: this.routeName, query })

                this.$emit('updateResult')
            }, 500),
            deep: true
        },
    },
    mounted() {
        this.getSelectdOptionsFromUrl()
        this.getOptions()
    },
    methods: {
        getOptions() {
            console.log('get options')
            this.opened = true;

            const query = { ...this.$route.query }
            query['column'] = this.column

            const queryString = new URLSearchParams(query).toString();

            axios.get(`/api/${this.apiPath}?${queryString}`).then(res => {
                this.options = res.data;
            }).catch(err => {
                console.log(err)
            })
        },
        loadOptions() {
            if (this.opened) {
                this.opened = false;
            } else {
                this.getOptions()
            }
        },
        getSelectdOptionsFromUrl() {
            const queries = this.$route.query;
            Object.keys(queries).forEach(key => {
                if (key == this.column && queries[key]) {
                    this.selectedItems = String(queries[key]).split(',')
                }
            })
        }
    }
}
</script>

<style lang="scss" scoped>
.multi-select-filter {
    position: relative;

    .label {
        display: flex;
        gap: 10px;
        align-items: center;
        cursor: pointer;

        span {
            font-family: 'Nexa';
            font-style: normal;
            font-weight: 400;
            font-size: 16px;
            line-height: 24px;
            color: #4C85A7;
        }
    }

    .options {
        position: absolute;
        display: flex;
        flex-direction: column;
        list-style: none;
        background-color: white;
        min-width: 268px;
        max-height: 350px;
        overflow: auto;
        left: 0px;
        padding: 0px;
        top: 40px;

        border: 1px solid #FEF1E7;
        max-height: 300px;
        overflow-y: auto;

        .option {
            border-top: 1px solid #FEF1E7;

            &:first-child {
                border-top: 0;
            }

            width: 100%;

            label {
                font-family: 'Nexa';
                font-style: normal;
                font-weight: 400;
                font-size: 16px;
                line-height: 24px;

                color: #4C85A7;
                cursor: pointer;

                display: flex;
                align-items: center;
                gap: 10px;
                padding: 10px;

                .option_checkbox {
                    appearance: none;

                    width: 24px;

                    &::before {
                        content: '';
                        width: 24px;
                        height: 24px;
                        background-color: #ffffff;
                        display: block;
                        outline: 2px solid #C8C8C8;
                        cursor: pointer;
                    }
                }

                .option_checkbox:checked {
                    &::before {
                        // content: '';
                        // width: 30px;
                        // height: 30px;
                        outline: 2px solid #C8C8C8;
                        border: 5px solid #ffffff;
                        padding: 5px;
                        background-color: #9FDCF8;
                        ;
                        display: block;
                    }
                }

                ;
            }
        }
    }
}
</style>
