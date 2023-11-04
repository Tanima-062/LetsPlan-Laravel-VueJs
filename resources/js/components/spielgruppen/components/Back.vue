<template>
    <div class="back-btn" @click="back">
        <svg
            width="31"
            height="24"
            viewBox="0 0 31 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                d="M2.66797 12L29.3346 12M2.66797 12L12.668 2M2.66797 12L12.668 22"
                stroke="#2C5B7D"
                stroke-width="2.5"
                stroke-linecap="square"
            />
        </svg>
        <p>Zurück</p>
    </div>
</template>

<script>
export default {
    methods: {
        back() {
            if (this.$attrs.showModal) {
                const title = "Änderungen verwerfen?";
                const description =
                    "Wenn Sie zurückgehen oder abbrechen, ohne zu speichern, werden alle Änderungen verworfen. Sind Sie sicher, dass Sie die Änderungen wirklich verwerfen wollen?";
                EventBus.$emit("confirmation-modal:show", {
                    title,
                    description,
                    noClick: () => EventBus.$emit("confirmation-modal:hide"),
                    yesClick: () => {
                        EventBus.$emit("confirmation-modal:hide");
                        this.$router.back();
                    },
                });
            } else {
                this.$router.back();
            }
        },
    },
};
</script>

<style lang="scss">
.back-btn {
    display: flex;
    gap: 22px;
    align-items: center;

    cursor: pointer;
    p {
        font-family: "Nexa";
        font-style: normal;
        font-weight: 700;
        font-size: 18px;
        line-height: 27px;
        color: #2c5b7d;
        margin-bottom: -7px;
    }
}
</style>
