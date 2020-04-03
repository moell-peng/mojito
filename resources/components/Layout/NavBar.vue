<template>
  <el-aside :width="isCollapse ? '65px' : '250px'">
    <el-menu
            class="el-menu-vertical"
            :collapse="isCollapse"
            :router="true"
            background-color="#263238"
            text-color="#afb5bd"
            :collapse-transition="false"
            active-text-color="#ffffff">
      <div class="logo">
        <div v-if="!isCollapse" class="normal">
          {{ fullName }}
        </div>
        <div v-else class="mini">
          {{ abbrName }}
        </div>
      </div>
      <nav-item v-for="item in this.menuItems" :item="item" :key="item.id"></nav-item>
    </el-menu>
  </el-aside>
</template>

<script>
  import NavItem from './NavItem'
  import { myMenu } from '../../api/menu'
  import config from '../../config'

  export default {
    name: 'NavBar',
    components: {
      NavItem
    },
    props: {
      isCollapse: Boolean
    },
    data () {
      return {
        menuItems: []
      }
    },
    created () {
      myMenu().then(response => {
        this.menuItems = response.data.data
      })
    },
    methods: {
    },
    computed: {
      fullName: function () {
         return config[this.$provider].hasOwnProperty('appName') ? config[this.$provider].appName.fullName : 'Mojito Admin'
      },
      abbrName: function () {
        return config[this.$provider].hasOwnProperty('appName') ? config[this.$provider].appName.abbrName : 'Mojito'
      }
    }
  }
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
  .el-menu-vertical {
    height:100%
  }

  .logo {
    height:60px;
    line-height:60px;
    color:#42b983;
    .normal {
      padding-left:20px;
    }
    .mini {
      text-align:center;
    }
  }
</style>
