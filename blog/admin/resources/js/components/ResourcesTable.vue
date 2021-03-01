<template>
    <div class="table-responsive">
        <table class="table table-borderless">
            <thead>
                <tr>
                    <th scope="col">
                        <input class="form-check-input"
                               type="checkbox"
                               :title="`Check All ${resourceInfo.pluralLabel}`"
                               :aria-label="`Check All ${resourceInfo.pluralLabel}`"/>
                    </th>
                    <th v-for="(field, index) in fields"
                        :key="index"
                        scope="col">
                        {{ field.name }}
                    </th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <resources-table-row v-for="resource in resources"
                                     :key="resource.id.value"
                                     :resource="resource"
                                     :resource-name="resourceName"
                                     :resource-info="resourceInfo"
                                     @delete-resource="$emit('deleteResource', $event)"/>
            </tbody>
        </table>
    </div>
</template>

<script>
import ResourcesTableRow from './ResourcesTableRow.vue';

export default {
    props: {
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
        white-space: nowrap !important;
    }
</style>
