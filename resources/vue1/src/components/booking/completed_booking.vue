<template>
  <div class="11">
    <div v-if="user">
      <p>You booking data has been sent</p>
      <p ></p>
    </div>
  </div>
</template>

<script>
  import bookingApi from 'Api/booking';
  import {TIME_SPACE} from 'Src/constants';

  const data = {
    
  };

  var comp = {
    name: 'completed_booking',
    data() { return data},
    methods:{
      
    },
    computed: {
      employeeByService() {
        return _.filter(employees, function(employee) {
          return employee.id == this.bookInfo.serviceId;
        });
      },
    },
    created: function() {

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