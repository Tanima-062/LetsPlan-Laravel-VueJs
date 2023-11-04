<template>
    <div
        class="dropdown-container bg-white pl-4 py-2 pr-3"
        :class="{ error: error }"
        @focusout="handleFocusOut"
        tabindex="0"
        v-click-outside="() => (showDropdown = false)"
    >
        <div
            class="flex justify-between items-center cursor-pointer"
            @click="handleShowItems"
        >
            <div class="flex items-center relative">
                <ul class="relative d-flex items-center justify-start px-0 py-[5px] mb-0">
                    <li
                        class="listItem"
                        :style="{ color: labelColor }"
                    >
                        {{ selectedItem }}
                    </li>
                </ul>
            </div>
            <div class="flex">
                <svg
                    width="22"
                    height="13"
                    viewBox="0 0 22 13"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M2.66699 2.66666L11.0003 11L19.3337 2.66666"
                        stroke="#063F5F"
                        stroke-width="2.5"
                        stroke-linecap="square"
                    />
                </svg>
            </div>
            <!-- dropdown for items -->
        </div>
        <div
            v-if="showDropdown"
            class="dropdown-items d-flex justify-start items-center flex-col"
        >
            <div
                v-for="(item, index) in itemList"
                :key="index"
                class="items"
                @click="getTexts(item)"
            >
                <ul class="d-flex items-center justify-start px-0">
                    <li>
                        {{ item.name }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    // name: "Unternehmen dropdown",
    props: ["itemList", "value", "error"],
    data() {
        let showDropdown = false;
        let item = "";
        return {
            showDropdown,
            item,
        };
    },
    computed: {
        selectedItem() {
            const company = this.itemList.find((item) => item.id == this.value);

            if (company) {
                return company.name;
            }

            return "Unternehmen auswählen";
        },
        labelColor() {
            return this.selectedItem != "Unternehmen auswählen"
                ? "#2c5b7d"
                : "#87b1cb";
        },
    },
    methods: {
        handleShowItems() {
            this.showDropdown = !this.showDropdown;
        },
        handleFocusOut() {
            // this.showDropdown = false;
        },
        getTexts(item) {
            // this.value = item.name;
            this.showDropdown = false;
            this.$emit("input", item.id);
        },
    },
};
</script>

<style lang="scss">
.dropdown-items {
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.items {
    width: 100%;
    padding: 0.5rem 1rem;
    display: flex;
    align-items: center;
    border-top: 1px solid #fef1e7;
    color: #2c5b7d;
    font-size: 15px;
    font-family: "Nexa";
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

.listItem {
    margin: 5px 3px 0px 6px;
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 15px;
    line-height: 23px;
    color: #2c5b7d;
    list-style: none;
}

.dropdown-container {
    /* width: 436px; */
    height: 40px;
}

.dropdown-items {
    /* width: 436px; */
    background: #fff;
    transform: translate(-3.6%, 2%);
    z-index: 3;
    max-height: 200px;
    overflow: auto;
}

.dropdown-items li {
    list-style: none;
}

.error {
    border: 1px solid rgb(233, 125, 50)
}
</style>
