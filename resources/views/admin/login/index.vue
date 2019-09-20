<template>
  <div id="login">
    <el-form :model="ruleForm" status-icon :rules="rules" ref="ruleForm" label-width="100px" class="login-container">
      <h2>{{$t('login.title')}}</h2>
      <el-form-item label="Username" prop="username">
        <el-input  v-model="ruleForm.username" auto-complete="off"></el-input>
      </el-form-item>
      <el-form-item label="Password" prop="password">
        <el-input type="Password" v-model="ruleForm.password" auto-complete="off"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" plain @click="submitForm('ruleForm')">Submit</el-button>
        <el-button plain @click="resetForm('ruleForm')">Reset</el-button>

        <el-select>

        </el-select>
      </el-form-item>
    </el-form>
  </div>
</template>
<script>
  import { mapActions } from 'vuex'
  import i18n from '@/lang'
  export default {
    data() {
      return {
        lang: i18n,
        ruleForm: {
          username: 'admin@admin.com',
          password: 'secret'
        },
        rules: {
          username: [
            { required: true, trigger: 'blur' }
          ],
          password: [
            { required: true, trigger: 'blur' }
          ]
        }
      };
    },
    methods: {
      ...mapActions([
        'loginHandle'
      ]),
      submitForm(formName) {
        this.$refs[formName].validate((valid) => {
          if (valid) {
            this.loginHandle({
              ...this.ruleForm,
              ...this.$config[this.$provider].authorize,
              provider: this.$provider
            }).then(result => {
              this.$router.push({
                name: 'adminMain'
              })
            })
          }
        });
      },
      resetForm(formName) {
        this.$refs[formName].resetFields();
      }
    },
    mounted() {
      // this.watermark('我是水印的文案')
    },
    beforeDestroy() {
      // this.watermark()
      // console.log(this)
    }
  }
</script>
<style scoped>
  h2 {
    text-align: center;
    color: #42b983;
  }
  #login {
    height:100%;
  }
  .login-container {
    width: 350px;
    padding: 20px;
    background: #fff;
    position: absolute;
    top:50%;
    left:50%;
    margin-top:-200px;
    margin-left:-195px;
    border: 1px solid #eaeaea;
    -webkit-border-radius: 5px;
    border-radius: 5px;
    -moz-border-radius: 5px;
  }
</style>