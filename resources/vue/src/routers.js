import VueRouter from 'vue-router';
import Booking from 'Components/booking/booking.vue';
import Manage_appointment from 'Components/manage_appointment/manage_appointment.vue';
import Login from 'Components/login/login.vue';
import Profile from 'Components/profile/profile.vue';

export const admin_route = [
	{ name: 'location', path: '/locations', component: Booking },
];

export default [
  { name: 'booking', path: '/booking', component: Booking },
  { name: 'manage_appointment', path: '/manage_appointment', component: Manage_appointment },
  { name: 'login', path: '/login', component: Login },
  { name: 'profile', path: '/profile', component: Profile }
]

