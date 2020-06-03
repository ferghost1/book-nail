import {axios} from 'Src/generalLib';
export default {
	getPageInfo() {
		return {
			booking: {
				name: 'booking',
				description: 'Booking PageAlu bala',
				typeMemberDesc: 'Money is everything',
				note: 'Fuck'
			},
			manageAppoinment: {
				name: 'manage_appointment',
				description: 'manageAppoinment PageAlu bala',
			},
			profile: {
				name: 'profile',
				description: 'profile PageAlu bala',
			},
			login: {	
				name: 'login',
				description: 'login PageAlu bala',
			}
		};
	},
	login() {
		return;
	}
}