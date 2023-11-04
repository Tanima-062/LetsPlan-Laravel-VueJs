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
                company: null,
            }
        },
        computed: {
            title(){
                return this.status_id == 1  ? 'Unternehmen aktivieren?' : 'Unternehmen deaktivieren?';
            },
            body(){
               return this.status_id == 1 ? `Sind Sie sicher, dass Sie das Unternehmen "${this.company.name}" wirklich aktivieren wollen?` : `Sind Sie sicher, dass Sie das Unternehmen "${this.company.name}" wirklich deaktivieren wollen?`;
            }
        },
        mounted(){
            EventBus.$on('show-confirm-modal', (e)=>{
                this.status_id = e.status
                this.company = e.company
                this.showModal = true;
            })
        },
        methods: {
            changeStatus(){
                let url = `/api/company/${this.status_id == 1 ? 'activate' : 'deactivate'}/${this.company.id}/`

                axios.post(url).then(res => {
                    const text = this.status_id == 1 ? `Das Unternehmen "${this.company.name}" wurde erfolgreich aktiviert.` : `Das Unternehmen "${this.company.name}" wurde erfolgreich deaktiviert.`

                    this.showModal = false;
                    EventBus.$emit('show-notification', text)
                    EventBus.$emit(`update-status-${this.company.id}`, this.status_id)
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
