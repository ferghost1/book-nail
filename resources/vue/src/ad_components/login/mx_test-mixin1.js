import comp from 'Components/test-mixin1/test-mixin1.vue'; 
import Mixin1 from 'Components/test-mixin1/test-mixin1.vue';
console.log(comp);
console.log(Mixin1);
window.Mixin1 = Mixin1;
export default {
    methods: {
      getTestMxin1: function() {
        return this.$root;
      },
      getDataTestMxin1: function(propName) {
        return _.cloneDeep(this.$root[propName]);
      },
      setDataTestMxin1: function(propName, value) {
        this.$root[propName] = value;
      },
      callDataTestMxin1: function(methodName) {
        return _.cloneDeep(this.$root[methodName]());
      }
    }
 }