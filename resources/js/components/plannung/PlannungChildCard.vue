<template>
<div :style="{backgroundColor:child.color}" class="card flex justify-between px-2 items-center py-2 mt-2">
    <div class="flex items-center">
        <img src="/images/plannung-item-icon.png" width="8"/>
        <p class="user-name">{{child.full_name}}</p>
    </div>
    <img @click="$router.push(`/anmeldungen/${child.child_reg_id}`)" src="/images/info-icon.png" width="12"/>
</div>
</template>

<script>
export default {
name: "PlannungChildCard",
props: ['child']
}
</script>

<style scoped>

 .user-name {
     font-size: 13px;
     font-weight: 400;
     color: #42474A;
     padding-left: 10px;
     white-space: break-spaces;
 }
</style>
