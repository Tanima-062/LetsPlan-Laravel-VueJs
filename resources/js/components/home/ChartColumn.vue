<template>
    <div class="col-parent">
        <h1>{{ title }}</h1>
        <div class="chart-container">
            <PieChart
                :data="data"
                :label="label"
                :colors="colors"
                :showPercentage="showPercentage"
                :textColors="textColors"
            />
        </div>
    </div>
</template>

<script>
import PieChart from "./Chart.vue";

export default {
    name: "ChartColumn",
    props: {
        title: { type: String, default: "Aktuelle Spielgruppen Planung" },
        label: { type: Array, default: () => [] },
        colors: { type: Array, default: () => [] },
        textColors: { type: [Array, String], default: 'white' },
        data: { label: { type: Array, default: () => [] } },
        showPercentage: {
            default: true,
        }
    },
    mounted() {
        console.log("check");
    },
    components: {
        PieChart,
    },
};
</script>

<style lang="scss" scoped>
.col-parent {
    background: white;
    padding: 24px 22px;
    margin-top: 12px;

    h1 {
        font-family: "Nexa";
        font-style: normal;
        font-weight: 700;
        font-size: 22px;
        line-height: 33px;
        color: #063f5f;
    }

    .chart-container {
        margin-top: 30px;
    }
}
</style>
