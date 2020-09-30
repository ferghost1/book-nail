import axiosBase from 'axios';
import moment from 'moment';
import _ from 'lodash';

window.moment = moment;
// Put config here
const axios = axiosBase.create({
  responseType: 'json',
  timeout: 3000
});
axios.process = function(obj) {
	return obj.then(res => {
		return res.data && res.data.success !== undefined ? res.data :  {success: false, error: 'Error code: ' + res.status}
	})
	.catch(res => {
		return {success: false, error: res.message}
	})
}

window.axios = axios;
export {axios} 