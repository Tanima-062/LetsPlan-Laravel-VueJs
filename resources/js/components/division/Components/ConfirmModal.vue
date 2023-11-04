<template>
    <div class="status__modal" v-if="showModal">
        <div class="status__modal--header">
            <h4>{{ title }}</h4>
        </div>
        <div class="status__modal--body">
            {{ body }}
        </div>
        <div class="status__modal--footer">
            <button class="button confirm" @click.prevent="deleteDivision">Ja</button>
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
                division: null,
            }
        },
        computed: {
            title(){
                return `Einteilung löschen?`;
            },
            body(){
               return `Das Löschen einer Einteilung kann nicht rückgängig gemacht werden. Sind Sie sicher, dass Sie diese Einteilung wirklich löschen wollen?`
            }
        },
        mounted(){
            EventBus.$on('show-confirm-modal', (e)=>{
                this.division = e.division
                this.showModal = true;
            })
        },
        methods: {
            deleteDivision(){
                let url = `/api/division-delete/${this.division.id}`

                axios.delete(url).then(res => {
                    const text = `Die Einteilung "${this.division.name}" wurde erfolgreich gelöscht.`

                    this.showModal = false;
                    EventBus.$emit('show-notification', text)
                    this.$emit('divisoin-deleted')
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
