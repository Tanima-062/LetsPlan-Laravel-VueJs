<template>
    <div>
        <label v-if="label" class="input-title" :for="id">{{label}} <sup v-if="required">*</sup></label>
        <date-picker
            :id="id"
            v-model="datePicker"
            type="time"
            :format="format"
            v-bind="$attrs"
            :append-to-body="false"
            :open.sync="open"
            placeholder="HH:mm"
            class="form-input w-full form-input-bordered"
            :class="{ error: error }"
            :value="value"
            @change="handleChange"
            title-format="HH:mm"
        >
               <template v-slot:icon-calendar>
                   <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                       <path d="M8 0C3.5816 0 0 3.5816 0 8C0 12.4184 3.5816 16 8 16C12.4184 16 16 12.4184 16 8C16 3.5816 12.4184 0 8 0ZM8 15.2C4.03 15.2 0.8 11.97 0.8 8C0.8 4.03 4.03 0.8 8 0.8C11.97 0.8 15.2 4.03 15.2 8C15.2 11.97 11.97 15.2 8 15.2Z" fill="#42474A"/>
                       <path d="M8.39975 4.40002H7.59975V8.40002H11.5997V7.60002H8.39975V4.40002Z" fill="#42474A"/>
                   </svg>
               </template>
        </date-picker>
        <div v-if="error" class="my-2 text-danger">{{ error }}</div>
    </div>
</template>

<script>
import DatePicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'
import 'vue2-datepicker/locale/de';
export default {
    components: { DatePicker },
    inheritAttrs: false,
    data: ()=>({
        open: false,
    }),
    props: {
        id: {
            type: String,
            default() {
                return `text-input-${this._uid}`
            },
        },
        required: {
            type: Boolean,
            default: false,
        },
        format:{
            type: String,
            default() {
                return 'HH:mm'
            },
        },
        value: [Date, String, Number],
        label: String,
        error: [String,Boolean],
    },
    computed: {
        datePicker: {
            get() { return this.value},
            set(updatedContent) { this.$emit('input', updatedContent) },
        },
    },
    methods: {
        focus() {
            this.$refs.input.focus()
        },
        select() {
            this.$refs.input.select()
        },
        handleChange(value, type) {
            if (type === 'minute') {
                this.open = false;
            }
        }
    },
}
</script>
<style  lang="scss">
.mx-datepicker{
    width: 100%;
    display: block;
    padding: 0;
    margin-top: 5px;
     .mx-input {
         height: 40px;
         border: 0;
         background: #f8fbfa;
     }
}
</style>

