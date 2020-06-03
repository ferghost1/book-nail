<template>
  <div class="11">
    <button @click="changeThu()"></button>
  </div>
</template>

<script>
  // import {accessTestMixin1} from 'Components/test-mixin1/test-mixin1.vue';
  
  export default {
    name: 'profile',
    data() {
      return {
        message: 'test-mixin2',
      };
    },
    methods: {
      changeThu: function() {
        console.log(accessTestMixin1.getData(this));
      }
    },
    created: function() {
      window.testCommp = this;
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