<template>
    <ul class="pagination" v-if="shouldPaginate">
        <li v-show="prevUrl" class="page-item">
            <a href="#" @click.prevent="page--" aria-label="Previous" rel="prev" class="page-link">
                <span aria-hidden="true">&laquo; Previous</span>
            </a>
        </li>
        <li class="page-item active"><a href="#" class="page-link" v-text="page"></a></li>
        <li v-show="nextUrl" class="page-item">
            <a href="#" @click.prevent="page++" aria-label="Next" rel="next" class="page-link">
                <span aria-hidden="true">Next &raquo;</span>
            </a>
        </li>
    </ul>
</template>

<script>
    export default {
        props: ['data'],

        data() {
            return {
                page: 1,
                prevUrl: false,
                nextUrl: false
            }
        },

        watch: {
            data() {
                this.page = this.data.current_page;
                this.prevUrl = this.data.prev_page_url;
                this.nextUrl = this.data.next_page_url;
            },

            page() {
                this.brodcast().updateEndpoint();
            }
        },

        computed: {
            shouldPaginate() { return !! this.prevUrl || this.nextUrl; }
        },

        methods: {
            brodcast() {
                return this.$emit('updatedPagination', this.page);
            },

            updateEndpoint() {
                history.pushState(null, null, `?page=${this.page}`);
            }
        }
    }
</script>
