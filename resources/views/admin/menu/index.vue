<template>
  <div>
    <el-form :inline="true" :model="queryParams"  size="mini">
      <el-form-item :label="$t('guardName')">
        <guard-select :nowValue.sync="queryParams.guard_name"></guard-select>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="requestData"  icon="el-icon-search">{{ $t('search') }}</el-button>
        <el-button type="primary" v-if="addPermission"  @click="dialogAddFormVisible = true" icon="el-icon-plus">{{ $t('add') }}</el-button>
      </el-form-item>
    </el-form>

    <el-table
            :data="tableListData"
            v-loading="loading"
            row-key="id"
            :tree-props="{children: 'children', hasChildren: 'hasChildren'}"
            border stripe
            class="init_table">
      <el-table-column
              :label="$t('name')"
              min-width="200"
              prop="name"
              show-overflow-tooltip
              align="left">
      </el-table-column>
      <el-table-column
              prop="uri"
              label="Router">
      </el-table-column>
      <el-table-column
              prop="permission_name"
              :label="$t('permission')">
      </el-table-column>
      <el-table-column
              prop="sequence"
              :label="$t('sequence')">
      </el-table-column>
      <el-table-column
              align="center"
              :label="$t('icon')">
        <template slot-scope="scope">
          <i :class="scope.row.icon"></i>
        </template>
      </el-table-column>

      <el-table-column
              align="center"
              :label="$t('actions')">
        <template slot-scope="scope">
          <el-button
                  v-if="updatePermission"
                  size="mini"
                  @click="handleEdit(scope.$index, scope.row)">{{ $t('edit') }}</el-button>
          <el-button
                  v-if="deletePermission"
                  type="danger"
                  size="mini"
                  @click="handleDelete(scope.$index, scope.row)">{{ $t('delete') }}</el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog :title="$t('add')" :visible.sync="dialogAddFormVisible" width="40%">
      <el-form :model="addForm" :rules="rules" ref="addForm">
        <el-form-item :label="$t('name')" prop="name" :label-width="formLabelWidth">
          <el-input v-model="addForm.name"></el-input>
        </el-form-item>
        <el-form-item :label="$t('router')" prop="uri" :label-width="formLabelWidth">
          <el-input v-model="addForm.uri"></el-input>
        </el-form-item>
        <el-form-item :label="$t('guardName')" prop="guard_name" :label-width="formLabelWidth">
          <guard-select :nowValue.sync="addForm.guard_name"></guard-select>
        </el-form-item>
        <el-form-item :label="$t('parentMenu')" prop="parent_id" :label-width="formLabelWidth">
          <menu-cascader :menu-id.sync="addForm.parent_id" :guard-name="addForm.guard_name"></menu-cascader>
        </el-form-item>
        <el-form-item :label="$t('permission')" prop="permission_name" :label-width="formLabelWidth">
          <el-input v-model="addForm.permission_name"></el-input>
        </el-form-item>
        <el-form-item :label="$t('icon')" prop="icon" :label-width="formLabelWidth">
          <el-input v-model="addForm.icon"></el-input>
        </el-form-item>
        <el-form-item :label="$t('sequence')" prop="sequence" :label-width="formLabelWidth">
          <el-input v-model.number="addForm.sequence"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogAddFormVisible = false">{{ $t('cancel') }}</el-button>
        <el-button type="primary" @click="handleAddMenu">{{ $t('confirm') }}</el-button>
      </div>
    </el-dialog>

    <el-dialog :title="$t('edit')" :visible.sync="dialogEditFormVisible" width="40%">
      <el-form :model="editForm" :rules="rules" ref="editForm">
        <el-form-item :label="$t('name')" prop="name" :label-width="formLabelWidth">
          <el-input v-model="editForm.name"></el-input>
        </el-form-item>
        <el-form-item :label="$t('router')" prop="uri" :label-width="formLabelWidth">
          <el-input v-model="editForm.uri"></el-input>
        </el-form-item>
        <el-form-item :label="$t('guardName')" prop="guard_name" :label-width="formLabelWidth">
          <guard-select :nowValue.sync="editForm.guard_name"></guard-select>
        </el-form-item>
        <el-form-item :label="$t('parentMenu')" prop="parent_id" :label-width="formLabelWidth">
          <menu-cascader :menu-id.sync="editForm.parent_id" :guard-name="editForm.guard_name"></menu-cascader>
        </el-form-item>
        <el-form-item :label="$t('permission')" prop="permission_name" :label-width="formLabelWidth">
          <el-input v-model="editForm.permission_name"></el-input>
        </el-form-item>
        <el-form-item :label="$t('icon')" prop="icon" :label-width="formLabelWidth">
          <el-input v-model="editForm.icon"></el-input>
        </el-form-item>
        <el-form-item :label="$t('sequence')" prop="sequence" :label-width="formLabelWidth">
          <el-input v-model.number="editForm.sequence"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogEditFormVisible = false">{{ $t('cancel') }}</el-button>
        <el-button type="primary" @click="handleEditMenu">{{ $t('confirm') }}</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
  import GuardSelect from '../../../components/Select/Guard'
  import { getMenuList, addMenu, editMenu, deleteMenu } from '../../../api/menu'
  import { tableDefaultData, editSuccess, addSuccess, deleteSuccess } from '../../../libs/tableDataHandle'
  import MenuCascader from '../../../components/Cascader/Menu'
  import { hasPermission } from '../../../libs/permission'

  export default {
    name: 'adminUserIndex',
    components: {
      GuardSelect,
      MenuCascader
    },
    data: () => ({
      ...tableDefaultData(),
      tableListData: [],
      foldList: [],
      addForm: {},
      editForm: {},
      rules: {
        name: [
          { required: true },
          { min: 1, max: 255 }
        ],
        uri: [
          { required: true },
          { min: 1, max: 255 }
        ],
        guard_name: [
          { required: true },
          { min: 1, max: 255 }
        ],
        parent_id: [
          { required: true, type: 'number' }
        ],
        sequence: [
          {type: 'number' }
        ]
      },
    }),
    methods: {
      handleDelete (index, row) {
        deleteMenu(row.id).then( response => {
          deleteSuccess(index, this)
          this.requestData()
        })
      },
      handleEdit (index, row) {
        this.editForm = row
        this.nowRowData = { index, row }
        this.dialogEditFormVisible = true
      },
      handleEditMenu () {
        this.$refs['editForm'].validate((valid) => {
          if (valid) {
            editMenu(this.nowRowData.row.id, this.editForm).then( response => {
              editSuccess(this)
              this.requestData()
            })
          } else {
            return false;
          }
        })
      },
      handleAddMenu () {
        this.$refs['addForm'].validate((valid) => {
          if (valid) {
            addMenu(this.addForm).then( response => {
              addSuccess(this)
              this.requestData()
            })
          } else {
            return false;
          }
        })
      },
      requestData () {
        this.loading = true
        getMenuList(this.queryParams).then( response => {
          this.tableListData = response.data.data
          this.loading = false
        })
      }
    },
    computed: {
      updatePermission: function() {
        return hasPermission('menu.update')
      },
      addPermission: function() {
        return hasPermission('menu.store')
      },
      deletePermission: function() {
        return hasPermission('menu.destroy')
      }
    },
    created() {
      this.requestData()
    }
  }
</script>

<style rel="stylesheet/scss" lang="scss" scoped>

</style>
