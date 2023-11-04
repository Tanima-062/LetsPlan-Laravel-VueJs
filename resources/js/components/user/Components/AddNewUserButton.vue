<template>
    <div class="export">
        <button class="export__btn" @click.prevent="addNewUser">
            Neuer Benutzer hinzufügen
        </button>
    </div>
</template>

<script>
    import PlusIcon from './Icons/PlusIcon.vue'
    export default {
        components: {
            PlusIcon
        },
        methods: {
            addNewUser(){
                this.$router.push({name: 'user-create'})
            },
        }
    }
</script>

<style lang="scss" scoped>

    .export {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 48px;
        .export__btn {
            background: #E97D32;
            font-family: 'Nexa';
            font-style: normal;
            font-weight: 700;
            font-size: 18px;
            line-height: 27px;
            text-align: center;
            color: #FFFFFF;
            width: 100%;
            height: 100%;

            background-image: url("data:image/svg+xml,%3Csvg width='31' height='30' viewBox='0 0 31 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M2 15H28.6667M15.3333 1.66666V28.3333' stroke='white' stroke-width='3' stroke-linecap='square'/%3E%3C/svg%3E%0A");
            background-repeat: no-repeat no-repeat;
            background-position: 10px center;
            // background-size: cover;
        }
        margin-left: 10px;
        width: 40%;
    }
</style>
