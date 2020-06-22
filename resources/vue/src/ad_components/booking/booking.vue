<template>
  <div class="11">
    <p class="breadcum-booking">
      <span>
        <span @click="step = 1">
          <a class="router-general">Select location</a>
        </span>
      </span>
      <span v-if="step > 1"> 
        >>
        <span @click="step = 2">
          <a class="router-general">Select service</a>
        </span>
      </span>
      <span v-if="step > 2" >
        >>
        <span @click="step = 3">
          <a class="router-general">Completed</a>
        </span>
      </span>
    </p>
    <keep-alive>
      <component :is="curCompName"></component>
    </keep-alive>
  </div>
</template>

<script>
  import bookingApi from 'Api/booking';
  import {TIME_SPACE} from 'Src/constants';
  import Location from 'Components/booking/location_booking.vue';
  import Service from 'Components/booking/service_booking.vue';
  import Completed from 'Components/booking/completed_booking.vue';

  const data = {
    registerBy: 'booking',
    testData: {},
    bookData: {},
    defaultBookData: {
      id: 0,
      customer_id: 0,
      location_id: 0,
      date: moment().format('YYYY-MM-DD'),
      service_id: 0,
      employee_id: 0,
      time_space: [],
      booked_time: 0,
      status: 0, // not approved
      created_at: moment().format('YYYY-MM-DD hh:mm:ss')
    },
    step: 1,
    compNameByStep: {
      1: 'c-location',
      2: 'c-service',
      3: 'c-completed'
    }
  };

  var comp = {
    components: {
      'c-location': Location,
      'c-service': Service,
      'c-completed': Completed
    },
    data() { return data},
    methods:{
      newAppointment() {
        _.merge(this.bookData, this.defaultBookData);
        this.bookData.time_space = [];
        this.step = 1;
      }
    },
    computed: {
      curCompName() { return this.compNameByStep[this.step] }
    },
    created: function() {
      window.bookingComp = this;
      if (_.isEmpty(this.bookData)) {
        this.bookData = _.cloneDeep(this.defaultBookData);
      }
    }
  };


  export default comp;
  export {data};
</script>

<style>
  #app {
    font-size: 18px;
    font-family: 'Roboto', sans-serif;
    color: blue;
  }
</style>