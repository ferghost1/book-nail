<template>
  <div class="11">
    <button @click="login()" style="display:block; margin: 20px auto">Login FB</button>
  </div>
</template>

<script>
  import userApi from 'Api/user';

  const data = {
    test: 'test Data' 
  };

  var comp = {
    data() { return data},
    methods:{
      login() {
        FB.login((res) => {
          if (res.status == 'connected') {
            userApi.login(this, res.authResponse).then(() => {
              this.$router.push('manage_appointment');
            });
          }
        })
      }
    },
    computed: {

    }
  };


  export default comp;
  export const accessTestMixin1 = {
    getComp: function(context) {
      return _.find(context.$children, (item) => item.$options.name == comp.name);
    },
    getData: function(context) {
      return this.getComp(context) || data;
    },
    setData: function(context, propName, value) {
      let currentComp = this.getData(context);
      currentComp[propName] = value;
    }
  };
</script>

<style>
  #app {
    font-size: 18px;
    font-family: 'Roboto', sans-serif;
    color: blue;
  }
</style>