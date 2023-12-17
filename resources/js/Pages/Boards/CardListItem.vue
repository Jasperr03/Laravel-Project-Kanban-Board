<script setup>
import { nextTick } from 'vue';
import { ref } from 'vue';
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { store } from '@/store';
import { Link } from '@inertiajs/vue3';
import axios from "axios";
import { onMounted } from 'vue';


const props = defineProps({
    card: Object
});

const inputTitleRef = ref();
const isShowingForm = computed(() => props.card.id === store.value.editingCardId);
const form = useForm({
    title: props.card.title,
});

async function showForm() {
    store.value.editingCardId = props.card.id;
    await nextTick();
    inputTitleRef.value.focus();
}

function onSubmit() {
    form.put(route('cards.update', {card: props.card.id}), {
        onSuccess: () => store.value.editingCardId = null
        });
}

const cards = ref()
const getCards = () => {
    axios.get('/api/cards')
    .then(res => cards.value = res.data)
    .catch(error => console.log(error))
}

onMounted(() => getCards()
);
</script>


<template>
    <li>
        <div 
            class="group relative bg-white p-2 shadow rounded-md border-b border-gray-300 hover:bg-bg-gray-50"
            >

            <form 
                @keydown.esc="store.editingCardId = null"
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
                        >Save card</button>
                    <button
                        type="button"
                        @click="store.editingCardId = null"
                        class="px-4 py-2 text-sm font-medium text-gray--700 hover:text-black rounded-md focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 foucus:outline-none"
                        >Cancel</button>
                </div>
                
            </form>

            <template v-if="!isShowingForm">
                <Link 
                    :href="route('boards.show', {board: card.board_id, card: card.id})" 
                    preserve-State
                    class="text-sm block p-2.5"
                    >{{ card.title }}
                    <h1 class="span flex items-center justify pt-1 pb-0">
                        <span class="text-sm font-small align-text bg-gray-100 bg: rounded-sm ml-1 mr-1 "> {{ card.created_at }}</span>
                    </h1>   
                </Link> 

                <button 
                    @click="showForm()"
                    class="hidden absolute top-1 right-1 w-8 h-8 bg-gray-50 group-hover:grid place-content-center rounded-md text-gray-600 hover:text-black hover:bg-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                </button>

            </template>

        </div>
    </li>
</template>

<style scoped>
.drag > div{
    transform: rotate(5deg);
}
.ghost{
    background: lightgray;
    border-radius: 6px;
}
.ghost > div {
    visibility:hidden;
}
</style>
