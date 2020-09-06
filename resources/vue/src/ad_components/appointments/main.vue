<template>
    <div>
        <h2>List Appointments Of 
            <select v-model="selectedLocationId" @change="selectLocation()">
                <option disabled value="0">Select location</option>
                <option v-for="l in locations" :value="l.id">{{l.name}}</option>
            </select>
        </h2>
        <div class="">
            <div class="search-box">
                <div class="row">
                    <label for="search-name">Name: </label>
                    <input id="search-name" type="text" v-model="search.name">
                </div>
                <div class="row">
                    <label for="search-fromdate">From date: </label>
                    <input id="search-fromdate" type="date" v-model="search.from_date">
                </div>
                <div class="row">
                    <button @click="getAppointments()">Search</button>   
                </div>
                <div class="advanced-search">
                    <div class="clickable" style="text-align: center;" @click="search.show_avanced = !search.show_avanced">Advanced search</div>
                    <div class="main" v-if="search.show_avanced">
                        <div>
                            <span>Search type:</span>
                            <div style="padding-left: 30px">
                                <label>
                                    <input type="radio" v-model="search.user_type" value="3">
                                    Customers
                                </label>
                                <label>
                                    <input type="radio" v-model="search.user_type" value="2">
                                    Employees
                                </label>
                            </div>
                        </div>
                        <div>
                            <span>Sort options:</span>
                            <div style="padding-left: 30px">
                                <label>
                                    <input type="radio" v-model="sort.type" :value="['date', 'status']">
                                    <button @click="changeSort('date')" style="padding-left: 1px 3px">Date {{sort.date == 'asc' ? '+' : '-'}}</button>
                                    <button @click="changeSort('status')" style="padding-left: 1px 3px">Status {{sort.status == 'asc' ? '+' : '-'}}</button>
                                </label>
                                <label>
                                    <input type="radio" v-model="sort.type" :value="['status', 'date']">
                                    <button @click="changeSort('status')" style="padding-left: 1px 3px">Status {{sort.status == 'asc' ? '+' : '-'}}</button>
                                    <button @click="changeSort('date')" style="padding-left: 1px 3px">Date {{sort.date == 'asc' ? '+' : '-'}}</button>
                                </label>
                            </div>
                        </div>
                        <div>
                            <span>Show:</span>
                            <div style="padding-left: 30px">
                                <label>
                                    <input type="checkbox" v-model="sort.show" :value="1">
                                    Peding
                                </label>
                                <label>
                                    <input type="checkbox" v-model="sort.show" :value="2">
                                    Accept
                                </label>
                                <label>
                                    <input type="checkbox" v-model="sort.show" :value="3">
                                    Declined
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="list-appointments">
                 <div class="list-item">
                    <div v-for="item in sortedItems" class="item">  
                        <div class="item-title" @click="showDetail(item)">
                            <div class="item-icon">XX</div>
                            <div class="item-title-detail">
                                <div class="item-title-detail-col1">
                                    <div>
                                        <span>{{item.booked_detail ? item.booked_detail.service_name : 'Unknown service'}}</span>
                                        <span> - {{item.booked_detail ? item.booked_detail.price + '$' : 'Unknown price'}}</span>
                                    </div>
                                    <div>
                                        <span>for {{item.cus_name}}</span>
                                        <span>by {{item.booked_detail ? item.booked_detail.employee_name : 'Unknown Employee'}}</span>
                                    </div>
                                </div>
                                <div class="item-title-detail-col2">
                                    <div>date {{item.date}}</div>
                                    <div>at {{TIME_SPACE[item.booked_time] || 'Not set'}}</div>
                                </div>
                                <div style="margin-left: 10px; font-size: 25px" class="arrow">
                                    <b v-if="item.show">&#9662;</b>
                                    <b v-else>&#9656;</b>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="divide-line"></div> -->
                        <div class="item-detail" v-if="item.show">
                            <div class="errors-box item-row" v-if="!lodash.isEmpty(errors)">
                                <ul>
                                    <li v-for="err in errors" class="error-detail">{{err}}</li>     
                                </ul>
                            </div>
                            <form @submit.prevent="saveAppointment()">    
                                <div class="item-row">
                                    <span class="item-detail-col1">Date</span>
                                    <span class="item-detail-col2">
                                        <input type="date" v-model="selectingItemTemp.date">
                                    </span>
                                </div>
                                <div class="item-row">
                                    <span class="item-detail-col1">Service</span>
                                    <span class="item-detail-col2">
                                        <select v-model="selectingItemTemp.service_id">
                                            <option v-for="ser in services" :value="ser.id">{{ser.name}}</option>
                                        </select>
                                    </span>
                                </div>
                                <div class="item-row">
                                    <span class="item-detail-col1">Employee</span>
                                    <span class="item-detail-col2">
                                        <select v-model="selectingItemTemp.employee_id" 
                                                :required="selectingItemTemp.service_id">
                                            <option v-for="emp in employeeByServices" :value="emp.employee_id">{{emp.emp_name}}</option>
                                        </select>
                                    </span>
                                </div>
                                <div class="item-row">
                                    <span class="item-detail-col1">Customer time</span>
                                    <span class="item-detail-col2">
                                        <span style="margin: 0 3px" v-for="time in selectingItemTemp.time_space">{{TIME_SPACE[time]}} <br></span>
                                    </span>
                                </div>
                                <div class="item-row">
                                    <span class="item-detail-col1">Booked time</span>
                                    <span class="item-detail-col2">
                                        <select v-model.number="selectingItemTemp.booked_time">
                                            <option value="0">Set time</option>
                                            <option v-for="time in validBookTime" :value="time.k">{{time.v}}
                                                <span v-if="time.status == 1">&#10060;</span>
                                                <span v-if="time.status == 2">&#9989;</span>
                                            </option>
                                        </select>
                                    </span>
                                </div>
                                <div class="item-row">
                                    <span class="item-detail-col1">Status</span>
                                    <span class="item-detail-col2">
                                        <label>
                                            Pending
                                            <input type="radio" v-model="selectingItemTemp.status" value="1">
                                        </label>
                                        <label>
                                            Accepted
                                            <input type="radio" v-model="selectingItemTemp.status" value="2">
                                        </label>
                                        <label>
                                            Decline
                                            <input type="radio" v-model="selectingItemTemp.status" value="3">
                                        </label>
                                    </span>
                                </div>
                                <div class="item-row">
                                    <button>Save</button>
                                </div>
                            </form>
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
        </div>
        <input type="hidden" v-if="watchEmpBookedTime">
    </div>
