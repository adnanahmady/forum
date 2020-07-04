<template>
    <div>
        <input id="trix" type="hidden" :value="value" :name="name">
        <trix-editor input="trix" ref="trix" :placeholder="placeholder"></trix-editor>
    </div>
</template>
<script>
    import 'trix';
    import 'trix/dist/trix.css';

    export default {
        props: {
            name: {
                required: false,
                default: '',
                type: String
            },
            value: {
                required: false,
                default: 'foobar',
                type: String
            },
            placeholder: {
                required: false,
                default: '',
                type: String
            },
            clear: {
                required: false,
                default: false,
                type: Boolean
            }
        },
        data() {
            return {
            }
        },
        watch: {
            clear: function () { this.$refs.trix.value = ''; },
        },
        mounted() {
            this.$refs.trix.addEventListener('trix-change', ({target: {innerHTML}}) => {
                this.$emit('input', innerHTML);
            });
        }
    };
</script>
