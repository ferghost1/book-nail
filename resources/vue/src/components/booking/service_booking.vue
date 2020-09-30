<template>
  <div class="11">
	<div class="errors-box item-row" v-if="!lodash.isEmpty(errors)">
		<ul>
			<li v-for="err in errors" class="error-detail">{{err}}</li>     
		</ul>
	</div>
    <!-- <p>You are booking service from {{selectedLocation.name}}</p> -->
    <p>For date: <input type="date" v-model="bookData.date" @change="changeDate()"></p>
    <p>
      <select v-model="bookData.service_id" @change="changeService()">
        <option value="0" disabled > Select service</option>
        <option v-for="service in services" :value="service.id">{{service.name}}</option>
      </select>
      <select v-model="bookData.employee_id" @change="changeEmployee()">
        <option value="0" disabled > Select employee</option>
        <option v-for=" emp in employeeByService" :value="emp.employee_id">
          {{emp.emp_name}} {{emp.price}}$
        </option>
      </select>
    </p>
    <div class="time-space">
      <div class="time-space-row">
        <input id="time_space1" type="checkbox" :disabled="lodash.includes(employeeBookedTime, 1)" v-model.number="bookData.time_space" value="1">
        <label for="time_space1">{{TIME_SPACE[1]}}</label>
      </div>
      <div class="time-space-row">
        <input id="time_space2" type="checkbox" :disabled="lodash.includes(employeeBookedTime, 2)" v-model.number="bookData.time_space" value="2">
        <label for="time_space2">{{TIME_SPACE[2]}}</label>
      </div>
      <div class="time-space-row">
        <input id="time_space3" type="checkbox" :disabled="lodash.includes(employeeBookedTime, 3)" v-model.number="bookData.time_space" value="3">
        <label for="time_space3">{{TIME_SPACE[3]}}</label>
      </div>
      <div class="time-space-row">
        <input id="time_space4" type="checkbox" :disabled="lodash.includes(employeeBookedTime, 4)" v-model.number="bookData.time_space" value="4">
        <label for="time_space4">{{TIME_SPACE[4]}}</label>
      </div>
      <div class="booking-time-guide">
        Please choose the time you can come and 
        <span class="note">make sure visit at right time to avoid some problem</span>
      </div>
    </div>
    <div class="navigate-booking">
      <button class="fl nav-button" @click="resetBookData()">Cancel</button>
      <button class="fr nav-button" @click="book()">Book</button>
    </div>

    <!-- Sync when change location or get booked time of employee when change info-->
    <input type="hidden" v-if="changeLocation + getEmployeeBookedTime">
    <input type="hidden" v-if="testData">
  </div>
</template>

<script>
  import bookingApi from 'Api/booking';
  import {TIME_SPACE} from 'Src/constants';
  import swal from 'sweetalert';

  const data = {
	registerBy: 'cService',
	errors: [],
    services: [],
    employees: [],
    employeeBookedTime: [],
    serviceRelations: [],
    bookData: null
  };

  var comp = {
    data() { return data},
    methods:{
        getServices() {
            bookingApi.getServiceByLocation(this.bookData.location_id).then(res => this.services = res);
        },
        getEmployees() {
            bookingApi.getEmployeeByLocation(this.bookData.location_id).then(res => this.employees = res);
        },
        getEmployeeSchedule() {
            bookingApi.getEmployeeBookedTime(this.bookData.employee_id, this.bookData.date, [this.bookData.id]).then(res => this.employeeBookedTime = res);
        },
        getServiceRelation() {
            bookingApi.getServiceRelation(this.bookData.location_id).then(res => this.serviceRelations = res);
        },
        book() {
            // check login
            if (_.isEmpty(this.user)) {
                swal({text: "You need to login before continue"}).then(this.$router.push('login'));
                return;
            }

            // check whether customer select service, employee and there selected prop be in list
            // let selectedService = _.find(this.services, ser => ser.id == this.bookData.service_id);
            // let selectedEmployee = _.find(this.serviceRelations, sr => sr.employee_id == this.bookData.employee_id);
            // let selectedTimespace = _.difference(this.bookData.time_space, this.employeeBookedTime);

            // if (!selectedService || !selectedEmployee || _.isEmpty(selectedTimespace)) {
            //     swal('Please fulfill your appointment to continue');
            //     return;
            // }

            // confirm book
            swal({
                title: "Are you done?",
                text: "Please make sure you will visit at time, thank you",
                icon: "success",
                buttons: true
            }).then(isDone => {
                if (isDone) {
					this.errors = [];
                    // save appointment here
                    let data = this.bookData;
                    
                    bookingApi.book(this.bookData).then(res => {
                        if (res.success) {
                        	this.getComp('booking').newAppointment();
							this.getComp('manageAppointment') && this.getComp('manageAppointment').getAppointments();
							this.$router.push('manage_appointment');
                        } else {
							this.errors = res.errors;
						}
                    });
                    // make new data appointment
                    
                }
            });
        },
        resetBookData() {
            swal({
                title: "Reset this process?",
                icon: "warning",
                buttons: true
            }).then(isDone => {
                isDone && this.getComp('booking').newAppointment();
            });
        },
      changeDate() {
        this.bookData.time_space = [];
      },
      changeEmployee() {
        this.bookData.time_space = [];
      },
      changeService() {
        this.bookData.employee_id = 0;
        this.changeEmployee();
      }
    },
    created: function() {
      window.serviceComp = this;
      let self = this;
      this.TIME_SPACE = TIME_SPACE;
      this.bookData = this.getComp('booking').bookData;
    },
    computed: {
        changeLocation() {
            this.getServices();
            this.getEmployees();
            this.getServiceRelation();
         },
         getEmployeeBookedTime() {
            this.getEmployeeSchedule();
         },
         employeeByService() {
            return _.filter(this.serviceRelations, se => se.service_id == this.bookData.service_id);
         },
         testData: {
            get() {console.log('get ',  this.registerBy);return this.getComp('cLocation').testData},
            set(value) {console.log('set ',  this.registerBy);this.getComp('cLocation').testData = value}
        }
    }
  };


  export default comp;
  export const accessBooking = {
    getComp: function(context) {
      return _.find(context.$children, (item) => item.$options.name == comp.name);
    },
    getData: function(context) {
      return this.getComp(context) || data;
    },
    setData: function(context, propName, value) {
      let currentComp = this.getData(context);
      currentComp[propName] = value;
    }
  };
</script>