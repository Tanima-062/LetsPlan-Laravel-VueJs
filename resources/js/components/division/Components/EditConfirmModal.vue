<template>
    <div class="status__modal" v-if="showModal">
        <div class="status__modal--header">
            <h4>{{ title }}</h4>
        </div>
        <div class="status__modal--body">
            {{ body }}
        </div>
        <div class="status__modal--footer">
            <button class="button confirm" @click.prevent="goToEditPage">
                Ja
            </button>
            <button class="button cancel" @click.prevent="showModal = false">
                Nein
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        id: {
            type: String,
            default: "",
        },
    },
    data() {
        return {
            showModal: false,
            division: null,
        };
    },
    computed: {
        title() {
            return `Einteilung bearbeiten`;
        },
        body() {
            return `Diese Einteilung ist oder war bereits aktiv. Sind Sie sicher, dass Sie diese Einteilung trotzdem bearbeiten möchten?`;
        },
    },
    mounted() {
        EventBus.$on("show-edit-modal", (e) => {
            this.division = e.division;
            this.showModal = true;
        });
    },
    methods: {
        goToEditPage() {
            // this.$router.push(`/division/edit/${this.division.id}`);
            this.$router.push({
                name: "division_edit",
                params: { id: this.division.id },
            });
        },
    },
};
</script>

<style lang="scss" scoped>
.status__modal {
    width: 600px;
    background: #ffffff;
    box-shadow: 8px 8px 0px #d3e3ec;
    padding: 24px 32px;
    position: absolute;
    z-index: 9;
    top: 400px;
    left: 50%;
    transform: translateX(-50%);

    &--header {
        text-align: center;
        margin-bottom: 32px;

        h4 {
            margin: 0;
            padding: 0;

            font-family: "Nexa";
            font-style: normal;
            font-weight: 700;
            font-size: 18px;
            line-height: 27px;

            color: #16a9b7;
        }
    }

    &--body {
        font-family: "Nexa";
        font-style: normal;
        font-weight: 400;
        font-size: 16px;
        line-height: 24px;
        text-align: center;
        color: #2c5b7d;
    }

    &--footer {
        margin-top: 32px;
        display: flex;
        justify-content: space-between;

        gap: 32px;
        .button {
            width: 50%;
            padding: 8px;

            /* Nexa Bold / 18 */

            font-family: "Nexa";
            font-style: normal;
            font-weight: 700;
            font-size: 18px;
            line-height: 27px;
            text-align: center;
        }

        .confirm {
            color: #ffffff;
            background: #16a9b7;
        }

        .cancel {
            background: #c6e9f2;
            color: #19849e;
        }
    }
}
</style>
