<template>
    <div class="status" :style="styles" :title="division.status_name">

    </div>
</template>

<script>
    export default {
        props: {
            division: {
                type: Object,
                required: true,
            }
        },
        data(){
            return {
                status: this.division.status_id
            }
        },
        mounted(){
            EventBus.$on(`update-status-${this.division.status_id}`, (e)=>{
                this.status = e
            })
        },
        computed: {
            styles() {
                let color = ''
                if(this.status == 1){
                    color = '9FDCF8'
                }else if(this.status == 2){
                    color = '87B1CB'
                }
                else if(this.status == 3){
                    color = 'FFDE86'
                }
                else if(this.status == 4){
                    color = '55C5CD'
                }


                return `background: #${color};`
            },
        }
    }
</script>

<style lang="scss" scoped>
    .status {
        width: 14px;
        height: 14px;

    }
</style>
