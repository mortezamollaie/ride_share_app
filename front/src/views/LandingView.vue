<template>
    <div class="pt-16">
        <h1 class="text-3xl font-semibold mb-4">Snack</h1>
        <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
            <div class="bg-white px-4 py-5 sm:p-6">
                <div class="flex justify-between">
                    <button class="rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-black/80 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2"
                    @click="handleStartDriving">
                        Start Driving
                    </button>
                    <button
                    @click="handleFindARide()"
                    class="rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-black/80 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2">
                        Find A Ride
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>


<script setup>

import {useRouter} from 'vue-router'
import http from '@/helpers/http'

const router = useRouter()

const handleStartDriving = () => {
    http().get('/api/driver')
    .then((response) => {
        if(response.data.diver){
            router.push({
                name: 'location'
            })
        } else {
            router.push({
                name: 'driver'
            })
        }
    })
    .catch((error) => {
        console.log(error)
    })
}

const handleFindARide = () => {
    router.push({
        name: 'location'
    })
}

</script>