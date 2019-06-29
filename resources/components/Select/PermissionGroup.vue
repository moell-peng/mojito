<template>
  <el-select v-model="optionValue" clearable  :placeholder="$t('permissionGroup')">
    <el-option v-for="item in items" :label="item.name" :value="item.id" :key="item.id"></el-option>
  </el-select>
</template>
<script>
  import { getPermissionGroupAll } from '../../api/permissionGroup'
  export default {
    name: 'PermissionGroupSelect',
    props: ['nowValue'],
    data () {
      return {
        optionValue: this.nowValue,
        items: [
        ]
      }
    },
    created() {
      getPermissionGroupAll().then( response  => {
        this.items = response.data.data
      })
    },
    watch: {
      optionValue(newValue, oldValue) {
        this.$emit("update:nowValue", newValue);
      },
      nowValue(newValue) {
        this.optionValue = newValue
      }
    }
  }
</script>