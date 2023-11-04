<template>
    <div class="planning__nofitication"  v-if="show">
        <div class="planning__nofitication--header">
            <h4>Benachrichtigung</h4>
            <button @click="show = false">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15.3133 2.33301L2.15625 15.6663M2.15626 2.33301L15.3133 15.6663" stroke="#135F84" stroke-width="2.5" stroke-linecap="square"/>
                </svg>
            </button>
        </div>
        <div class="planning__nofitication--body">
            {{ message }}
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                show: false,
                message: ''
            }
        },
        mounted(){
            EventBus.$on('plan-child-delete', (e)=> {
                this.message = e;
                this.show = true;

                setTimeout(()=> {
                    this.show = false;
                }, 3000)
            })
        }
    }
</script>

<style lang="scss" scoped>
    .planning__nofitication {
        position: fixed;
        width: 600px;
        top: 40px;
        left: 50%;
        transform: translateX(-50%);

        background: #D2EEFC;
        box-shadow: 8px 8px 0px #D3E3EC;
        padding: 24px;
        z-index: 99;

        &--header {
            display: flex;
            justify-content: space-between;

            margin-bottom: 20px;
            h4 {
                font-family: 'Ubuntu';
                font-style: normal;
                font-weight: 700;
                font-size: 18px;
                line-height: 21px;
                color: #063F5F;
            }

            button {

            }
        }

        &--body {
            font-family: 'Nexa';
            font-style: normal;
            font-weight: 400;
            font-size: 18px;
            line-height: 27px;
            color: #063F5F;
        }
    }
</style>
