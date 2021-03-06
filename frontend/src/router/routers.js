import Main from '@/components/main'
import parentView from '@/components/parent-view'

/**
 * iview-admin中meta除了原生参数外可配置的参数:
 * meta: {
 *  title: { String|Number|Function }
 *         显示在侧边栏、面包屑和标签栏的文字
 *         使用'{{ 多语言字段 }}'形式结合多语言使用，例子看多语言的路由配置;
 *         可以传入一个回调函数，参数是当前路由对象，例子看动态路由和带参路由
 *  hideInBread: (false) 设为true后此级路由将不会出现在面包屑中，示例看QQ群路由配置
 *  hideInMenu: (false) 设为true后在左侧菜单不会显示该页面选项
 *  notCache: (false) 设为true后页面不会缓存
 *  access: (null) 可访问该页面的权限数组，当前路由设置的权限会影响子路由
 *  icon: (-) 该页面在左侧菜单、面包屑和标签导航处显示的图标，如果是自定义图标，需要在图标名称前加下划线'_'
 *  beforeCloseName: (-) 设置该字段，则在关闭当前tab页时会去'@/router/before-close.js'里寻找该字段名对应的方法，作为关闭前的钩子函数
 * }
 */

export default [
  {
    path: '/login',
    name: 'login',
    meta: {
      title: 'Login - 登录',
      hideInMenu: true
    },
    component: () => import('@/view/login/login.vue')
  },
  {
    path: '/',
    name: '_home',
    redirect: '/home',
    component: Main,
    meta: {
      hideInMenu: true,
      notCache: true
    },
    children: [
      {
        path: '/home',
        name: 'home',
        meta: {
          hideInMenu: true,
          title: '首页',
          notCache: true,
          icon: 'md-home'
        },
        component: () => import('@/view/single-page/home')
      }
    ]
  },
  {
    path: '/user-manage',
    name: 'user-manage',
    meta: {
      icon: 'ios-lock',
      title: '用户权限',
      access: ['super_admin']
    },
    component: Main,
    children: [
      {
        path: 'user-list',
        name: 'user-list',
        meta: {
          icon: 'ios-people',
          title: '用户列表'
        },
        component: () => import('@/view/user-manage/users/user.vue')
      },
      {
        path: 'roles-list',
        name: 'roles-list',
        meta: {
            icon: 'md-card',
            title: '角色列表'
        },
        component: () => import('@/view/user-manage/roles/roles.vue')
      },
      {
          path: 'permissions-list',
          name: 'permissions-list',
          meta: {
              icon: 'ios-key',
              title: '权限列表'
          },
          component: () => import('@/view/user-manage/permissions/permissions.vue')
      },
    ]
  },
  {
    path: '/dictionaries',
    name: 'dictionary-manage',
    meta: {
      icon: 'ios-lock',
      title: '字典配置',
      access: ['super_admin']
    },
    component: Main,
    children: [
      {
        path: 'dic_types',
        name: 'dictionary-type-list',
        meta: {
            icon: 'ios-people',
            title: '字典类型'
        },
        component: () => import('@/view/dictionaries/types/index.vue')
      },
      {
        path: 'dic_items',
        name: 'dictionary-item-list',
        meta: {
            icon: 'md-card',
            title: '字典项目'
        },
        component: () => import('@/view/dictionaries/items/index.vue')
      },
    ]
  },
  {
    path: '/articles',
    name: 'article',
    meta: {
      icon: 'ios-paper',
      title: '文章',
      access: ['user']
    },
    component: Main,
    children: [
      {
        path: 'write/:id?',
        name: 'write_article',
        meta: {
          icon: 'ios-brush',
          title: '所有文章',
          hideInBread: true,
          hideInMenu: true,
            notCache: true
        },
        component: () => import('@/view/articles/article/write.vue')
      },
      {
        path: 'index',
        name: 'articles',
        meta: {
          icon: 'ios-paper',
          title: '所有文章'
        },
        component: () => import('@/view/articles/article/index.vue')
      },
      {
            path: 'categories',
            name: 'categories',
            meta: {
                icon: 'ios-cog',
                title: '分类目录'
            },
            component: () => import('@/view/articles/category/index.vue')
      },
      {
        path: 'tags',
        name: 'tags',
        meta: {
          icon: 'ios-pricetags',
          title: '标签'
        },
        component: () => import('@/view/articles/tag/index.vue')
      },
    ]
  },
  {
    path: '/message',
    name: 'message',
    component: Main,
    meta: {
        hideInBread: true,
        hideInMenu: true
    },
    children: [
      {
        path: 'message_page',
        name: 'message_page',
        meta: {
          icon: 'md-notifications',
          title: '消息中心'
        },
        component: () => import('@/view/single-page/message/index.vue')
      },
    ]
  },
  {
    path: '/components',
    name: 'components',
    meta: {
        icon: 'logo-buffer',
        title: '组件'
    },
    component: Main,
    children: [
      {
        path: 'org_tree_page',
        name: 'org_tree_page',
        meta: {
          icon: 'ios-people',
          title: '组织结构树'
        },
        component: () => import('@/view/components/org-tree')
      },
      {
        path: 'calendar',
        name: 'calendar',
        meta: {
          icon: 'ios-people',
          title: '日历'
        },
        component: () => import('@/view/components/calendar')
      },
      {
        path: 'form_creator',
        name: 'form creator',
        meta: {
          icon: 'ios-people',
          title: '表单构建器'
        },
        component: () => import('@/view/form')
      },
    ]
  },
  {
    path: '/error_logger',
    name: 'error_logger',
    meta: {
      hideInBread: true,
      hideInMenu: true
    },
    component: Main,
    children: [
      {
        path: 'error_logger_page',
        name: 'error_logger_page',
        meta: {
          icon: 'ios-bug',
          title: '错误收集'
        },
        component: () => import('@/view/single-page/error-logger.vue')
      }
    ]
  },
  {
    path: '/401',
    name: 'error_401',
    meta: {
      hideInMenu: true
    },
    component: () => import('@/view/error-page/401.vue')
  },
  {
    path: '/500',
    name: 'error_500',
    meta: {
      hideInMenu: true
    },
    component: () => import('@/view/error-page/500.vue')
  },
  {
    path: '*',
    name: 'error_404',
    meta: {
      hideInMenu: true
    },
    component: () => import('@/view/error-page/404.vue')
  }
]
