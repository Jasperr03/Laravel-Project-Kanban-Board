<script setup> 
import UserGraph from '@/Components/usersGraph.vue';  // Adjust the path based on your project structure
import cardsGraph from '@/Components/cardsGraph.vue';  // Adjust the path based on your project structure
import boardsGraph from '@/Components/boardsGraph.vue';  // Adjust the path based on your project structure
import boardTable from '@/Components/boardTable.vue';
import AdminAuthenticatedLayout from '@/Layouts/AdminAuthenticatedLayout.vue';

</script>

<template>
    <AdminAuthenticatedLayout>

        <div class="bg-slate-600 grid grid-cols-2 gap-4 mb-10 pl-64 h-full overflow-y-hidden pb-10">

            <div class="p-4 text-white bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <UserGraph :data="userData"/> 
            </div>

            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <cardsGraph :data="cardData"/>
            </div>

            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <boardsGraph :data="boardData"/>
            </div>

            <div class="px-2 py-4 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <boardTable :data="boardTableData"/>
            </div>

        </div> 

        
    </AdminAuthenticatedLayout>
</template>

<script>
export default {
    data() {
        return {
            userData: {
                // ... data structure expected by UsersGraph
            },
            cardData: {
                // ... data structure expected by UsersGraph
            },
            boardData: {
                // ... data structure expected by UsersGraph
            },
            boardTableData: {
                // ... data structure expected by UsersGraph
            }
        };
    }
}
</script>
