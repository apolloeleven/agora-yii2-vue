import router from '../../shared/router'
import UserInvitations from "./Invitation/UserInvitations";
import User from "./User";

let {routes} = router.options;
const route = routes.find(r => r.path === '/');

let tmpRoutes = [
  {
    path: 'users',
    redirect: {name: 'user.invitations'},
    component: User,
    name: 'user',
    meta: {
      requiresAuth: true
    },
    children: [
      {
        path: 'invitations',
        name: 'user.invitations',
        component: UserInvitations,
      }
    ]
  },
];

tmpRoutes.forEach((obj) => {
  route.children.push(obj);
});

router.addRoutes([route]);