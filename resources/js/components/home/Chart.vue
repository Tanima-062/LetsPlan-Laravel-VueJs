<template>
    <div class="chart-box">
        <Pie
            :chart-options="chartOptions"
            :chart-data="chartData"
            :chart-id="chartId"
            :dataset-id-key="datasetIdKey"
            :plugins="plugins"
            :css-classes="cssClasses"
            :styles="styles"
            :width="width"
            :height="height"
        />
        <div class="labels">
            <div
                v-for="(item, index) in chartData.labels"
                :key="item"
                class="labels-container"
            >
                <div
                    class="box"
                    style="width: 24px; height: 24px"
                    :style="{
                        background:
                            chartData.datasets[0].backgroundColor[index],
                    }"
                ></div>
                <div class="label">
                    {{ item }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Pie } from "vue-chartjs/legacy";
import DataLabels from 'chartjs-plugin-datalabels'
import staticColors from "../../colors.js"

import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    ArcElement,
    CategoryScale,
} from "chart.js";

ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale);

export default {
    name: "PieChart",
    components: {
        Pie,
    },
    props: {
        chartId: {
            type: String,
            default: "pie-chart",
        },
        datasetIdKey: {
            type: String,
            default: "label",
        },
        width: {
            type: Number,
            default: 300,
        },
        height: {
            type: Number,
            default: 300,
        },
        cssClasses: {
            default: "",
            type: String,
        },
        styles: {
            type: Object,
            default: () => {},
        },
        plugins: {
            type: Array,
            default: () => [DataLabels],
        },
        label: {
            type: Array,
            default: () => [],
        },
        colors: {
            type: Array,
            default: () => [],
        },
        textColors: {
            type: [String, Array],
            default: 'white'
        },
        data: {
            type: Array,
            default: () => [],
        },
        showPercentage: {
            default: true,
        }
    },
    computed: {
        chartData() {
            return {
                labels: this.label,
                datasets: [
                    {
                        datalabels: {
                            anchor: 'end',
                            clamp: true,
                            align: 'start',
                            formatter: (value,ctx) => {
                                if(this.showPercentage) {
                                    const total = this.data.reduce((total, cur) => total + cur, 0);
                                    const percentage = (value / total * 100).toFixed(1)
                                    const display = [value, `${percentage} %`];
                                    return display;
                                }
                                return value;
                            },
                            color: this.textColors,
                            font: {
                                family: 'Ubuntu',
                                style: 'normal',
                                size: '16px',
                                weight: '700',
                            }
                        },
                        backgroundColor: this.colors.length ? this.colors : staticColors,
                        data: this.data,
                    },
                ],
            }
        },
    },
    data() {
        return {
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
            },
        };
    },

    methods: {
        generateRandomColors() {
            const colors = [];
            for (let i = 0; i < this.data.length; i++) {
                var g = Math.floor(Math.random() * 200);
                var b = Math.floor(Math.random() * 200);
                colors.push("rgb(" + 156 + "," + g + "," + b + ")");
            }
            return colors;
         }
    },
};
</script>

<style scoped>

#pie-chart label {
    display: block;
    color: red !important;
}
.labels {
    max-height: 200px;
    overflow-y: auto;
}
.labels-container {
    display: flex;
    align-items: center;
    margin: 20px 0;
}

.label {
    font-family: "Nexa";
    font-style: normal;
    font-weight: 400;
    font-size: 13px;
    line-height: 20px;
    color: #2c5b7d;
    margin-left: 15px;
}
</style>
