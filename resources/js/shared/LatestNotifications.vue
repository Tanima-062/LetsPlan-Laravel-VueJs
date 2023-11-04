<template>
    <div class="card-panel-home bg-white">
        <div>
            <div class="blue-header">
                <p style="color: white">Benachrichtigungen</p>
                <div>
                    <img src="/images/bell-not.png"/>
                </div>
            </div>
            <div class="notification-wrapper">
                <div class="flex justify-between items-baseline notification-message">
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                        industry.</p>
                    <div class="new-notification"></div>
                </div>
                <p class="notification-time">5 minutes ago</p>
                <hr class="notification-divider m-0"/>
            </div>
            <div class="notification-wrapper">
                <div class="flex justify-between items-center">
                    <p class="old-notification">Lorem Ipsum is simply dummy text of the printing and
                        typesetting industry.</p>
                </div>
                <p class="notification-time old-notification">6 hours ago</p>
                <hr class="notification-divider m-0"/>
            </div>
            <div class="notification-wrapper">
                <div class="flex justify-between items-center notification-message">
                    <p class="old-notification">Lorem Ipsum is simply dummy text of the printing and
                        typesetting industry.</p>
                </div>
                <p class="notification-time old-notification">7 months ago</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "LatestNotifications"
}
</script>

<style scoped lang="scss">


.blue-header {
    background: #0063A7;
    display: flex;
    justify-content: space-between;
    padding: 8px 13px 10px 25px;
    border-top-left-radius: 15px;
    border-top-right-radius:15px;
    p{
        font-weight: 600;
        font-size: 18px;
        line-height: 22px;
    }
    img{
        vertical-align: middle;
    }
}
.notification-wrapper{
    padding: 10px 20px 0px;
    p{
        font-weight: 500;
        font-size: 14px;
        line-height: 17px;
        color: #42474A;
        &.old-notification {
            color: #A8ACAD;
        }
    }
}
.new-notification {
    border-radius: 50%;
    width: 5px;
    height: 5px;
    background: #E62D4F;
    margin-right: 5px;
}

.notification-divider {
    border-top: 1px solid #C2C5C6;
}

.notification-time {
    font-size: 12px;
    color: #727574;
    margin-top: 6px;
    margin-bottom: 11px;
}

.notification-message {
    font-size: 14px;
    font-weight: 500;
}</style>
