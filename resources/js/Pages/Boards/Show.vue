<script setup>
import AuthenticatedLayoutVue from '@/Layouts/AuthenticatedLayout.vue';
import BoardNameForm from '@/Pages/Boards/BoardNameForm.vue';
import CardListCreateForm from '@/Pages/Boards/CardListCreateForm.vue';
import CardList from "@/Pages/Boards/CardList.vue";
import CardListItemModal from "@/Pages/Boards/CardListItemModal.vue";

const props = defineProps({
    board: Object,
    card: Object
});
</script>
<template>
    <AuthenticatedLayoutVue>
            <div class="flex flex-col h-full bg-blue-800">
                <div class="shrink-0 flex flex-wrap justify-between items-center p-4">
                    <BoardNameForm :board="board"/>
                </div>
                <div class="flex-1 overflow-x-auto">
                    <div class="inline-flex h-full items-start px-4 pb-4 space-x-4">
                        <CardList 
                            v-for="list in board.lists"
                            :key="list.id"
                            :list="list"
                            class="w-72 bg-gray-200 max-h-full flex flex-col rounded-md"
                            >
                        </CardList>

                        <div class="w-72">
                           <CardListCreateForm :board="board" />
                        </div>
                    </div>
                </div>
            </div>

            <CardListItemModal :card="props.card"/>
    </AuthenticatedLayoutVue>
</template>