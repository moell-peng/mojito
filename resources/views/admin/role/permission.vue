<template>
  <div v-loading="loading">
    <el-card v-for="group in guardNameByPermissions" :key="group.id" style="margin-bottom:20px;">
      <div slot="header">
        <div style="float:right">
          <el-radio v-model="radio[group.id]" @change="change(group.id)" :label="true">{{ $t('selectAll') }}</el-radio>
          <el-radio v-model="radio[group.id]" @change="change(group.id)" :label="false">{{ $t('unselectAll') }}</el-radio>
        </div>
        <span class="permission-group">{{ group.name }}</span>
      </div>
      <el-row>
        <el-checkbox-group v-model="rolePermissions">
          <el-col
                  class="permission-item"
                  :span="6"
                  v-for="permission in group.permission"
                  :key="permission.id"><el-checkbox :label="permission.name">{{ permission.display_name}}</el-checkbox>
          </el-col>
        </el-checkbox-group>
      </el-row>
    </el-card>
    <el-col style="text-align: center">
      <el-button type="primary" @click="assignPermission" >{{ $t('confirm') }}</el-button>
    </el-col>
  </div>
</template>
<script>
  import { guardNameForPermissions } from '../../../api/permissionGroup'
  import { rolePermission, roleAssignPermission } from '../../../api/role'
  import notify from '../../../libs/notify'

  export default {
    name: 'rolePermission',

    data() {
      return {
        rolePermissions: [],
        guardNameByPermissions: [],
        groupPermissions: {},
        radio: {}
      }
    },

    methods: {
      assignPermission () {
        roleAssignPermission(this.$route.params.id, this.rolePermissions).then( response => {
          notify.editSuccess(this)
        })
      },
      change (groupId) {
        this.groupPermissions[groupId].forEach( permission => {
          let index = this.rolePermissions.indexOf(permission)

          if (!this.radio[groupId] && index >= 0) {
            this.rolePermissions.splice(index, 1)
          } else if (this.radio[groupId] && index === -1) {
            this.rolePermissions.push(permission)
          }
        })
      },
      loadData () {

        this.loading = true
        this.rolePermissions = []
        this.guardNameByPermissions = []
        this.groupPermissions = {}
        this.radio = {}

        let permissionGroups = guardNameForPermissions(this.$route.params.guardName)
        let rolePermissions = rolePermission(this.$route.params.id)

        Promise.all([permissionGroups, rolePermissions]).then( result => {
          this.guardNameByPermissions  = result[0].data.data

          result[0].data.data.forEach(item => {
            if (!this.groupPermissions.hasOwnProperty(item.id)) {
              this.groupPermissions[item.id] = []
            }
            item.permission.forEach (permission => {
              this.groupPermissions[item.id].push(permission.name)
            })
          })

          result[1].data.data.forEach(item => {
            this.rolePermissions.push(item.name)
          })
        })

        this.loading = false
      }
    },
    watch: {
      $route(route) {
        if (route.name === 'rolePermission') {
          this.loadData()
        }
      }
    },
    created() {
      this.loadData()
    }
  }
</script>
<style rel="stylesheet/scss" lang="scss" scoped>
  .permission-item {
    margin-top: 15px;
  }

  .permission-group {
    font-size: 15px;
  }
</style>