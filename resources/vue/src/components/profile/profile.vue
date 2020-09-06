<template>
    <div class="contain">
        <h3>Customer info</h3>
        <div class="cus-info">
            <div v-if="!isEditing">
                <div class="item-row">
                    <div class="item-detail-col1">Name</div>
                    <div class="item-detail-col2">{{this.user.name}}</div>
                </div>
                <div class="item-row">
                    <div class="item-detail-col1">Address</div>
                    <div class="item-detail-col2">{{this.user.address}}</div>
                </div>
                <div class="item-row">
                    <div class="item-detail-col1">Phone</div>
                    <div class="item-detail-col2">{{this.user.phone}}</div>
                </div>
                <div class="item-row">
                    <div class="item-detail-col1">Is trusted customer</div>
                    <div class="item-detail-col2">{{this.user.is_trusted ? 'Yes' : 'No'}}</div>
                </div>
                <div class="item-row">
                    <div class="item-detail-col1">Email</div>
                    <div class="item-detail-col2">{{this.user.email}}</div>
                </div>
                <button style="float:right; margin-right: 20px" @click="editUser()">Edit</button>
            </div>
            

            <div class="editBox" v-if="isEditing">
                <div class="errors-box item-row" v-if="!lodash.isEmpty(errors)">
                    <ul>
                        <li v-for="err in errors" class="error-detail">{{err}}</li>     
                    </ul>
                </div>
                <div class="item-row">
                    <div class="item-detail-col1">Name</div>
                    <div class="item-detail-col2"><input type="text" v-model="tempItem.name"></div>
                </div>
                <div class="item-row">
                    <div class="item-detail-col1">Address</div>
                    <div class="item-detail-col2"><input type="text" v-model="tempItem.address"></div>
                </div>
                <div class="item-row">
                    <div class="item-detail-col1">Phone</div>
                    <div class="item-detail-col2"><input type="text" v-model="tempItem.phone"></div>
                </div>
                <div style="text-align: right;">
                    <button style="margin-right: 10px" type="button" @click="isEditing = false">Cancel</button>
                    <button style="margin-right: 10px" type="button" @click="save()">Save</button>
                </div>
            </div>
            

        </div>
    </div>
</template>

<script>
    import userApi from 'Api/user';
  
  export default {
    name: 'profile',
    data() {
      return {
        message: 'test-mixin2',
        errors: [],
        isEditing: false,
        tempItem: {}
      };
    },
    methods: {
        save() {
            userApi.updateCustomerProfile(this.tempItem).then(res => {
                this.errors = [];
                if (res.success) {
                    _.merge(this.user, res.data);
                    this.$notify({type: 'success', text: 'Profile saved'});
                    this.isEditing = false;
                } else {
                    this.errors = res.errors;
                }
            });
        },
        editUser() {
            this.tempItem = _.cloneDeep(this.user);
            this.isEditing = true;
        }
    },
    created: function() {
      window.pfComp = this;
    }
  };
</script>

<style scoped>
    .cus-info {
        margin: 10px auto;
        max-width: 500px;
    }
    #app {
        font-size: 18px;
        font-family: 'Roboto', sans-serif;
        color: blue;
    }
</style>