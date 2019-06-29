export default [
  {
    name: 'permissionIndex',
    path: 'permission',
    meta: {
      provider: 'admin',
      title: 'permission',
      cache: true,
      permission: 'permission.index'
    },
    component: resolve => void(require(['./index.vue'], resolve))
  }
]
