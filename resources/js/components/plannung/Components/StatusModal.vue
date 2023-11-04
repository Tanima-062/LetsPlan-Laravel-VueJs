<template>
    <div class="status__modal" v-if="showModal" v-click-outside="outsideClick">
        <!-- <div class="status__modal--header">
            <h4>Aktueller Status</h4>
            <h4>Status ändern</h4>
        </div> -->
        <div class="status__modal--body">
            <div class="status__modal--body__column">
                <h4>Aktueller Status</h4>
                <div  class="dropdown disabled">
                    <button class="dropdown-button">{{currentStatusText}}</button>
                </div>
            </div>
            <div class="status__modal--body__column">
                <h4>Status ändern</h4>
                <div  class="dropdown">
                    <button class="dropdown-button" @click="showDropdown = !showDropdown">Status ändern</button>
                    <div class="dropdown-content" v-if="showDropdown && status_id != 1">
                        <!-- <template v-for="status in statuses" >
                            <li
                                class="dropdown-option"
                                @click="selectItem(status.status_id)"
                                :key="status.status_id"

                                :class="{active: status.status_id == status_id}"
                                v-if="status.status_id != 1"
                                >
                                {{ status.status_name }}
                            </li>
                        </template> -->
                    <template v-if="status_id == 2">
                        <li
                            class="dropdown-option"
                            @click="selectItem(3)"
                        >
                            Aktiv-Bereit zur Besprechung
                        </li>
                        <li
                            class="dropdown-option"
                            @click="selectItem(4)"
                        >
                            Abgeschlossen
                        </li>
                    </template>
                    <template v-if="status_id == 3">
                        <li
                            class="dropdown-option"
                            @click="selectItem(4)"
                        >
                            Abgeschlossen
                        </li>
                    </template>
                    <template v-if="status_id == 4">
                        <li
                            class="dropdown-option"
                            @click="selectItem(3)"
                        >
                            Aktiv-Bereit zur Besprechung
                        </li>
                    </template>

                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="status__modal--footer">
            <button class="button confirm" >Ja</button>
            <button class="button cancel" >Nein</button>
        </div> -->
    </div>
</template>

<script>
    export default {
        props: {
            statuses: {
                type: Array,
                required: true
            },
            status_id: {
                type: Number,
                required: true
            },
        },
        data(){
            return  {
                showModal: false,
                showDropdown: false
            }
        },
        computed: {
            currentStatusText(){
                const status=  this.statuses.find(item => item.status_id == this.status_id)
                if(status) return status.status_name;
                return ''
            },
            firstStatusText(){
                if(this.statuses.length){
                    return this.statuses[0].status_name
                }

                return ''
            }
        },
        mounted(){
            EventBus.$on('show-status-modal', (e)=>{
                setTimeout(()=> {
                    this.showModal = true;
                }, 200)
            })
        },
        methods: {
            selectItem(status){
               this.$emit('changeStatus', status)
               this.showModal = false;
               this.showDropdown = false
            },
            outsideClick(){
                console.log('outside clicked');
                // () => (showModal = false)
                if(this.showModal) {
                    console.log('already show modal')
                    this.showModal = false;
                    this.showDropdown = false
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
     .status__modal {
        width: 660px;
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
            display: flex;
            gap: 32px;

            &__column {
                width: 50%;

                h4 {
                    font-family: 'Nexa';
                    font-style: normal;
                    font-weight: 700;
                    font-size: 18px;
                    line-height: 27px;
                    color: #16A9B7;
                    margin-bottom: 32px;
                }



                .dropdown {
                    width: 100%;
                    height: 40px;
                    outline: none;
                    border: 1px solid #D2EEFC;
                    background: #FFFFFF;

                    // appearance: none;

                    background-repeat: no-repeat no-repeat;
                    background-position: 95% center;
                    // background-size: cover;
                    background-image: url("data:image/svg+xml,%3Csvg width='22' height='14' viewBox='0 0 22 14' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M2.66406 2.66675L10.9974 11.0001L19.3307 2.66675' stroke='%23063F5F' stroke-width='3' stroke-linecap='square'/%3E%3C/svg%3E%0A");



                    &-button {
                        padding: 12px 24px;
                        font-family: 'Nexa';
                        font-style: normal;
                        font-weight: 700;
                        font-size: 15px;
                        line-height: 23px;
                        color: #87B1CB;
                        margin: 0;
                        height: 40px;
                        cursor: pointer;
                        outline: none;
                        width: 100%;
                        text-align: left;
                    }

                    &-content {
                        box-shadow: 1px 1px 5px rgba($color: #000000, $alpha: 0.1);
                    }

                    &-option {
                        font-family: 'Nexa';
                        font-style: normal;
                        font-weight: 700;
                        font-size: 15px;
                        line-height: 23px;
                        color: #2C5B7D;
                        border-top: 1px solid #FEF1E7;
                        padding: 12px 24px;
                        list-style: none;
                        background: white;
                        cursor: pointer;


                        &:hover {
                            background: #D2EEFC;
                        }
                    }

                    &-option.active {
                        background: #D2EEFC;
                    }
                }
                .dropdown.disabled {
                    background: #E9F7FE;
                    background-image: none;
                    .dropdown-button {
                        font-family: 'Nexa';
                        font-style: normal;
                        font-weight: 700;
                        font-size: 15px;
                        line-height: 23px;
                        color: #2C5B7D;
                    }
                }
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
