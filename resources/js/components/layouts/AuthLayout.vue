<template>
    <div class="login">
        <!-- <div class="rounded-full red"></div>
        <div class="rounded-full green"></div>
        <div class="rounded-full orange"></div>
        <div class="rounded-full orange-small"></div>
        <div class="rounded-full gray"></div> -->
        <router-view></router-view>
    </div>
</template>

<script>
export default {
    name: "AuthLayout",
};
</script>

<style>
.space-bottom-15 {
    margin-bottom: 24px;
}
.login {
    height: 100vh;
    background: #fff;
}

.form-label {
    font-weight: 500;
    font-size: 12px;
    line-height: 14px;
    color: #42474a;
}

.login-btn:hover {
    cursor: pointer;
}

.forgot-password {
    color: #aec90b;
    font-weight: 400;
    font-size: 18px;
    line-height: 22px;
}

.logo-login {
    object-fit: contain;
}

.login-title {
    font-weight: 700;
    margin-top: 23px;
    margin-bottom: 12px;
    font-size: 22px;
    line-height: 22.6px;
    color: #1b343d;
}
.login input {
    background: #f8fbfa;
    border: 0.5px solid #727574;
    box-sizing: border-box;
    border-radius: 5px;
    height: 40px;
}
.red {
    width: 56px;
    height: 56px;
    background-color: #e62d4f;
    position: absolute;
    bottom: 12%;
    left: 23%;
}

.green {
    width: 30px;
    height: 30px;
    background-color: #aec90b;
    position: absolute;
    bottom: 34%;
    left: 28%;
}

.gray {
    width: 19px;
    height: 19px;
    background-color: #c2c5c6;
    position: absolute;
    top: 23%;
    right: 28%;
}

.orange {
    width: 50px;
    height: 50px;
    background-color: #f2a402;
    position: absolute;
    top: 4%;
    right: 36%;
}

.orange-small {
    width: 17px;
    height: 17px;
    background-color: #f2a402;
    position: absolute;
    bottom: 6%;
    left: 34%;
}
</style>
