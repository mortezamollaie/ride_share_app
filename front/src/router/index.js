import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from '@/views/LoginView.vue'
import LandingView from '@/views/LandingView.vue'
import LocationView from '@/views/LocationView.vue'
import MapView from '@/views/MapView.vue'
import axios from 'axios'
import TripView from '@/views/TripView.vue'
import DriverView from '@/views/DriverView.vue'
import StandbyView from '@/views/StandbyView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginView,
    },
    {
      path: '/landing',
      name: 'landing',
      component: LandingView,
    },
    {
      path: '/location',
      name: 'location',
      component: LocationView,
    },
    {
      path: '/map',
      name: 'map',
      component: MapView,
    }, 
    {
      path: '/trip',
      name: 'trip',
      component: TripView
    },
    {
      path: '/standby',
      name: 'standby',
      component: StandbyView
    },
    {
      path: '/driver',
      name: 'driver',
      component: DriverView
    }
  ],
})

router.beforeEach((to) => { 
  if(to.name === 'login'){
    console.log('No token found')
    return true
  }
  if(!localStorage.getItem('token')){
    return {
      name: 'login'
    }
  }

  checkTokenAuthenticity()
})

const checkTokenAuthenticity = () => {
  axios.get('http://127.0.0.1:8000/api/user', {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`
    }
  })
  .then(response => {})
  .catch(error => {
    localStorage.removeItem('token')
    router.push({
      name:'login'
    })
  })
}

export default router
