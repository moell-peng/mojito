<template>
  <el-select v-model="optionValue" :placeholder="$t('guardName')">
    <el-option v-for="item in optionItems" :label="formatOption(item)" :value="item.id" :key="item.id"></el-option>
  </el-select>
</template>
<script>
  import config from '../../config'
  import { getMenuList } from '../../api/menu'
  export default {
    name: 'MenuTreeSelect',
    props: ['nowValue', 'guardName'],
    data () {
      return {
        optionValue: this.nowValue,
        items: []
      }
    },
    computed: {
      optionItems: function() {
        return [
          {
            id: 0,
            name: this.$t('topMenu'),
            level: 0
          },
          ...this.items
        ]
      }
    },
    methods: {
      formatOption (menu) {
        let placeholder = ''

        for (let i = 0; i < menu.level; i++ ){
          placeholder += '-'
        }

        return placeholder + menu.name
      },
      requestData () {
        getMenuList({'guard_name': this.guardName}).then( response => {
          this.items = this.getLevelMenu([], response.data.data)
        })
      },
      getLevelMenu (items, children, index = 0) {
        if (children.length > 0) {
          children.map( menu => {
            items.push({
              id: menu.id,
              name: menu.name,
              level: index
            })

            if (menu.hasOwnProperty('children')) {
              this.getLevelMenu(items, menu.children, index + 1)
            }
          })
        }
        return items
      }
    },
    created() {
      this.requestData()
    },
    watch: {
      optionValue(newValue, oldValue) {
        this.$emit("update:nowValue", newValue);
      },
      nowValue(newValue, oldValue) {
        this.optionValue = newValue
      },
      guardName (newValue, oldValue) {
        this.requestData()
      }
    }
  }
</script>