import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('../views/AboutView.vue')
    },
    {
      path: '/author',
      name: 'author',
      component: () => import('../views/AuthorView.vue')
    },
    {
      path: '/book',
      name: 'book',
      component: () => import('../views/BookView.vue')
    }
  ]
})

export default router
