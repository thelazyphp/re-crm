<template>
    <tr class="border-top">
        <td :class="{ 'border-end': resourceInfo.borderedTable }">
            <input class="form-check-input"
                   type="checkbox"
                   :title="`Check ${resourceInfo.name}`"
                   :aria-label="`Check ${resourceInfo.name}`"/>
        </td>
        <td v-for="(field, index) in resource.fields"
            :key="index"
            :class="{ 'border-end': resourceInfo.borderedTable }">
            <component :is="`index-${field.component}`"
                       :field="field"/>
        </td>
        <td class="text-end">
            <router-link :to="{
                             name: 'show',
                             params: {
                                 resourceKey,
                                 resourceId: resource.id.value
                             }
                         }"
                         role="button"
                         :title="`${resourceInfo.name} Details`">
                <i class="far fa-eye"></i>
            </router-link>
            <router-link class="ms-2"
                         :to="{
                             name: 'update',
                             params: {
                                 resourceKey,
                                 resourceId: resource.id.value
                             }
                         }"
                         role="button"
                         :title="`Edit ${resourceInfo.name}`">
                <i class="far fa-edit"></i>
            </router-link>
            <a class="ms-2"
               href=""
               role="button"
               :title="`Delete ${resourceInfo.name}`"
               @click.prevent="$emit('delete-resource', resource)">
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

        resourceKey: {
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

    td:first-child {
        padding-left: 1rem !important;
    }

    td:last-child {
        padding-right: 1rem !important;
    }
</style>
