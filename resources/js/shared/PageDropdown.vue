<template>
    <div
        class="page-dropdown-container pl-2 pt-1"
        @focusout="handleFocusOut"
        tabindex="0"
    >
        <div class="flex justify-between items-center" @click="handleShowItems">
            <div class="flex items-center relative">
                <ul
                    class="relative d-flex items-center justify-start px-0 py-[5px] mb-0"
                >
                    <li class="listItem">
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
                    </li>
                </ul>
            </div>
            <!-- dropdown for pages -->
        </div>
        <div
            v-if="showDropdown"
            class="page-dropdown-items d-flex justify-start items-center flex-col absolute"
        >
            <div
                v-for="(item, index) in [10, 25, 50, 100]"
                :key="index"
                class="items"
                @click="getTexts(item)"
            >
                <ul class="d-flex items-center justify-start px-0">
                    <li>
                        {{ item }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        let showDropdown = false;
        let item = null;
        return {
            showDropdown,
            item,
        };
    },
    methods: {
        handleShowItems() {
            this.showDropdown = !this.showDropdown;
        },
        handleFocusOut() {
            // this.showDropdown = false;
        },
        getTexts(item) {
            this.item = item;
            this.showDropdown = false;
            // this.$emit("changeDropdownItem", item.name);
        },
    },
    mounted() {
        // console.log(itemList);
    },
};
</script>
<style>
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
    color: #87b1cb;
    margin: 5px 3px 0px 6px;
    font-size: 15px;
    font-family: "Nexa", sans-serif;
    list-style: none;
}

.page-dropdown-container {
    width: 30px;
    height: 40px;
}

.page-dropdown-items {
    width: 80px;
    background: #fff;
    transform: translate(-40%, 2%) !important;
    z-index: 3;
}

.page-dropdown-items li {
    list-style: none;
}
</style>
