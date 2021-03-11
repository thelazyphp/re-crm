<template>
    <table class="table table-borderless align-middle mb-0"
           :class="{ 'table-sm': resourceInfo.smallTable }">
        <thead class="table-light text-secondary text-uppercase">
            <tr>
                <th :class="{ 'border-end': resourceInfo.borderedTable }"
                    scope="col"></th>
                <th v-for="(field, index) in fields"
                    :key="index"
                    :class="{ 'border-end': resourceInfo.borderedTable }"
                    scope="col">
                    <template v-if="field.sortable">
                        <sort-icon :attribute="field.attribute"
                                   :sort="sort"
                                   :order="order"
                                   @sort="$emit('sort-resources', $event)">
                            {{ field.name }}
                        </sort-icon>
                    </template>
                    <template v-else>
                        <span>
                            {{ field.name }}
                        </span>
                    </template>
                </th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <resources-table-row v-for="resource in resources"
                                 :key="resource.id.value"
                                 :resource="resource"
                                 :checked="checkedResources.includes(resource)"
                                 :resource-key="resourceKey"
                                 :resource-info="resourceInfo"
                                 @check="$emit('check-resource', $event)"
                                 @delete-resource="$emit('delete-resource', $event)"/>
        </tbody>
    </table>
</template>

<script>
import SortIcon from './SortIcon.vue';
import ResourcesTableRow from './ResourcesTableRow.vue';

export default {
    components: {
        SortIcon,
        ResourcesTableRow,
    },

    props: {
        sort: String,

        order: {
            type: String,
            validator: value => [null, 'asc', 'desc'].includes(value)
        },

        resources: {
            type: Array,
            required: true
        },

        checkedResources: {
            type: Array,
            default: () => []
        },

        resourceKey: {
            type: String,
            required: true
        },

        resourceInfo: {
            type: Object,
            required: true
        }
    },

    computed: {
        fields () {
            return this.resources[0].fields;
        }
    }
};
</script>

<style scoped>
    th {
        font-weight: 300 !important;
        white-space: nowrap !important;
    }

    th:first-child {
        padding-left: 1rem !important;
    }

    th:last-child {
        padding-right: 1rem !important;
    }
</style>
