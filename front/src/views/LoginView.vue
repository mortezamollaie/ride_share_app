<template>
    <div class="pt-16">
        <h1 class="text-3xl font-semibold mb-4">Enter your phone number</h1>
        <form v-if="!waitingOnVerification" action="#" @submit.prevent="handleLogin">
            <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
                <div class="bg-white px-4 py05 sm:p-6">
                    <div>

                        <input type="text" v-maska="'#### ### ####'" v-model="credentials.phone" name="phone" id="phone" placeholder="09123456789" class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50">
                    </div>
                </div>

                <div class="bg-gray-100 px-4 py-3 text-right sm:px-6">
                    <button type="submit" @submit.prevent="handleLogin" class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-black/80 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2">
                        Continue
                    </button>
                </div>
            </div>
        </form>
        <form v-else action="#" @submit.prevent="handleVerification">
            <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
                <div class="bg-white px-4 py05 sm:p-6">
                    <div>
                        <input type="text" v-maska="'######'" v-model="credentials.login_code" name="login_code" id="login_code" placeholder="111111" class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50">
                    </div>
                </div>

                <div class="bg-gray-100 px-4 py-3 text-right sm:px-6">
                    <button type="submit" @submit.prevent="handleVerification" class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-black/80 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2">
                        Verify
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script setup>
import { vMaska } from "maska/vue"
import { reactive, ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { onMounted } from 'vue'

const router = useRouter()

const credentials = reactive({
    phone: null,
    login_code: null,
})

const waitingOnVerification = ref(false);

onMounted(() => {
    if(localStorage.getItem('token')) {
        router.push({
            name: 'landing'
        })
    }
})


const handleLogin = () => {
    axios.post('http://127.0.0.1:8000/api/login', {
        phone : credentials.phone.replace(/\s/g, '')
    })
    .then(response => {
        console.log(response.data)
        waitingOnVerification.value = true
    })

    .catch(error => {
        console.log(error)
    })
}

const handleVerification = () => {
    axios.post('http://127.0.0.1:8000/api/login/verify', {
        phone: credentials.phone.replace(/\s/g, ''),
        login_code: credentials.login_code.replace(/\s/g, '')
    })
    .then(response => {
        localStorage.setItem('token', response.data.token)
        router.push({
            name: 'landing'
        })
    })
    .catch(error => {
        console.log(error)
    })

}




</script>

<style>

</style>



