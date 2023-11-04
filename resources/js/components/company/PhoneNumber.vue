<template>
    <div class="phone-input" :class="{ error: $attrs.error || errorMessage }">
        <div class="select-country" v-click-outside="() => (opened = false)">
            <div class="label" @click="toggle()">
                <span v-if="!selectedCountry">0000</span>
                <div class="selected-label" v-if="selectedCountry">
                    <img :src="selectedCountry.flag" />
                    <span>+{{ selectedCountry.dialCode }}</span>
                </div>
                <DownArrowIcon />
            </div>
            <ul class="countries" v-show="opened">
                <li
                    v-for="country in countries"
                    :key="country.iso"
                    class="country"
                    :class="{ selected: dialCode == country.dialCode }"
                    @click="updateDialCode(country)"
                >
                    <img :src="country.flag" alt="" />
                    <span>
                        {{ country.dialCode }}
                    </span>
                </li>
            </ul>
            <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
        </div>
        <input
            class="number-input"
            maxlength="12"
            :value="phoneNumber"
            placeholder="11 111 11 11"
            @keypress="isDigit($event)"
            @input="updatePhoneNumber"
        />
    </div>
</template>

<script>
import DownArrowIcon from "./DownArrowIcon.vue";
import Checkbox from "./Checkbox.vue";
import countries from "../../../contryCodes.js";

export default {
    props: {
        errorMessage: {
            type: String,
            required: false,
        },

        dialCode: {
            type: [String, Number],
            required: true,
        },

        phoneNumber: {
            type: String,
            required: false,
        },
    },
    emit: ["updateDialCode", "updatePhoneNumber", "update"],
    components: { DownArrowIcon, Checkbox },

    data() {
        return {
            opened: false,
            countries,
        };
    },

    computed: {
        selectedCountry() {
            const selectedCountry = this.countries.find(
                (country) => country.dialCode == this.dialCode
            );
            if (selectedCountry) {
                return selectedCountry;
            } else {
                return null;
            }
        },
    },

    methods: {
        isDigit(evt) {
            evt = evt ? evt : window.event;
            var charCode = evt.which ? evt.which : evt.keyCode;
            if (charCode < 48 || charCode > 57) {
                evt.preventDefault();
            } else {
                return true;
            }
        },
        updateDialCode(value) {
            this.$emit("updateDialCode", value.dialCode);
            this.$emit("update", {
                dialCode: value.dialCode,
                phoneNumber: this.phoneNumber,
            });
            this.toggle();
        },

        updatePhoneNumber(e) {
            this.$emit("updatePhoneNumber", e.target.value.slice(0, 12));
            this.$emit("update", {
                dialCode: this.dialCode,
                phoneNumber: e.target.value.slice(0, 12),
            });
        },

        toggle() {
            this.opened = !this.opened;
        },
    },
};
</script>

<style lang="scss" scoped>
.phone-input {
    display: flex;
    align-items: center;
    background-color: white;

    .select-country {
        position: relative;
        z-index: 1;

        .label {
            display: flex;
            align-items: center;
            gap: 15px;
            font-family: "Nexa";
            font-style: normal;
            font-weight: 700;
            font-size: 15px;
            line-height: 23px;
            color: #87b1cb;
            align-items: center;
            background-color: white;
            padding: 14px 11px 14px 24px;
            height: 40px;
            min-width: 118px;

            span {
                margin-top: 3px;
            }

            .selected-label {
                gap: 8px;
                display: flex;
                align-items: center;
                width: auto;

                img {
                    width: 20px;
                    height: 20px;
                }

                span {
                    font-family: "Nexa";
                    font-style: normal;
                    font-weight: 700;
                    font-size: 15px;
                    line-height: 23px;
                    color: #2c5b7d;
                    margin-top: 3px;
                }
            }
        }

        .error {
            border: 1px solid #e97d32;
        }

        .error-message {
            width: 100%;
            margin-top: 8px;
            margin-bottom: 0px;
            font-family: "Nexa";
            font-style: italic;
            font-weight: 400;
            font-size: 12px;
            line-height: 18px;
            text-align: right;
            color: #e97d32;
        }

        .countries {
            position: absolute;
            display: flex;
            flex-direction: column;
            list-style: none;
            background-color: white;
            min-width: 118px;
            max-height: 250px;
            overflow: auto;
            width: 100%;
            left: 0px;
            padding: 0px;

            .country {
                border-top: 1px solid #fef1e7;
                width: 100%;
                display: flex;
                padding: 8px;
                gap: 8px;
                display: flex;
                align-items: center;

                img {
                    width: 20px;
                    height: 20px;
                }

                span {
                    font-family: "Nexa";
                    font-style: normal;
                    font-weight: 700;
                    font-size: 15px;
                    line-height: 23px;
                    color: #2c5b7d;
                }

                &:hover {
                    background: #d2eefc;
                }
            }

            .option.selected {
                background-color: #d2eefc;
            }
        }
    }

    .number-input {
        flex: 2;
        font-family: "Nexa";
        font-style: normal;
        font-weight: 700;
        font-size: 15px;
        line-height: 23px;
        height: 90%;
        color: #2c5b7d;
        border-left: 1px solid #d3e3ec;
        padding-left: 16px;
        outline: none;

        &:focus {
            outline: none;
        }

        &::placeholder {
            font-family: "Nexa";
            font-style: normal;
            font-weight: 700;
            font-size: 15px;
            line-height: 23px;
            color: #87b1cb;
        }
    }
}

.error {
    border: 1px solid #e97d32;
}
</style>
