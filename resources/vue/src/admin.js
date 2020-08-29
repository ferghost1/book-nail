import Vue from 'vue';
import VueRouter from 'vue-router';
import {admin_route} from './routers.js';
import gMixin from 'Src/global-mixin';
import {axios} from 'Src/generalLib';
import userApi from 'Api/user';
import swal from 'sweetalert';

Vue.config.devtools = true;
window.Vue = Vue;
Vue.use(VueRouter);
Vue.mixin(gMixin);

window.app = new Vue({
  	el: '#app',
  	router: new VueRouter({routes: admin_route}),
  	data: {
  		registerBy: 'root',
  		showMenu: false,
  	},
	methods: {
		init() {
			!this.$route.name && this.$router.push('manage_service');
		}
	},
	created: function() {
		this.init();
	},
	computed: {
	}
});
