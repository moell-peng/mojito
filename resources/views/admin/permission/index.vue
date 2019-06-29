<template>
  <div>
    <el-form :inline="true" :model="queryParams"  size="mini">
      <el-form-item :label="$t('name')">
        <el-input v-model="queryParams.name"></el-input>
      </el-form-item>
      <el-form-item :label="$t('permissionGroup')">
        <permission-group-select :nowValue.sync="queryParams.pg_id"></permission-group-select>
      </el-form-item>
      <el-form-item :label="$t('guardName')">
        <guard-select :nowValue.sync="queryParams.guard_name"></guard-select>
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
              prop="display_name"
              :label="$t('displayName')">
      </el-table-column>
      <el-table-column
              prop="guard_name"
              :label="$t('guardName')">
      </el-table-column>
      <el-table-column
              prop="group.name"
              :label="$t('permissionGroup')">
      </el-table-column>
      <el-table-column
              prop="icon"
              :label="$t('icon')">
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

    <el-dialog :title="$t('add')" :visible.sync="dialogAddFormVisible" width="40%">
      <el-form :model="addForm" :rules="rules" ref="addForm">
        <el-row>
          <el-col :span="12">
            <el-form-item :label="$t('name')" prop="name" :label-width="formLabelWidth">
              <el-input v-model="addForm.name"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item :label="$t('displayName')" prop="display_name" :label-width="formLabelWidth">
              <el-input v-model="addForm.display_name"></el-input>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item :label="$t('guardName')" prop="guard_name" :label-width="formLabelWidth">
              <guard-select :nowValue.sync="addForm.guard_name"></guard-select>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item :label="$t('permissionGroup')" prop="pg_id" :label-width="formLabelWidth">
              <permission-group-select :nowValue.sync="addForm.pg_id"></permission-group-select>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item :label="$t('icon')" prop="icon" :label-width="formLabelWidth">
              <el-input v-model="addForm.icon"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item :label="$t('sequence')" prop="sequence" :label-width="formLabelWidth">
              <el-input v-model="addForm.sequence"></el-input>
            </el-form-item>
          </el-col>
        </el-row>
        <el-form-item :label="$t('description')" prop="description" :label-width="formLabelWidth">
          <el-input v-model="addForm.description"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogAddFormVisible = false">{{ $t('cancel') }}</el-button>
        <el-button type="primary" @click="handleAddPermission">{{ $t('confirm') }}</el-button>
      </div>
    </el-dialog>

    <el-dialog :title="$t('edit')" :visible.sync="dialogEditFormVisible" width="40%">
      <el-form :model="editForm" :rules="rules" ref="editForm">
        <el-row>
          <el-col :span="12">
            <el-form-item :label="$t('name')" prop="name" :label-width="formLabelWidth">
              <el-input v-model="editForm.name"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item :label="$t('displayName')" prop="display_name" :label-width="formLabelWidth">
              <el-input v-model="editForm.display_name"></el-input>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item :label="$t('guardName')" prop="guard_name" :label-width="formLabelWidth">
              <guard-select :nowValue.sync="editForm.guard_name"></guard-select>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item :label="$t('permissionGroup')" prop="pg_id" :label-width="formLabelWidth">
              <permission-group-select :nowValue.sync="editForm.pg_id"></permission-group-select>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item :label="$t('icon')" prop="icon" :label-width="formLabelWidth">
              <el-input v-model="editForm.icon"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item :label="$t('sequence')" prop="sequence" :label-width="formLabelWidth">
              <el-input  v-model="editForm.sequence"></el-input>
            </el-form-item>
          </el-col>
        </el-row>
        <el-form-item :label="$t('description')" prop="description" :label-width="formLabelWidth">
          <el-input v-model="editForm.description"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogEditFormVisible = false">{{ $t('cancel') }}</el-button>
        <el-button type="primary" @click="handleEditPermission">{{ $t('confirm') }}</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
  import { getPermissionList, addPermission, editPermission, deletePermission } from '../../../api/permission'
  import { responseDataFormat, tableDefaultData, editSuccess, addSuccess, deleteSuccess } from '../../../libs/tableDataHandle'
  import GuardSelect from '../../../components/Select/Guard'
  import PermissionGroupSelect from "../../../components/Select/PermissionGroup";
  import { hasPermission } from "../../../libs/permission"
  import { queryParams } from "../../../mixins/queryParams"

  export default {
    name: 'permissionIndex',
    components: {
      PermissionGroupSelect, GuardSelect
    },
    mixins:[queryParams],
    data() {
      return {
        ...tableDefaultData(),
        addForm: {
          name: '',
          guard_name: '',
          description: '',
          sequence:0,
        },
        editForm: {
          name: '',
          guard_name: '',
          description: '',
        },
        rules: {
          name: [
            { required: true },
            { min: 1, max: 255 }
          ],
          display_name: [
            { required: true },
            { min: 1, max: 255 }
          ],
          guard_name: [
            { required: true },
            { min: 1, max: 255 }
          ],
          pg_id: [
            { required: true, type: 'number' }
          ]
        }
      }
    },
    methods: {
      handleEdit(index, row) {
        this.editForm = row
        this.nowRowData = { index, row }
        this.dialogEditFormVisible = true
      },
      handleDelete(index, row) {
        deletePermission(row.id).then( response => {
          deleteSuccess(index, this)
          this.requestData()
        })
      },
      requestData() {
        this.loading = true
        getPermissionList({...this.queryParams, page: this.queryPage}).then( response => {
          responseDataFormat(response, this)
        })
      },
      handleAddPermission() {
        this.$refs['addForm'].validate((valid) => {
          if (valid) {
            addPermission(this.addForm).then( response => {
              addSuccess(this)
              this.requestData()
            })
          } else {
            return false;
          }
        });
      },
      handleEditPermission() {
        this.$refs['editForm'].validate((valid) => {
          if (valid) {
            editPermission(this.nowRowData.row.id, this.editForm).then( response => {
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
        return hasPermission('permission.update')
      },
      addPermission: function() {
        return hasPermission('permission.store')
      },
      deletePermission: function() {
        return hasPermission('permission.destroy')
      }
    },
    created() {
      this.requestData()
    }
  }
</script>