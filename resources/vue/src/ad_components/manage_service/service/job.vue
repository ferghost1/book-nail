<template>
    <div>
        <h3 class="breadcum-booking"> Edit employees </h3>

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
                <div class="item-detail" v-if="item.show">
                    <h3> 
                        <button style="border-radius: 20px; padding: 3px; color: white; background: blueviolet"
                                @click="editEmployee(item)">Edit employees</button>
                    </h3>
                    <div class="item-row" v-for="emp in item.employees">
                        <span class="item-detail-col1">{{emp.name}}</span>
                        <span class="item-detail-col2">{{emp.price}}$</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Add Edit -->
        <edit-form v-if="showForm" :editItem="editItem"></edit-form>
        <edit-employee-form v-if="showEmployeeForm" :editItem="editItem"></edit-employee-form>

        <!-- Trigger computed on change parent value -->
        <input type="hidden" :value="changeParentServices" name="">
    </div>
</template>

<script>
  import bookingApi from 'Api/booking';
  import serviceApi from 'Api/service';
  import formEdit from 'AdComponents/manage_service/form/job_form.vue';
  import formEditEmployee from 'AdComponents/manage_service/form/edit_employee_job.vue';

    var data = {
        registerBy: 'cJob',
        defaultItem: {
            location_id: '',
            name: '',
            phone: '',
            email: '',
            address: ''
        },
        items: null,
        showForm: false,
        showEmployeeForm: false,
        editItem: null
    }

    var comp = {
        data() { return data; },
        components: {
          'edit-form': formEdit,
          'edit-employee-form': formEditEmployee
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
            editEmployee(item) {
                this.editItem = item;
                this.showEmployeeForm = true;
            },
            remove(item) {
                // confirm delete
                // confirm delete
                swal({
                    title: "Are you sure?",
                    icon: "warning",
                    buttons: true
                }).then(isDone => {
                    if (isDone) {
                        serviceApi.deleteService(item.id).then(res => {
                            if (res.success) {
                                this.items = _.without(this.items, item);
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
            showDetail(item) {
                let tempStatus = item.show;
                _.map(this.items, it => it.show = false);
                item.show = !tempStatus;
            }
        },
        created: function() {
            window.CJob = this;
            this.defaultItem.location_id = this.getComp('manageService').selectedLocation.id;
        },
        computed: {
            changeParentServices() {
                this.items = this.getComp('cService').services;
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