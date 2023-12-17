<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';

import { Popover, PopoverButton, PopoverPanel, PopoverOverlay } from '@headlessui/vue';

const props = defineProps({
    boards: Array
});

const form = useForm({
    name: ''
});

function onSubmit(closePopover) {
    form.post(route('boards.store'), {
        onSuccess: () => {
            form.reset();
            closePopover();
        },
    });
}
</script>

<template>
    <Head title="Boards" />

    <AuthenticatedLayout>
        <div class="h-full bg-gray-50 px-4 py-6 overflow-y-auto">
            <div class="max-w-5xl mx-auto">
                <div class="px-3 flex items-center mb-4">
                    <h3 class="font-black text-gray-700">My Projects</h3>
                    <Popover v-slot="{ open }" class="relative">
                        <PopoverButton
                            :class="open ? 'bg-blue-50 text-blue-600' : ''"
                            class="inline-flex items-center ml-4 py-2 px-3 bg-gray-100 rounded font-medium hover:bg-gray-200 "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            <span class="ml-1">Create Project</span>
                        </PopoverButton>
                        <PopoverOverlay class="z-10 fixed inset-0 bg-black opacity-30" />

                        <transition
                            enter-active-class="transition duration-200 ease-out"
                            enter-from-class="translate-y-1 opacity-0"
                            enter-to-class="translate-y-0 opacity-100"
                            leave-active-class="transition duration-150 ease-in"
                            leave-from-class="translate-y-0 opacity-100"
                            leave-to-class="translate-y-1 opacity-0"
                        >
                            <PopoverPanel
                                :focus="true"
                                v-slot="{close}"
                                class="absolute left-1/2 z-10 mt-3 w-72 -translate-x-1/2  px-4 sm:px-0"
                            >
                                <div
                                    class="p-3 bg-white overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5"
                                > 
                                 <form @submit.prevent="onSubmit(close)">
                                    <label for="name" class="block text-sm text-gray-600 font-medium mb-1">Your new board:</label>
                                    <input 
                                        v-model="form.name"
                                        class="block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-400 focus:ring-blue-400"
                                        type="text"  
                                        name="name" 
                                        id="name" 
                                        placeholder="Board name">
                                    <p  
                                        v-if="form.errors.name"
                                        class="text-sm text-red-600">{{form.errors.name}}</p>


                                    <div class="flex justify-end mt-2">
                                        <button class="px-4 py-2 text-sm font-medium text-white bg-rose-600 hover:bg-rose-500 rounded-md shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 foucus:outline-none">Create board</button>
                                    </div>
                                 </form>
                                </div>
                            </PopoverPanel>
                        </transition>
                    </Popover>
                </div>

                <ul class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    <li 
                        v-for="board in boards"
                        :key="board.id"
                        class="relative bg-blue-800 hover:bg-blue-700 rounded-md min-h-[7rem]">
                        <Link :href="route('boards.show', {board: board.id})"
                              class="p-3 text-white text-lg font-bold absolute inset-0">
                            {{board.name}}
                        </Link>
                    </li>
                </ul>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
