<template>
    <button type="submit" @click="toggleFavorite" :class="classes">
        <i aria-hidden="true" class="fa fa-heart"></i>
        <span v-text="count"></span>
    </button>
</template>
<script>
    export default {
        props: ['reply'],

        data() {
            return {
                count: this.reply.favorites_count || 0,
                active: this.reply.isFavorited
            }
        },

        computed: {
            classes() {
                return ['btn', this.active ? 'btn-success' : 'btn-light'];
            },

            endpoint() {
                return `/replies/${this.reply.id}/favorites`;
            }
        },

        methods: {
            toggleFavorite() {
                if (this.active) {
                    this.unFavorite();
                } else {
                    this.favorite();
                }
            },

            unFavorite() {
                axios.delete(this.endpoint);

                this.active = false;
                this.count--;
            },

            favorite() {
                axios.post(this.endpoint);

                this.active = true;
                this.count++;
            }
        }
    }
</script>
