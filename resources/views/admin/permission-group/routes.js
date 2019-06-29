export default [
  {
    name: 'permissionGroupIndex',
    path: 'permission-group',
    meta: {
      provider: 'admin',
      title: 'permissionGroup',
      cache: true,
      permission: 'permission-group.index'
    },
    component: resolve => void(require(['./index.vue'], resolve))
  },
]
