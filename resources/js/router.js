import Vue from 'vue'
import VueRouter from "vue-router";

import PhotoList from './pages/PhotoList.vue'
import LikesPhotoList from "./pages/LikesPhotoList";
import PhotoDetail from './pages/PhotoDetail.vue'
import Login from './pages/Login.vue'
import Users from "./pages/Users";

import SystemError from './pages/errors/System.vue'
import NotFound from './pages/errors/NotFound.vue'

import store from './store'

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        component: PhotoList,
        props: route => {
            const page = route.query.page
            return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1 }
        }
    },
    {
        path: '/likes',
        component: LikesPhotoList,
        props: route => {
            const page = route.query.page
            return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1 }
        }
    },
    {
        path: '/users',
        component: Users,
        props: true
    },
    {
        path: '/photos/:id',
        component: PhotoDetail,
        props: true
    },
    {
        path: '/login',
        component: Login,
        beforeEnter (to, from, next) {
            if (store.getters['auth/check']) {
                next('/')
            } else {
                next()
            }
        }
    },
    {
        path: '/500',
        component:SystemError
    },
    {
        path: '*',
        component: NotFound
    }
]

const router = new VueRouter({
    mode: 'history',
    scrollBehavior () {
        return { x: 0, y: 0 }
    },
    routes
})

export default router

