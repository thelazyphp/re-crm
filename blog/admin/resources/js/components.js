import Vue from 'vue';

import FormTextField from './components/form/TextField.vue';
import FormSelectField from './components/form/SelectField.vue';
import FormTextareaField from './components/form/TextareaField.vue';
import FormCheckboxField from './components/form/CheckboxField.vue';

import IndexTextField from './components/index/TextField.vue';
import IndexSelectField from './components/index/SelectField.vue';
import IndexTextareaField from './components/index/TextareaField.vue';
import IndexCheckboxField from './components/index/CheckboxField.vue';

import ShowTextField from './components/show/TextField.vue';
import ShowSelectField from './components/show/SelectField.vue';
import ShowTextareaField from './components/show/TextareaField.vue';
import ShowCheckboxField from './components/show/CheckboxField.vue';

Vue.component('form-text-field', FormTextField);
Vue.component('form-select-field', FormSelectField);
Vue.component('form-textarea-field', FormTextareaField);
Vue.component('form-checkbox-field', FormCheckboxField);

Vue.component('index-text-field', IndexTextField);
Vue.component('index-select-field', IndexSelectField);
Vue.component('index-textarea-field', IndexTextareaField);
Vue.component('index-checkbox-field', IndexCheckboxField);

Vue.component('show-text-field', ShowTextField);
Vue.component('show-select-field', ShowSelectField);
Vue.component('show-textarea-field', ShowTextareaField);
Vue.component('show-checkbox-field', ShowCheckboxField);
