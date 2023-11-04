<template>
    <div class="letsplan-table">
        <div class="thead">
            <slot name="thead">

            </slot>
        </div>
        <div class="tbody">
            <slot name="tbody">
            </slot>
        </div>
    </div>
</template>

<script>
export default {}
</script>

<style lang="scss">
.letsplan-table {
    .thead {
        display: flex;
        background-color: white;
        height: 56px;
        align-items: center;
        padding: 0px 22px 0px 25px;
        margin-top: 12px;

        .th {
            display: flex;
            gap: 9px;

            span {
                font-family: 'Nexa';
                font-style: normal;
                font-weight: 700;
                font-size: 18px;
                line-height: 27px;
                color: #42BEAF;
            }
        }
    }
}

.letsplan-table {
    width: calc(100% + 30px);
    margin-left: -15px;
}
</style>
