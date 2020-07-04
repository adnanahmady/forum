<template>
    <div>
        <div class="row">
            <div class="col-3 col-lg-2 m-2 mx-auto">
                <img :src="avatar"
                     :alt="user.name"
                     width="100"
                     height="100"
                     class="img-thumbnail">
            </div>
            <div class="col" v-if="canUpload">
                <div class="d-flex">
                    <span class="display-3 text-secondary">
                        {{ user.name }}
                    </span>
                    <span class="display-6 text-secondary" v-text="registeredAt"></span>
                </div>
                <p>
                    <avatar-upload-component @sended="onChange"></avatar-upload-component>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    import AvatarUploadComponent from './AvatarUploadComponent';

    export default {
        props: {
            user: {
                type: Object,
                required: true
            },
            avatarRoute: {
                type: String,
                required: true
            }
        },

        name: "AvatarComponent",

        components: { AvatarUploadComponent },

        data() {
            return {
                avatar: this.user.avatar_path
            };
        },

        computed: {
            canUpload() {
                return this.authorize(user => user.id == this.user.id);
            },

            hasErrors() {
                return !!this.errors.length;
            },

            registeredAt() {
                return moment(this.user.created_at).fromNow();
            }
        },

        methods: {
            onChange({src, avatar}) {
                this.avatar = src;
                this.persist(avatar);
            },

            persist(_avatar) {
                const data = new FormData();
                data.append('avatar', _avatar);

                axios.post(this.avatarRoute, data)
                    .then((response) => flash('avatar Uploaded'))
                    .catch(({response}) => flash(response.data.errors.avatar.shift(), 'danger'));
            }
        }
    }
</script>
