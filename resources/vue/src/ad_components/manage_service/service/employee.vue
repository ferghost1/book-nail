<template>
    <div>
        <h3 class="breadcum-booking"> List employees of this location</h3>
        <div class="list-item">
            <button @click="add()">Add more</button>
            <div v-for="item in items" class="item">
                <div class="item-title">
                    <span class="item-icon">item icon</span>
                    <span class="item-title-detail" @click="showDetail(item)">{{item.name}}</span>
                    <span class="item-action">
                        <button @click="edit(item)">Edit</button>
                        <button @click="remove(item)">Delete</button>
                    </span>
                </div>
                <!-- <div class="divide-line"></div> -->
                <div class="item-detail" v-if="item.show">
                    <div class="item-row">
                        <span class="item-detail-col1">Username</span>
                        <span class="item-detail-col2">{{item.user_name}}</span>
                    </div>
                    <div class="item-row">
                        <span class="item-detail-col1">Email</span>
                        <span class="item-detail-col2">{{item.email}}</span>
                    </div>
                    <div class="item-row">
                        <span class="item-detail-col1">Name</span>
                        <span class="item-detail-col2">{{item.name}}</span>
                    </div>
                    <div class="item-row">
                        <span class="item-detail-col1">Phone</span>
                        <span class="item-detail-col2">{{item.phone}}</span>
                    </div>
                    <div class="item-row">
                        <span class="item-detail-col1">Address</span>
                        <span class="item-detail-col2">{{item.address}}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Add Edit -->
        <edit-form v-if="showForm" :editItem="editItem"></edit-form>

        <!-- Trigger computed on change parent value -->
        <input type="hidden" :value="changeParentServices" name="">
    </div>
</template>

<script>
    import bookingApi from 'Api/booking';
    import serviceApi from 'Api/service';
    import formEdit from 'AdComponents/manage_service/form/employee_form.vue';

    var data = {
        registerBy: 'cEmployee',
        defaultItem: {
            location_id: '',
            user_name: '',
            password: '',
            email: '',
            name: '',
            phone: '',
            address: '',
            is_active: 1
        },
        items: null,
        showForm: false,
        editItem: null
    };

    var comp = {
        data() {return data; },
        components: {
            'edit-form': formEdit
        },
        methods:{
            getItems() {},
            add() {
                this.editItem = _.cloneDeep(this.defaultItem);
                this.showForm = true;
            },
            edit(item) {
                this.editItem = item;
                this.showForm = true;
            },
            block(item) {
                swal({
                    title: "Block it?",
                    icon: "warning",
                    buttons: true
                }).then(isDone => {
                    if (isDone) {
                        serviceApi.blockEmployee(item);
                    }
                });
            },
            remove(item) {
                // confirm delete
                swal({
                    title: "Are you sure?",
                    icon: "warning",
                    buttons: true
                }).then(isDone => {
                    if (isDone) {
                        serviceApi.deleteEmployee(item.id).then(res => {
                            if (res.success) {
                                this.getComp('cService').employees = _.without(this.items, item);
                                // fetch services to remove employees was delete from service
                                this.getComp('cService').getServiceByLocation(item.location_id);
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
            showDetail(employee) {
                let tempStatus = employee.show;
                _.map(this.items, emp => emp.show = false);
                employee.show = !tempStatus;
            }
        },
        created: function() {
            window.CEmployee = this;
            this.defaultItem.location_id = this.getComp('manageService').selectedLocation.id;
        },
        computed: {
            changeParentServices() {
                this.items = this.getComp('cService').employees;
            }
        }
    };


  export default comp;
</script>

<style scoped>
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
        flex-grow: 8
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