export default [
  {
    name: 'adminDashboard',
    path: 'dashboard',
    meta: {
      provider: 'admin',
      title: 'dashboard',
      cache: true,
      notClosable: true
    },
    component: resolve => void(require(['./index.vue'], resolve))
  },
]
