<template>
    <div
        class="multi-select-filter"
        ref="multi_select_filter"
        v-click-outside="() => (opened = false)"
    >
        <div class="label">
            <span>{{ labelText }}</span>
            <span @click="toggle()"><DownArrowIcon /></span>
        </div>
        <ul class="options" v-show="opened">
            <li
                v-for="option in options"
                :key="option[valueKey]"
                @click.stop="setSelectedItems(option[valueKey])"
                class="option"
            >
                <label>
                    <Checkbox
                        :value="option[valueKey]"
                        :checked="
                            selectedItems.some(
                                (item) => item == option[valueKey]
                            )
                        "
                    />
                    {{ option[labelKey] }}
                </label>
            </li>
        </ul>
    </div>
</template>

<script>
import DownArrowIcon from "../DownArrowIcon.vue";
import Checkbox from "../Checkbox.vue";
import { thisExpression } from "@babel/types";
export default {
    props: {
        label: {
            type: String,
            required: true,
        },
        options: {
            type: Array,
            default: [],
        },
        valueKey: {
            type: String,
            default: "value",
        },
        labelKey: {
            type: String,
            default: "label",
        },
        column: {
            type: String,
            required: true,
        },
    },
    emit: ["updateData"],
    components: { DownArrowIcon, Checkbox },

    data() {
        return {
            opened: false,
            selectedItems: [],
        };
    },

    computed: {
        labelText() {
            if (this.selectedItems.length && this.options.length) {
                return this.options
                    .find(
                        (item) => item[this.valueKey] == this.selectedItems[0]
                    )
                    [this.labelKey].concat(
                        this.selectedItems.length > 1
                            ? `, +${this.selectedItems.length - 1}`
                            : ""
                    );
            } else {
                return this.label;
            }
        },
    },

    methods: {
        setSelectedItems(value) {
            if (this.selectedItems.some((item) => item == value)) {
                this.selectedItems = this.selectedItems.filter(
                    (item) => item != value
                );
            } else {
                this.selectedItems.push(value);
            }
            this.setFilterToQueryString();
        },

        toggle() {
            this.opened = !this.opened;
            if (this.opened == true) {
                this.$emit("updateFilterable");
            }
        },

        setFilterToQueryString() {
            const params = new URLSearchParams(location.search);
            if (this.selectedItems.length) {
                params.append(this.column, this.selectedItems.join(","));
            } else {
                params.delete(this.column);
            }
            this.$router.push({
                name: "spielgruppen",
                query: Object.fromEntries(params),
            });
            this.$emit("updateResult");
        },
    },

    watch: {
        selectedItems: {
            handler(newV, prevV) {
                if (newV.length < prevV.length) {
                    this.$emit("updateFilterable");
                }
            },
        },
    },

    mounted() {
        const params = new URLSearchParams(location.search);
        this.selectedItems = params.get(this.column)?.split(",") || [];
        this.setFilterToQueryString();
        this.$emit("updateFilterable");
    },
};
</script>

<style lang="scss" scoped>
.multi-select-filter {
    position: relative;

    .label {
        span {
            font-family: "Nexa";
            font-style: normal;
            font-weight: 400;
            font-size: 16px;
            line-height: 24px;
            color: #4c85a7;
            cursor: pointer;
        }
    }

    .options {
        position: absolute;
        display: flex;
        flex-direction: column;
        list-style: none;
        background-color: white;
        min-width: 268px;
        left: 0px;
        padding: 0px;
        z-index: 99;

        .option {
            border-top: 1px solid #fef1e7;
            width: 100%;

            label {
                display: flex;
                padding: 8px;
                gap: 8px;

                font-family: "Nexa";
                font-style: normal;
                font-weight: 700;
                font-size: 15px;
                line-height: 23px;
                display: flex;
                align-items: center;
                color: #2c5b7d;
            }
        }
    }
}
</style>
