<template>
    <div class="search">
        <input
            class="search__input"
            type="text"
            placeholder="Nach Vor- oder Nachnamen suchen"
            v-model="query"
        >
    </div>
</template>

<script>
    import { debounce as _debounce } from "lodash";
    import axios  from '../../../axios'

    export default {
        props: {
            routeName: {
                type: String,
                required: true,
            },
        },
        data(){
            return {
                query: ''
            }
        },
        watch: {
            query:{
                handler: _debounce(async function (newValue, oldValue) {
                    const query = {...this.$route.query}
                    delete query.page;
                    query['query'] = newValue
                    this.$router.push({ name: this.routeName, query})

                    this.$emit('updateResult', newValue)
                },500),
                deep: true
            },
        },
    }
</script>

<style lang="scss" scoped>
    .search {
        background: #FFFFFF;
        margin-right: 10px;
        &__input {
            font-family: 'Nexa';
            font-style: normal;
            font-weight: 400;
            font-size: 16px;
            line-height: 24px;
            color: #4C85A7;
            padding: 10px 10px;

            background-image: url("data:image/svg+xml,%3Csvg width='33' height='33' viewBox='0 0 33 33' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M29.2727 31.394L30.3333 32.4547L32.4547 30.3333L31.394 29.2727L29.2727 31.394ZM24.3995 22.2782L23.3388 21.2175L21.2175 23.3388L22.2782 24.3995L24.3995 22.2782ZM14.5 25.5C8.42487 25.5 3.5 20.5751 3.5 14.5H0.5C0.5 22.232 6.76801 28.5 14.5 28.5V25.5ZM3.5 14.5C3.5 8.42487 8.42487 3.5 14.5 3.5V0.5C6.76801 0.5 0.5 6.76801 0.5 14.5H3.5ZM14.5 3.5C20.5751 3.5 25.5 8.42487 25.5 14.5H28.5C28.5 6.76801 22.232 0.5 14.5 0.5V3.5ZM25.5 14.5C25.5 20.5751 20.5751 25.5 14.5 25.5V28.5C22.232 28.5 28.5 22.232 28.5 14.5H25.5ZM31.394 29.2727L24.3995 22.2782L22.2782 24.3995L29.2727 31.394L31.394 29.2727Z' fill='%23063F5F'/%3E%3C/svg%3E%0A");
            background-repeat: no-repeat no-repeat;
            background-position: 10px center;
            background-size: 28px 28px;
            padding-left: 52px;
            padding-top: 15px;
            width: 100%;
        }

        width: 80%;
    }
</style>
