<template>
    <button
        class="btn btn-outline-danger btn-block"
        :class="locked === true ? 'active' : ''"
        v-if="authorize('isAdmin')"
        @click="handleLock">
        {{ this.locked ? 'unlock' : 'lock'}}
    </button>
</template>

<script>
    export default {
        props: {
            thread: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                locked: !! this.thread.locked
            }
        },

        methods: {
            handleLock() {
                this.locked = !this.locked;
                this.$emit('on-lock');
            }
        }
    }
</script>
