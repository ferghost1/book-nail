import {axios} from 'Src/generalLib';

export default {
	locations: null,
	services: null,
	employees: null,
	servicesByLocation: null,
	employeeByService: null,
	getLocations() {
		if (this.locations) {
			return this.locations;
		}

		// call api here
		this.locations = {
	      	1: {
	      		id: 1,
	        	name: 'Like shit store',
	        	address: 'UG09 Chifley Plaza 2 Chifley Square Sydney NSW 2000 Australia',
	        	phone: '15372893852',
	        	description: 'thich thi ghi zo',
	        	show: false
	      	},
	      	2: {
	      		id:2,
	        	name: 'Like dick store',
	        	address: 'UG09 Chifley Plaza 2 Chifley Square Sydney NSW 2000 Australia',
	        	phone: '15372893852',
	        	description: 'thich thi ghi zo',
	        	show: false
	      	}
	    }
		return this.locations;
	},
	getServices() {
		if (this.services) {
			return this.services;
		}

		// call api here
		let services = {
			1: {id: 1, name: 'Cạo gió l1', location_id: 1},
			2: {id: 2, name: 'Massage l2', location_id: 2},
			3: {id: 3, name: 'Hút lỗ mũi l2', location_id: 2},
			4: {id: 4, name: 'Bắn gym hair l1', location_id: 1},
			5: {id: 5, name: 'Cạo gió2 l1', location_id: 1},
			6: {id: 6, name: 'Massage3 l2', location_id: 2},
		};

		// cache here
		this.services = services;
		return services;
	},
	getServiceByLocation(locationId) {
		let services = this.getServices();
		return _.filter(this.getServices(), service => service.location_id == locationId);
	},
	getEmployeesByServices(serviceId) {
		if(this.employeeByService && !serviceId) {
			return this.employeeByService;
		}

		// call api here
		this.employeeByService = [
			{id: 1, employee_id: 1, service_id: 1, name: 'Mặp 1', price: '25$'},
			{id: 2, employee_id: 1, service_id: 2, name: 'Mặp 1', price: '15$'},
			{id: 3, employee_id: 1, service_id: 6, name: 'Mặp 1', price: '35$'},
			{id: 4, employee_id: 2, service_id: 1, name: 'Mặp 2', price: '10$'},
			{id: 5, employee_id: 2, service_id: 3, name: 'Mặp 2', price: '20$'},
			{id: 6, employee_id: 2, service_id: 4, name: 'Mặp 2', price: '30$'},
			{id: 7, employee_id: 3, service_id: 5, name: 'Mặp 3', price: '7$'},
			{id: 8, employee_id: 3, service_id: 6, name: 'Mặp 3', price: '77$'},
			{id: 9, employee_id: 3, service_id: 3, name: 'Mặp 3', price: '777$'}
		];
		if(serviceId) {
			return _.filter(this.employeeByService, e => e.service_id == serviceId);
		}
		return this.employeeByService;
	},
	getAppointmentsBy(param) {
		let appointments = JSON.parse(sessionStorage.getItem('appointments')) || [];
		return _.filter(appointments, appointment => appointment[param[0]] == param[1]);
	},
	getEmployeeAppointment(employeeId) {
		let appointments = this.getAppointmentsBy(['employee_id', employeeId]);
		return _.filter(appointments, appointment => appointment.employee_id == employeeId);
	},
	getCustomerAppointment(user, orderBy) {
		let appointments = this.getAppointmentsBy(['customer_id', user.id]);
		return _.filter(appointments, appointment => moment(appointment.date) >= moment(orderBy.from_date));
	},
	getEmployeeSchedule(employeeId, date) {
		//call api to
		let appointments = this.getAppointmentsBy(['employee_id', employeeId]);
		return _.filter(appointments, appointment => {
			return appointment.status == 2 && appointment.date == date;
		});
	},
	book(bookData) {
		// call api here to save appointment
		let appointments = JSON.parse(sessionStorage.getItem('appointments')) || [];
		var oldBookData = null;
		if (bookData.id) {
			// Edit
			let oldBookData = _.find(appointments, {id: bookData.id});
			oldBookData.time_space.length = 0;
			_.merge(oldBookData, bookData);
			bookData.booked_time = _.min(bookData.time_space);
		} else { 
			// Create 
			bookData.id = Math.floor(Math.random() * 10000);
			bookData.status = Math.floor(Math.random() * 10) >= 5 ? 1 : 2;
			if (bookData.status == 2) {
				bookData.booked_time = _.min(bookData.time_space);
			}
	        appointments.push(bookData);
		}
        sessionStorage.setItem('appointments', JSON.stringify(appointments));
        return bookData;

		// if user is trusted appointment will be set time, no need admin check
	},
}