<template>
    <div class="selectdiv">
        <label>
            <select :id="id" ref="input" :style="{background:backgroundColor}" v-model="selected" v-bind="$attrs">
                <slot />
            </select>
        </label>
    </div>
</template>

<script>
export default {
    inheritAttrs: false,
    props: {
        id: {
            type: String,
            default() {
                return `select-input-${this._uid}`
            },
        },
        value: [String, Number, Boolean],
    },
    data() {
        return {
            selected: this.value,
            backgroundColor: '#0063A7',
        }
    },
    mounted() {
        this.backgroundColor = this.statusColors(this.value)
    },
    watch: {
        selected(selected) {
            this.$emit('input', selected)
            this.$emit('change', selected)
            this.backgroundColor = this.statusColors(selected)
        },
        value(val){
            this.selected = val;
            this.backgroundColor = this.statusColors(val)
        }
    },
    methods: {
        focus() {
            this.$refs.input.focus()
        },
        select() {
            this.$refs.input.select()
        },
        statusColors(prop){
            return [
                '#AEC90B',
                '#0063A7',
                '#E62D4F',
                '#F2A402',
                '#AEC90B'
            ][parseInt(prop)]
        },
    },
}
</script>

<style scoped lang="scss">
.selectdiv {
    position: relative;
    &:after {
        content: url("/images/arrow.png");
        right: 10px;
        top: 0px;
        height: 30px;
        padding: 4px 0px 0px 8px;
        border-left: 1px solid #ffffff;
        position: absolute;
        pointer-events: none;
     }
    select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        display: block;
        width: 100%;
        max-width: 130px;
        height: 30px;
        float: right;
        padding:0px 30px 0 5px;
        font-weight: 400;
        font-size: 12px;
        line-height: 1.75;
        border: 0;
        border-radius: 5px;
        color: white;
        -ms-word-break: normal;
        word-break: normal;
        &::-ms-expand {
            display: none;
        }
    }

}
</style>
