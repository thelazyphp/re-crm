<template>
    <v-select v-model="selectedAction"
              :options="options"
              small
              :placeholder="placeholder"/>
</template>

<script>
import VSelect from './VSelect.vue';

export default {
    components: {
        VSelect,
    },

    props: {
        actions: {
            type: Array,
            default: () => []
        }
    },

    data () {
        return {
            selectedAction: ''
        };
    },

    computed: {
        options () {
            return this.actions.map(action => {
                return {
                    value: action.name,
                    label: action.name
                };
            });
        },

        placeholder () {
            return 'Choose an action...';
        }
    }
};
</script>
