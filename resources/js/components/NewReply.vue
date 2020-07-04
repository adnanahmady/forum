<template>
    <div v-if="isSignedIn">
        <div class="row">
        </div>
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <wysiwyg v-model="body" id="reply-body"
                             placeholder="Has something to say?" :clear="clear"></wysiwyg>
                </div>
                <div class="form-group">
                    <button @click="addReply" class="btn btn-primary">Answer</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center" v-else>
        <div class="col text-center">
            Please <a href='/login'>login</a> for answering questions.
        </div>
    </div>
</template>

<script>
    import 'jquery.caret';
    import 'at.js';
    import Wysiwyg from './Wysiwyg';

    export default {
        components: {Wysiwyg},

        data() {
            return {
                body: '',
                clear: false
            }
        },

        computed: {
            isSignedIn() {
                return window.AwesomeForum.isSignedIn;
            },
            action() {
                return location.pathname + '/replies';
            }
        },

        mounted() {
            $('#reply-body').atwho({
                at: '@',
                delay: 750,
                callbacks: {
                    remoteFilter: function (query, callback) {
                        $.getJSON('/api/users', {search: query}, function(data) {
                            callback(data);
                        });
                    }
                }
            });
        },

        methods: {
            addReply() {
                axios.post(this.action, {body: this.body})
                    .then(({data}) => {
                        this.body = '';
                        this.clear = true;

                        this.$emit('addedReply', data);

                        flash('Your have left a reply');
                    })
                    .catch(({response}) => flash(response.data.message, 'danger'));
            }
        }
    }
</script>