</template>

<script>
  import bookingApi from 'Api/booking';
  import adminApi from 'Api/admin';
  import {TIME_SPACE, STATUS_DESC} from 'Src/constants';
  const data = {
    registerBy: 'Appointments',
    TIME_SPACE, STATUS_DESC,
    errors: [],
    locations: [],
    services: [],
    employees: [],
    serviceRelation: [],
    selectedLocationId: 0,
    selectingItemTemp: {},
    empBookedTime: [1, 2, 3, 4], // alway busy
    appointments: null,
    search: {
        show_avanced: false,
        name: '',
        from_date: moment().format('YYYY-MM-DD'),
        user_type: 3
    },
    sort: {
        type: ['date', 'status'],
        date: 'desc',
        status: 'asc',
        show: [1, 2, 3] 
    },
    paginate: {
        current_page: 1,
        last_page: 1,
        go_to: ''
    }
  };

  var comp = {
    components: {},
    data() { return data},
    methods:{
        init() {
            this.getLocations();
        },
        saveAppointment() {
            adminApi.saveAppointment(this.selectingItemTemp).then(res => {
                this.errors = res.errors || [];
                if (res.success) {
                    let item = _.find(this.appointments, it => {
                        return it.id == this.selectingItemTemp.id;
                    });
                    _.merge(item, res.data);
                    this.$notify({type: 'success', text: 'Appointment was saved'})
                }
            })
        },
        getLocations() {
            return bookingApi.getLocations().then(res => {
                this.locations = res;
                this.selectedLocationId = localStorage.getItem('selectedLocationId') || 0;
                this.selectLocation();
            });
        },
        selectLocation() {
            localStorage.setItem('selectedLocationId', this.selectedLocationId);
            this.getServices();
            this.getAppointments();
            this.getEmployees();
            this.getServiceRelation();
        },
        getServices() {
            return bookingApi.getServiceByLocation(this.selectedLocationId).then(res => this.services = res);
        },
        getServiceRelation() {
            bookingApi.getServiceRelation(this.selectedLocationId).then(res => this.serviceRelation = res);
        },
        getEmployees() {
            bookingApi.getEmployeeByLocation(this.selectedLocationId).then(res => this.employees = res)
        },
        getAppointments() {
            let params = {...this.search, location_id: this.selectedLocationId, page: this.paginate.current_page};
            return adminApi.getAppointments(params).then(
                res => {
                    if (!res) {
                        swal('Something went wrong while get appointments');
                        return;
                    }
                    this.paginate.last_page = res.last_page;
                    _.map(res.data, it => it.show = false);
                    this.appointments = res.data;
                }
                
            );
            
        },
        getEmployeeBookedTime() {
            bookingApi.getEmployeeBookedTime(this.selectingItemTemp.employee_id, this.selectingItemTemp.date, [this.selectingItemTemp.id]).then(res => this.empBookedTime = res);
        },
        changeSort(sortOps) {
             this.sort[sortOps] = this.sort[sortOps] == 'asc' ? 'desc' : 'asc';
        },
        showDetail(item) {
            this.errors = []; 
            this.selectingItemTemp = _.cloneDeep(item);
            let tempStatus = item.show;
            _.map(this.appointments, amp => amp.show = false);
            item.show = !tempStatus;
        },
        changePaginate(page) {
            page = page > this.paginate.last_page ? this.paginate.last_page : page;
            page = page < 1 ? 1 : page;
            this.paginate.current_page = page;
            this.paginate.go_to = '';
            this.getAppointments();
        }
    },
    computed: {
        employeeByServices() {
            return _.filter(this.serviceRelation, sr => {
                return sr.service_id == this.selectingItemTemp.service_id;
            });
        },
        watchEmpBookedTime() {
            this.selectingItemTemp.service_id;
            return this.getEmployeeBookedTime();
        },
        validBookTime() {
            // status 1: be booked, 2: free and fit with customer time, 3: free but not in customer time
            return _.map(TIME_SPACE, (v, k) => {
                let status = 3;
                if (_.includes(this.empBookedTime, parseInt(k))) {
                    status = 1;
                } else if (_.includes(this.selectingItemTemp.time_space, parseInt(k))) {
                    status = 2;
                }

                return {k, v, status}
            });
        },
        sortedItems() {
            let first = this.sort.type[0];
            let second = this.sort.type[1];
            let items = _.orderBy(ap.appointments, this.sort.type, [this.sort[first], this.sort[second]]);
            return _.filter(items, it => _.includes(this.sort.show, parseInt(it.status)));
        }
    },
    created: function() {
        this.init();
        window.ap = this;
    }
  };


  export default comp;
  export {data};
