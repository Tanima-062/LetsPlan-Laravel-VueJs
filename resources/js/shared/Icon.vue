<template>
    <svg v-if="name === 'angle-right'" width="7" height="14" viewBox="0 0 7 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 13L5.46651 7.64018C5.77555 7.26934 5.77555 6.73066 5.46651 6.35982L0.999999 1" stroke="white" stroke-width="1.5" stroke-linecap="round"/></svg>
    <svg v-else-if="name === 'angles-right'" width="12" height="14" viewBox="0 0 12 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 13L5.46651 7.64018C5.77555 7.26934 5.77555 6.73066 5.46651 6.35982L0.999999 1" stroke="white" stroke-width="1.5" stroke-linecap="round"/><path d="M6 13L10.4665 7.64018C10.7756 7.26934 10.7756 6.73066 10.4665 6.35982L6 1" stroke="white" stroke-width="1.5" stroke-linecap="round"/></svg>
    <svg v-else-if="name === 'angle-left'" width="7" height="14" viewBox="0 0 7 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 1L1.53349 6.35982C1.22445 6.73066 1.22445 7.26934 1.53349 7.64018L6 13" stroke="white" stroke-width="1.5" stroke-linecap="round"/></svg>
    <svg  v-else-if="name === 'angles-left'" width="12" height="14" viewBox="0 0 12 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11 1L6.53349 6.35982C6.22445 6.73066 6.22445 7.26934 6.53349 7.64018L11 13" stroke="white" stroke-width="1.5" stroke-linecap="round"/><path d="M6 1L1.53349 6.35982C1.22445 6.73066 1.22445 7.26934 1.53349 7.64018L6 13" stroke="white" stroke-width="1.5" stroke-linecap="round"/></svg>
</template>
<script>
export default {
    props: {
        name: String,
    },
}
</script>
