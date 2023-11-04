<template>
    <div class="overlay" v-if="visible">
        <div class="confirmation-modal" v-click-outside="() => this.visible = !this.hideOnOutsideClick">
            <h5 class="title">{{title}}</h5>
            <p class="description">{{description}}</p>
            <div class="mt-4 ml-6">
                <div>
                    <svg width="26" height="34" viewBox="0 0 26 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.33594 32H0.0859375V33.25H1.33594V32ZM24.6693 32V33.25H25.9193V32H24.6693ZM1.33594 2V0.75H0.0859375V2H1.33594ZM16.3359 2L17.2198 1.11612L16.8537 0.75H16.3359V2ZM24.6693 10.3333H25.9193V9.81557L25.5532 9.44945L24.6693 10.3333ZM1.33594 33.25H24.6693V30.75H1.33594V33.25ZM0.0859375 2V32H2.58594V2H0.0859375ZM14.6693 0.75H1.33594V3.25H14.6693V0.75ZM25.9193 32V12H23.4193V32H25.9193ZM14.6693 3.25H16.3359V0.75H14.6693V3.25ZM25.9193 12V10.3333H23.4193V12H25.9193ZM25.5532 9.44945L17.2198 1.11612L15.4521 2.88388L23.7854 11.2172L25.5532 9.44945ZM13.4193 2V8.66667H15.9193V2H13.4193ZM13.4193 8.66667C13.4193 9.90654 14.0266 11.0418 14.8271 11.8422C15.6275 12.6426 16.7627 13.25 18.0026 13.25V10.75C17.5758 10.75 17.0444 10.524 16.5948 10.0744C16.1453 9.62488 15.9193 9.09346 15.9193 8.66667H13.4193ZM18.0026 13.25H24.6693V10.75H18.0026V13.25Z" fill="#E97D32"/>
                    </svg>
                    <span class="ml-4">Generelle Informationen für Spielgruppeneltern</span>
                </div>
                <div>
                    <svg width="26" height="34" viewBox="0 0 26 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.33594 32H0.0859375V33.25H1.33594V32ZM24.6693 32V33.25H25.9193V32H24.6693ZM1.33594 2V0.75H0.0859375V2H1.33594ZM16.3359 2L17.2198 1.11612L16.8537 0.75H16.3359V2ZM24.6693 10.3333H25.9193V9.81557L25.5532 9.44945L24.6693 10.3333ZM1.33594 33.25H24.6693V30.75H1.33594V33.25ZM0.0859375 2V32H2.58594V2H0.0859375ZM14.6693 0.75H1.33594V3.25H14.6693V0.75ZM25.9193 32V12H23.4193V32H25.9193ZM14.6693 3.25H16.3359V0.75H14.6693V3.25ZM25.9193 12V10.3333H23.4193V12H25.9193ZM25.5532 9.44945L17.2198 1.11612L15.4521 2.88388L23.7854 11.2172L25.5532 9.44945ZM13.4193 2V8.66667H15.9193V2H13.4193ZM13.4193 8.66667C13.4193 9.90654 14.0266 11.0418 14.8271 11.8422C15.6275 12.6426 16.7627 13.25 18.0026 13.25V10.75C17.5758 10.75 17.0444 10.524 16.5948 10.0744C16.1453 9.62488 15.9193 9.09346 15.9193 8.66667H13.4193ZM18.0026 13.25H24.6693V10.75H18.0026V13.25Z" fill="#E97D32"/>
                    </svg>
                    <span class="ml-4">Uebergangssituationen bei Kindern</span>
                </div>
                <div>
                    <svg width="26" height="34" viewBox="0 0 26 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.33594 32H0.0859375V33.25H1.33594V32ZM24.6693 32V33.25H25.9193V32H24.6693ZM1.33594 2V0.75H0.0859375V2H1.33594ZM16.3359 2L17.2198 1.11612L16.8537 0.75H16.3359V2ZM24.6693 10.3333H25.9193V9.81557L25.5532 9.44945L24.6693 10.3333ZM1.33594 33.25H24.6693V30.75H1.33594V33.25ZM0.0859375 2V32H2.58594V2H0.0859375ZM14.6693 0.75H1.33594V3.25H14.6693V0.75ZM25.9193 32V12H23.4193V32H25.9193ZM14.6693 3.25H16.3359V0.75H14.6693V3.25ZM25.9193 12V10.3333H23.4193V12H25.9193ZM25.5532 9.44945L17.2198 1.11612L15.4521 2.88388L23.7854 11.2172L25.5532 9.44945ZM13.4193 2V8.66667H15.9193V2H13.4193ZM13.4193 8.66667C13.4193 9.90654 14.0266 11.0418 14.8271 11.8422C15.6275 12.6426 16.7627 13.25 18.0026 13.25V10.75C17.5758 10.75 17.0444 10.524 16.5948 10.0744C16.1453 9.62488 15.9193 9.09346 15.9193 8.66667H13.4193ZM18.0026 13.25H24.6693V10.75H18.0026V13.25Z" fill="#E97D32"/>
                    </svg>
                    <span class="ml-4">Wichtige Informationen für SpielgruppenleiterIn</span>
                </div>
            </div>
            <div class="control">
                <button class="yes" @click.stop="yesClick()">Ja</button>
                <button class="no" @click.stop="noClick()">Nein</button>
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
            noClick: null,
            visible: false,
            hideOnOutsideClick: true,
        }
    },

    mounted() {
        EventBus.$on('email-modal:show', (e) => {
            this.title = e.title
            this.description = e.description
            this.yesClick = e.yesClick
            this.noClick = e.noClick
            this.hideOnOutsideClick = e.hideOnOutsideClick ?? false
            this.visible = true
        })
        EventBus.$on('email-modal:hide', (e) => this.visible = false)
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


    .confirmation-modal {
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
                color: #FFFFFF;
            }

            .no {
                width: 248px;
                height: 43px;
                background: #C6E9F2;
                color: #19849E;

            }

            .yes,
            .no {

                font-family: 'Nexa';
                font-style: normal;
                font-weight: 700;
                font-size: 18px;
                line-height: 27px;
                text-align: center;
            }
        }
    }
}
</style>
