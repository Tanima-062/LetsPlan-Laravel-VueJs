<template>
    <div class="single-select-filter" v-click-outside="() => opened = false" style="cursor: pointer">
        <div class="label" @click="toggle()">
            <span>{{ labelText }}</span>
            <DownArrowIcon />
        </div>
        <ul class="options" v-show="opened && options.length > 0">
            <li v-for="option in options" :key="option[valueKey]" class="option"
                :class="{selected: option[valueKey] == selected}" @click="setSelected(option[valueKey])">
                {{ option[labelKey] }}
            </li>
        </ul>
    </div>

</template>

<script>
import DownArrowIcon from "../DownArrowIcon.vue";
import Checkbox from "../Checkbox.vue";
export default {
    props: {
        label: {
            type: String,
            required: true,
        },
        options: {
            type: [Array, Object],
            default: [],
        },
        valueKey: {
            type: String,
            default: "value"
        },
        labelKey: {
            type: String,
            default: "label"
        },
        column: {
            type: String,
            required: true,
        },
    },
    emit: ['updateResult', 'updateFilterable'],
    components: { DownArrowIcon, Checkbox },

    data() {
        return {
            opened: false,
            selected: null,
        }
    },

    computed: {
        labelText: {
            get() {
                if(this.options.length && this.selected !== null) {
                    return this.options.find(item => item[this.valueKey] == this.selected)[this.labelKey]
                } else {
                    return this.label
                }
                // return this.selected == null ? this.label : this.options?.find()
            }
        },

    },

    methods: {

        setSelected(value) {
            this.selected = this.selected == value ? null : value
            this.setFilterToQueryString()
        },

        toggle() {
            this.opened = !this.opened;
            if (this.opened == true) {
                this.$emit('updateFilterable')
            }
        },

        setFilterToQueryString() {
            const params = new URLSearchParams(location.search)
            if (this.selected !== null) {
                params.append(this.column, this.selected)
            } else {
                params.delete(this.column)
            }
            this.$router.push({ name: 'anmeldungen', query: Object.fromEntries(params) })
            this.$emit('updateResult')

            if (this.selected == null) {
                this.$emit('updateFilterable')
            }
        }
    },

    mounted() {
        const params = new URLSearchParams(location.search)
        this.selected = params.get(this.column) || null
        this.setFilterToQueryString()
        this.$emit('updateFilterable')
    },
}
</script>

<style lang="scss" scoped>
.single-select-filter {
    position: relative;

    .label {
        display: flex;
        gap: 10px;
        align-items: center;
        font-family: 'Nexa';
        font-style: normal;
        font-weight: 400;
        font-size: 16px;
        line-height: 24px;
        color: #4C85A7;
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

        .option {
            border-top: 1px solid #FEF1E7;
            width: 100%;
            display: flex;
            padding: 8px;
            gap: 8px;
            font-family: 'Nexa';
            font-style: normal;
            font-weight: 700;
            font-size: 15px;
            line-height: 23px;
            display: flex;
            align-items: center;
            color: #2C5B7D;
        }

        .option.selected {
            background-color: #D2EEFC;
        }
    }
}
</style>
