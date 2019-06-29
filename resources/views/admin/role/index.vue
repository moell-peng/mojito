<template>
  <div>
    <el-form :inline="true" :model="queryParams"  size="mini">
      <el-form-item :label="$t('name')">
        <el-input v-model="queryParams.name"></el-input>
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
              prop="guard_name"
              :label="$t('guardName')">
      </el-table-column>
      <el-table-column
              prop="description"
              :label="$t('description')">
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
          <router-link :to="{ name: 'rolePermission', params: {id: scope.row.id, guardName: scope.row.guard_name}}">
            <el-button
                    v-if="assignPermission"
                    size="mini">{{ $t('assignPermission') }}</el-button>
          </router-link>
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
      <el-form :model="addForm" :rules="rules" ref="addForm">
        <el-form-item :label="$t('name')" prop="name" :label-width="formLabelWidth">
          <el-input v-model="addForm.name"></el-input>
        </el-form-item>
        <el-form-item :label="$t('guardName')" prop="guard_name" :label-width="formLabelWidth">
          <guard-select :nowValue.sync="addForm.guard_name"></guard-select>
        </el-form-item>
        <el-form-item :label="$t('description')" prop="description" :label-width="formLabelWidth">
          <el-input v-model="addForm.description"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogAddFormVisible = false">{{ $t('cancel') }}</el-button>
        <el-button type="primary" @click="handleAddRole">{{ $t('confirm') }}</el-button>
      </div>
    </el-dialog>

    <el-dialog :title="$t('edit')" :visible.sync="dialogEditFormVisible" width="30%">
      <el-form :model="editForm" :rules="rules" ref="editForm">
        <el-form-item :label="$t('name')" prop="name" :label-width="formLabelWidth">
          <el-input v-model="editForm.name"></el-input>
        </el-form-item>
        <el-form-item :label="$t('guardName')" prop="guard_name" :label-width="formLabelWidth">
          <guard-select :nowValue.sync="editForm.guard_name"></guard-select>
        </el-form-item>
        <el-form-item :label="$t('description')" prop="description" :label-width="formLabelWidth">
          <el-input v-model="editForm.description"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogEditFormVisible = false">{{ $t('cancel') }}</el-button>
        <el-button type="primary" @click="handleEditRole">{{ $t('confirm') }}</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
  import { getRoleList, addRole, editRole, deleteRole } from '../../../api/role'
  import { responseDataFormat, tableDefaultData, editSuccess, addSuccess, deleteSuccess } from '../../../libs/tableDataHandle'
  import { hasPermission } from '../../../libs/permission'
  import GuardSelect from '../../../components/Select/Guard'
  import { queryParams } from "../../../mixins/queryParams"

  export default {
    name: 'roleIndex',
    mixins:[queryParams],
    components: {
      GuardSelect
    },
    data() {
      return {
        ...tableDefaultData(),
        addForm: {
          name: '',
          guard_name: '',
          description: ''
        },
        editForm: {
          name: '',
          guard_name: '',
          description: ''
        },
        rules: {
          name: [
            { required: true },
            { min: 1, max: 255 }
          ],
          guard_name: [
            { required: true },
            { min: 1, max: 255 }
          ]
        }
      }
    },
    methods: {
      handleEdit(index, row) {
        this.editForm = {
          name: row.name,
          guard_name: row.guard_name,
          description: row.description
        }
        this.nowRowData = { index, row }
        this.dialogEditFormVisible = true
      },
      handleDelete(index, row) {
        deleteRole(row.id).then( response => {
          deleteSuccess(index, this)
          this.requestData()
        })
      },
      requestData() {
        this.loading = true
        getRoleList({...this.queryParams, page: this.queryPage}).then( response => {
          responseDataFormat(response, this)
        })
      },
      handleAddRole() {
        this.$refs['addForm'].validate((valid) => {
          if (valid) {
            addRole(this.addForm).then( response => {
              addSuccess(this)
              this.requestData()
            })
          } else {
            return false;
          }
        });
      },
      handleEditRole() {
        this.$refs['editForm'].validate((valid) => {
          if (valid) {
            editRole(this.nowRowData.row.id, this.editForm).then( response => {
              editSuccess(this)
            })
          } else {
            return false;
          }
        });
      }
    },
    computed: {
      updatePermission: function () {
        return hasPermission('role.update')
      },
      addPermission: function () {
        return hasPermission('role.store')
      },
      deletePermission: function () {
        return hasPermission('role.destroy')
      },
      assignPermission: function () {
        return hasPermission('role.assign-permissions')
      }
    },
    created() {
      this.requestData()
    }
  }
</script>