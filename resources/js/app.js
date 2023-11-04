import Vue from 'vue'
import VueRouter from "vue-router";
import StatisticCard from "./components/home/StatisticCard";
import RegistrationDateForm from "./components/home/registrationDateForm"
import SupervisorRegistration from "./components/supervisor/SupervisorRegistration";
import Supervisor from "./components/supervisor/Supervisor";
import Sidebar from "./components/partials/Sidebar";
import Toasted from 'vue-toasted';
import GlobalSearch from "./components/partials/GlobalSearch";
import LetplayNavbar from "./components/partials/LetplayNavbar";
import SpielgruppenItem from "./components/spielgruppen/SpielgruppenItem";
import { VuejsDatatableFactory } from 'vuejs-datatable';
import CustomGoogleAutocomplete from 'vue-custom-google-autocomplete'
import ClickOutside from 'vue-click-outside'
import VCalander from "v-calendar"
import DatePicker from 'v-calendar/lib/components/date-picker.umd'

import 'material-icons/iconfont/material-icons.css'
import 'vue2-datepicker/index.css'
import 'vue2-datepicker/locale/de';

import App from './views/app.vue'
import Vuex from 'vuex'
import store from "./store";
import router from './router/routes'


//roles
window.PLAYGROUP_LEADER = 2;
window.PLAYGROUP_ASSISTANT = 3;
window.PLAYGROUP_ADMIN = 1;
window.SUPER_ADMIN = 4;

Vue.use(Vuex)
Vue.use(VueRouter)
Vue.use(VuejsDatatableFactory)
Vue.use(CustomGoogleAutocomplete)
Vue.component('statistic-card', StatisticCard)
Vue.component('registration-date-form', RegistrationDateForm)
Vue.component('supervisor-registration', SupervisorRegistration)
Vue.component('sidebar', Sidebar)
Vue.component('supervisor', Supervisor)
Vue.component('spielgruppen-item', SpielgruppenItem)
Vue.component('global-search', GlobalSearch)
Vue.component('letsplay-navbar', LetplayNavbar)
Vue.component('date-picker', DatePicker)
/*Vue.component('company-datatable-component', require('./components/company/CompanyDatatable.vue').default);*/

Vue.directive('click-outside', ClickOutside)
Vue.component('app', App)
Vue.filter('splitNumber', function (value) {
    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
})
Vue.use(Toasted, {
    iconPack: 'material'
})

window.EventBus = new Vue();

Vue.use(VCalander, {
    componentPrefix: 'v'
})
//Main pages

const app = new Vue({
    el: '#app',
    router,
    store,
    components: { App }
});

