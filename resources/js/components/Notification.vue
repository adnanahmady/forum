<template>
    <div class="dropdown-item">
        <a :href="link" @click="readNotification">
            <span v-text="message"></span>
        </a>
    </div>
</template>

<script>
    export default {
        props: ['data'],

        data() {
            return {
                message: this.data.data.message,
                link: this.data.data.link
            }
        },

        methods: {
            readNotification() {
                this.$emit('readNotification', this.data.id);
            }
        }
    }
</script>
