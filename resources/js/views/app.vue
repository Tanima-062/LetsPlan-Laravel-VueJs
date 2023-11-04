<template>
    <div>
        <Notification />
        <router-view></router-view>
    </div>
</template>

<script>
import Notification from '../shared/Notification.vue';
export default {
    name: "app",
    components: { Notification }
}
</script>

<style>

</style>
