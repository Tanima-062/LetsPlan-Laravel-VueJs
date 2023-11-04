<template>
    <div>
        <a class="dropdown-item" href="#" @click.prevent="toggle">{{
                status == 1 ? "Deaktivieren" : "Aktivieren"
        }}</a>
    </div>
</template>

<script>
import axios from '../../../axios'
export default {
    props: {
        value: {
            type: Number,
            required: true,
        },
        description: {
            type: String,
            required: true,
        },
        title: {
            type: String,
            default: "Sind Sie sicher?"
        },
        status: {
            type: [ Number, String],
            default: "inactive",
        },
    },

    setup(props) {
        const title = props.title;
        const description = props.description;
        const toggle = () => {
            EventBus.$emit('confirmation-modal:show', {
                title,
                description,
                noClick: () => EventBus.$emit('confirmation-modal:hide'),
                yesClick: () => {
                    EventBus.$emit('confirmation-modal:hide')
                    axios.get('/api/spielgruppen-toggle-status/' + props.value).then(res => {
                        this.$emit
                        EventBus.$emit('notification-modal:show', res.data)
                    }).catch(err => {
                        console.log(err)
                    })
                },
            })
        };
        return {
            toggle,
        };
    },
};
</script>

<style lang="scss" scoped>
</style>
