import {axios} from 'Src/generalLib';
export default {
	checkCusLogin(context) {
		return axios.post('users/checkCusLogin').then((res) => {
			let resData = res.data;
			if (resData.success) {
				_.each(res.data.data, (prop, key) => {
		          context.$set(context.user, key, prop);
		        });
			}
		});
	},
	login(context, data) {
		return axios.post('users/cusLogin', data).then((res) => {
			let resData = res.data;
			if (!resData.success) {
				swal('Some thing went wrong, please try again!');
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
	updateCustomerProfile(data) {
		return axios.process(axios.post('users/updateCustomerProfile', data));
	}
}