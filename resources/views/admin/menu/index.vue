<template>
  <div>
    <el-form :inline="true" :model="queryParams"  size="mini">
      <el-form-item :label="$t('guardName')">
        <guard-select :nowValue.sync="queryParams.guard_name"></guard-select>
      </el-form-item>
      <el-form-item>
        <el-button type="primary"  icon="el-icon-search">{{ $t('search') }}</el-button>
        <el-button type="primary" v-if="addPermission"  @click="dialogAddFormVisible = true" icon="el-icon-plus">{{ $t('add') }}</el-button>
      </el-form-item>
    </el-form>

    <el-table
            :data="tableListData"
            v-loading="loading"
            :row-style="toggleDisplayTr"
            border stripe
            class="init_table">
      <el-table-column
              :label="$t('name')"
              min-width="200"
              show-overflow-tooltip
              align="left">
        <template slot-scope="scope">
          <p :style="`margin-left: ${scope.row.__level * 20}px;margin-top:0;margin-bottom:0`"><i  @click="toggleFoldingStatus(scope.row)" class="permission_toggleFold" :class="toggleFoldingClass(scope.row)"></i>{{scope.row.name}}</p>
        </template>
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
          <menu-tree-select :nowValue.sync="addForm.parent_id" :guardName.sync="addForm.guard_name"></menu-tree-select>
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
          <menu-tree-select :nowValue.sync="editForm.parent_id" :guardName.sync="editForm.guard_name"></menu-tree-select>
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
  import Vue from 'vue'
  import GuardSelect from '../../../components/Select/Guard'
  import { getMenuList, addMenu, editMenu, deleteMenu } from '../../../api/menu'
  import { tableDefaultData, editSuccess, addSuccess, deleteSuccess } from '../../../libs/tableDataHandle'
  import MenuTreeSelect from '../../../components/Select/MenuTree.vue'
  import { hasPermission } from '../../../libs/permission'

  export default {
    name: 'adminUserIndex',
    components: {
      GuardSelect,
      MenuTreeSelect
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
      //Author: zyx <https://github.com/no-simple/vue-tree-table>
      toggleFoldingStatus (params) {
        this.foldList.includes(params.__identity) ? this.foldList.splice(this.foldList.indexOf(params.__identity), 1) : this.foldList.push(params.__identity)
      },

      //Author: zyx <https://github.com/no-simple/vue-tree-table>
      toggleDisplayTr ({row, index}) {
        for (let i = 0; i < this.foldList.length; i++) {
          let item = this.foldList[i]
          if (row.__family.includes(item) && row.__identity !== item) return 'display:none;'
        }
        return ''
      },

      //Author: zyx <https://github.com/no-simple/vue-tree-table>
      toggleFoldingClass (params) {
        return params.children.length === 0 ? 'permission_placeholder' : (this.foldList.indexOf(params.__identity) === -1 ? 'iconfont el-icon-minus' : 'iconfont el-icon-plus')
      },

      //Author: zyx <https://github.com/no-simple/vue-tree-table>
      formatConversion (parent, children, index = 0, family = [], elderIdentity = 'x') {
        if (children.length > 0) {
          children.map((x, i) => {
            Vue.set(x, '__level', index)
            Vue.set(x, '__family', [...family, elderIdentity + '_' + i])
            Vue.set(x, '__identity', elderIdentity + '_' + i)
            parent.push(x)
            if (!x.hasOwnProperty('children')) {
              x.children = []
            }
            if (x.children.length > 0) this.formatConversion(parent, x.children, index + 1, [...family, elderIdentity + '_' + i], elderIdentity + '_' + i)
          })
        } return parent
      },
      requestData () {
        this.loading = true
        getMenuList(this.queryParams).then( response => {
          this.tableListData = this.formatConversion([], response.data.data)
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
  .app_title {
    display:block;
    width:100%;
    font-size:24px;
    line-height:60px;
    color:#41dae4;
    text-align:center;
  }
  .permission_toggleFold {
    vertical-align:middle;
    padding-right:5px;
    font-size:16px;
    cursor:pointer;
  }
  .permission_placeholder {
    content:' ';
    display:inline-block;
    width:16px;
    font-size:16px;
  }

  .init_table {
    th {
      background-color: #edf6ff;
      text-align: center !important;
      color: #066cd4;
      font-weight:bold;
      .cell {
        padding:0 !important;
      }
    }
    td, th {
      font-size:12px;
      padding:0 !important;
      height:40px !important;
    }
    .el-table--border, .el-table--group {
      border: 1px solid #dde2ef;
    }

    td, th.is-leaf {
      border-bottom: 1px solid #dde2ef
    }

    .el-table--border td, .el-table--border th, .el-table__body-wrapper .el-table--border.is-scrolling-left~.el-table__fixed {
      border-right: 1px solid #dde2ef
    }

    .el-table--striped .el-table__body tr.el-table__row--striped td {
      background-color:#f7f9fa;
    }
  }

</style>