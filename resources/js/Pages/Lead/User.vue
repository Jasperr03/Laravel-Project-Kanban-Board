<template>
    <LeadAuthenticatedLayout>
        <div class="max-w-5xl mx-auto mt-4 p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div class="mt-4 p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <h2 class="text-xl mb-2">User Details</h2> 
                <p class="text-lg"> {{ user.name }}</p>
                <p class="font-semibold text-xs text-blue-600/50"> {{ user.email }}</p>
            </div>

            <div class="flex gap-2">
                <div class="w-1/3 mt-4 p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex justify-between align-center mb-4">
                        <h2 class="text-xl mb-2">Boards</h2>
                        <button @click="openCreateBoardModal" class="flex items-center gap-2 p-2 px-4 bg-blue-500 text-white rounded hover:bg-blue-700">
                            Create Board
                        </button>
                    </div>

                    <!-- Display boards -->
                    <ul class="max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400">
                        <button class="w-full flex items-center gap-2 p-1 px-4 bg-blue-500 text-white rounded hover:bg-blue-700"
                            v-for="board in boards" :key="board.id" @click="fetchCardList(board.id, board.name)">
                            {{ board.name }}
                            <!-- Display other board details as needed -->
                        </button>
                    </ul>
                </div>
                <div class="w-full mt-4 p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex justify-between align-center mb-4">
                        <h2 class="text-xl mb-2">Card List</h2>
                        <button @click="printTable" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">
                            Print
                        </button>
                    </div>

                    <!-- Display selected board name -->
                    <p class="mb-4" v-if="selectedBoardName">Board: <span class="font-semibold text-lg">{{ selectedBoardName }}</span></p>

                    <!-- Display cards -->
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Card List</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cards</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="card in cardList" :key="card.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ card.name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <ul class="max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400">
                                        <li v-for="list in card.cards" :key="list.id">
                                            {{ list.title }}
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal for creating a new board -->
        <div v-show="isCreateBoardModalVisible" class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white w-1/2 p-6 rounded-lg shadow-xl">
                    <h2 class="text-xl mb-4">Create Board</h2>
                    <form @submit.prevent="createBoard">
                        <label for="boardName" class="block text-sm font-medium text-gray-700">Board Name</label>
                        <input v-model="newBoardName" type="text" id="boardName" name="boardName" class="mt-1 p-2 w-full border-gray-300 rounded-md" required>

                        <div class="mt-4">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Create Board</button>
                            <button type="button" @click="closeCreateBoardModal" class="ml-2 px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </LeadAuthenticatedLayout>
</template>

<script setup>
import LeadAuthenticatedLayout from '@/Layouts/LeadAuthenticatedLayout.vue';
import { ref, onMounted, defineProps } from 'vue';

const { user } = defineProps(['user']);
const boards = ref([]);
const cardList = ref([]);
const selectedBoardName = ref('');

const isCreateBoardModalVisible = ref(false);
const newBoardName = ref('');

onMounted(() => {
    fetchUserDetails(user.id);
});

const fetchUserDetails = (userId) => {
    console.log('Fetching user details for user ID:', userId);
    // Assuming you have an API endpoint to fetch boards for a user
    // Replace 'your_api_endpoint' with the actual API endpoint
    fetch(`/api/boards/${userId}`)
        .then(response => response.json())
        .then(data => {
            // Assuming the response has a 'board' property containing an array of boards
            boards.value = data.board;
        })
        .catch(error => console.error('Error fetching boards:', error));
};

const fetchCardList = (boardId, boardName) => {
    console.log('Fetching card list for board ID:', boardId);
    // Assuming you have an API endpoint to fetch card list for a board
    // Replace 'your_api_endpoint' with the actual API endpoint
    fetch(`/api/card-lists/${boardId}/${user.id}`)
        .then(response => response.json())
        .then(data => {
            // Assuming the response has a 'card_list' property containing an array of cards
            cardList.value = data.card_list;
            selectedBoardName.value = boardName; // Set the selected board name
        })
        .catch(error => console.error('Error fetching card list:', error));
};

const createBoard = () => {
    // Make an API call to create a new board
    // Replace 'your_api_endpoint' with the actual API endpoint
    fetch('/api/boards', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            name: newBoardName.value,
            user_id: user.id,
        }),
    })
        .then(response => response.json())
        .then(data => {
            // Assuming the response has a 'board' property containing the newly created board
            // You can add it to the 'boards' array if needed
            boards.value.push(data.board);
            closeCreateBoardModal();
        })
        .catch(error => console.error('Error creating board:', error));
};

const openCreateBoardModal = () => {
    isCreateBoardModalVisible.value = true;
};

const closeCreateBoardModal = () => {
    isCreateBoardModalVisible.value = false;
    newBoardName.value = ''; // Clear the input field
};

const printTable = () => {
    // Open the print dialog
    window.print();
};
</script>


<style scoped>
@media print {
    /* Hide elements other than the table when printing */
    #app > div > div > main > div.max-w-5xl.mx-auto.mt-4.p-6.bg-white.border.border-gray-200.rounded-lg.shadow.dark\:bg-gray-800.dark\:border-gray-700 > div.flex.gap-2 > div.w-1\/3.mt-4.p-6.bg-white.border.border-gray-200.rounded-lg.shadow.dark\:bg-gray-800.dark\:border-gray-700 > div > button,
     #app > div > div > main > div.max-w-5xl.mx-auto.mt-4.p-6.bg-white.border.border-gray-200.rounded-lg.shadow.dark\:bg-gray-800.dark\:border-gray-700 > div.flex.gap-2 > div.w-full.mt-4.p-6.bg-white.border.border-gray-200.rounded-lg.shadow.dark\:bg-gray-800.dark\:border-gray-700 > div > button{
        display: none;
     }
}
</style>
