<template>
    <div class="input-wrapper">
        <label :id="id" v-if="$attrs.label" style="margin-bottom: 16px; display: block;">{{$attrs.label}}</label>
        <input :name="id" :type="$attrs.type || 'text'" :placeholder="$attrs.placeholder || 'Type here'"
            :class="{error: $attrs.error || errorMessage}" v-bind="$attrs" @input="e => $emit('input', e.target.value)" @blur="$emit('blur')">
        <p v-if="errorMessage" class="error-message">{{errorMessage}}</p>
    </div>
</template>

<script>
import { uniqueId } from 'lodash';
export default {

    props: {
        errorMessage: {
            type: String,
            required: false,
        }
    },

    data() {
        return {
            id: this.id || uniqueId()
        }
    },
}
</script>

<style lang="scss" scoped>
.input-wrapper {
    display: flex;
    flex-direction: column;

    label {
        font-family: 'Nexa';
        font-style: normal;
        font-weight: 700;
        font-size: 18px;
        line-height: 27px;
        color: #16A9B7;
    }

    input {
        width: 100%;
        height: 40px;
        background: #FFFFFF;
        padding: 15px 40px 14px 24px;
        font-family: 'Nexa';
        font-style: normal;
        font-weight: 700;
        font-size: 15px;
        line-height: 23px;
        outline: none;
        color: #2C5B7D;
    }

    .error {
        border: 1px solid #E97D32;
    }

    .error-message {
        width: 100%;
        margin-top: 8px;
        margin-bottom: 0px;
        font-family: 'Nexa';
        font-style: italic;
        font-weight: 400;
        font-size: 12px;
        line-height: 18px;
        text-align: right;
        color: #E97D32;
    }

}
</style>
