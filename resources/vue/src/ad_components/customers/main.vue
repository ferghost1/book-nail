<template>
    <div>
        <h2>List Customers</h2>
        <div class="">
            <div class="search-box">
                <div style="margin: 5px">
                    <label for="search-name">Name: </label>
                    <input id="search-name" type="text" v-model="search.name">
                    <button @click="getCustomers()">Search</button>
                </div>
            </div>
             <div class="list-item">
                <div v-for="item in items" class="item">  
                    <div class="item-title">
                        <span class="item-icon">xx</span>
                        <span class="item-title-detail" @click="showDetail(item)">{{item.name}}</span>
                        <span class="item-action">
                            <button @click="edit(item)">Edit</button>
                        </span>
                    </div>
                    <!-- <div class="divide-line"></div> -->
                    <div class="item-detail" v-if="item.show">
                        <div class="item-row">
                            <span class="item-detail-col1">Name</span>
                            <span class="item-detail-col2">{{item.name}}</span>
                        </div>
                        <div class="item-row">
                            <span class="item-detail-col1">Email</span>
                            <span class="item-detail-col2">{{item.email}}</span>
                        </div>
                        <div class="item-row">
                            <span class="item-detail-col1">Phone</span>
                            <span class="item-detail-col2">{{item.phone}}</span>
                        </div>
                        <div class="item-row">
                            <span class="item-detail-col1">Address</span>
                            <span class="item-detail-col2">{{item.address}}</span>
                        </div>
                        <div class="item-row">
                            <span class="item-detail-col1">isTrusted</span>
                            <span class="item-detail-col2">{{item.is_trusted ? 'Yes' : 'No'}}</span>
                        </div>
                        <div class="item-row">
                            <span class="item-detail-col1">Blocked</span>
                            <span class="item-detail-col2">{{!item.is_active ? 'Yes' : 'No'}}</span>
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

            <!-- Form Add Edit -->
            <edit-form v-if="showForm" :editItem="editItem"></edit-form>
        </div>
    </div>
</template>

<script>
  import bookingApi from 'Api/booking';
  import adminApi from 'Api/admin';
  import formEdit from 'AdComponents/customers/form/customer_form.vue'; 
  import {TIME_SPACE, STATUS_DESC} from 'Src/constants';


  const data = {
    registerBy: 'Customers',
    items: [],
    defaultItem: {
        id: 0,
        email: '',
        name: '',
        phone: '',
        address: '',
        is_active: 1,
        is_trusted: 0
    },
    showForm: false,
    editItem: null,
    search: {
        name: ''
    },
    paginate: {
        current_page: 1,
        last_page: 1,
        go_to: ''
    }
  };

  var comp = {
    components: {
        'edit-form': formEdit
    },
    data() { return data},
    methods:{
        init() {
            this.getCustomers();
        },
        getCustomers() {
            let params = {...this.search, user_type: 3, page: this.paginate.current_page};
            return adminApi.getUsers(params).then(
                res => {
                    if (!res) {
                        swal('Something went wrong while get customers');
                        return;
                    }
                    this.paginate.last_page = res.last_page;
                    _.map(res.data, it => it.show = false);
                    this.items = res.data;
                }
                
            );
            
        },
        edit(item) {
            this.editItem = item;
            this.showForm = true;
        },
        showDetail(employee) {
            let tempStatus = employee.show;
            _.map(this.items, emp => emp.show = false);
            employee.show = !tempStatus;
        },
        changePaginate(page) {
            page = page > this.paginate.last_page ? this.paginate.last_page : page;
            page = page < 1 ? 1 : page;
            this.paginate.current_page = page;
            this.paginate.go_to = '';
            this.getCustomers();
        }
    },
    computed: {
        watchpaginate() {
            this.getCustomers();
            return 
        }
    },
    created: function() {
        this.init();
        window.cus = this;
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
        margin-right: 15px
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