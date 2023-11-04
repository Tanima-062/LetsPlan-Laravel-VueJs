<template>
    <div class="sort">
        <svg width="10" height="16" viewBox="0 0 10 16" fill="none" xmlns="http://www.w3.org/2000/svg"
            @click="setFilterToQueryString(columnName)" style="cursor: pointer;">
            <path d="M2 10.917L5 13.917L8 10.917M8 4.91699L5 1.91699L2 4.91699" stroke="#42BEAF" stroke-width="2"
                stroke-linecap="square" />
        </svg>
    </div>
</template>

<script>
export default {
    props: ['columnName'],
    emit: ['updateResult'],
    data() {
        return {
            direction: 'ASC',
        }
    },
    methods: {
        setFilterToQueryString(column) {
            const params = new URLSearchParams(location.search)
            this.direction = this.direction == 'DESC' ? 'ASC' : 'DESC'
            params.append('direction', this.direction)
            params.append('order_by', column)
            this.$router.push({ name: 'adminuser', query: Object.fromEntries(params) })
            this.$emit('updateResult')
        }
    },

    mounted() {
        const params = new URLSearchParams(location.search)
        this.direction = params.get('direction') || 'ASC'
    },
}
</script>
