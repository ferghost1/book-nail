import {axios} from 'Src/generalLib';
export default {
	checkLogin(context) {
		if (!FB) return;
		FB.getLoginStatus(res => {
			if (res.status != 'connected') return;
			console.log(res);
			// request token and user id to api save session
			this.login(context, res.authResponse);
		})
	},
	login(context, data) {
		return axios.post('users/login', data).then((res) => {
			let resData = res.data;
			if (!resData.success) {
				swal('Some thing went wrong, please try again!');
				FB.logout();
				return;
			}

			// set value for user
			_.each(res.data.data, (prop, key) => {
	          context.$set(context.user, key, prop);
	        });
		});
	},
	logout(context) {
		return axios.post('users/logout').then(() => {
			_.each(context.user, (prop, key) => delete context.user[key]);
		});
	},
}