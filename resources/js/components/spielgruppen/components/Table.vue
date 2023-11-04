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
        <div class="footer">
            <slot name="footer"></slot>
        </div>
    </div>
</template>

<script>
export default {}
</script>

<style lang="scss" scoped>
.letsplan-table {
    margin: 0px;
    width: 100%;

    .thead {
        display: flex;
        background-color: white;
        height: 56px;
        align-items: center;
        gap: 10px;

        .th {
            display: flex;
            gap: 9px;
            height: 100%;
            align-items: center;

            span {
                font-family: 'Nexa';
                font-style: normal;
                font-weight: 700;
                font-size: 18px;
                line-height: 27px;
                color: #42BEAF;
            }

            .sort {
                svg {
                    margin-top: -5px;
                }
            }
        }
    }
}
</style>
