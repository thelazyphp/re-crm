import Vue from 'vue';
import BaseField from './components/form/BaseField.vue';
import TextField from './components/form/TextField.vue';
import SelectField from './components/form/SelectField.vue';
import TextareaField from './components/form/TextareaField.vue';
import CheckboxField from './components/form/CheckboxField.vue';

Vue.component('base-field', BaseField);
Vue.component('text-field', TextField);
Vue.component('select-field', SelectField);
Vue.component('textarea-field', TextareaField);
Vue.component('checkbox-field', CheckboxField);
