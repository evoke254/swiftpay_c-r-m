window.$ = window.jQuery = require('jquery');
window.Cookies = require('js-cookie');

require('./bootstrap');
require('bootstrap-select');
require('datatables');
require('ionicons');
var moment = require('moment');
require('bootstrap-datepicker');
require('./sidebar');
window.moment = require('moment');

window.Vue = require('vue');

Vue.prototype.$userId = document.querySelector("meta[name='user-id']").getAttribute('content');
Vue.prototype.$moduleName = document.querySelector("meta[name='moduleName']").getAttribute('content');
Vue.prototype.$ObjectId = document.querySelector("meta[name='objectId']").getAttribute('content');
Vue.prototype.$moment = moment;



Vue.component('editinvoice', require('./components/EditInvoice.vue').default);
Vue.component('invoice', require('./components/Invoice.vue').default);

Vue.component('pos', require('./components/Pos.vue').default);
Vue.component('editquote', require('./components/EditQuote.vue').default);
Vue.component('quote', require('./components/Quote.vue').default);

Vue.component('createmodule', require('./components/CreateModule.vue').default);
Vue.component('editmodule', require('./components/EditModule.vue').default);
Vue.component('navtab-component', require('./components/NavTab.vue').default);
Vue.component('meetingscalls-component', require('./components/MeetingsCalls.vue').default);
Vue.component('messagechatbox-component', require('./components/MessageChatbox.vue').default);
Vue.component('formchatbox-component', require('./components/FormChatbox.vue').default);
Vue.component('dropzone', require('./components/DropZone.vue').default);
Vue.component('datetimepicker', require('./components/DateTimepicker.vue').default);


const app = new Vue({
    el: '#app',
});




