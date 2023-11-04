<template>
    <div class="card-panel-home bg-white">
        <div class="right-info-card">
            <div class="blue-header">
                <p class="text-white">Aktuelle Spielgruppen Planung </p>
                <div>
                    <img src="/images/man-icon.png"/>
                </div>
            </div>
            <div class="statistic-numbers">
                <p class="green-number statistic-number">{{green}}</p>
                <p class="orange-number statistic-number">{{orange}}</p>
                <p class="yellow-number statistic-number">{{yellow}}</p>
                <p class="red-number statistic-number">{{red}}</p>
            </div>
            <hr style="margin-inline: 20px;" class="input-divider"/>
            <ul class="info-statistic-list">
                <div class="flex items-center mt-3">
                    <div class="green-number-item list-item-bullet"></div>
                    <li class="statistic-item">Optimal eingeteilt</li>
                </div>
                <div class="flex items-center mt-3">
                    <div class="orange-number-item list-item-bullet"></div>
                    <li class="statistic-item">Nicht im naheliegendsten Standort eingeteilt</li>
                </div>
                <div class="flex items-center mt-3">
                    <div class="yellow-number-item list-item-bullet"></div>
                    <li class="statistic-item">Nicht bei derselben Spielgruppenleitung eingeteilt
                    </li>
                </div>
                <div class="flex items-center mt-3">
                    <div class="red-number-item list-item-bullet"></div>
                    <li class="statistic-item">Konnte nicht eingeteilt werden</li>
                </div>
            </ul>
        </div>
    </div>
</template>

<script>
import axios from "../axios";

export default {
    name: "PlayGroupPlanning",
    data: ()=> ({
      green:0,
      orange:0,
      yellow:0,
      red:0,
    }),
    created(){
        this.getPlanungStats()
    },
    methods:{
        getPlanungStats(){
            axios.get(`/api/planung/stats`).then(res=>{
                this.green = res.data.green
                this.orange = res.data.orange
                this.yellow = res.data.yellow
                this.red = res.data.red
            })
        }
    }
}
</script>

<style scoped lang="scss">
.input-divider {
    border-top: 1px solid #C2C5C6;
    margin-block: 15px;
}
.right-info-card {
    border-radius: 12px;
}

.blue-header {
    background: #0063A7;
    display: flex;
    justify-content: space-between;
    padding: 8px 13px 10px 25px;
    border-top-left-radius: 15px;
    border-top-right-radius:15px;
    p{
        font-weight: 600;
        font-size: 18px;
        line-height: 22px;
    }
    img{
        vertical-align: middle;
    }
}
.statistic-numbers {
    padding: 11px 18px 0 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.green-number {
    color: #AEC90B;
}

.orange-number {
    color: #F0A500;
}

.yellow-number {
    color: #FFE926;
}

.red-number {
    color: #E62D4F;
}

.statistic-number {
    font-size: 35px;
    font-weight: 600;
    line-height: 42px;
}

.info-statistic-list {
    list-style: none;
    padding: 0 20px 20px 20px;
}

.list-item-bullet {
    width: 10px;
    height: 10px;
    margin-right: 6px;
}

.green-number-item {
    background-color: #AEC90B;
}

.orange-number-item {
    background-color: #F0A500;
}

.yellow-number-item {
    background-color: #FFE926;
}

.red-number-item {
    background-color: #E62D4F;
}

.statistic-item {
    font-size: 11px;
    line-height: 13px;
    color: #42474A;
}
</style>
