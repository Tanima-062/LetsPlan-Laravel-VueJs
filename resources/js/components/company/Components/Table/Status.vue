<template>
    <div class="status" :style="styles" :title="status == 1 ? 'Aktiv' : 'Inaktiv'">

    </div>
</template>

<script>
    export default {
        props: {
            company: {
                type: Object,
                required: true,
            }
        },
        data(){
            return {
                status: this.company.status
            }
        },
        mounted(){
            EventBus.$on(`update-status-${this.company.id}`, (e)=>{
                this.status = e
            })
        },
        computed: {
            styles() {
                let color = ''
                if(this.status == 1){
                    color = '55C5CD'
                }else if(this.status == 0){
                    color = 'E97D32'
                }
                else {
                    color = '063F5F'
                }

                return `background: #${color};`
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
