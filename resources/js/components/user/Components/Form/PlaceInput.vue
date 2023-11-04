<template>
    <div class="input-wrapper">
        <label
            :id="id"
            v-if="$attrs.label"
            style="margin-bottom: 16px; display: block;"
        >{{$attrs.label}}</label>
        <input
            ref="placeInput"
            :name="id"
            :type="$attrs.type || 'text'"
            :placeholder="$attrs.placeholder || 'Type here'"
            :class="{error: $attrs.error || errorMessage}"
            v-bind="$attrs"
            @keyup="e => this.$emit('input', e.target.value)"
        >
        <p
            v-if="errorMessage"
            class="error-message"
        >{{errorMessage}}</p>
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

    emit: ['input', 'placeChanged'],

    data() {
        return {
            id: this.id || uniqueId()
        }
    },
    mounted() {
        const BASIC_DATA_FIELDS = ['address_components', 'adr_address', 'alt_id',
            'formatted_address', 'geometry', 'icon', 'id', 'name',
            'business_status', 'photo', 'place_id', 'scope', 'type', 'url',
            'utc_offset_minutes', 'vicinity'];
        const options = {
            types: ['address'],
            fields: ['address_components', 'formatted_address', 'place_id', 'type'],
            strictBounds: false,
        };
        const inputElement = this.$refs.placeInput;
        const autocomplete = new google.maps.places.Autocomplete(inputElement, options);
        autocomplete.addListener('place_changed', () => {
            const place = autocomplete.getPlace()
            const formattedData = { place_id: place.place_id, address: place.formatted_address }

            place.address_components.forEach(component => {
                if (component.types.includes('locality')) {
                    formattedData['locality'] = component.long_name
                }
                if (component.types.includes('postal_code')) {
                    formattedData['postal_code'] = component.short_name
                }
                if (component.types.includes('street_number')) {
                    formattedData['street_number'] = component.short_name
                }
                if (component.types.includes('route')) {
                    formattedData['route'] = component.long_name
                }
            })
            this.$emit('placeChanged', formattedData)

            // console.log(place)
        })
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
        color: #2C5B7D;
        outline: none;
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
