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
              prop="created_at"
              :label="$t('createdAt')">
      </el-table-column>
      <el-table-column
              prop="updated_at"
              :label="$t('updatedAt')">
      </el-table-column>
      <el-table-column
              fixed="right"
              :label="$t('actions')"
              >
        <template slot-scope="scope">
          <el-button
                  v-if="updatePermission"
                  size="mini"
                  @click="handleEdit(scope.$index, scope.row)">{{ $t('edit') }}</el-button>
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
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogAddFormVisible = false">{{ $t('cancel') }}</el-button>
        <el-button type="primary" @click="handleAddPermissionGroup">{{ $t('confirm') }}</el-button>
      </div>
    </el-dialog>

    <el-dialog :title="$t('edit')" :visible.sync="dialogEditFormVisible" width="30%">
      <el-form :model="editForm" :rules="rules" ref="editForm">
        <el-form-item :label="$t('name')" prop="name" :label-width="formLabelWidth">
          <el-input v-model="editForm.name"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogEditFormVisible = false">{{ $t('cancel') }}</el-button>
        <el-button type="primary" @click="handleEditPermissionGroup">{{ $t('confirm') }}</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
  import { getPermissionGroupList, addPermissionGroup, editPermissionGroup, deletePermissionGroup } from '../../../api/permissionGroup'
  import { responseDataFormat, tableDefaultData, editSuccess, addSuccess, deleteSuccess } from '../../../libs/tableDataHandle'
  import { hasPermission } from '../../../libs/permission'
  import notify from '../../../libs/notify'
  import { queryParams } from "../../../mixins/queryParams"

  export default {
    name: 'permissionGroupIndex',
    mixins:[queryParams],
    data() {
      return {
        ...tableDefaultData(),
        addForm: {
          name: '',
        },
        editForm: {
          name: '',
        },
        rules: {
          name: [
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
        }
        this.nowRowData = { index, row }
        this.dialogEditFormVisible = true
      },
      handleDelete(index, row) {
        deletePermissionGroup(row.id).then( response => {
          deleteSuccess(index, this)
          this.requestData()
        })
      },
      requestData() {
        this.loading = true
        getPermissionGroupList({...this.queryParams, page: this.queryPage}).then( response => {
          responseDataFormat(response, this)
        })
      },
      handleAddPermissionGroup() {
        this.$refs['addForm'].validate((valid) => {
          if (valid) {
            addPermissionGroup(this.addForm).then( response => {
              addSuccess(this)
              this.requestData()
            })
          } else {
            return false;
          }
        });
      },
      handleEditPermissionGroup() {
        this.$refs['editForm'].validate((valid) => {
          if (valid) {
            editPermissionGroup(this.nowRowData.row.id, this.editForm).then( response => {
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
        return hasPermission('permission-group.update')
      },
      addPermission: function() {
        return hasPermission('permission-group.store')
      },
      deletePermission: function() {
        return hasPermission('permission-group.destroy')
      }
    },
    created() {
      this.requestData()
    }
  }
</script>