<template>
  <el-header>
    <el-row class="header-menu">
      <el-col :span="1" class="open-menu">
        <i class="el-icon-menu" @click="menuOpenOrClose"></i>
      </el-col>
      <el-col :span="15">
        <el-breadcrumb separator="/">
          <el-breadcrumb-item v-for="bc in breadcrumb" :to="{ path: bc.path }" :key="bc.path"> {{ $t(`meta.title.${bc.meta.title}`) }}</el-breadcrumb-item>
        </el-breadcrumb>
      </el-col>
      <el-col :span="8">
        <div class="avatar">
          <el-dropdown>
            <el-button :plain="true">
              <img src="https://moell.cn/uploads/avatar/7be338418efc00ab728281b923653dc4.jpg" width="30" height="30" style="border-radius:30px">
              <i class="el-icon-arrow-down el-icon--right"></i>
            </el-button>
            <el-dropdown-menu slot="dropdown">
              <a href="https://github.com/moell-peng/mojito" target="_blank">
                <el-dropdown-item>
                  Github
                </el-dropdown-item>
              </a>
              <el-dropdown-item @click.native="logout">Logout</el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </div>
        <!--<div class="lang">
          <el-dropdown>
            <span class="el-dropdown-link">
              语言<i class="el-icon-arrow-down el-icon&#45;&#45;right"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item>中文简体</el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </div>-->
      </el-col>
    </el-row>
  </el-header>
</template>

<script>
  import { mapActions } from 'vuex'
  import config from '../../config'

  export default {
    name: 'Header',
    props: {
      isCollapse: Boolean
    },
    methods: {
      ...mapActions([
        'logoutHandle'
      ]),
      menuOpenOrClose: function (event) {
        this.$emit('menu', !this.isCollapse)
      },
      logout ()  {
        this.logoutHandle(this.$provider).then(this.$router.push({
          name: config[this.$provider].loginRouteName
        }))
      }
    },
    computed: {
      breadcrumb () {
        return this.$store.getters.breadcrumb
      }
    }
  }
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
  .el-header {
    border-bottom: 1px solid #e6e6e6;
    height: 60px;
    line-height:60px;
  }
  .el-button {
    border:none;
  }
  .avatar {
    float:right;
  }
  .lang {
    float:right;
    width:50px;
  }
  .open-menu {
    cursor:pointer;
  }
  .el-breadcrumb {
    line-height:60px;
  }
  a {
    text-decoration:none;
  }
</style>