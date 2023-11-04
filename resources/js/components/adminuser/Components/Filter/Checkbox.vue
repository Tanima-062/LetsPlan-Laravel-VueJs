<template>

    <div>
        <!-- checked -->
        <svg @click="$emit('toggle', value)" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" v-if="checked == true">
            <rect x="1.25" y="1.25" width="21.5" height="21.5" stroke="#D3E3EC" stroke-width="2.5" />
            <rect x="5" y="5" width="14" height="14" fill="#9FDCF8" />
        </svg>

        <!-- not checked -->
        <svg @click="$emit('toggle', value)" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" v-if="checked == false">
            <rect x="1.25" y="1.25" width="21.5" height="21.5" stroke="#D3E3EC" stroke-width="2.5" />
        </svg>
    </div>

    </template>

    <script>
    export default {
        props: {
            checked: {
                type: Boolean,
                required: true,
            },
            value: {
                required: true,
            }
        },
        emit: ['toggle']
    }
    </script>
