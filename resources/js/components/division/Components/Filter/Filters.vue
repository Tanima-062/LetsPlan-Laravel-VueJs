<template>
    <div class="filters">
        <div class="wrapper d-flex align-items-center" :class="{'bg-white': filter_opened}" style="gap: 24px;">
            <FilterButton @toggle="filter_opened = !filter_opened"/>

            <div v-if="filter_opened" class="w-100 d-flex justify-between" style="gap: 26px; padding-right: 36px;">
                <slot ></slot>
            </div>
        </div>
    </div>
</template>

<script>
    import FilterButton from "./FilterButton.vue";
    export default {
        components: {
            FilterButton
        },
        data(){
            return {
                filter_opened: false,
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>
