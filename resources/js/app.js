require('./bootstrap');

import Vue from 'vue';
import VueJsModal from 'vue-js-modal';

import Flash from './components/Flash.vue';
import FeatherIcon from './components/FeatherIcon.vue';
import VueTypeahead from './components/VueTypeahead.vue';
import VueMultiTypeahead from './components/VueMultiTypeahead.vue';
import TabbedPane from './components/TabbedPane.vue';
import FileInput from './components/FileInput.vue';

import LettersSearchFilters from './components/LettersSearchFilters.vue';
import ProgrammeUpdateModal from "./components/ProgrammeUpdateModal.vue";
import CourseUpdateModal from './components/CourseUpdateModal.vue';
import CollegeUpdateModal from "./components/CollegeUpdateModal.vue";
import RemarkUpdateModal from './components/RemarkUpdateModal.vue';
import ReminderUpdateModal from './components/ReminderUpdateModal.vue';
import UserUpdateModal from "./components/UserUpdateModal.vue";
import RoleUpdateModal from './components/RoleUpdateModal.vue';

Vue.use(VueJsModal);

window.Events = new Vue();

Vue.component('v-flash', Flash);
Vue.component('feather-icon', FeatherIcon);
Vue.component('vue-typeahead', VueTypeahead);
Vue.component('v-multi-typeahead', VueMultiTypeahead);
Vue.component("v-tabbed-pane", TabbedPane);
Vue.component("v-file-input", FileInput);

Vue.component("programme-update-modal", ProgrammeUpdateModal);
Vue.component("course-update-modal", CourseUpdateModal);
Vue.component('letter-search-filters', LettersSearchFilters);
Vue.component("college-update-modal", CollegeUpdateModal);
Vue.component("remark-update-modal", RemarkUpdateModal);
Vue.component("reminder-update-modal", ReminderUpdateModal);
Vue.component("user-update-modal", UserUpdateModal);
Vue.component("role-update-modal", RoleUpdateModal);

Vue.mixin({
    methods: {
        route: route
    }
});

const app = new Vue({
    el: '#app'
});
