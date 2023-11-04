<template>
    <div class="card availability">
        <div :style="{backgroundColor:color}" class="ribbon"></div>
        <p :style="[smallItem ? {fontSize:'10px'} : '']" class="availability-text">Anzahl Kinder ({{countPerDay}}/{{ maximumOccupancy }})</p>
    </div>
</template>

<script>
export default {
    name: "PlannungAvailability",
    props: ['smallItem', 'maximumOccupancy', 'countPerDay'],
    computed: {
        color() {
            if ( this.maximumOccupancy === 0){
                return '#000000';
            }
            return this.countPerDay >= this.maximumOccupancy ? '#E62D4F' : '#6E9E22'
        }
    }
}
</script>

<style scoped lang="scss">
.availability {
    display: flex;
    height: 30px;
    border-radius: 5px;
    margin: 3px 5px 3px 5px;
}

.ribbon {
    width: 9px;
    border-radius: 5px 0px 0px 5px;

}

.availability-text {
    font-size: 13px;
    padding-left: 7px;
    align-self: center;
}
.plannung-second-block{
    .availability-text{
        font-size: 10px;
        line-height: 12px;
    }
}
</style>
