<template>
  <div>
    <div>
        <h3 class="breadcum-booking"> Select location </h3>
        <div class="sub-booking-comp location">
            <div>
                <button @click="add()">Add more</button>
            </div>
         <div class="location-container" v-for="location in locations" :key="'location-' + location.id">
           <div class="location-title">
             <div class="icon-arrow" style="flex-grow: 1"></div>
             <div class="location-name" style="flex-grow: 6">
               {{location.name}}
             </div>
             <div style="flex-grow: 1">
                <button @click="edit(location)">Edit</button>
                <button @click="remove(location)">Delete</button>
             </div>
           </div>
           <div class="location-detail">
             <div class="icon-home"></div>
             <div class="location-name">
              <div>
                <h3 class="title-location-detail" @click="selectLocation(location)">{{location.name}}</h3>
              </div>
               <p style="margin: 2px">{{location.address}}</p>
               <p style="margin: 2px">{{location.phone}}</p>
               <p style="margin: 2px">{{location.description}}</p>
             </div>
           </div>
         </div>
        </div>
    </div>

    <!-- Form Add Edit -->
    <edit-form v-if="showForm" :editItem="editItem"></edit-form>

  </div>
</template>

<script>
  import bookingApi from 'Api/booking';
  import serviceApi from 'Api/service';
  import formEdit from 'AdComponents/manage_service/form/location_form.vue';

  const data = {
    registerBy: 'cLocation',
    locations: [],
    editItem: null,
    showForm: false,
    defaultItem: {
      name: '',
      phone: '',
      email: '',
      description: ''
    }
  };

  var comp = {
    data() { return data},
    components: {
      'edit-form': formEdit
    },
    methods:{
        getLocations() {
          bookingApi.getLocations().then(data => this.locations = data);
        },
        add() {
            this.editItem = _.cloneDeep(this.defaultItem);
            this.showForm = true;
        },
        edit(location) {
            this.editItem = location;
            this.showForm = true;
        },
        remove(item) {
            // confirm delete
            swal({
                title: "Are you sure?",
                icon: "warning",
                buttons: true
            }).then(isDone => {
                if (isDone) {
                    serviceApi.deleteLocation(item.id).then(res => {
                        if (res.success) {
                            this.locations = _.without(this.locations, item);
                        } else {
                            swal({
                                title: res.error,
                                icon: 'warning'
                            });
                        }
                    });
                }
            });
        },
        selectLocation(location) {
            this.getComp('manageService').selectLocation(location);
        },
    },
    computed: {
    },
    created: function() {
      this.getLocations();
      window.lcomp = this;
    }
  };


  export default comp;
</script>