export default {
    methods: {
        errorMessage(message) {
            this.$toasted.show(message, {
                icon: {
                    name: "error",
                },
                type: "error",
                position: "bottom-right",
                duration: 3000,
            });
        },

        loginErrorMessage(message) {
            this.$toasted.show(message, {
                icon: {
                    name: "error",
                },
                type: "error",
                position: "bottom-right",
                duration: 5000,
            });
        },

        successMessage(message) {
            const text = `
                <div class="success_notification">
                    <div class="notification-text">
                        Benachrichtigung
                    </div>
                    <div>
                         ${message}
                    </div>
                </div>
            `;
            this.$toasted.show(text, {
                icon: "null",
                type: "success",
                position: "top-center",
                duration: 3000,
                action: {
                    text: "X",
                    onClick: (e, toastObject) => {
                        toastObject.goAway(0);
                    },
                },
            });
        },
    },
};
