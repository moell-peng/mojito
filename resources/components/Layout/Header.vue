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
              <i class="el-icon-user-solid"></i>
              <i class="el-icon-arrow-down el-icon--right"></i>
            </el-button>
            <el-dropdown-menu slot="dropdown">
              <a href="https://github.com/moell-peng/mojito" target="_blank" v-if="showAuthorGitHubUrl">
                <el-dropdown-item>
                  Github
                </el-dropdown-item>
              </a>
              <el-dropdown-item @click.native="openDialogChangePasswordForm">{{ $t('changePassword') }} </el-dropdown-item>
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
    <el-dialog :title="$t('changePassword')" :visible.sync="dialogChangePasswordFormVisible" width="30%">
      <el-form :model="changePasswordForm" :rules="rules" ref="changePasswordForm">
        <el-form-item :label="$t('oldPassword')" prop="old_password" :label-width="formLabelWidth">
          <el-input v-model="changePasswordForm.old_password" type="password" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item :label="$t('password')" prop="password" :label-width="formLabelWidth">
          <el-input v-model="changePasswordForm.password" type="password" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item :label="$t('confirmPassword')" prop="password_confirmation" :label-width="formLabelWidth">
          <el-input v-model="changePasswordForm.password_confirmation" type="password" autocomplete="off"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogChangePasswordFormVisible = false">{{ $t('cancel') }}</el-button>
        <el-button type="primary" @click="handleChangePassword">{{ $t('confirm') }}</el-button>
      </div>
    </el-dialog>
  </el-header>
</template>

<script>
  import { mapActions } from 'vuex'
  import config from '../../config'
  import { changePassword } from '../../api/changePassword'
  import notify from '../../libs/notify'

  export default {
    name: 'Header',
    props: {
      isCollapse: Boolean
    },
    data() {
      let confirmPassword = (rule, value, callback) => {
        if (value === '') {
          callback(new Error('Please enter your password again'))
        } else if (value !== this.changePasswordForm.password) {
          callback(new Error('Inconsistent password entered twice'))
        } else {
          callback()
        }
      }
      return {
        formLabelWidth: '150px',
        dialogChangePasswordFormVisible: false,
        changePasswordForm:{
          old_password: null,
          password: null,
          password_confirmation: null
        },
        rules: {
          old_password: [
            { required: true },
            { min: 8, max: 32 }
          ],
          password: [
            { required: true },
            { min: 8, max: 32 }
          ],
          password_confirmation: [
            { required: true },
            { validator: confirmPassword },
            { min: 8, max: 32 }
          ]
        }
      }
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
      },
      openDialogChangePasswordForm () {
        this.dialogChangePasswordFormVisible = true
      },
      handleChangePassword () {
        this.$refs['changePasswordForm'].validate((valid) => {
          if (valid) {
            changePassword(this.changePasswordForm).then( response => {
              notify.editSuccess(this)
              this.dialogChangePasswordFormVisible = false
              this.$refs['changePasswordForm'].resetFields()
            })
          } else {
            return false;
          }
        });
      }
    },
    computed: {
      breadcrumb () {
        return this.$store.getters.breadcrumb
      },
      showAuthorGitHubUrl () {
        return config.showAuthorGitHubUrl
      },
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