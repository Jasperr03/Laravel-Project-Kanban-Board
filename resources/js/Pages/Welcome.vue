<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

const dropdown = {
    open: false,
    toggleDropdown() {
        this.open = !this.open;
    },
    closeDropdown() {
        this.open = false;
    },
};

</script>

<template>
    <Head title="Welcome" />

    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white"
    >
        <div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
            <Link
                v-if="$page.props.auth.user"
                :href="route('boards')"
                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >Boards</Link
            >

            <template v-else> 
                <div class="flex">
                    <div x-data="dropdown" class="relative">
                        <button @click="toggleDropdown" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                            Login
                        </button>

                        <div x-show="open" @click.away="closeDropdown" class="absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded-md shadow-md">
                            <a :href="route('login')" class="block flex justify-start px-4 py-2 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Login</a>
                            <a :href="route('lead.login')" class="block flex justify-start px-4 py-2 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Lead</a>
                            <a :href="route('admin.login')" class="block flex justify-start px-4 py-2 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Admin</a>
                        </div>
                    </div>



                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                        >Register</Link
                    >

                </div>

            </template>
        </div>

        <div class="max-w-7xl mx-auto p-6 lg:p-8">
             <img class="h-auto max-w-xs" src="https://nbsc.edu.ph/is/assets/img/nbsclogo.png" alt="">
        </div>
    </div>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
