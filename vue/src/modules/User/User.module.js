import router from '../../shared/router'
import User from "./User";
import UserList from "./user/UserList";

let {routes} = router.options;
const route = routes.find(r => r.path === '/');

let tmpRoutes = [
  {
    path: 'users',
    redirect: {name: 'user.employees'},
    component: User,
    name: 'user',
    meta: {
      requiresAuth: true
    },
    children: [
      {
        path: 'employees',
        name: 'user.list',
        component: UserList,
      }
    ]
  },
];

tmpRoutes.forEach((obj) => {
  route.children.push(obj);
});

router.addRoutes([route]);
