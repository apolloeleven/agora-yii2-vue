import Vue from 'vue'
import Router from 'vue-router'
import DefaultLayout from "./../core/components/layout/DefaultLayout/DefaultLayout";
import AuthLayout from "./../core/components/layout/AuthLayout/AuthLayout";
import NotFoundComponent from "./../core/components/pages/NotFoundComponent";
import Dashboard from "@/modules/Dashboard/Dashboard";
import Login from "@/modules/Auth/Login";
import Register from "@/modules/Auth/Register";
import RequestPasswordReset from "@/modules/Auth/PasswordReset/RequestPasswordReset";
import ResetPassword from "@/modules/Auth/PasswordReset/ResetPassword";
import Setup from "@/modules/setup/Setup";
import CountryList from "@/modules/setup/countries/CountryList";
import UserInvitations from "@/modules/setup/invitations/UserInvitations";
import Profile from "@/modules/User/Profile";
import Workspace from "@/modules/Workspace/Workspace";
import WorkspaceView from "@/modules/Workspace/WorkspaceView";
import EmployeeList from "@/modules/setup/employees/EmployeeList";
import Orgchart from "../modules/Orgchart/Orgchart";
import WorkspaceTimeline from "@/modules/Workspace/view/timeline/WorkspaceTimeline";
import WorkspaceFiles from "@/modules/Workspace/view/files/WorkspaceFiles";
import WorkspaceArticles from "@/modules/Workspace/view/articles/WorkspaceArticles";
import WorkspaceAbout from "@/modules/Workspace/view/about/WorkspaceAbout";
import ArticleForm from "@/modules/Workspace/view/articles/ArticleForm";
import ArticleView from "@/modules/Workspace/view/articles/ArticleView";

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
          component: ResetPassword,
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
        {path: 'dashboard', name: 'dashboard', component: Dashboard,},
        {path: 'orgchart', name: 'orgchart', component: Orgchart,},
        {path: '/setup', name: 'setup', component: Setup,},
        {path: '/setup/countries', name: 'countries', component: CountryList},
        {path: '/setup/invitations', name: 'invitations', component: UserInvitations},
        {path: '/setup/users', name: 'users', component: EmployeeList},
        {path: '/profile', name: 'profile', component: Profile,},
        {path: '/setup/countries', name: 'countries', component: CountryList},
        {path: '/workspace', name: 'workspace', component: Workspace},
        {
          path: '/workspace/:id',
          name: 'workspace.view',
          component: WorkspaceView,
          redirect: 'workspace/:id/timeline',
          meta: {requiresAuth: true},
          children: [
            {path: 'timeline', name: 'workspace.timeline', component: WorkspaceTimeline},
            {path: 'files/:folderId', name: 'workspace.files', component: WorkspaceFiles},
            {path: 'articles', name: 'workspace.articles', component: WorkspaceArticles},
            {path: 'articles/new', name: 'workspace.articles.create', component: ArticleForm},
            {path: 'articles/update/:articleId', name: 'workspace.articles.update', component: ArticleForm},
            {path: 'articles/view/:articleId', name: 'workspace.articles.view', component: ArticleView},
            {path: 'about', name: 'workspace.about', component: WorkspaceAbout},
          ]
        },
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
