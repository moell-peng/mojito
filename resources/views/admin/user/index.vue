<template>
  <div>
    <el-form :inline="true" :model="queryParams"  size="mini">
      <el-form-item :label="$t('name')">
        <el-input v-model="queryParams.name"></el-input>
      </el-form-item>
      <el-form-item :label="$t('email')">
        <el-input v-model="queryParams.email"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="requestData" icon="el-icon-search">{{ $t('search') }}</el-button>
        <el-button type="primary" v-if="addPermission"  @click="dialogAddFormVisible = true" icon="el-icon-plus">{{ $t('add') }}</el-button>
      </el-form-item>
    </el-form>
    <el-table
            :data="tableData"
            v-loading="loading"
            border
            style="width: 100%">
      <el-table-column
              prop="name"
              :label="$t('name')">
      </el-table-column>
      <el-table-column
              prop="email"
              :label="$t('email')">
      </el-table-column>
      <el-table-column
              prop="created_at"
              :label="$t('createdAt')">
      </el-table-column>
      <el-table-column
              prop="updated_at"
              :label="$t('updatedAt')">
      </el-table-column>
      <el-table-column
              fixed="right"
              width="300px"
              :label="$t('actions')"
              >
        <template slot-scope="scope">
          <el-button
                  v-if="updatePermission"
                  size="mini"
                  @click="handleEdit(scope.$index, scope.row)">{{ $t('edit') }}</el-button>
          <el-button
                  v-if="assignRolePermission"
                  size="mini"
                  @click="assignRole(scope.row)">{{ $t('assignRole') }}</el-button>
          <el-button
                  v-if="deletePermission"
                  size="mini"
                  type="danger"
                  @click="handleDelete(scope.$index, scope.row)">{{ $t('delete') }}</el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-pagination class="mo-page"
                   @current-change="requestData"
                   :current-page.sync="pagination.currentPage"
                   :page-size="pagination.pageSize"
                   layout="total, prev, pager, next, jumper"
                   :total="pagination.total">
    </el-pagination>

    <el-dialog :title="$t('add')" :visible.sync="dialogAddFormVisible" width="30%">
      <el-form :model="addForm" :rules="addRules" ref="addForm">
        <el-form-item :label="$t('name')" prop="name" :label-width="formLabelWidth">
          <el-input v-model="addForm.name"></el-input>
        </el-form-item>
        <el-form-item :label="$t('email')" prop="email" :label-width="formLabelWidth">
          <el-input v-model="addForm.email"></el-input>
        </el-form-item>
        <el-form-item :label="$t('password')" prop="password" :label-width="formLabelWidth">
          <el-input  type="password" v-model="addForm.password"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogAddFormVisible = false">{{ $t('cancel') }}</el-button>
        <el-button type="primary" @click="handleAddAdminUser">{{ $t('confirm') }}</el-button>
      </div>
    </el-dialog>

    <el-dialog :title="$t('edit')" :visible.sync="dialogEditFormVisible" width="30%">
      <el-form :model="editForm" :rules="editRules" ref="editForm">
        <el-form-item :label="$t('name')" prop="name" :label-width="formLabelWidth">
          <el-input v-model="editForm.name"></el-input>
        </el-form-item>
        <el-form-item :label="$t('password')" prop="password" :label-width="formLabelWidth">
          <el-input  type="password" v-model="editForm.password"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogEditFormVisible = false">{{ $t('cancel') }}</el-button>
        <el-button type="primary" @click="handleEditAdminUser">{{ $t('confirm') }}</el-button>
      </div>
    </el-dialog>

    <user-assign-role
            :user-id="assignRoleParams.id"
            :guard-name="assignRoleParams.guardName"
            :provider="assignRoleParams.provider"
            :dialog-visible.sync="assignRoleParams.dialogVisible"></user-assign-role>
  </div>
</template>

<script>
  import { getAdminUserList, addAdminUser, editAdminUser, deleteAdminUser } from '../../../api/adminUser'
  import { responseDataFormat, tableDefaultData, editSuccess, addSuccess, deleteSuccess } from '../../../libs/tableDataHandle'
  import { hasPermission } from '../../../libs/permission'
  import UserAssignRole from "../../../components/User/AssignRole";
  import { queryParams } from "../../../mixins/queryParams"

  export default {
    name: 'adminUserIndex',
    components: {
      UserAssignRole
    },
    mixins:[queryParams],
    data() {
      return {
        ...tableDefaultData(),
        assignRoleParams: {
          id: 0,
          guardName: 'admin',
          provider: this.$provider,
          dialogVisible: false,
        },
        addForm: {
          name: '',
          email: '',
          password: ''
        },
        editForm: {
          name: ''
        },
        addRules: {
          name: [
            { required: true },
            { min: 3, max: 255 }
          ],
          email: [
            { type: 'email', required: true}
          ],
          password: [
            { required: true },
            { min: 8, max: 32 }
          ]
        },
        editRules: {
          name: [
            { required: true },
            { min: 3, max: 255 }
          ],
          password: [
            { min: 8, max: 32 }
          ]
        }
      }
    },
    methods: {
      assignRole (row) {
        this.assignRoleParams.id = row.id
        this.assignRoleParams.dialogVisible = true
      },
      handleEdit (index, row) {
        this.editForm.name = row.name
        this.nowRowData = { index, row }
        this.dialogEditFormVisible = true
      },
      handleDelete (index, row) {
        deleteAdminUser(row.id).then( response => {
          deleteSuccess(index, this)
          this.requestData()
        })
      },
      requestData () {
        this.loading = true
        getAdminUserList({...this.queryParams, page: this.queryPage}).then( response => {
          responseDataFormat(response, this)
        })
      },
      handleAddAdminUser () {
        this.$refs['addForm'].validate((valid) => {
          if (valid) {
            addAdminUser(this.addForm).then( response => {
              addSuccess(this)
              this.requestData()
            })
          } else {
            return false;
          }
        });
      },
      handleEditAdminUser () {
        this.$refs['editForm'].validate((valid) => {
          if (valid) {
            editAdminUser(this.nowRowData.row.id, this.editForm).then( response => {
              editSuccess(this)
            })
          } else {
            return false;
          }
        });
      }
    },
    computed: {
      updatePermission: function() {
        return hasPermission('admin-user.update')
      },
      addPermission: function() {
        return hasPermission('admin-user.store')
      },
      deletePermission: function() {
        return hasPermission('admin-user.destroy')
      },
      assignRolePermission: function() {
        return hasPermission('admin-user.assign-roles')
      }
    },
    created() {
      this.requestData()
    }
  }
</script>