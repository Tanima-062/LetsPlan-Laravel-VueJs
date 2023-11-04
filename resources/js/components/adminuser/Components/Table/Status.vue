<template>
    <div class="status" :style="styles" :title="statusTooltipText">

    </div>
</template>

<script>
    export default {
        props: {
            user: {
                type: Object,
                required: true,
            }
        },
        data(){
            return {
                userStatus: this.user.status_id
            }
        },
        mounted(){
            EventBus.$on(`update-status-${this.user.id}`, (e)=>{
                this.userStatus = e
            })
        },
        computed: {
            styles() {
                let color = ''
                if(this.userStatus == 1){
                    color = '55C5CD'
                }else if(this.userStatus == 2){
                    color = 'E97D32'
                }
                else {
                    color = '063F5F'
                }

                return `background: #${color};`
            },
            statusTooltipText(){
                if(this.userStatus == 1){
                    return 'Aktiv'
                }else if(this.userStatus == 2){
                    return 'Inaktiv'
                }

                return 'Pendent'
            }
        }
    }
</script>

<style lang="scss" scoped>
    .status {
        width: 14px;
        height: 14px;

    }
</style>
