<template>
    <div :title="statusTooltipText">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"
            v-if="status == 2">
            <rect width="14" height="14" fill="#E97D32" />
        </svg>

        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"
            v-if="status == 1">
            <rect width="14" height="14" fill="#55C5CD" />
        </svg>
    </div>


</template>

<script>
export default {
    props: {
        status: {
            required: true,
        }
    },
    computed:{
        statusTooltipText(){
                if(this.status == 1){
                    return 'Aktiv'
                }else if(this.status == 2){
                    return 'Inaktiv'
                }

                return 'Pendent'
            }
    }
}
</script>
