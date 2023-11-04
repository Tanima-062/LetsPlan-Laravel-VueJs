<template>
    <div class="status__modal" v-if="showModal">
        <div class="status__modal--header">
            <h4>{{ title }}</h4>
        </div>
        <div class="status__modal--body">
            {{ body }}
        </div>
        <div class="status__modal--footer">
            <button class="button confirm" @click.prevent="changeStatus">Ja</button>
            <button class="button cancel" @click.prevent="showModal = false">Nein</button>
        </div>
    </div>
</template>

<script>
    import axios from '../../../axios'
    export default {
        props: {

        },
        data(){
            return  {
                showModal: false,
                status_id: null,
                user: null,
            }
        },
        computed: {
            fullName(){
                return `${this.user.first_name} ${this.user.last_name}`
            },
            title(){
                return this.status_id == 1  ? 'Spielgruppen Admin aktivieren?' : 'Spielgruppen Admin deaktivieren? ';
            },
            body(){
               return this.status_id == 1 ? `Sind Sie sicher, dass Sie den Spielgruppen Admin "${this.fullName}" wirklich aktivieren wollen?` : `Sind Sie sicher, dass Sie den Spielgruppen Admin "${this.fullName}"  wirklich deaktivieren wollen?`;
            }
        },
        mounted(){
            EventBus.$on('show-confirm-modal', (e)=>{
                this.showModal = true;
                this.status_id = e.status_id
                this.user = e.user
            })
        },
        methods: {
            changeStatus(){
                // axios.patch(`/api/benutzer/${this.user.id}/status`, {
                axios.put(`/api/adminuser/status/${this.user.id}`, {
                    status_id: this.status_id
                }).then(res => {
                    const text = this.status_id == 1 ? `Der Spielgruppen Admin "${this.fullName}" wurde erfolgreich aktiviert.` : `Der Spielgruppen Admin "${this.fullName}" wurde erfolgreich deaktiviert.” `
                    this.showModal = false;
                    EventBus.$emit('show-notification', text)
                    EventBus.$emit(`update-status-${this.user.id}`, this.status_id)
                }).catch(err => {
                    console.log(err)
                })
            }
        }
    }
</script>

<style lang="scss" scoped>
     .status__modal {
        width: 600px;
        background: #FFFFFF;
        box-shadow: 8px 8px 0px #D3E3EC;
        padding: 24px 32px;
        position: absolute;
        z-index: 9;
        top: 400px;
        left: 50%;
        transform: translateX(-50%);

        &--header {
            text-align: center;
            margin-bottom: 32px;

            h4 {
                margin: 0;
                padding: 0;

                font-family: 'Nexa';
                font-style: normal;
                font-weight: 700;
                font-size: 18px;
                line-height: 27px;

                color: #16A9B7;
            }
        }

        &--body {
            font-family: 'Nexa';
            font-style: normal;
            font-weight: 400;
            font-size: 16px;
            line-height: 24px;
            text-align: center;
            color: #2C5B7D;
        }

        &--footer {
            margin-top: 32px;
            display: flex;
            justify-content: space-between;

            gap: 32px;
            .button {
                width: 50%;
                padding: 8px;

                /* Nexa Bold / 18 */

                font-family: 'Nexa';
                font-style: normal;
                font-weight: 700;
                font-size: 18px;
                line-height: 27px;
                text-align: center;
            }

            .confirm {
                color: #FFFFFF;
                background: #16A9B7;
            }

            .cancel {
                background: #C6E9F2;
                color: #19849E;
            }
        }
    }
</style>
