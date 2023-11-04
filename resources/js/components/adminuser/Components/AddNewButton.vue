<template>
    <div>
        <router-link :to="target" style="text-decoration:none">
            <button class="create-button">
                <svg width="31" height="30" viewBox="0 0 31 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 15.0003H28.6667M15.3333 1.66699V28.3337" stroke="white" stroke-width="3"
                        stroke-linecap="square" />
                </svg>
                <h4>{{text}}</h4>
            </button>
        </router-link>
    </div>
</template>

<script>
export default {
    props: {
        text: {
            type: String,
            required: true,
        },
        target: {
            type: String,
            required: true,
        },
    }
}
</script>

<style lang="scss" scoped>
.create-button {
    display: flex;
    background-color: #E97D32;
    width: 420px;
    height: 48px;
    padding: 10px;
    gap: 46px;

    h4 {
        font-family: 'Nexa';
        font-size: 18px;
        line-height: 27px;
        color: white;
        margin-top: 3px;
        text-align: center;
    }
}
</style>
