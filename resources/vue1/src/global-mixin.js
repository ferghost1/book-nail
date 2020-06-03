export default {
    data() {
      return {
        compList: {},
        lodash: _,
        user: {},
        // user: {
        //   id: null,
        //   name: null,
        //   phone: null,
        //   address: null,
        //   is_trusted: null,
        //   fb_id: null
        // }
      }
    },
    methods: {
      getComp(compName) {
        return this.compList[compName];
      }
    },
    created() {
        this.compList = this.$root.compList;
        this.user = this.$root.user;
        // register component to global by the name declare in component
        if (this.registerBy && !this.compList[this.registerBy]) {
            this.compList[this.registerBy] = this;
        }
    },
    beforeDestroy() {
        // remove comp from compList when the component not exist anymore
        if (this.registerBy && this.compList[this.registerBy]) {
            delete this.compList[this.registerBy];
        }
    }
 }