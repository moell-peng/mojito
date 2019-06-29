
export const queryParams = {
  data() {
    return {
      queryParamsChange: false
    }
  },
  methods: {

  },
  watch: {
    queryParams:{
      handler:function () {
        this.queryParamsChange = true
      },
      deep:true
    }
  },
  computed: {
    queryPage: function() {
      return this.queryParamsChange ? 1 : this.pagination.currentPage
    }
  }
}