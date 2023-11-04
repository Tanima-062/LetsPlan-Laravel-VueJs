<template>
    <svg width="30" height="20" viewBox="0 0 30 20" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M1.66406 18.3333H28.3307M1.66406 10H28.3307M1.66406 1.66667H28.3307" :stroke="fill" stroke-width="3"
            stroke-linecap="square" />
    </svg>
</template>

<script>
export default {
    props: {
        fill: {
            type: String,
            default: '#4C85A7'
        }
    }
}
</script>

<style lang="scss" scoped>

</style>
