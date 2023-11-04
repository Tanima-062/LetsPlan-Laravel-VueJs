<template>
    <div class="active__provissional">
        <button @click="showStatusModal">{{ statusText }}</button>
        <span @click="showStatusModal"><svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M23.3307 2L24.2146 1.11612L23.3307 0.232233L22.4468 1.11612L23.3307 2ZM29.9974 8.66667L30.8813 9.55055L31.7652 8.66667L30.8813 7.78278L29.9974 8.66667ZM14.9974 23.6667V24.9167H15.5152L15.8813 24.5506L14.9974 23.6667ZM8.33073 23.6667H7.08073V24.9167H8.33073V23.6667ZM8.33073 17L7.44685 16.1161L7.08073 16.4822V17H8.33073ZM28.3307 30.3333V31.5833H29.5807V30.3333H28.3307ZM1.66406 30.3333H0.414062L0.414062 31.5833H1.66406L1.66406 30.3333ZM1.66406 3.66666L1.66406 2.41666L0.414065 2.41666V3.66666L1.66406 3.66666ZM11.6641 4.91667H12.9141V2.41667L11.6641 2.41667V4.91667ZM29.5807 20.3333V19.0833H27.0807V20.3333H29.5807ZM22.4468 2.88388L29.1135 9.55055L30.8813 7.78278L24.2146 1.11612L22.4468 2.88388ZM14.9974 22.4167H8.33073V24.9167H14.9974V22.4167ZM9.58073 23.6667V17H7.08073V23.6667H9.58073ZM9.21461 17.8839L19.2146 7.88388L17.4468 6.11612L7.44685 16.1161L9.21461 17.8839ZM19.2146 7.88388L24.2146 2.88388L22.4468 1.11612L17.4468 6.11612L19.2146 7.88388ZM29.1135 7.78278L24.1135 12.7828L25.8813 14.5506L30.8813 9.55055L29.1135 7.78278ZM24.1135 12.7828L14.1135 22.7828L15.8813 24.5506L25.8813 14.5506L24.1135 12.7828ZM25.8813 12.7828L19.2146 6.11612L17.4468 7.88388L24.1135 14.5506L25.8813 12.7828ZM28.3307 29.0833L1.66406 29.0833L1.66406 31.5833L28.3307 31.5833V29.0833ZM2.91406 30.3333L2.91406 3.66666L0.414065 3.66666L0.414062 30.3333H2.91406ZM1.66406 4.91666L11.6641 4.91667V2.41667L1.66406 2.41666L1.66406 4.91666ZM27.0807 20.3333V30.3333H29.5807V20.3333H27.0807Z" fill="#063F5F"/>
            </svg>
        </span>
    </div>
</template>

<script>
    export default {
        props: {
            statuses: {
                type: Array,
                required: true
            },
            status_id: {
                type: Number,
                required: true
            },
            disabled: {
                type: Boolean,
                required: true
            }
        },
        computed: {
            statusText(){
                const status=  this.statuses.find(item => item.status_id == this.status_id)
                if(status) return status.status_name;
                return ''
            }
        },
        methods:{
            showStatusModal(){
                if(this.disabled) return

                EventBus.$emit('show-status-modal')
            }
        }
    }
</script>

<style lang="scss" scoped>
       .active__provissional {
            margin-left: auto;
            display: flex;
            align-items: center;
            // width: 25%;

            button {
                font-family: 'Nexa';
                font-style: normal;
                font-weight: 400;
                font-size: 18px;
                line-height: 27px;
                color: #063F5F;
                margin-right: 10px;
            }
            span {
                cursor: pointer;
            }
        }
</style>
