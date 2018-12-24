import Admin from '../views/admin/main/index'
import ParentView from '../components/ParentView/ParentView'

export default [
  {
    name: 'adminMain',
    path: '/admin',
    redirect: '/admin/dashboard',
    meta: {
      provider: 'admin',
      title: 'home',
    },
    component: Admin,
    children: [
      {
        name: 'adminDashboard',
        path: 'dashboard',
        meta: {
          provider: 'admin',
          title: 'dashboard',
          cache: true,
          notClosable: true
        },
        component: resolve => void(require(['../views/admin/dashboard/index.vue'], resolve))
      },
      {
        name: 'adminUserIndex',
        path: 'admin-user',
        meta: {
          provider: 'admin',
          title: 'adminUser',
          cache: true,
          permission: 'admin-user.index'
        },
        component: resolve => void(require(['../views/admin/user/index.vue'], resolve))
      },
      {
        path: 'role',
        meta: {
          provider: 'admin',
          title: 'role',
          cache: true,
        },
        component: ParentView,
        children: [
          {
            name: 'rolePermission',
            path: 'assign-permissions/:id/:guardName',
            meta: {
              provider: 'admin',
              title: 'roleAssignPermission',
              permission: 'role.assign-permissions'
            },
            component: resolve => void(require(['../views/admin/role/permission.vue'], resolve)),
          },
          {
            name: 'roleIndex',
            path: '',
            meta: {
              provider: 'admin',
              title: 'role',
              cache: true,
              permission: 'role.index'
            },
            component: resolve => void(require(['../views/admin/role/index.vue'], resolve)),
          },
        ]
      },
      {
        name: 'permissionIndex',
        path: 'permission',
        meta: {
          provider: 'admin',
          title: 'permission',
          cache: true,
          permission: 'permission.index'
        },
        component: resolve => void(require(['../views/admin/permission/index.vue'], resolve))
      },
      {
        name: 'permissionGroupIndex',
        path: 'permission-group',
        meta: {
          provider: 'admin',
          title: 'permissionGroup',
          cache: true,
          permission: 'permission-group.index'
        },
        component: resolve => void(require(['../views/admin/permission-group/index.vue'], resolve))
      },
      {
        name: 'menuIndex',
        path: 'menu',
        meta: {
          provider: 'admin',
          title: 'menu',
          cache: true,
          permission: 'menu.index'
        },
        component: resolve => void(require(['../views/admin/menu/index.vue'], resolve))
      }
    ]
  },
  {
    name: 'adminLogin',
    path: '/admin/login',
    component: resolve => void(require(['../views/admin/login/index.vue'], resolve))
  }
]
