<template>
  <div class="modal">
    <div class="overlay" @click="cancelEdit()"></div>
    <div class="detail">
        <h3 class="breadcum-booking"> Edit service </h3>
        <div>
            <div style="margin-top: 5px">
                <input type="text" placeholder="Service name" v-model="tempItem.name">
            </div>
            <div style="margin-top: 15px">
                <button style="border: green; margin-right: 10px" v-if="tempItem.id" @click="edit()">Save</button>
                <button style="border: green; margin-right: 10px" v-else @click="add()">Add</button>
                <button style="border: red" @click="cancelEdit()">Cancel</button>
            </div>
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
        data() {return {tempItem: null}},
        methods:{
            add() {
                serviceApi.saveService(this.tempItem).then(res => {
                    if (res.success) {
                        res.data.show = false;
                        res.data.employees = [];
                        this.$parent.items.push(res.data);
                        this.cancelEdit();
                    } else {
                        swal('Something went wrong');
                    }
                });
            },
            edit() {
                serviceApi.saveService(this.tempItem).then(res => {
                    if (res.success) {
                        _.merge(this.$parent.editItem, res.data);
                        this.cancelEdit();
                    } else {
                        swal('Something went wrong');
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