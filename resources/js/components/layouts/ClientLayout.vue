<template>
        <div class="content" style="background: #F5F5F5;">
            <ClientNavbar/>
            <div class="px-view mx-auto">
                <div>
                    <router-view></router-view>
                </div>
            </div>
        </div>
</template>

<script>
import ClientNavbar from "../partials/ClientNavbar";
export default {
name: "ClientLayout",
    components: {ClientNavbar}
}
</script>

<style scoped>

</style>
