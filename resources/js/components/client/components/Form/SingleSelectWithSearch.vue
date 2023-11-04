<template>
    <div class="single-select-filter" v-click-outside="() => opened = false">
        <div class="label" :class="{selected: $attrs.value, error: $attrs.error || errorMessage}" @click="toggle()" style="cursor: pointer">
            <span>{{ placeholderText }}</span>
            <DownArrowIcon />
        </div>
        <div class="dropdown-content" v-show="opened">
            <input type="text" placeholder="Suche…" class="searchInput" @keyup="search($event)"/>
            <ul class="options">
                <li v-for="option in options" :key="option[valueKey]" class="option"
                    :class="{selected: option[valueKey] == $attrs.value}" @click="setSelected(option)">
                    {{ option[labelKey] }}
                </li>
            </ul>
        </div>
        <p v-if="errorMessage" class="error-message">{{errorMessage}}</p>
    </div>

</template>

<script>
import DownArrowIcon from "../DownArrowIcon.vue";
import Checkbox from "../Checkbox.vue";
import axios from "../../../../axios";
export default {
    props: {
        placeholder: {
            type: String,
            required: true,
        },

        options: {
            type: Array,
            default: () => []
        },

        errorMessage: {
            type: String,
            required: false,
        },
        valueKey: {
            type: String,
            default: 'value',
        },
        labelKey: {
            type: String,
            default: 'label',
        },
        searchType:{
            required: true,
            type: String
        }
    },
    emit: ['input', 'setOptions'],
    components: { DownArrowIcon, Checkbox },

    data() {
        return {
            opened: false,
        }
    },

    computed: {
        placeholderText: {
            get() {
                const selectedItem = this.options.find(item => item[this.valueKey] == this.$attrs.value);
                if (selectedItem) {
                    return selectedItem[this.labelKey]
                } else {
                    return this.placeholder
                }
            }
        },
    },

    methods: {
        setSelected(option) {
            this.$emit('input', option[this.valueKey])
            this.toggle()
        },

        toggle() {
            this.opened = !this.opened;
        },

        search(e){
            var query = e.target.value;
            if(query != ''){
                if(this.searchType == 'nationalities'){
                    axios.get("/api/get-filterable-nationalities/"+query).then((res) => {
                        this.$emit('setOptions', res.data);
                    });
                }
                if(this.searchType == 'mother_tongues'){
                    axios.get("/api/get-filterable-mother_tongues/"+query).then((res) => {
                        this.$emit('setOptions', res.data);
                    });
                }
            }
        }
    },
}
</script>

<style lang="scss" scoped>
.single-select-filter {
    position: relative;
    height: 40px;

    .label {
        display: flex;
        gap: 10px;
        font-family: 'Nexa';
        font-style: normal;
        font-weight: 700;
        font-size: 15px;
        line-height: 23px;
        color: #87B1CB;
        align-items: center;
        background-color: white;
        justify-content: space-between;
        padding: 14px 11px 14px 24px;
        height: 40px;
    }

    .label.selected {
        color: #2C5B7D;
    }

    .error {
        border: 1px solid #E97D32;
    }

    .error-message {
        width: 100%;
        margin-top: 8px;
        margin-bottom: 0px;
        font-family: 'Nexa';
        font-style: italic;
        font-weight: 400;
        font-size: 12px;
        line-height: 18px;
        text-align: right;
        color: #E97D32;
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
        width: 100%;
        left: 0px;
        padding: 0px;
        z-index: 2;
        cursor: pointer;

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

            &:hover {
                background: #D2EEFC;
            }
        }

        .option.selected {
            background-color: #D2EEFC;
        }
    }

    .searchInput {
        width: 100%;
        box-sizing: border-box;
        background-image: url('/images/search.png');
        background-position: 14px 12px;
        background-repeat: no-repeat;
        font-size: 16px;
        padding: 14px 20px 12px 45px;
        border: none;
        border-bottom: 1px solid #ddd;
        color: #2C5B7D;
    }

    .searchInput:focus {outline: none}

    .dropdown-content {
        width:100%;
        position: absolute;
        z-index: 1;
    }
}
</style>
