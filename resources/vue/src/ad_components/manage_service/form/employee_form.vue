<template>
  <div class="modal">
    <div class="overlay" @click="cancelEdit()"></div>
    <div class="detail">
        <h3 class="breadcum-booking"> Edit Employee </h3>
        <div>
            <form @submit.prevent="tempItem.id ? edit() : add()">
                <div class="errors-box item-row" v-if="!lodash.isEmpty(errors)">
                    <ul>
                        <li v-for="err in errors" class="error-detail">{{err}}</li>     
                    </ul>
                </div>
                <div style="margin-top: 5px">
                    <label>Username:
                        <input type="text" required v-model="tempItem.user_name">
                    </label>
                </div>
                <div style="margin-top: 5px">
                    <label>Password:
                        <input type="text" v-model="tempItem.password">
                    </label>
                </div>
                <div style="margin-top: 5px">
                    <label>Name:
                        <input type="text" required v-model="tempItem.name">
                    </label>
                </div>
                <div style="margin-top: 5px">
                    <label>Phone:
                        <input type="text" v-model="tempItem.phone">
                    </label>
                </div>
                <div style="margin-top: 5px">
                    <label>Address:
                        <input type="text" v-model="tempItem.address">
                    </label>
                </div>
                <div style="margin-top: 5px">
                    <div class="item-detail-col1">Activing</div>
                    <div class="item-detail-col2" style="margin-left:10px">
                        <label for="active-yes">Yes</label>
                        <input id="active-yes" type="radio" name="is_active" value="1" 
                                v-model="tempItem.is_active">

                        <label for="active-no">No</label>
                        <input id="active-no" type="radio" name="is_active" value="0" 
                                v-model="tempItem.is_active">
                    </div>
                </div>

                <div style="margin-top: 15px">
                    <button style="border: green; margin-right: 10px">{{tempItem.id ? 'Save' : 'Add'}}</button>
                    <button type="button" style="border: red" @click="cancelEdit()">Cancel</button>
                </div>
            </form>
            
        </div>
    </div>
  </div>
</template>

<script>
    import bookingApi from 'Api/booking';
    import serviceApi from 'Api/service';
    
    var comp = {
        props: {
            editItem: Object 
        },
        data() {return {tempItem: null, errors: []}},
        methods:{
            add() {
                serviceApi.saveEmployee(this.tempItem).then(res => {
                    this.errors = [];
                    if (res.success) {
                        res.data.show = false;
                        this.$parent.items.push(res.data);
                        this.$notify({type: 'success', text: 'Employee was added'});
                        this.cancelEdit();
                    } else {
                        this.errors = res.errors;
                    }
                });
            },
            edit() {
                serviceApi.saveEmployee(this.tempItem).then(res => {
                    this.errors = [];
                    if (res.success) {
                        _.merge(this.$parent.editItem, res.data);
                        this.$notify({type: 'success', text: 'Employee was edit'});
                        this.cancelEdit();
                    } else {
                        this.errors = res.errors;
                    }
                });
            },
            cancelEdit() {
                this.$parent.showForm = false;
            }
        },
        created: function() {
            window.formEdit = this;
            this.tempItem = _.cloneDeep(this.editItem);
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