<template>
    <span class="d-inline-flex align-items-center"
          style="cursor: pointer;"
          @click="handleClick">
        <slot/>
        <span class="position-relative ms-1"
              style="width: 10px; height: 16px;">
            <i class="fas fa-sort-up position-absolute top-0"
               :style="{
                   color: isAscOrder ? 'inherit' : '#dee2e6'
               }"></i>
            <i class="fas fa-sort-down position-absolute top-0"
               :style="{
                   color: isDescOrder ? 'inherit' : '#dee2e6'
               }"></i>
        </span>
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
            validator: value => [null, 'asc', 'desc'].includes(value)
        }
    },

    computed: {
        isAscOrder () {
            return this.order === 'asc' && this.sort === this.attribute;
        },

        isDescOrder () {
            return this.order === 'desc' && this.sort === this.attribute;
        }
    },

    methods: {
        handleClick () {
            this.$emit('sort', {
                sort: this.attribute,
                order: this.isDescOrder ? 'asc' : 'desc'
            });
        }
    }
};
</script>
