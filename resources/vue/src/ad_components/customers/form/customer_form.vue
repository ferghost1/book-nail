<template>
  <div class="modal">
    <div class="overlay" @click="cancelEdit()"></div>
    <div class="detail">
        <h3 class="breadcum-booking"> Edit Employee </h3>
        <div>
            <div style="margin-top: 5px">
                <input type="text" placeholder="Email" v-model="tempItem.email">
            </div>
            <div style="margin-top: 5px">
                <input type="text" placeholder="Name" v-model="tempItem.name">
            </div>
            <div style="margin-top: 5px">
                <input type="text" placeholder="Phone" v-model="tempItem.phone">
            </div>
            <div style="margin-top: 5px">
                <input type="text" placeholder="Address" v-model="tempItem.address">
            </div>
            <div style="margin-top: 5px">
                <div class="item-detail-col1">Is Blocked</div>
                <div class="item-detail-col2" style="margin-left:10px">
                    <label for="active-yes">Yes</label>
                    <input id="active-yes" type="radio" name="is_active" :value="0" 
                            v-model="tempItem.is_active">

                    <label for="active-no">No</label>
                    <input id="active-no" type="radio" name="is_active" :value="1" 
                            v-model="tempItem.is_active">
                </div>
            </div>
            <div style="margin-top: 5px">
                <div class="item-detail-col1">Is Trusted</div>
                <div class="item-detail-col2" style="margin-left:10px">
                    <label for="active-yes">Yes</label>
                    <input id="active-yes" type="radio" :value="1" 
                            v-model="tempItem.is_trusted">

                    <label for="active-no">No</label>
                    <input id="active-no" type="radio" :value="0" 
                            v-model="tempItem.is_trusted">
                </div>
            </div>
            <div style="margin-top: 15px">
                <button style="border: green; margin-right: 10px" @click="edit()">Save</button>
                <button style="border: red" @click="cancelEdit()">Cancel</button>
            </div>
        </div>
    </div>
  </div>
</template>

<script>
    import bookingApi from 'Api/booking';
    import serviceApi from 'Api/service';
    import adminApi from 'Api/admin';
    
    var comp = {
        props: {
            editItem: Object 
        },
        data() {return {tempItem: null}},
        methods:{
            edit() {
                adminApi.saveCustomer(this.tempItem).then(res => {
                    if (res.success) {
                        _.merge(this.$parent.editItem, res.data);
                        this.cancelEdit();
                    } else {
                        swal('Something went wrong');
                    }
                });
                console.log(this.tempItem);
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