import Vue from 'vue'
import Router from 'vue-router'
import Home from './pages/Home.vue'

Vue.use(Router)

export default new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [

    {
      path: '/',
      component: Home
    },

    {
      path: '/page/auth',
      component: () => import('./pages/Auth')
    },

    {
      path: '/page/register',
      component: () => import('./pages/UserRegister')
    },
    {
      path: '/page/profile',
      component: () => import('./pages/UserProfile')
    }
  ]
})
