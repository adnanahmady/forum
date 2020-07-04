<script>
    import algoliasearch from 'algoliasearch/lite';
    import 'instantsearch.css/themes/algolia-min.css';

    export default {
        data() {
            return {
                searchClient: algoliasearch(
                    AwesomeForum.algolia.id,
                    AwesomeForum.algolia.secret
                ),
            };
        },
    };
</script>

<style scope>
    .ais-RefinementList-showMore {
        display: block !important;
        width: 100%;
        padding: .6rem;
    }
</style>
