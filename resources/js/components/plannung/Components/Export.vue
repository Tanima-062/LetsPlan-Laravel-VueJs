<template>
    <div class="export">
        <button class="export__btn" @click.prevent="exportChildrens">Planungsdaten exportieren</button>
    </div>
</template>

<script>
    import axios from '../../../axios'
    export default {
        methods: {
            exportChildrens(){
                const query = {...this.$route.query}
                const queryString =new URLSearchParams(query).toString();

                axios({
                    url:`/api/exports/childrens?${queryString}`,
                    method: 'GET',
                    responseType: 'blob', // important
                }).then((response) => {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'childrens.xlsx'); //or any other extension
                    document.body.appendChild(link);
                    link.click();
                })
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
        }
        margin-left: 10px;
        width: 40%;
    }
</style>
