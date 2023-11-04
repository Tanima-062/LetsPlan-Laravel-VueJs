<template>
    <div class="card supervisor-wrapper">
        <div class="flex items-center">
            <img :src="supervisor.image? supervisor.image : '/images/user-286-64.png' " class="rounded-full supervisor-avatar"/>
            <p class="supervisor-name">{{supervisor.first_name}} {{supervisor.last_name}}</p>
        </div>
        <p class="supervisor-info">{{supervisor.role_name}}</p>
        <p class="supervisor-info" style="width: 100px">{{supervisor.postal_code}}</p>
        <p class="supervisor-info" style="width: 100px">{{supervisor.location}}</p>
        <p class="supervisor-info">{{supervisor.email}}</p>
        <p class="supervisor-info">{{supervisor.prefix}} {{supervisor.phone_number}}</p>
        <select-input
            v-model="supervisor.status_id"
            @change="updateStatus()"
            :inlineStyle="{background:background}" >
            <option v-for="status in statuses" :key="status.status_id" :value="status.status_id">{{status.status_name}}</option>
        </select-input>
        <div class="pr-4 pl-2 whitespace-no-wrap">
            <img @click="goToEditPage(supervisor.id)" class="action-icon" src="/images/edit.png"/>
            <img @click="onDelete" class="action-icon ml-4" src="/images/delete.png"/>
        </div>
    </div>
</template>

<script>
import axios from '../../axios';
import SelectInput from "../../shared/SelectInput";
export default {
    name: "Supervisor",
    components:{
        SelectInput
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
    computed: {
        background() {
            return [
                '#AEC90B',
                '#0063A7',
                '#E62D4F',
                '#F2A402',
                '#AEC90B',
            ][parseInt(this.supervisor.status_id)]
        }
    },

    methods: {
        async updateStatus(){
            await  axios.patch(`/api/benutzer/${this.supervisor.id}/status`, {
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
            this.$router.push(`/benutzer/edit/${id}`)
        },
    }
}
</script>

<style scoped lang="scss">
@import "_supervisor-item.scss";
</style>
