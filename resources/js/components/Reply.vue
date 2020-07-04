<template>
    <div :id="replyAnchor" class="card" :class="isBest ? 'bg-success' : ''">
        <div class="card-header d-flex">
            <span class="mr-auto">
                <a :href="'/profile/'+ownerName" v-text="ownerName"></a>
                <strong>answered</strong>
                <span v-text="fromNow"></span>
            </span>
            <span class="ml-auto" v-if="isSignedIn">
                <favorite :reply="data"></favorite>
            </span>
        </div>
        <div class="card-body">
            <div v-if="edit">
                <form @submit.prevent="updateReply()">
                    <wysiwyg name="body" class="mb-2" v-model="body" required></wysiwyg>
                    <button class="btn btn-primary btn-xm mr-2">update</button>
                    <button class="btn btn-link btn-xm" @click="setEdit()" type="button">cancel</button>
                </form>
            </div>

            <span v-else style="white-space: pre-line;" v-html="body"></span>
        </div>
        <div class="card-footer d-flex" v-if="authorize('owns', data) ||
         (authorize('owns', data.thread) && ! isBest)">
            <div v-if="authorize('owns', data)">
                <button class="btn btn-success btn-sm mr-2" @click="setEdit()">edit</button>
                <button class="btn btn-danger btn-sm" @click="deleteReply()">delete</button>
            </div>
            <button
                class="btn btn-outline-success ml-auto btn-sm"
                @click="bestReply"
                v-if="authorize('owns', data.thread)"
                v-show="! isBest">
                Best Reply?
                <i class="fa fa-thumbs-up" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</template>

<script>
    import Favorite from './Favorite.vue'
    import moment from 'moment';

    export default {
        props: ['data'],

        components: {Favorite},

        computed: {
            fromNow() {
                return moment(this.data.created_at).fromNow();
            },
            replyAnchor() {
                return this.data.replyAnchor;
            },
            ownerName() {
                return this.data.owner.name;
            },
        },

        data() {
            return {
                edit: false,
                isBest: this.data.isBest,
                body: this.data.body
            }
        },

        created() {
            window.events.$on('reply-selected-as-best', id => {
                this.isBest = (id === this.data.id);
            });
        },

        methods: {
            setEdit() {
                this.edit = !this.edit;
            },

            async updateReply() {
                try {
                    await axios.patch(`/replies/${this.data.id}`, {
                        body: this.body
                    });

                    this.setEdit();

                    flash('Reply Updated');
                } catch ({response}) {
                    flash(response.data.message, 'danger');
                }
            },
            deleteReply() {
                let _parent = [...$(this.$el).parents('.row')].shift();
                axios.delete(`/replies/${this.data.id}`);

                this.$emit('deleted', this.data.id);
            },
            bestReply() {
                axios.post('/replies/'+this.data.id+'/best');

                window.events.$emit('reply-selected-as-best', this.data.id)
            }
        }
    }
</script>
