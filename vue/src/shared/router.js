import Vue from 'vue'
import Router from 'vue-router'
import DefaultLayout from "./../core/components/layout/DefaultLayout/DefaultLayout";
import AuthLayout from "./../core/components/layout/AuthLayout/AuthLayout";
import NotFoundComponent from "./../core/components/pages/NotFoundComponent";
import Dashboard from "@/modules/Dashboard/Dashboard";
import Login from "../modules/Auth/Login";
import Register from "@/modules/Auth/Register";
import RequestPasswordReset from "../modules/Auth/PasswordReset/RequestPasswordReset";
import ResetPasswordForm from "../modules/Auth/PasswordReset/ResetPasswordForm";

Vue.use(Router);

const router = new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/auth',
      name: 'auth',
      redirect: '/login',
      component: AuthLayout,
      children: [
        {
          path: 'login',
          name: 'auth.login',
          component: Login,
        },
        {
          path: 'password-reset',
          name: 'password-reset',
          component: RequestPasswordReset,
        },
        {
          path: 'password-reset/:token',
          name: 'password-reset',
          component: ResetPasswordForm,
        },
        {
          path: 'register/:token',
          name: 'auth.register',
          component: Register,
        }
      ]
    },
    {
      path: '/login',
      redirect: '/auth/login'
    },
    {
      path: '/',
      redirect: '/dashboard',
      component: DefaultLayout,
      children: [
        {
          path: 'dashboard',
          component: Dashboard,
          // meta: {
          //   requiresAuth: true
          // }
        }
      ]
    },
    {
      path: '*',
      component: NotFoundComponent,
      children: []
    }
  ]
});

// router.beforeEach((to, from, next) => {
//   if (to.matched.some(record => record.meta.requiresAuth)) {
//     // this route requires auth, check if logged in
//     // if not, redirect to login page.
//     if (!auth.loggedIn()) {
//       next({path: '/login'})
//     } else {
//       next()
//     }
//   } else if (to.matched.some(record => record.meta.guest) && auth.loggedIn()) {
//     next({path: '/'})
//   } else {
//     next() // make sure to always call next()!
//   }
// });

export default router;