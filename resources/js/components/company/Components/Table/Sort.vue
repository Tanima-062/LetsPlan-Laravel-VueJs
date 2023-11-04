<template>
    <div class="th">
        <span>{{ label }}</span>
        <span  @click="sort" >
            <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg"
               style="cursor: pointer;">
                <path d="M2 10.917L5 13.917L8 10.917M8 4.91699L5 1.91699L2 4.91699" stroke="#42BEAF" stroke-width="2"
                    stroke-linecap="square" />
            </svg>
        </span>
        <!-- <Sort columnName="child_name" @updateResult="getAnmeldungens()" /> -->
    </div>
</template>

<script>
    import { debounce as _debounce } from "lodash";

    export default {
        props: {
            label: {
                type: String,
                required: true
            },
            routeName: {
                type: String,
                required: true
            },
            columnName:  {
                type: String,
                required: true
            }
        },
        data(){
           return {
                direction: 'ASC'
           }
        },
        methods: {
            sort(){
                this.direction = (this.direction === 'ASC') ? 'DESC' : 'ASC'
            }
        },
        watch: {
            direction:{
                handler: _debounce(async function (newValue, oldValue) {
                    const query = {...this.$route.query}
                    query['order_by'] = this.columnName
                    query['direction'] = newValue
                    this.$router.push({ name: this.routeName, query})

                    this.$emit('updateResult')
                },200),
                deep: true
            },
        },
    }
</script>

<style lang="scss" scoped>
    .th {
        display: flex;
        align-items: center;
    }
</style>
