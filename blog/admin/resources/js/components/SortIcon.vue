<template>
    <span style="cursor: pointer;"
          @click="handleSort">
        <slot/>
        <i v-if="isAscOrder"
           class="fas fa-sort-up"></i>
        <i v-if="isDescOrder"
           class="fas fa-sort-down"></i>
    </span>
</template>

<script>
export default {
    props: {
        attribute: {
            type: String,
            required: true
        },

        sort: String,

        order: {
            type: String,
            validator: (value) => [null, 'asc', 'desc'].includes(value)
        }
    },

    computed: {
        isAscOrder () {
            return this.sort === this.attribute && this.order === 'asc';
        },

        isDescOrder () {
            return this.sort === this.attribute && this.order === 'desc';
        }
    },

    methods: {
        handleSort () {
            this.$emit('sort', {
                sort: this.attribute,
                order: this.isDescOrder ? 'asc' : 'desc'
            });
        }
    }
};
</script>
