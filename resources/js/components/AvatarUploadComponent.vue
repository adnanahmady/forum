<template>
    <input type="file"
           @change="onChanged"
           accept="image/*"
           id="avatar"
           class="btn btn-outline-info">
</template>

<script>
    export default {
        name: "AvatarUploadComponent",

        methods: {
            onChanged(e) {
                if (!e.target.files.length) return;
                const _reader = new FileReader();
                const avatar = [...e.target.files].shift();
                _reader.onload = (e) => {
                    const src = e.currentTarget.result;

                    this.$emit('sended', {src, avatar});
                };
                _reader.readAsDataURL(avatar);
            }
        }
    }
</script>

<style scoped>

</style>
