<template>
    <div class="flex bg-white justify-between card-parent">
        <div class="data">
            <h1 class="number">{{ number }}</h1>
            <p>{{ category }}</p>
            <div>
                <router-link :to="{ name: linkName }">{{linkText}}</router-link>
            </div>
        </div>
        <div class="pic">
            <img
                :src="imgIcon"
                alt="icon"
                class="img-icon"
            />
            <img
                :src="img"
                alt="homepage img"
            />
        </div>
    </div>
</template>

<script>
// import Router
import home1 from "../../../../public/images/home-1.png";
import home2 from "../../../../public/images/home-2.png";
import home3 from "../../../../public/images/home-3.png";
import icon1 from "../../../../public/images/pinpaper-filled.png";
import icon2 from "../../../../public/images/home-check.png";
import icon3 from "../../../../public/images/home-smile.png";
export default {
    name: "StatisticCard",

    props: {
        number: [String, Number],
        category: String,
        linkText: String,
        linkName: String,
        type: String,
    },

    data() {
        return {
            img: "",
            imgIcon: "",
        };
    },

    methods: {
        setImg(type) {
            console.log(typeof type);
            if (type === "1") {
                this.img = home1;
                this.imgIcon = icon1;
            } else if (type === "2") {
                this.img = home2;
                this.imgIcon = icon2;
            } else if (type === "3") {
                this.img = home3;
                this.imgIcon = icon3;
            } else {
                return;
            }
        },
    },

    mounted() {
        this.setImg(this.type);
    },
};
</script>

<style lang="scss" scoped>
.card-parent {
    padding: 16px 24px;

    .number {
        font-family: "Nexa";
        font-style: normal;
        font-weight: 700;
        font-size: 48px;
        line-height: 73px;
        color: #e97d32;
        margin-bottom: 0;
    }

    p {
        font-family: "Nexa";
        font-style: normal;
        font-weight: 700;
        font-size: 18px;
        line-height: 27px;
        color: #063f5f;
        margin-top: -15px;
    }

    div a {
        font-family: "Nexa";
        font-style: normal;
        font-weight: 700;
        font-size: 15px;
        line-height: 23px;
        color: #e97d32;
        text-decoration: none;
        cursor: pointer;
    }

    .pic {
        position: relative;

        .img-icon {
            position: absolute;
            top: -10px;
            right: -16px;
        }
    }
}
</style>
