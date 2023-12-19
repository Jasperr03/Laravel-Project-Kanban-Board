<script setup>
import NavLink from '@/Components/NavLink.vue';
import { Link } from '@inertiajs/vue3';
import {Menu, MenuButton, MenuItem, MenuItems} from '@headlessui/vue';

</script>

<template>
    <div>
        <div class="flex flex-col h-screen">
            <nav class="shrink-0 flex justify-between bg-gray-200 px-10 py-3 border-b">
                <div class="flex items-center px-2">
                    <img class="m-2 h-9 w-9 squared-full" src="https://nbsc.edu.ph/is/assets/img/nbsclogo.png" alt="">
                    <a class="text-2xl font-sans font-bold tracking-tight" :href="route('boards')">PROBE</a>
                </div>
                <div class="flex items-center px-2">
                    <NavLink :href="route('lead.dashboard')">Dashboard</NavLink>
                    <NavLink :href="route('lead.members')">Members</NavLink>

                    <Menu as="div" class="m-3 relative z-10">
                        <MenuButton class="focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 rounded-full"> 
                            <img class="h-9 w-9 inline rounded-full" 
                            src="data:image/png;base64,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"
                            alt="$pages.props.auth.user.name">
                        </MenuButton>
 
                        <transition
                            enter-active-class="transition transform duration-100 ease-out"
                            enter-from-class="opacity-0 scale-90"
                            enter-to-class="opacity-100 scale-100"
                            leave-active-class="transition transform duration-100 ease-in"
                            leave-from-class="opacity-100 scale-100"
                            leave-to-class="opacity-0 scale-90"
                        >
                            <MenuItems class="origin-top-right mt-2 focus:outline-none absolute right-0 bg-white overflow-hidden rounded-md shadow-lg border w-48">
                                <MenuItem v-slot="{active}">
                                    <a href="#" :class="{'bg-gray-100': active}" class="block px-4 py-2 text-sm text-gray-700">My Profile</a>
                                </MenuItem>
                                <MenuItem v-slot="{active}">
                                    <Link
                                        :class="{'bg-gray-100': active}" 
                                        :href="route('lead.logout')"
                                        method="post"
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700">Log out</Link>
                                </MenuItem>
                            </MenuItems>
                        </transition>
                    </Menu>
                </div>
            </nav>

            <main class="flex-1">
                <slot />
            </main>
        </div>
    </div>
</template>
