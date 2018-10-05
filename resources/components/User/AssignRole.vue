<template>
  <el-dialog :title="$t('assignRole')" :visible.sync="visible" width="40%">
    <el-row>
      <el-checkbox-group v-model="userRoles">
        <el-col
                class="item"
                :span="8"
                v-for="role in roles"
                :key="role"><el-checkbox :label="role">{{ role }}</el-checkbox>
        </el-col>
      </el-checkbox-group>
    </el-row>
    <div slot="footer" class="dialog-footer">
      <el-button @click="visible = false">{{ $t('cancel') }}</el-button>
      <el-button type="primary" @click="assignRole">{{ $t('confirm') }}</el-button>
    </div>
  </el-dialog>
</template>
<script>
  import { getUserRoles, assginRoles  } from '../../api/adminUser'
  import { guardNameRoles  } from '../../api/role'
  import notify from '../../libs/notify'
  export default {
    name: 'UserAssignRole',
    props: ['userId', 'guardName', 'dialogVisible', 'provider'],
    data() {
      return {
        visible: this.dialogVisible,
        roles: [],
        userRoles: []
      }
    },
    methods: {
      assignRole () {
        assginRoles(this.userId, this.provider, this.userRoles).then( response => {
          this.visible = false
          notify.editSuccess(this)
        })
      }
    },
    watch: {
      dialogVisible (newValue) {
        this.roles = []
        this.userRoles = []
        this.visible = newValue
        if (newValue) {
          let guardRoles = guardNameRoles(this.guardName)
          let userRoles = getUserRoles(this.userId, this.provider)

          Promise.all([guardRoles, userRoles]).then( result => {
            let roles = []
            result[0].data.data.forEach( role => {
              roles.push(role.name)
            })

            let userRoles = []
            result[1].data.data.forEach( role => {
              userRoles.push(role.name)
            })

            this.roles = roles
            this.userRoles = userRoles
          }).catch( error => {
            this.visible = false
          })
        }
      },
      visible (newValue) {
        this.$emit("update:dialogVisible", newValue);
      }
    }
  }
</script>
<style rel="stylesheet/scss" lang="scss" scoped>
  .item {
    margin-bottom: 15px;
  }
</style>