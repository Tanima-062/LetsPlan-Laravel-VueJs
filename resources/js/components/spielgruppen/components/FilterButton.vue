<template>

    <button class="filter-btn" @click="$emit('toggle')">
        <svg width="32" height="33" viewBox="0 0 32 33" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M2.66675 1.66699L11.0001 15.0003V25.0003L21.0001 30.0003V15.0003L29.3334 1.66699H2.66675Z"
                stroke="#063F5F" stroke-width="2.5" stroke-linecap="round" />
        </svg>
        <p>Filter</p>
    </button>
</template>

<script>
export default {
    emit: ['toggle']
}
</script>

<style lang="scss" scoped>
.filter-btn {
    display: flex;
    gap: 14px;
    background-color: #BEE7FB;
    padding: 10px 16px 9px 22px;
    align-items: center;

    p {
        font-family: 'Nexa';
        font-style: normal;
        font-weight: 700;
        font-size: 18px;
        line-height: 27px;
        margin-bottom: 0px;
    }
}
</style>
