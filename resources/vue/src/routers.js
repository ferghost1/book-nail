// customer component
import VueRouter from 'vue-router';
import Booking from 'Components/booking/booking.vue';
import Manage_appointment from 'Components/manage_appointment/manage_appointment.vue';
import Login from 'Components/login/login.vue';
import Profile from 'Components/profile/profile.vue';

// admin components
import ManageService from 'AdComponents/manage_service/main.vue';
import Appointments from 'AdComponents/appointments/main.vue';
import Customers from 'AdComponents/customers/main.vue';

export const admin_route = [
	{ name: 'manage_service', path: '/manage_service', component: ManageService },
	{ name: 'appointments', path: '/appointments', component: Appointments },
	{ name: 'customers', path: '/customers', component: Customers },
];

export default [
  { name: 'booking', path: '/booking', component: Booking },
  { name: 'manage_appointment', path: '/manage_appointment', component: Manage_appointment },
  { name: 'login', path: '/login', component: Login },
  { name: 'profile', path: '/profile', component: Profile }
]

