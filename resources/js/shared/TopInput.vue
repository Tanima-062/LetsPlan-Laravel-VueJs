<template>
    <div style="width: 100%">
        <div class="flex justify-between w-100">
            <div class="input-container form">
                <div class="flex justify-center">
                    <i class="fa fa-search icon"></i>
                </div>
                <input
                    class="input-field"
                    type="text"
                    :placeholder="placeholder"
                    name="search"
                    v-model="filter"
                    @input="updateValue"
                />
            </div>

            <div
                @click="$emit('goToPage')"
                class="button-group col-xs-4 justify-end button-add"
            >
                <span class="plus">
                    <svg
                        width="31"
                        height="30"
                        viewBox="0 0 31 30"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            d="M2 15H28.6667M15.3333 1.66666V28.3333"
                            stroke="white"
                            stroke-width="3"
                            stroke-linecap="square"
                        />
                    </svg>
                </span>
                <div class="vertical-line"></div>
                <span class="btn-title-group">{{ title }}</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["title", "placeholder"],
    data() {
        return {
            filter: "",
        };
    },
    methods: {
        updateValue() {
            this.$emit("updateValue", this.filter);
        },
    },
};
</script>

<style scoped>
.input-container {
    display: flex;
    min-width: 888px;
}

.plus {
    padding: 10px;
}

.plus svg {
    width: 80%;
}
.form {
    width: 75%;
    margin-right: 3%;
}

.input-field {
    width: 100%;
    padding: 18px 52px 18px 14.67px;
    outline: none;
    height: 48px;
    font-size: 16px;
    line-height: 24px;
    color: #4c85a7;
    font-family: "Nexa", sans-serif;
}
.input-field::placeholder {
    font-family: "Nexa";
    font-style: normal;
    font-weight: 400;
    font-size: 16px;
    line-height: 24px;
    color: #4c85a7;
}

.input-field:focus {
    border: none;
}
.button-add {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    padding: 0px;
    position: relative;
    min-width: 420px;
    border-radius: 0;
    height: 48px;

    /* Primary / Dusty Orange */

    background: #e97d32;
}

.icon {
    padding: 10px;
    background: #fff;
    color: #063f5f;
    text-align: center;
    height: 48px;
    font-size: 25px;
}
.button-group {
    margin: 0;
}
.btn-title-group {
    color: #fff;
    font-family: "Nexa", sans-serif;
    line-height: 27px;
    margin-top: 5px;
}

@media only screen and (min-width: 768px) {
    .btn-title-group {
        font-size: 18px;
    }
}
</style>
