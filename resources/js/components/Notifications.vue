<template>
    <li class="nav-item dropdown" v-show="hasNotification">
        <a id="NavbarNotificationsDropdown" class="nav-link dropdown-toggle" href="#" role="button"
           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            <i class="fa fa-bell" aria-hidden="true"></i>
        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="NavbarNotificationsDropdown">
            <notification
                :data="notification"
                v-for="notification in notifications"
                :key="notification.id"
                @readNotification="markAsRead"
            ></notification>
        </div>
    </li>
</template>

<script>
    import Notification from './Notification.vue';

    export default {
        components: { Notification },

        data() {
            return {
                notifications: false
            }
        },

        created() {
            axios.get('/profiles/notifications')
                .then(({data}) => this.notifications = data);
        },

        computed: {
            hasNotification() { return !! this.notifications.length; }
        },

        methods: {
            markAsRead(notificationId) {
                axios.delete('/profiles/' + window.AwesomeForum.user.name + '/notifications/' + notificationId)
                    .then(() =>
                        {

                            this.notifications = this.notifications
                                .filter(notification => notification.id != notificationId);
                        }
                    );
            }
        }
    }
</script>
