<template>
  <div class="11">
    <h2 style="padding: 5px">Your appointments</h2>
    <div style="padding-left: 20px">
      <span>From date:</span>
      <input type="date" v-model="orderBy.from_date" @change="getAppointments()">
    </div>
    <div style="padding-left: 20px ">
      <p>
        <span>
          <span style="position: relative; top: -5px; padding-right: 5px;">Sort by:</span>
        </span>
        <span style="padding-right: 5px">
          <input id="sort-by-status" type="radio" v-model="sortBy" @change="sortAppointment()" value="status">
          <label for="sort-by-status">Status</label>
        </span>
        <span>
          <input id="sort-by-date" type="radio" v-model="sortBy" @change="sortAppointment()" value="date">
          <label for="sort-by-date">Date</label>
        </span>
      </p>
    </div>
    <div class="appointments" style="padding-left: 3px">
      <div class="appointment-container" v-for="appointment in appointments">
       <div class="appointment-title" :style="{'background-color': BACKGROUND_COLOR[appointment.status]}">
         <div class="icon-arrow"></div>
         <div class="appointment-name" @click="appointment.show = !appointment.show">
          <div>
            <h3 class="title-location-detail">{{showServiceName(appointment)}}</h3>
            <div style="padding-left: 15px">
                <span style="color: white">on: {{appointment.date}}</span>
                <p style="color: white; margin: 0">
                    at:
                    <span style="color: white" v-if="appointment.status == 2">
                        {{TIME_SPACE[appointment.booked_time]}}
                    </span>
                    <span v-else style="color: white">
                        Wating for check
                    </span>
                </p>
                
            </div>
          </div>
         </div>
         <div>
             <div class="icon-edit" @click="edit(appointment)"></div>
         </div>
       </div>
        <div class="appointment-detail" v-if="appointment.show">
          <div class="icon-appointment"></div>
          <div class="appointment-detail-info">
            <p>At store: {{showLocationName(appointment)}}</p>
            <p>Service: {{showServiceName(appointment)}}</p>
            <p>By: {{showServiceEmployee(appointment)}}</p>
            <p>Be booked at: 
                <span v-if="appointment.status == 2">
                        {{TIME_SPACE[appointment.booked_time]}}
                </span>
                <span v-else>
                    Wating for check from admin
                </span>
            </p>
            <p>
                <span v-if="appointment.status == 1">
                    This appointment is waiting check from admin
                </span>
                <span v-else-if="appointment.status == 2">
                    This appointment was approved, Please visit at booked time above
                </span>
                <span v-else>
                    Your appointment was reject by some problem, please contact admin
                </span>
            </p>
            <p>
                Note to admin:
                <p style="padding-left: 10px; margin: 0">acsascascascsacascsacsac{{appointment.note}}</p>
            </p>
          </div>
        </div>
     </div>
    </div>
  </div>
</template>

<script>
  import bookingApi from 'Api/booking';
  import {TIME_SPACE} from 'Src/constants';
  import {data as initBookCompData} from 'Components/booking/booking.vue';
  const data = {
    registerBy: 'manageAppointment',
    TIME_SPACE,
    BACKGROUND_COLOR: {
        1: '#97970447',
        2: '#7bdb7b',
        3: '#ec807b'
    },
    orderBy: {
      from_date: moment().subtract(1, 'week').format('YYYY-MM-DD'),
      page: 1
    },
    sortBy: 'date',
    services: [],
    locations: [],
    employeeServices: [],
    appointments: null
  };
  var comp = {
    data() { return data},
    methods:{
      getAppointments() {
        let appointments = bookingApi.getCustomerAppointment(this.user, this.orderBy);
        _.map(appointments, apm => apm.show = false);
        this.appointments = appointments;
        this.sortAppointment();
      },
      sortAppointment() {
        let sortSeuquence = this.sortBy == 'date' ? ['date', 'status'] : ['status', ' date'];
        this.appointments = _.orderBy(this.appointments, sortSeuquence, ['desc', 'desc']);
      },
      edit(appointment) {
        console.log(appointment);
        swal({
          title: "Change this appointment?",
          buttons: true
        }).then(res => {
            if(res) {
                let bookComp = this.getComp('booking') || initBookCompData;
                _.merge(bookComp.bookData, appointment);
                bookComp.step = 2;
                this.$router.push('booking');
            }
        });
      },
      showLocationName(appointment) {
        return this.locations[appointment.location_id] ? this.locations[appointment.location_id].name : 'Unrecognized Store';
      },
      showServiceName(appointment) {
        return this.services[appointment.service_id] ? this.services[appointment.service_id].name : 'Unrecognized Service';
      },
      showServiceEmployee(appointment) {
        let empService = _.find(this.employeeServices, 
          {service_id: appointment.service_id, employee_id: appointment.employee_id});
        return empService ? empService.name + '  -  ' + empService.price : 'Unrecognized worker';
      },
    },
    created() {
      window.apmComp = this;
      this.services = bookingApi.getServices();
      this.locations = bookingApi.getLocations();
      this.employeeServices = bookingApi.getEmployeesByServices();
      this.getAppointments();
    },
    computed: {
      
    }
  };


  export default comp;
  export const accessTestMixin1 = {
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

<style>
  #app {
    font-size: 18px;
    font-family: 'Roboto', sans-serif;
    color: blue;
  }
</style>