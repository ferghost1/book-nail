<template>
  <div class="11">
    <h2 style="padding: 5px">Your appointments</h2>
    <div style="padding-left: 20px">
      <span>From date:</span>
      <input type="date" v-model="orderBy.from_date">
      <button type="button" @click="getAppointments()">Search</button>
    </div>
    <div style="padding-left: 20px ">
      
    </div>
    <div class="appointments" style="padding-left: 3px">
      <div class="appointment-container" v-for="appointment in sortedItems">
       <div class="appointment-title" :style="{'background-color': BACKGROUND_COLOR[appointment.status]}">
         <div class="icon-arrow"></div>
         <div class="appointment-name" @click="appointment.show = !appointment.show">
          <div>
            <h3 class="title-location-detail">{{appointment.booked_detail.service_name}}</h3>
            <div style="padding-left: 15px">
                <span style="color: white">on: {{appointment.date}}</span>
                <p style="color: white; margin: 0">
                    
                    <span style="color: white" v-if="appointment.status == 2">
                        at: {{TIME_SPACE[appointment.booked_time]}}
                    </span>
                    <span v-if="appointment.status == 1" style="color: white">
                        This booking is wating for check
                    </span>
                    <span v-if="appointment.status == 3" style="color: white">
                        This booking was declined, please contact admin for more info.
                    </span>
                </p>
                
            </div>
          </div>
         </div>
         <div v-if="canEdit(appointment)">
             <div class="icon-edit" @click="edit(appointment)"></div>
         </div>
       </div>
        <div class="appointment-detail" v-if="appointment.show">
          <div class="icon-appointment"></div>
          <div class="appointment-detail-info">
            <p>At store: {{appointment.booked_detail.location_name}}</p>
            <p>Service: {{appointment.booked_detail.service_name}} - ${{appointment.booked_detail.price}}</p>
            <p>By: {{appointment.booked_detail.emp_name}}</p>
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

    <div class="pagination" v-if="paginate.last_page > 1">
        <button @click="changePaginate(1)" :disabled="paginate.current_page < 2"> << </button>
        <button @click="changePaginate(paginate.current_page - 1)" :disabled="paginate.current_page < 2"> < </button>
        <span> {{paginate.current_page}} </span>
        <button @click="changePaginate(paginate.current_page + 1)" :disabled="paginate.current_page >= paginate.last_page"> > </button>
        <button @click="changePaginate(paginate.last_page)" :disabled="paginate.current_page >= paginate.last_page"> >> </button>
        <div>
            <label>Go to 
                <input v-model="paginate.go_to" type="number">
                <button @click="changePaginate(paginate.go_to)" type="button">Go</button>
            </label>
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
      from_date: moment().subtract(1, 'week').format('YYYY-MM-DD')
    },
    paginate: {
        current_page: 1,
        last_page: 3,
        go_to: ''
    },
    sortBy: ['date', 'status'],
    services: [],
    locations: [],
    employeeServices: [],
    appointments: null
  };
  var comp = {
    data() { return data},
    methods:{
        init() {
            window.apmComp = this;
            this.getAppointments();
        },
        getAppointments() {
            let appointments = bookingApi.getCustomerAppointment({...this.orderBy, page: this.paginate.current_page}).then(res => {
                    _.map(res, apm => apm.show = false);
                    this.appointments = res;
                });
        },
        edit(appointment) {
            console.log(appointment);
            swal({
              title: "Change this appointment?",
              buttons: true
            }).then(res => {
                if(res) {
                    let bookComp = this.getComp('booking') || initBookCompData.bookData;
                    _.merge(bookComp.bookData, appointment);
                    bookComp.step = 2;
                    this.$router.push('booking');
                }
            });
        },
        canEdit(item) {
            // at least 1 day before book date or not approved
            return item.status == 1 || (item.status == 2 && item.date < moment().format('YYYY-MM-DD'));
        },
        changePaginate(page) {
            page = page > this.paginate.last_page ? this.paginate.last_page : page;
            page = page < 1 ? 1 : page;
            this.paginate.current_page = page;
            this.paginate.go_to = '';
            this.getCustomers();
        }
    },
    created() {
        this.init();
    },
    computed: {
        sortedItems() {
            return _.orderBy(this.appointments, ['date', 'status'], ['desc', 'asc']);
        }
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