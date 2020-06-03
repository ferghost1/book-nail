<template>
  <div class="11">
    {{ message }}
  </div>
</template>

<script>
  import App1 from './App1.vue';
  export default {
    data() {
      return {
        message: 'Hello World',
      };
    },
    components: {
      app1: App1
    },
    name: 'App'
  };
</script>

<style>
  #app {
    font-size: 18px;
    font-family: 'Roboto', sans-serif;
    color: blue;
  }
</style>