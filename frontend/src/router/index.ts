import { createRouter, createWebHistory } from 'vue-router'
import middlewarePipeline from '@/middleware';
import auth from '@/middleware/auth';
const routes = [
  {
    path: '/auth/:auth_action/:token?',
    name: 'auth',
    component: () => import('../views/Auth/index.vue'),
    meta: {
      title: 'Login',
    },
  },
  {
    path: '/reset-password',
    name: 'reset-password',
    component: () => import('../views/Auth/ResetPassword.vue'),
    meta: {
      title: 'Login',
    },
  },
  {
    path: '/print/:module/:id',
    name: 'print',
    component: () => import('@/views/Module/View/components/print.vue'),
    meta: {
      title: 'Print',
    },
    
  },
  {
    path: '/report/print/:id',
    name: "print-report",
    component: () => import("@/views/Module/Report/print.vue"),
    meta: {
      title: 'Print',
    },
  },
  {
    path: '/',
    name: 'home',
    component: () => import('@/components/layout/AdminLayout.vue'),
    meta: {
      middleware: [auth],
    },
    children:[
      {
        path: 'login-history',
        name: 'login-history',
        component: () => import('@/views/Module/System/login-history.vue'),
        meta: {
          title: 'Login History',
        },
      },
      {
        path: 'notification',
        name: 'notification',
        component: () => import('@/views/Module/System/notification.vue'),
        meta: {
          title: 'Notification',
        },
      },
      {
        path: '/profile',
        name: 'Profile',
        component: () => import('@/views/Module/User/profile/index.vue'),
        meta: {
          title: 'Profile',
        },
      },
      {
        path: 'dashboard',
        name: 'dashboard',
        component: () => import('@/views/Module/Dashboard/index.vue'),
        meta: {
          title: 'Dashboard',
        },
      },
      {
        path: 'list/:module',
        name: 'list',
        component: () => import('@/views/Module/List/index.vue'),
        meta: {
          title: 'List',
        },
      },
      {
        path: 'view/:module/:id/:related_module?',
        name: 'view',
        component: () => import('@/views/Module/View/index.vue'),
        meta: {
          title: 'View',
        },
      },
      {
        path: '/save/:module/:id?',
        name: 'save',
        component: () => import('@/views/Module/Save/index.vue'),
        meta: {
          title: 'Save',
        },
      },
      {
        path: '/reports/save/:option/:id?',
        name: 'save_report',
        component: () => import('@/views/Module/Report/index.vue'),
        meta: {
          title: 'Save Report',
        },
      },
      {
        path: 'map/:map',
        name: 'map',
        component: () => import('@/views/Module/Map/index.vue'),
        meta: {
          title: 'Map',
        },
      },
    ]
  },
  {
    path: "/:catchAll(.*)",
    name: "404",
    component: () => import('../views/Errors/FourZeroFour.vue'),
    meta: {
      title: '404 Error',
    },
  }
];
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  scrollBehavior(to, from, savedPosition) {
    return savedPosition || { left: 0, top: 0 }
  },
 routes
})

export default router

router.beforeEach((to, from, next) => {
  document.title = `IMS | ${to.meta.title}`
  if(!to.meta.middleware){
    return next();
  }
  const middleware:any = to.meta.middleware;
  const context = { to, from, next }
  return middleware[0]({
    ...context,
    next: middlewarePipeline(context, middleware, 1)
  })
})
