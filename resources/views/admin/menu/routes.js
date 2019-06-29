export default [
  {
    name: 'menuIndex',
    path: 'menu',
    meta: {
      provider: 'admin',
      title: 'menu',
      cache: true,
      permission: 'menu.index'
    },
    component: resolve => void(require(['./index.vue'], resolve))
  }
]
