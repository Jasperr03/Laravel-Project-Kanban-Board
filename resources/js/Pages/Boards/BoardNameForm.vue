<script setup>
import { useForm } from '@inertiajs/vue3';
import { nextTick } from 'vue';
import { ref } from 'vue';
const props = defineProps({
    board: Object
});
const form = useForm({
    name: props.board.name
});

const isEditing = ref(false);

const input = ref();


async function edit() {
    isEditing.value = true;
    await nextTick();
    input.value.select();
}

function onSubmit() {
    isEditing.value = false;
    form.put(route('boards.update', {board: props.board.id}),{
        onError: () => form.reset()
    });
}
</script>
<template>
    <div class="flex flex-col items-start max-w-full relative">
        <h1 
            @click="edit()"
            :class="[isEditing ? 'invisible': '']"
            class="hover:bg-white/20 whitespace-pre w-full overflow-hidden text-ellipsis border border-transparent rounded-md cursor-pointer px-3 py-1.5 text-xl text-white font-bold"
            >{{ form.name ? form.name : ' ' }}</h1>
        <form 
            @submit.prevent="onSubmit()"
            @focusout="onSubmit()"
            v-show="isEditing"
            class="w-full"
            >
            <input 
                ref="input"
                v-model="form.name"
                type="text" 
                placeholder="Board name"
                class="absolute inset-0 text-2xl max-w-full font-bold placeholder-gray-400 px-3 py-1.5 rounded-md focus:ring-2 focus:ring-blue-900"
            >
        </form>
    </div>
</template>