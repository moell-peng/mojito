export default [
  {
    name: 'adminUserIndex',
    path: 'admin-user',
    meta: {
      provider: 'admin',
      title: 'adminUser',
      cache: true,
      permission: 'admin-user.index'
    },
    component: resolve => void(require(['./index.vue'], resolve))
  },
]
