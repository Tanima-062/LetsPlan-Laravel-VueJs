<template>
    <div class="flex min-h-screen">
        <sidebar></sidebar>
        <div class="content">
            <letsplay-navbar></letsplay-navbar>
            <div data-testid="content" :class="[currentPage!=='/plannung'? 'px-view mx-auto' : '', '']">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "../../axios";

export default {
    computed: {
        currentPage() {
            return this.$route.path
        }
    },
    name: "AdminLayout",
    mounted() {
        axios.get('/api/user').then(res => {
            this.$store.dispatch('setUser', res.data.data)
        }).catch(err => {
            console.log(err)
        })
    }
}
</script>

<style scoped>

</style>
