<template>
  <div>
    <keep-alive>
        <component v-if="curCompName == 'c-location'" is='c-location'></component>
    </keep-alive>
    <!-- do not keep alive, force re get all service by selected location -->
    <component v-if="curCompName == 'c-service'" is='c-service'></component>
  </div>
</template>

<script>
  import bookingApi from 'Api/booking';
  import Location from 'AdComponents/manage_service/location.vue';
  import Service from 'AdComponents/manage_service/service.vue';
  
  const data = {
    registerBy: 'manageService',
    locations: null,
    curCompName: 'c-location',
    selectedLocation: null
  };

  var comp = {
    components: {
      'c-location': Location,
      'c-service': Service
    },
    data() { return data},
    methods:{
        selectLocation(location) {
            this.selectedLocation = location;
            this.curCompName = 'c-service';
        },
        cancelConfigLocation() {
            this.curCompName = 'c-location';
        }
    },
    computed: {
      
    },
    created: function() {
      window.service = this;
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