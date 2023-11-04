<template>
    <div class="entries-per-page" @click="opened = !opened" v-click-outside="() => opened = false">
        <div class="label">
            <span>{{labelText}}</span>

            <!-- Down -->
            <svg width="22" height="13" viewBox="0 0 22 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M2.6665 2.66699L10.9998 11.0003L19.3332 2.66699" stroke="#063F5F" stroke-width="2.5"
                    stroke-linecap="square" />
            </svg>
        </div>

        <div class="options" v-if="opened">
            <p class="option" :class="{active: per_page == 10}" @click="setPerPage(10)">10</p>
            <p class="option" :class="{active: per_page == 25}" @click="setPerPage(25)">25</p>
            <p class="option" :class="{active: per_page == 50}" @click="setPerPage(50)">50</p>
            <p class="option" :class="{active: per_page == 100}" @click="setPerPage(100)">100</p>
        </div>
    </div>
</template>

<script>

export default {
    props: {
        routeName: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            opened: false,
            per_page: null
        }
    },

    computed: {
        labelText () {
            return 'Einträge pro Seite'
        }
    },
    methods: {
        setPerPage(item_count) {
            if(this.per_page == item_count) return;
            this.per_page = item_count

            const query = { ...this.$route.query }
            delete query.page;
            query.per_page = item_count;

            this.$router.push({ name: this.routeName, query })

            this.$emit('updateResult')
        }
    },
    mounted() {
        this.per_page = this.$route.query.per_page;
    },
}
</script>

<style lang="scss" scoped>
.entries-per-page {
    position: relative;

    .label {
        cursor: pointer;

        span {
            font-family: 'Nexa';
            font-style: normal;
            font-weight: 400;
            font-size: 18px;
            line-height: 27px;
            color: #063F5F;

        }
    }

    .options {
        position: absolute;
        background-color: white;
        width: 64px;
        right: 0px;
        z-index: 1;

        .option {
            padding: 10px 0px 7px 16px;
            border-bottom: 1px solid #FEF1E7;
            font-family: 'Nexa';
            font-style: normal;
            font-weight: 400;
            font-size: 13px;
            line-height: 20px;
            color: #093E60;
            cursor: pointer;
            margin: 0px;
        }

        .option:last-of-type {
            border-bottom: none;
        }

        .option.active {
            background-color: #D2EEFC;
        }
    }
}

.entries-per-page .options .option {
    padding: 10px 0px 5px 20px;
}
</style>
