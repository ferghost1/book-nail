<template>
  <div class="sub-booking-comp location">
     <div class="location-container" v-for="location in locations">
       <div class="location-title" @click="location.show = !location.show">
         <div class="icon-arrow"></div>
         <div class="location-name">
           {{location.name}}
         </div>
       </div>
       <div class="location-detail" v-show="location.show">
         <div class="icon-home"></div>
         <div class="location-name">
          <div>
            <h3 class="title-location-detail" @click="selectLocation(location.id)">{{location.name}}</h3>
          </div>
           <p style="margin: 2px">{{location.address}}</p>
           <p style="margin: 2px">{{location.phone}}</p>
           <p style="margin: 2px">{{location.description}}</p>
         </div>
       </div>
     </div>
  </div>
     
  </div>
</template>

<script>
  import bookingApi from 'Api/booking';
  import {TIME_SPACE} from 'Src/constants';

  const data = {
    registerBy: 'cLocation',
    locations: null
  };

  var comp = {
    data() { return data},
    methods:{
      init() {
            window.lcomp = this;
            this.bookData = this.getComp('booking').bookData;
            this.getLocations();
      },
      getLocations() {
        bookingApi.getLocations().then(res => {
            _.map(res, it => it.show = false);
            this.locations = res;
        });
      },
      selectLocation(locationId) {
        if (this.bookData.location_id != locationId) {
            this.bookData.service_id = 0;
            this.bookData.employee_id = 0;
            this.bookData.time_space.length = 0;
        }

        this.bookData.location_id = locationId;
        this.getComp('booking').step = 2;
      },
    },
    computed: {
        testData: {
            get() {console.log('get ',  this.registerBy);return this.getComp('booking').testData},
            set(value) {console.log('set ',  this.registerBy);this.getComp('booking').testData = value}
        }
    },
    created: function() {
        this.init();
    }
  };


  export default comp;
</script>