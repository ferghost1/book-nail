<template>
  <div class="11">
    <!-- <p>You are booking service from {{selectedLocation.name}}</p> -->
    <p>For date: <input type="date" v-model="bookData.date" @change="changeDate()"></p>
    <p>
      <select v-model="bookData.service_id" @change="changeService()">
        <option value="0" disabled > Select service</option>
        <option v-for="service in services" :value="service.id">{{service.name}}</option>
      </select>
      <select v-model="bookData.employee_id" @change="changeEmployee()">
        <option value="0" disabled > Select employee</option>
        <option v-for=" employeeService in employeeByService" :value="employeeService.employee_id">
          {{employeeService.name}} {{employeeService.price}}
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
      <button class="fl nav-button" @click="step = 1">Prev</button>
      <button class="fr nav-button" @click="book()">Book</button>
    </div>
  </div>
</template>

<script>
  import bookingApi from 'Api/booking';
  import {TIME_SPACE} from 'Src/constants';
  import swal from 'sweetalert';

  const data = {
    registerBy: 'cService'
  };

  var comp = {
    data() { return data},
    methods:{
      book() {
        // check login
        if (_.isEmpty(this.user)) {
          swal({text: "You need to login before continue"}).then(this.$router.push('login'));
          return;
        }

        // check whether customer select service, employee and there selected prop be in list
        let selectedService = _.find(this.services, {id: this.bookData.service_id});
        let selectedEmployee = _.find(this.employeeByService, {employee_id: this.bookData.employee_id});
        let selectedTimespace = _.difference(this.bookData.time_space, this.employeeBookedTime);
        if (!selectedService || !selectedEmployee || _.isEmpty(selectedTimespace)) {
          swal('Please choose your appointment before continue');
          return;
        }

        // confirm book
        swal({
          title: "Are you done?",
          text: "Please make sure you will visit at time, thank you",
          icon: "success",
          buttons: true
        }).then(isDone => {
          if (isDone) {
            this.bookData.customer_id = this.user.id;

            // save appointment here
            bookingApi.book(this.bookData);

            // make new data appointment
            this.getComp('booking').newAppointment();
            this.$router.push('manage_appointment');
          }
        });
      },
      changeDate() {
        this.bookData.time_space = [];
      },
      changeEmployee() {
        this.bookData.time_space = [];
        console.log('change employee');
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
      services() {
        return bookingApi.getServiceByLocation(this.bookData.location_id);
      },
      employeeByService() {
        return bookingApi.getEmployeesByServices(this.bookData.service_id) || [];
      },
      employeeBookedTime() {
        let self = this;
        let schedules = bookingApi.getEmployeeSchedule(this.bookData.employee_id, this.bookData.date);
        // In case update
        _.remove(schedules, {id: this.bookData.id});
        let bookedTime = _.union(_.map(schedules, 'booked_time'));
        // remove selected time space from book time
        _.remove(this.bookData.time_space, time => _.includes(bookedTime, time));
        return bookedTime;
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