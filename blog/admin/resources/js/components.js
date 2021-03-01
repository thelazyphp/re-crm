import Vue from 'vue';

import FormTextField from './components/form/TextField.vue';
import FormSelectField from './components/form/SelectField.vue';
import FormTextareaField from './components/form/TextareaField.vue';
import FormCheckboxField from './components/form/CheckboxField.vue';

import IndexTextField from './components/index/TextField.vue';
import IndexSelectField from './components/index/SelectField.vue';
import IndexTextareaField from './components/index/TextareaField.vue';
import IndexCheckboxField from './components/index/CheckboxField.vue';

import DetailTextField from './components/detail/TextField.vue';
import DetailSelectField from './components/detail/SelectField.vue';
import DetailTextareaField from './components/detail/TextareaField.vue';
import DetailCheckboxField from './components/detail/CheckboxField.vue';

Vue.component('form-text-field', FormTextField);
Vue.component('form-select-field', FormSelectField);
Vue.component('form-textarea-field', FormTextareaField);
Vue.component('form-checkbox-field', FormCheckboxField);

Vue.component('index-text-field', IndexTextField);
Vue.component('index-select-field', IndexSelectField);
Vue.component('index-textarea-field', IndexTextareaField);
Vue.component('index-checkbox-field', IndexCheckboxField);

Vue.component('detail-text-field', DetailTextField);
Vue.component('detail-select-field', DetailSelectField);
Vue.component('detail-textarea-field', DetailTextareaField);
Vue.component('detail-checkbox-field', DetailCheckboxField);
