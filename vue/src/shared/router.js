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
import Setup from "@/modules/setup/Setup";
import CountryList from "@/modules/setup/countries/CountryList";
import DepartmentList from "@/modules/setup/departments/DepartmentList";
import Profile from "@/modules/User/Profile";

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
          path: 'request-password-reset',
          name: 'request-password-reset',
          component: RequestPasswordReset,
        },
        {
          path: 'password-reset/:token',
          name: 'password-reset',
          component: ResetPasswordForm,
        },
        {
          path: 'register',
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
        {path: 'dashboard', name: 'dashboard', component: Dashboard,},
        {path: '/setup', name: 'setup', component: Setup,},
        {path: '/setup/countries', name: 'countries', component: CountryList},
        {path: '/setup/departments', name: 'departments', component: DepartmentList}
      ]
    },
    {
      path: '/User',
      name: 'user',
      redirect: '/profile',
      component: DefaultLayout,
      children: [
        {
          path: 'profile',
          component: Profile,
        },
      ]
    },
    {
      path: '/profile',
      redirect: '/user/profile'
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