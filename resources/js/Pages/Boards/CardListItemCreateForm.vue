<script setup>
import { nextTick } from 'vue';
import { ref } from 'vue';
import {computed} from 'vue';
import { useForm } from '@inertiajs/vue3';
import {store} from '@/store';

const props = defineProps({
    list: Object
});
const emit = defineEmits(['created']);


const inputTitleRef = ref();
const isShowingForm = computed(() => props.list.id === store.value.listCreatingCardId);
const form = useForm({
    title: '',
    card_list_id: props.list.id,
    board_id: props.list.board_id
});

async function showForm() {
    store.value.listCreatingCardId = props.list.id;
    await nextTick();
    inputTitleRef.value.focus();
}

function onSubmit() {
    form.post(route('cards.store'), {
        onSuccess: () => {
            form.reset();
            inputTitleRef.value.focus();
            emit('created');
        }
    });
}

</script>
<template>
    <form 
        @keydown.esc="store.listCreatingCardId = null"
        v-if="isShowingForm"
        @submit.prevent="onSubmit()" 
        >
        <textarea
            v-model="form.title"
            ref="inputTitleRef"
            rows="3"
            @keydown.enter.prevent="onSubmit()"
            class="block w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-400 focus:ring-blue-400" 
            placeholder="Enter card title..."
        ></textarea>

        <div class="mt-2 space-x-2">
            <button 
                type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-rose-600 hover:bg-rose-500 rounded-md shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 foucus:outline-none"
                >Add card</button>
            <button
                type="button"
                @click="store.listCreatingCardId = null"
                 class="px-4 py-2 text-sm font-medium text-gray--700 hover:text-black rounded-md focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 foucus:outline-none"
                 >Cancel</button>
        </div>
    </form>

    <button 
        @click="showForm()"
        v-if="!isShowingForm"
        class="flex items-center p-2 text-sm font-medium text-gray-600 hover:text-black hover:bg-gray-300 w-full rounded-md">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        <span class="ml-1">Add card</span>
    </button>
</template>