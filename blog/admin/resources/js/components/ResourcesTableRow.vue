<template>
    <tr class="border-top">
        <td>
            <input class="form-check-input"
                   type="checkbox"
                   :title="`Check ${resourceInfo.label}`"
                   :aria-label="`Check ${resourceInfo.label}`"/>
        </td>
        <td v-for="(field, index) in resource.fields"
            :key="index">
            <component :is="`index-${field.component}`"
                       :field="field"/>
        </td>
        <td class="text-end">
            <router-link class="mx-1"
                         :to="{
                             name: 'show',
                             params: {
                                 resourceId: resource.id.value,
                                 resourceName
                             }
                         }"
                         :title="`${resourceInfo.label} Details`">
                <i class="far fa-eye"></i>
            </router-link>
            <router-link class="mx-1"
                         :to="{
                             name: 'update',
                             params: {
                                 resourceId: resource.id.value,
                                 resourceName
                             }
                         }"
                         :title="`Edit ${resourceInfo.label}`">
                <i class="far fa-edit"></i>
            </router-link>
            <a class="mx-1"
               href=""
               :title="`Delete ${resourceInfo.label}`"
               @click.prevent="$emit('deleteResource', resource)">
                <i class="far fa-trash-alt"></i>
            </a>
        </td>
    </tr>
</template>

<script>
export default {
    props: {
        resource: {
            type: Object,
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
    }
};
</script>

<style scoped>
    td {
        white-space: nowrap !important;
    }
</style>
