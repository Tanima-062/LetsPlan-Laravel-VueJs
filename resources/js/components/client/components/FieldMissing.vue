<template>
    <div class="overlay" v-if="visible">
        <div class="field-missing-modal" v-click-outside="() => this.visible = !this.hideOnOutsideClick">
            <h5 class="title" v-if="title">{{title}}</h5>
            <p class="description">{{description}}</p>
            <div class="control">
                <button class="yes" @click.stop="yesClick()">OK</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            title: "",
            description: "",
            yesClick: null,
            visible: false,
            hideOnOutsideClick: true,
        }
    },

    mounted() {
        EventBus.$on('field-missing-modal:show', (e) => {
            this.title = e.title
            this.description = e.description
            this.yesClick = e.yesClick
            this.hideOnOutsideClick = e.hideOnOutsideClick ?? false
            this.visible = true
        })
        EventBus.$on('field-missing-modal:hide', (e) => this.visible = false)
    },
}
</script>

<style lang="scss">
.overlay {
    background-color: transparent;
    width: 100vw;
    height: 100vh;
    position: fixed;
    top: 0px;
    left: 0px;
    z-index: 30;


    .field-missing-modal {
        background-color: white;
        box-shadow: 8px 8px 0px #D3E3EC;
        position: absolute;
        width: 592px;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 24px 32px 27px 32px;

        .title {
            font-family: 'Nexa';
            font-style: normal;
            font-weight: 700;
            font-size: 18px;
            line-height: 27px;
            color: #16A9B7;
            text-align: center;
        }

        .description {
            font-family: 'Nexa';
            font-style: normal;
            font-weight: 400;
            font-size: 16px;
            line-height: 24px;
            text-align: center;
            color: #2C5B7D;
            margin-top: 32px;
        }

        .control {
            display: flex;
            justify-content: space-around;
            flex-direction: row;
            margin-top: 32px;

            .yes {
                width: 248px;
                height: 43px;
                // padding: 8px 96px;
                background: #16A9B7;
                font-family: 'Nexa';
                font-style: normal;
                font-weight: 700;
                font-size: 18px;
                line-height: 27px;
                text-align: center;
                color: #FFFFFF;
            }
        }
    }
}
</style>
