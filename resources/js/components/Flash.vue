<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-flash" :class="'alert-'+level" role="alert" v-show="show">
                    {{ body }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['message', 'flashLevel'],

        data() {
            return {
                body: this.message,
                level: this.flashLevel || 'success',
                show: false
            }
        },

        created() {
            this.flash(this.message, this.level);

            window.events.$on('flash', ({ message, level }) => this.flash(message, level));
        },

        methods: {
            flash(message, level) {
                if (message) {
                    this.body = message;
                    this.level = level;
                    this.show = true;
                }

                this.hide();
            },

            hide() {
                setTimeout(() => { this.show = false }, 3000);
            }
        }
    }
</script>

<style>
    .alert-flash {
        position: fixed;
        z-index: 1;
        bottom: 25px;
        right: 25px;
    }
</style>
