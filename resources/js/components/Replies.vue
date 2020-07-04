<template>
    <div>
        <div v-for="(item, index) in items" :key="item.id">
            <reply :data="item" @deleted="deleteItem(index)" class="mb-4"></reply>
        </div>

        <paginator :data="dataSet" @updatedPagination="fetch"></paginator>

        <div class="row" v-if="$parent.locked">
            <div class="col">this thread has been closed.</div>
        </div>
        <new-reply @addedReply="addItem" v-else></new-reply>
    </div>
</template>

<script>
    import Reply from './Reply.vue';
    import NewReply from './NewReply.vue';
    import collection from '../mixins/collection';
    import Paginator from './Paginator.vue';

    export default {
        components: { Reply, NewReply, Paginator },

        mixins: [collection],

        data() {
            return {
                dataSet: [],
                url: location.pathname + '/replies'
            }
        },

        created() {
            this.fetch();
        },

        methods: {
            fetch(page) {
                axios.get(this.endpoint(page))
                    .then(this.refresh);
            },

            endpoint(page) {
                if (! page) {
                    const query = location.search.match(/page=(\d)+/);

                    page = query ? query[1] : 1;
                }
                return `${location.pathname}/replies?page=${page}`;
            },

            refresh({data: dataSet}) {
                this.dataSet = dataSet;
                this.items = dataSet.data;

                window.scrollTo(0, 0);
            }
        }
    }
</script>
