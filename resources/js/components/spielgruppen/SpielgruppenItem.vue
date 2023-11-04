<template>
    <div class="card supervisor-wrapper">
        <div class="flex items-center">
            <img :src="supervisor.image" class="rounded-full supervisor-avatar"/>
            <p class="supervisor-name">{{supervisor.name}}</p>
        </div>
        <p class="supervisor-info">{{supervisor.street_name}} {{supervisor.street_number}}</p>
        <p class="supervisor-info">{{supervisor.postal_code}} {{supervisor.location}}</p>
        <p class="supervisor-info">{{supervisor.playgroup_lead}}</p>
        <p class="supervisor-info">{{supervisor.playgroup_assistant}}</p>
        <select-input
            v-model="supervisor.status_id"
            @change="updateStatus" >
            <option v-for="status in statuses" :key="status.status_id" :value="status.status_id">{{status.status_name}}</option>
        </select-input>
        <div class="pr-4 pl-2 whitespace-no-wrap">
            <img @click="goToEditPage(supervisor.id)" class="action-icon" :src="role === 'admin' ? '/images/edit.png': '/images/eye.png'"/>
            <img v-if="role === 'admin'" @click="onDelete" class="action-icon ml-4" src="/images/delete.png"/>
        </div>
    </div>
</template>

<script>
import axios from "../../axios";
import SelectInput from "../../shared/SelectInput";
export default {
    name: "Spielgruppen",
    components:{
        SelectInput
    },
    data() {
        return {
            role: ''
        }
    },
    props: {
        supervisor: {
            type: [Array , Object],
            required: true
        },
        statuses: {
            type: [Array , Object],
            required: true
        },
    },

    mounted() {
        this.role = localStorage.getItem('role')
    },

    methods: {
        async updateStatus(){
            await  axios.patch(`/api/spielgruppen/${this.supervisor.id}/status`, {
                status_id:this.supervisor.status_id
            }).then(res => {
                this.$toasted.show(res.data, {
                    icon: 'check',
                    type: 'success',
                    duration: 2000
                })
            }).catch(err => {
                console.log(err)
            })
        },
        onDelete() {
            this.$emit('delete')
        },

        goToEditPage(id) {
            this.$router.push(`/spielgruppen/edit/${id}`)
        },
    }
}
</script>

<style scoped lang="scss">
@import "../supervisor/supervisor-item";
</style>
