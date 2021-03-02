<template>
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex align-items-center justify-content-between">
            <input class="form-check-input"
                   type="checkbox"
                   :title="`Check All ${resourceInfo.pluralLabel}`"
                   :aria-label="`Check All ${resourceInfo.pluralLabel}`"/>
            <div class="dropdown">
                <a class="dropdown-toggle"
                   href=""
                   role="button"
                   data-bs-toggle="dropdown"
                   aria-expanded="false"
                   :title="`${resourceInfo.label} Filters`">
                    <i class="fas fa-filter"></i>
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-borderless mb-0"
                   :class="{ 'table-sm': resourceInfo.smallTable }">
                <thead class="table-light text-secondary text-uppercase">
                    <tr>
                        <th scope="col"></th>
                        <th v-for="(field, index) in fields"
                            :key="index"
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
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <resources-table-row v-for="resource in resources"
                                         :key="resource.id.value"
                                         :resource="resource"
                                         :resource-name="resourceName"
                                         :resource-info="resourceInfo"
                                         @delete-resource="$emit('delete-resource', $event)"/>
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-light d-flex align-items-center justify-content-between">
            <a class="btn btn-link text-decoration-none"
               href="">
                Previous
            </a>
            <span class="text-secondary mx-auto">
                1-{{ resources.length }} of {{ resources.length }}
            </span>
            <a class="btn btn-link text-decoration-none"
               href="">
                Next
            </a>
        </div>
    </div>
</template>

<script>
import SortIcon from './SortIcon.vue';
import ResourcesTableRow from './ResourcesTableRow.vue';

export default {
    props: {
        sort: String,

        order: {
            type: String,
            validator: (value) => [null, 'asc', 'desc'].includes(value)
        },

        resources: {
            type: Array,
            required: true
        },

        resourceName: {
            type: String,
            required: true
        },

        resourceInfo: {
            type: Object,
            required: true
        }
    },

    components: {
        SortIcon,
        ResourcesTableRow,
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
