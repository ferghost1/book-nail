import {axios} from 'Src/generalLib';

export default {
	saveLocation(data) {
		return axios.post('admin/saveLocation', data).then(res => {
			return res.data;
		});
	},
	deleteLocation(id) {
		return axios.post('admin/deleteLocation', {id: id}).then(res => {
			return res.data;
		});
		console.log('deleting location');
	},
	saveEmployee(data) {
		return axios.process(axios.post('admin/saveEmployee', data));
	},
	blockEmployee() {
		console.log('blocking employee');
	},
	deleteEmployee(id) {
		return axios.post('admin/deleteEmployee', {id: id}).then(res => {
			return res.data;
		});
	},
	saveService(data) {
		return axios.post('admin/saveService', data).then(res => {
			return res.data;
		});
	},
	deleteService(id) {
		return axios.post('admin/deleteService', {id: id}).then(res => {
			return res.data;
		});
	},
	saveEmployeeService(data) {
		return axios.post('admin/saveServiceEmployee', data).then(res => {
			return res.data;
		});
	}
}