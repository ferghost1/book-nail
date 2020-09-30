import {axios} from 'Src/generalLib';

export default {
	getServiceByLocation(locationId) {
		return axios.get('admin/getServiceByLocation', {params: {location_id: locationId}}).then(res => {
			return res.data;
		});
	},
	getAppointments(params) {
		return axios.get('admin/getAppointments', {params}).then(res => {
			return res.data && res.data.success ? res.data.data : null;
		});
	},
	saveAppointment(data) {
		return axios.process(axios.post('admin/saveAppointment', data));
	},
	getUsers(params) {
		return axios.get('admin/getUsers', {params}).then(res => {
			return res.data && res.data.success ? res.data.data : null;
		});
	},
	saveCustomer(data) {
		return axios.post('admin/saveCustomer', data).then(res => {
			return res.data;
		});
	},
}