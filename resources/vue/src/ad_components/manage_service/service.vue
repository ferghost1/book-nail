<template>
    <div>
        <h3 style="cursor: pointer; color: purple" @click="backToLocations()"><- back</h3>
        <h3>Config servicee for location {{selectedLocation.name}}</h3>
        <div class="middle-menu">
            <button class="menu-item" @click="curCompName = 'c-service'">Services</button>
            <button class="menu-item" @click="curCompName = 'c-employee'">Employees</button>
        </div>
        <component :is="curCompName"></component>
    </div>
</template>

<script>
  import bookingApi from 'Api/booking';
  import adminApi from 'Api/admin';
  import swal from 'sweetalert';
  import CJob from 'AdComponents/manage_service/service/job.vue';
  import CEmployee from 'AdComponents/manage_service/service/employee.vue';

  const data = {
    registerBy: 'cService',
    curCompName: 'c-service',
    employees: [],
    services: []
  };

  var comp = {
    data() { return data},
    components: {
        'c-service': CJob,
        'c-employee': CEmployee
    },
    methods:{
        getEmployeeByLocation() {
            bookingApi.getEmployeeByLocation(this.getComp('manageService').selectedLocation.id).then(resData => {
                    _.map(resData, item => item.show = false);
                    this.employees = resData;
            });  
        },
        getServiceByLocation() {
            adminApi.getServiceByLocation(this.getComp('manageService').selectedLocation.id).then(resData => {
                if(resData.success) {
                    let items = resData.data;
                    _.map(items, item => item.show = false);
                    this.services = items;
                }
            });
            },
        backToLocations() {
            this.getComp('manageService').cancelConfigLocation();
        },
    },
    created: function() {
        window.serviceComp = this;
        this.getEmployeeByLocation();
        this.getServiceByLocation();
        let self = this;
    },
    computed: {
        selectedLocation() {
            return this.getComp('manageService').selectedLocation;
        }
    },
  };


  export default comp;
</script>
<style type="text/css" scoped>
    .middle-menu {
        display: flex;
        width: 100%;
        min-height: 30px;
        justify-content: center;
    }
    .menu-item {
        border-radius: 15px;
        border: solid black 1px;
        min-width: 50px; 
        margin: 5px;
        padding: 5px;
        cursor: pointer;
    }
</style>