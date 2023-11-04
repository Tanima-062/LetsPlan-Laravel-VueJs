<template>
    <div :style="[smallItem ? {minWidth:'200px', width:'100%',padding:'15px'} : '']" class="plannung-item-card">
        <div class="flex items-center mb-15">
            <img class="plannung-icon" src="/images/plannung-item-icon.png"/>
            <h3>{{plannung.name}}</h3>
        </div>
        <div class="flex items-center">
            <img :style="[smallItem ? {width:'125px',height:'84px'} : '']" class="plannung-image" :src="plannung.image_path"/>
            <div class="plannung-info-section">
                <div :style="[smallItem ? {paddingTop:'4px'} : '']" class="plannung-info">
                    <div class="left-info-section">
                        <p :class="[smallItem ? 'small-text-size' : 'plannung-info-text']">Strasse</p>
                        <p :class="[smallItem ? 'small-text-size gray-text' : 'plannung-info-text gray-text']">{{plannung.street_name}}</p>
                    </div>
                    <div>
                        <p :class="[smallItem ? 'small-text-size' : 'plannung-info-text']">PLZ</p>
                        <p :class="[smallItem ? 'small-text-size gray-text' : 'plannung-info-text gray-text']">{{ plannung.postal_code }}</p>
                    </div>
                </div>
                <div class="plannung-info flex flex-col justify-between">
                    <div class="left-info-section">
                        <p :class="[smallItem ? 'small-text-size' : 'plannung-info-text']">Hausnummer</p>
                        <p :class="[smallItem ? 'small-text-size gray-text' : 'plannung-info-text gray-text']">{{plannung.street_number}}</p>
                    </div>
                    <div>
                        <p :class="[smallItem ? 'small-text-size' : 'plannung-info-text']">Ort</p>
                        <p :class="[smallItem ? 'small-text-size gray-text' : 'plannung-info-text gray-text']">{{plannung.location}}</p>
                    </div>
                </div>
            </div>
        </div>
        <PlannungTable :plannung="plannung"/>
    </div>
</template>

<script>
import PlannungTable from "./PlannungTable";
export default {
    name: "PlannungCardItem",
    components: {PlannungTable},
    props: ['smallItem','plannung']
}
</script>

<style scoped lang="scss">
.plannung-item-card {
    width: 100%;
    max-width:543px;
    padding: 20px;
    border: 1px solid #C2C5C6;
    box-sizing: border-box;
    border-radius: 5px;
    background-color: white;
    h3{
        font-weight: 700;
        font-size: 20px;
        line-height: 24px;
        color: #42474A;
        padding-left: 8px;
    }
    .plannung-image {
        width: 200px;
        height: 107px;
        object-fit: cover;
        margin-right: 20px;
    }
    .plannung-info-text {
        font-weight: 500;
        font-size: 13px;
        color: #42474A;
        word-break: break-word;
        width: 217px;
        margin-bottom: 5px;
        &.gray-text{
            font-weight: 400;
            color: #727574;
        }
    }
    &.plannung-second-block{
        h3{
            font-size: 17px;
            line-height: 20px;
        }
        .plannung-info-text {
            font-size: 11px;
            line-height: 13px;
            width: 100px;
            &.gray-text{
                font-size: 11px;
                line-height: 13px;
            }
        }
        .left-info-section {
            padding-bottom: 12px;
        }
    }
}

.plannung-info-section {
    width: 400px;
    display: flex;
}

.left-info-section {
    padding-bottom: 20px;
}



</style>
