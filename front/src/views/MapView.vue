<template>
    <div class="pt-16">
        <h1 class="text-3xl font-semibold mb-4">Here's your trip</h1>
        <div>
            <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
                <div class="bg-white px-4 py-5 sm:p-6">
                    <div>
                        <GMapMap :zoom="11" :center="locationStore.destination.geometry"
                         ref="gMap" style="width: 100%; height: 256px;">
                            
                        </GMapMap>
                    </div>
                    <div class="mt-2">
                        <p class="text-xl">Going to <strong>{{ locationStore.destination.name }}</strong></p>
                    </div>   
                </div>
                <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                    <button
                    @click="handleConfirmTrip"
                     class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-black/80 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2">
                        Let's Go
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useLocationStore } from '@/stores/location';
import { useRouter } from 'vue-router';
import { onMounted } from 'vue';
import { ref } from 'vue';
import axios from 'axios';
import http from '@/helpers/http';

const locationStore = useLocationStore();
const router = useRouter();
const gMap = ref(null);

const handleConfirmTrip = () => {
    http().post('/api/trip', {
        origin: locationStore.current.geometry,
        destination: locationStore.destination.geometry,
        destination_name: locationStore.destination.name,
    })
        .then(() => {
            router.push({
                name: 'trip'
            })
        })
        .catch((error) => {
            console.log(error)
        })
}

onMounted(async() => {
    // does the user have a location set?
    if(!locationStore.destination.name) {
        router.push({name: 'location'});
    }

    // lets go the users current location
    await locationStore.updateCurrentLocation();

    // draw a path on the map
    gMap.value.$mapPromise.then((mapObject)=>{
        let currentPoint = new google.maps.LatLng(locationStore.current.geometry);
        let destinationPoint = new google.maps.LatLng(locationStore.destination.geometry);
        let directionsService = new google.maps.DirectionsService();
        let directionsDisplay = new google.maps.DirectionsRenderer({
            map: mapObject,
        });

        directionsService.route({
            origin: currentPoint,
            destination: destinationPoint,
            avoidTolls: false,
            avoidHighways: false,
            travelMode: google.maps.TravelMode.DRIVING,
        }, (result, status) => {
            if(status === google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(result);
            } else {
                console.log(result, status);
            }
        })
    })
})


</script>