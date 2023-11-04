<template>
        <div class="login relative flex justify-center flex-col pt-3">
            <div class="rounded-full red"></div>
            <div class="rounded-full green"></div>
            <div class="rounded-full orange"></div>
            <div class="rounded-full orange-small"></div>
            <div class="rounded-full gray"></div>
            <div class="bg-white shadow rounded-lg py-8 mx-auto login-form">
                <div  class="text-center">
                    <img class="logo-login" src="/images/lets_play_logo.png"/>
                </div>
                <div class="message-text-content">
                  <p>{{message}}</p>
                </div>
                <div class="text-center" v-if="goBack">
                    <router-link to="/login"  class="w-full btn btn-primary login-btn">
                        Zurück zur Anmeldungsseite
                    </router-link>
                </div>
            </div>
        </div>
    </template>
<script>
export default {
    name: "RegistrationMessage",
    props:{
        message:{
            required:true,
            type: String,
        },
        goBack:{
            default:false
        }
    }
}
</script>

<style scoped>

.login-form {
    max-width: 516px;
    margin-top: 150px;
    width: 100%;
    padding: 27px 51px 44px;
    border: 0.5px solid #C2C5C6;
    border-radius: 15px;
    box-shadow: none !important;
}

.login-btn {
    background: #AEC90B;
    border-radius: 5px;
    font-weight: 500;
    font-size: 15px;
    line-height: 41px;
    text-shadow: none !important;
    text-align: center;
    width: 242px;
    height: 41px;
}

.login-btn:hover {
    cursor: pointer;
    background: #8ca008 !important;
}

.logo-login {
    object-fit: contain;
    margin-bottom: 31px;
}
.message-text-content{
    font-weight: 400;
    font-size: 14px;
    line-height: 17px;
    text-align: center;
    color: #AEC90B;
    margin-bottom: 25px;
}

.red {
    width: 56px;
    height: 56px;
    background-color: #E62D4F;
    position: absolute;
    bottom: 12%;
    left: 23%;
}

.green {
    width: 30px;
    height: 30px;
    background-color: #AEC90B;
    position: absolute;
    bottom: 34%;
    left: 28%;
}

.gray {
    width: 19px;
    height: 19px;
    background-color: #C2C5C6;
    position: absolute;
    top: 23%;
    right: 28%;
}

.orange {
    width: 50px;
    height: 50px;
    background-color: #F2A402;
    position: absolute;
    top: 4%;
    right: 36%;
}

.orange-small {
    width: 17px;
    height: 17px;
    background-color: #F2A402;
    position: absolute;
    bottom: 6%;
    left: 34%;
}

</style>
