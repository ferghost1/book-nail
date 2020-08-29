<template>
  <div class="modal">
    <div class="overlay" @click="cancelEdit()"></div>
    <div class="detail">
        <h3 class="breadcum-booking"> Edit service </h3>
        <div>
            <div style="margin-top: 5px" v-for="item in tempItem.employees">
                <span class="item-detail-col1" @click="removeEmployee(item)">{{item.name}}</span>
                <span class="item-detail-col2">
                    <input v-model="item.price">
                </span>
            </div>
            <div style="margin-top: 15px">
                <button style="border: green; margin-right: 10px" @click="save()">Save</button>
                <button style="border: red" @click="cancelEdit()">Cancel</button>
            </div>
        </div>
        <div>
            <h3>list employee</h3>
            <div v-for="emp in validEmployees" @click="addEmployee(emp)">{{emp.name}}</div>
        </div>
    </div>


    <!-- Trigger computed on change parent value -->
    <input type="hidden" :value="onChangeParent">
  </div>
</template>

<script>
    import bookingApi from 'Api/booking';
    import serviceApi from 'Api/service';

    var comp = {
        props: {
            editItem: Object 
        },
        data() {return {tempItem: null, }},
        methods:{
            addEmployee(item) {
                this.tempItem.employees.push(item);
            },
            removeEmployee(item) {
                let indexOfItem = this.tempItem.employees.indexOf(item);
                this.tempItem.employees.splice(indexOfItem, 1);
            },
            save() {
                let employees = _.map(this.tempItem.employees, emp => {
                    return {
                        'employee_id': emp.id,
                        'price': emp.price
                    }
                });
                let reqData = {
                    service_id: this.tempItem.id,
                    employees: employees
                }

                serviceApi.saveEmployeeService(reqData).then(resData => {
                    if (resData.success) {
                        this.$parent.editItem.employees = this.tempItem.employees;
                        this.cancelEdit();
                    }
                });
            },
            cancelEdit() {
                this.$parent.showEmployeeForm = false;
            }
        },
        computed: {
            validEmployees() {
                // get the list of not picked employee
                return _.differenceBy(this.getComp('cService').employees, this.tempItem.employees, 'id');
            },
            onChangeParent() {

            }
        },
        created: function() {
            window.formEdit = this;
            this.tempItem = _.cloneDeep(this.editItem);
            this
        }
    };


    export default comp;
</script>

<style scoped>
  .detail {
    background: white;
    padding: 15px
  }
</style>