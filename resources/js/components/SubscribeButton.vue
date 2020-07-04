<template>
    <div class="form-group mt-3" v-if="isLogedIn">
        <button :class="classes" @click="threadSubscribed">Subscribe</button>
    </div>
</template>

<script>
    export default {
        props: ['active'],

        data() {
            return {
                subscribed: this.active
            }
        },

        methods: {
            threadSubscribed() {
                axios[this.subscribed ? 'delete' : 'post'](location.pathname + '/subscribes');
                this.subscribed = ! this.subscribed
            },
            
        },

        computed: {
            classes() { return ['btn btn-block', this.subscribed ? 'btn-primary' : 'btn-outline-primary']},
            isLogedIn() { return window.AwesomeForum.isSignedIn; }
        }
    }
</script>
