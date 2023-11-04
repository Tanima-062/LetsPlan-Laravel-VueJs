<template>
    <div class="planning__card">
        <div class="planning__card--top">
            <DotIconVue />
            <h3 class="planning__card--top--title">{{ plannung.name  }}</h3>
        </div>
        <div class="planning__card--body">
            <div class="planning__card--image__section">
                <img :src="plannung.file_url" alt="">
            </div>
            <div class="planning__card--info__section">
                <div class="planning__card--info">
                    <div class="planning__card--info__item">
                        <h4>Strasse</h4>
                        <p>{{plannung.street_name}}</p>
                    </div>
                    <div class="planning__card--info__item">
                        <h4>Hausnummer</h4>
                        <p>{{plannung.street_number}}</p>
                    </div>
                </div>
                <div class="planning__card--info">
                    <div class="planning__card--info__item">
                        <h4>PLZ</h4>
                        <p>{{ plannung.postal_code }}</p>
                    </div>
                    <div class="planning__card--info__item">
                        <h4>Ort</h4>
                        <p>{{plannung.location}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="planning__card--table">
            <PlanningTable :plannung="plannung" :key="key"></PlanningTable>
        </div>
    </div>
</template>

<script>
    import DotIconVue from './Icons/DotIcon.vue';
    import PlanningTable from './Table/PlanningTable.vue';
    import throttle from 'lodash/throttle'

    export default {
        components: {
            DotIconVue,
            PlanningTable,
        },
        props: {
            plannung: {
                type: Object,
                required: true
            }
        },
        data(){
            return {
                key: 0
            }
        },

        watch:{
            plannung: {
                deep: true,
                handler: throttle(function (newVal, oldVal) {
                    this.key++

                }, 1000),
            },
        },

    }
</script>

<style lang="scss" scoped>
    .planning__card {
        background: #FAFCFF;
        border: 1px solid #87B1CB;
        margin-top: 24px;
        margin-bottom: 24px;
        width: 50%;
        // margin-left: 10px;
        // margin-right: 10px;

        width: 49%;
        height: max-content;

        &--top {
            display: flex;
            align-items: center;
            padding: 16px;

            &--title {
                margin: 0;
                padding: 0;
                margin-left: 16px;
                font-family: 'Ubuntu';
                font-style: normal;
                font-weight: 700;
                font-size: 22px;
                line-height: 25px;
                color: #2C5B7D;
            }
        }
        &--body{
            padding: 16px;
            display: flex;
            gap: 20px;

            .planning__card--image__section {
                width: 30%;

                img {
                    max-width: 100%;
                    height: auto;
                    max-width: 112px;
                }
            }

            .planning__card--info__section {
                width: 70%;
            }
            .planning__card--info {
                display: flex;
                gap: 10px;
                margin-bottom: 20px;

                &:last-child {
                    margin-bottom: 0;
                }
                &__item {
                    width: 50%;
                    h4 {
                        font-family: 'Nexa';
                        font-style: normal;
                        font-weight: 700;
                        font-size: 16px;
                        line-height: 24px;
                        color: #16A9B7;
                        margin: 0;
                        padding: 0;
                    }
                    p {
                        font-family: 'Nexa';
                        font-style: normal;
                        font-weight: 400;
                        font-size: 15px;
                        line-height: 23px;
                        color: #2C5B7D;
                        margin: 0;
                        padding: 0;
                    }


                    &:last-child {
                        margin-bottom: 0;
                    }
                }
            }
        }

        &--table {

        }
    }

    .planning__table {
        &--header {
            border-top: 1px solid #87B1CB;
            display: flex;
            &__row {
                padding: 20px 24px;
                font-family: 'Nexa';
                font-style: normal;
                font-weight: 700;
                font-size: 18px;
                line-height: 27px;
                display: flex;
                align-items: center;
                color: #2C5B7D;
                width: 33.33%;

                border-right:  1px solid #87B1CB;

                &:last-child {
                    border: none;
                }
            }
        }
    }
</style>
