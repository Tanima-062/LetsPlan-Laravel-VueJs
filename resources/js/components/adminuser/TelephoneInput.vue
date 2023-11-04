<template>
    <div
        class="telephone-container bg-white flex justify-start items-start pl-4 py-2 pr-0"
        @focusout="handleFocusOut"
        tabindex="0"
    >
        <div class="flex items-center relative" @click="handleShowCountry">
            <ul
                class="codes-container d-flex items-center justify-start px-0 py-[5px] mb-0"
            >
                <li>
                    <img
                        :src="flag ? flag : countryCodes[0].flag"
                        :alt="ext ? ext : countryCodes[0].name"
                        class="flag-img"
                    />
                </li>
                <li class="dialCode">
                    +{{ phone ? phone : ext ? ext : countryCodes[0].dialCode }}
                </li>
            </ul>
            <i class="fa-solid fa-angle-down mx-3"></i>
            <div class="divider"></div>
        </div>
        <!-- dropdown for countries -->

        <div
            v-if="showCountries"
            class="ext d-flex justify-start items-center flex-col"
        >
            <div
                v-for="(country, index) in countryCodes"
                :key="index"
                class="items"
                @click="getTexts(country)"
            >
                <ul
                    class="codes-container d-flex items-center justify-start px-0"
                >
                    <li>
                        <img
                            :src="country.flag"
                            :alt="country.name"
                            class="flag-img"
                        />
                    </li>
                    <li>+{{ country.dialCode }}</li>
                </ul>
            </div>
        </div>

        <div class="number">
            <input type="number" :value="value" @input="handleInput" />
        </div>
    </div>
</template>

<script>
const codes = require("../../../contryCodes");
export default {
    name: "TelephoneInput",
    props: {
        value: {
            type: String,
        },
        phone: {
            type: String,
        },
        // ext: {
        //     type: String,
        // },
    },
    data() {
        let countryCodes = codes.codes;
        let ext = "";
        let flag = "";
        let showCountries = false;
        return {
            countryCodes,
            showCountries,
            flag,
            ext,
        };
    },
    methods: {
        handleShowCountry() {
            this.showCountries = !this.showCountries;
        },
        handleFocusOut() {
            this.showCountries = false;
        },
        getTexts(country) {
            this.flag = country.flag;
            this.ext = country.dialCode;
            this.showCountries = false;
            this.$emit("changeCountryCode", country.dialCode);
            console.log(country);
        },
        handleInput(e) {
            // this.$emit("input", this.ext + e.target.value);

            this.$emit("changeTelephoneNumber", e.target.value);
            // console.log("number", e.target.value);
        },
    },
};
</script>

<style>
.flag-img {
    width: 20px;
    margin-right: 5px;
    object-fit: contain;
}

.codes-container {
    list-style: none;
}

.ext {
    height: 400px;
    overflow: scroll;
    padding: 0.5rem 0rem;
    overflow-x: hidden;
    width: 8.5%;
    position: absolute;
    background-color: #fff;
    left: 12px;
    top: 100%;
}

.items {
    width: 100%;
    padding: 0.5rem 1rem;
    display: flex;
    align-items: center;
}

.items:hover {
    cursor: pointer;
    background-color: #e97d32;
    color: #fff;
}

ul {
    display: flex;
    align-items: center;
    font-size: 15px;
    margin-bottom: 0;
}

input {
    width: 300px;
    padding-left: 5px;
    font-size: 15px;
    padding-right: 10px;
}

input:focus {
    outline: none;
    border: 0;
}

.dialCode {
    color: #2c5b7d;
    margin: 5px 3px 0px 6px;
}

.telephone-container {
    width: 436px;
    height: 40px;
}

.number {
    padding-top: 4px;
}

.divider {
    width: 2px;
    height: 25px;
    background-color: #d3e3ec;
}
</style>
