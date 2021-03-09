<template>
    <div class="dropdown">
        <button class="form-select text-start"
                :class="errorClass"
                type="button"
                style="cursor: default;"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                :disabled="readonly">
            {{ selected ? selected.label : placeholder }}
        </button>
        <ul class="dropdown-menu w-100">
            <li>
                <input v-model="search"
                       class="form-control"
                       type="search"
                       placeholder="Search"/>
            </li>
            <li>
                <a class="dropdown-item"
                   :class="{ disabled: !nullable }"
                   href=""
                   :tabindex="!nullable ? -1 : null"
                   :aria-disabled="!nullable ? true : null"
                   @click.prevent="$emit('input', '')">
                    {{ placeholder }}
                </a>
            </li>
            <li v-for="(option, index) in filteredOptions"
                :key="index">
                <a class="dropdown-item"
                   :class="{ active: value === option.value }"
                   href=""
                   :aria-current="value === option.value ? true : null"
                   @click.prevent="$emit('input', option.value)">
                    {{ option.label }}
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: {
        options: {
            type: Array,
            default: () => []
        },

        value: {
            default: ''
        },

        errorClass: {
            type: Object
        },

        readonly: {
            type: Boolean,
            default: false
        },

        nullable: {
            type: Boolean,
            default: false
        },

        placeholder: {
            type: String,
            default: 'Choose an option...'
        }
    },

    data () {
        return {
            search: ''
        };
    },

    computed: {
        selected () {
            return this.options.find(option => option.value === this.value);
        },

        filteredOptions () {
            return this.options.filter(option => option.label.toLowerCase().indexOf(this.search.toLowerCase()) !== -1);
        }
    }
};
</script>

<style scoped>
    [type="search"] {
        border: none !important;
    }

    [type="search"]:focus {
        box-shadow: none !important;
    }
</style>
