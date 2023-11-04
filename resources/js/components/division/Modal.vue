<script>
export default {
    props: {
        show: Boolean,
        containerStyle: {
            type: String,
            default: "",
        },
    },
};
</script>

<template>
    <Transition name="modal">
        <div v-if="show" class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container" :style="containerStyle">
                    <div class="modal-header">
                        <slot name="header"></slot>
                    </div>

                    <div class="modal-body">
                        <slot name="body"></slot>
                    </div>

                    <div class="modal-footer">
                        <slot name="footer">
                            <slot name="first-button">
                                <button
                                    class="modal-primary-button"
                                    @click="$emit('close')"
                                >
                                    OK
                                </button>
                            </slot>
                            <slot name="second-button">
                                <button
                                    class="modal-seconday-button"
                                    @click="$emit('close')"
                                >
                                    Close
                                </button>
                            </slot>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style>
.modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: table;
    transition: opacity 0.3s ease;
}

.modal-wrapper {
    display: table-cell;
    vertical-align: middle;
}

.modal-container {
    width: 592px;
    height: 226px;
    margin: 0px auto;
    padding: 20px 30px;
    background-color: #fff;
    border-radius: 2px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
    transition: all 0.3s ease;
}

.modal-header h3 {
    margin-top: 0;
    font-family: "Nexa";
    font-style: normal;
    font-weight: 700;
    font-size: 18px;
    line-height: 27px;
    display: flex;
    align-items: center;
    text-align: center;
    color: #16a9b7;
}

.modal-body {
    margin: 20px 0;
}

.modal-default-button {
    float: right;
}

.modal-enter-from {
    opacity: 0;
}

.modal-leave-to {
    opacity: 0;
}

.modal-enter-from .modal-container,
.modal-leave-to .modal-container {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
}
.modal-primary-button {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    padding: 8px 96px;
    gap: 10px;
    color: w#E3E9EB;
    position: absolute;
    width: 248px;
    height: 43px;
    left: calc(50% - 248px / 2 - 140px);
    top: calc(50% - 43px / 2 + 66.5px);
    background: #16a9b7;
}
.modal-secondary-button {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    padding: 8px 96px;
    gap: 10px;
    color: w#E3E9EB;
    position: absolute;
    width: 248px;
    height: 43px;
    left: calc(50% - 248px / 2 + 140px);
    top: calc(50% - 43px / 2 + 66.5px);
    background: #c6e9f2;
}
</style>