</script>

<style scoped>
    #app {
        font-size: 18px;
        font-family: 'Roboto', sans-serif;
        color: blue;
    }
    .list-item {
        padding: 5px 10px;
    }
    .item {
        display: flex;
        align-items: stretch;
        justify-content: center;
        flex-direction: column;
        margin: 10px 0;
    }
    .item .divide-line {
        width: 90%;
        margin-left: 10%;
        border: none;
        padding: 0.3px;
        background: darkgrey;
    }
    .item .item-title {
        display: flex;
        flex-direction: row;
    }
    .item-title .item-action {
        flex-grow: 4;
        text-align: right;
        padding-right: 5px;
    }
    .item-title .item-icon {
        flex-grow: 2
    }
    .item-title .item-title-detail {
        display: flex;
        flex-grow: 8
    }
    .item-title .item-title-detail .item-title-detail-col1 {
        display: flex;
        flex-direction: column;
        min-width: 200px;
        margin-right: 25px
    }
    .item-title .item-title-detail .item-title-detail-col2 {
        display: flex;
        flex-direction: column;
    }
    .item .item-detail {
        display: flex;
        flex-direction: column;
        padding: 1px 10px 0;
        margin-left: 25px;
    }
    .item-detail .item-row {
        display: flex;
        flex-direction: row;
        background: white;
        border-radius: 20px;
        margin: 5px 0;
        padding: 3px 10px 0;
    }
    .item-detail .item-detail-col1 {
        min-width: 30%;
    }
</style>