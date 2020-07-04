<script>
    import Replies from '../components/Replies.vue';
    import SubscribeButton from '../components/SubscribeButton.vue';
    import LockButton from '../components/LockButton.vue';

    export default {
        props: ['thread'],

        components: { Replies, SubscribeButton, LockButton },

        data() {
            return {
                repliesCount: this.thread.replies_count || 0,
                locked: !! this.thread.locked,
                editing: false,
                title: this.thread.title,
                body: this.thread.body,
                form: {}
            }
        },

        created() {
            this.resetForm();
        },

        methods: {
            handleLock() {
                const method = this.locked ? 'delete' : 'post';
                axios[method]('/threads/'+this.thread.slug+'/lock');
                this.locked = !this.locked;
            },

            resetForm() {
                this.form = {
                    title: this.thread.title,
                    body: this.thread.body
                };

                this.editing = false;
            },

            update() {
                let uri = `/threads/${this.thread.channel.slug}/${this.thread.slug}`;

                axios.patch(uri, this.form).then(() => {
                    this.editing = false;
                    this.title = this.form.title;
                    this.body = this.form.body;

                    flash('Your Thread Updated Successfully!!');
                })
            }
        }
    }
</script>
