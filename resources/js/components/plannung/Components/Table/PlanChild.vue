<template>
    <div class="plan__child" :style="{backgroundColor:backgroundColor}" :id="child.id">
        <div class="left">
            <div>
                <DotIconVue />
            </div
            >
            <div style="text-align: left;">
                <h4>{{child.full_name}}</h4>
            </div>
            <div style="margin-left: auto;">
                <span @click="openRegistrationPage">
                    <DocumentIcon class="document__icon" />
                </span>
            </div>
        </div>
        <div class="right">
            <span @click="showModal= true" v-if="user && user.role_id == 1">
                <TrashIcon style="cursor:pointer;" />
            </span>
        </div>
        <div class="delete__modal" v-if="showModal">
            <div class="delete__modal--header">
                <h4>Eintrag löschen?</h4>
            </div>
            <div class="delete__modal--body">
                <p>
                    {{ `Das Löschen eines Eintrages kann nicht rückgängig gemacht werden. Sind Sie sicher, dass Sie den Eintrag für "${child.full_name}" wirklich löschen möchten?` }}

                </p>
            </div>
            <div class="delete__modal--footer">
               <button class="button confirm" @click="deletePlan">Ja</button>
               <button class="button cancel" @click="showModal= false">Nein</button>
            </div>
        </div>
    </div>
</template>

<script>
    import DotIconVue from '../Icons/DotIcon.vue';
    import DocumentIcon from '../Icons/DocumentIcon.vue'
    import TrashIcon from '../Icons/TrashIcon.vue'
    import axios from '../../../../axios';
    export default {
        components: {
            DotIconVue,
            DocumentIcon,
            TrashIcon
        },
        props: {
            child: {
                type: Object,
                required: true
            },
            dayIndex: {
                type: Number,
                required: true
            },
            planning_id: {
                type: Number,
                required: true
            },
            type: {
                type: String,
                required: true
            }
        },
        data(){
            return {
                showModal: false,
                backgroundColor: this.child.color
            }
        },

        mounted(){
            // console.log(this.user)
            EventBus.$on(`changeBackground-${this.child.id}-${this.child.child_reg_id}`, (e)=>{
                // '#ffffff'
                console.log(e)
                this.backgroundColor = e
            })
        },
        computed: {
            user(){
                return this.$store.getters['user'];
            }
        },
        methods: {
            deletePlan(){
                axios.delete(`api/planung/delete-child/${this.child.id}`)
                .then(res => {
                    this.showModal = false;
                    EventBus.$emit('plan-child-delete', `Der Eintrag für "${this.child.full_name}" wurde erfolgreich gelöscht.`)
                    EventBus.$emit(`plan-child-removed-${this.planning_id}`, {
                        type: this.type,
                        dayIndex: this.dayIndex,
                        id: this.child.id
                    })
                }).catch(err => {
                  console.log(err)
                })
            },
            openRegistrationPage(){
                EventBus.$emit(`open-registration-view`, this.child)
            }
        }
    }
</script>

<style lang="scss" scoped>
    .plan__child {
        margin-top: 8px;
        // background: #F8E7A8;
        display: flex;
        // justify-content: space-between;
        align-items: center;

        .left {
            padding: 9px 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width:  80%;
            h4 {
                margin: 0;
                padding: 0;
                margin-left: 8px;

                font-family: 'Ubuntu';
                font-style: normal;
                font-weight: 400;
                font-size: 15px;
                line-height: 17px;
                color: #2C5B7D;
            }
            .document__icon {
                // justify-self: flex-end;
                margin-left: auto;
                cursor: pointer;
            }
        }

        .right {
            padding: 9px 8px;
            border-left: 2px solid white;
        }

    }

    .delete__modal {
        width: 600px;
        background: #FFFFFF;
        box-shadow: 8px 8px 0px #D3E3EC;
        padding: 25px 32px;
        // position: absolute;
        z-index: 9;
        left: 30%;
        transform: translateX(-50%);
        position: fixed;
        top: 50%;
        transform: translateY(-50%);

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
            p {
                font-family: 'Nexa';
                font-style: normal;
                font-weight: 400;
                font-size: 16px;
                line-height: 24px;
                color: #2C5B7D;
                text-align: center;
            }
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
