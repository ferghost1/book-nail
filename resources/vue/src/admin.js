import Vue from 'vue';
import VueRouter from 'vue-router';
import routes from './routers.js';
import gMixin from 'Src/global-mixin';
import {axios} from 'Src/generalLib';
import pageInfoApi from 'Api/page_info';
import userApi from 'Api/user';
import swal from 'sweetalert';
// import App from './components/App.vue';
Vue.config.devtools = true;
window.Vue = Vue;
Vue.use(VueRouter);
Vue.mixin(gMixin);
window.app = new Vue({
  	el: '#app',
  	router: new VueRouter({routes}),
  	data: {
  		registerBy: 'root',
  		page: {
			booking: {
				selected: true,
			},
			manageAppoinment: {
				selected: false,
			},
			profile: {
				selected: false,
			},
			login: {	
				selected: false,
			}
		}
  	},
	methods: {
		changePage: function(pageName, event = {}) {
			window.event = event;
			if(event.target)
			_.each(this.page, (item) => {item.selected = false});
			this.page[pageName].selected = true;
		},
		logout() {
			swal({
				text: 'Do you want sure to logout?',
				icon: 'warning',
				buttons: true
			}).then(res => {
				if (res) {
					userApi.logout(this).then(() => {
						FB.logout();
						this.$router.push('booking');
						this.$forceUpdate();
					});
					
					
				}
			})
		},
	},
	created: function() {
		let self = this;
		this.$router.push('booking');
		_.merge(this.page, pageInfoApi.getPageInfo());
		setTimeout(function() {
			userApi.checkLogin(self);
		}, 1500);
	},
	computed: {
		currentPage() {
			let self = this;
			return _.find(this.page, page => page.name == self.$route.name);
		},
	}
});
