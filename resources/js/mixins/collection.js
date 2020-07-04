export default {
    data() {
        return {
            items: []
        }
    },

    methods: {
        deleteItem(index) {
            this.items.splice(index, 1);

            this.$emit('removed');
        },
        addItem(item) {
            this.items.push(item);

            this.$emit('addedItem');
        }
    }
}
