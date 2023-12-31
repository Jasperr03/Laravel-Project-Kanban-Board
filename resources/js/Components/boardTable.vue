<script>
import { ref, onMounted, computed } from 'vue';
import dayjs from 'dayjs';
import axios from 'axios';

export default {
    data() {
        return {
            boards: [], // Store fetched boards here
            searchTerm: '', // Search term for filtering boards
            filteredBoards: [], // Store filtered boards here
            currentPage: 1, // Current page
            itemsPerPage: 6, // Number of items per page
            totalItems: 0, // Total number of items
        };
    },
    methods: {
        async fetchBoards() {
            try {
                const response = await axios.get('/api/getBoards');
                this.boards = response.data.boards;
                this.totalItems = this.boards.length;
                this.filteredBoards = this.boards;
            } catch (error) {
                console.error('Error fetching boards', error);
            }
        },
        performSearch() {
            this.filteredBoards = this.boards.filter(board =>
                board.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                (board.user && board.user.name.toLowerCase().includes(this.searchTerm.toLowerCase()))
            );
            this.totalItems = this.filteredBoards.length;
        },
        formatDate(date) {
            return dayjs(date).format('YYYY-MM-DD HH:mm:ss');
        },
        downloadCSV() {
            let csvContent = '';
            const headers = ['ID', 'Name', 'Owner', 'Date Created', 'Date Accomplished'];

            csvContent += headers.join(',') + '\n';

            this.filteredBoards.forEach(board => {
                const rowData = [
                    board.id,
                    board.name,
                    board.user ? `${board.user.name} - ${board.user.email}` : 'N/A',
                    this.formatDate(board.created_at),
                    this.formatDate(board.updated_at),
                ];
                csvContent += rowData.join(',') + '\n';
            });

            // Create a Blob and generate a URL to trigger the download
            let blob = new Blob([csvContent], { type: 'text/csv' });
            const url = URL.createObjectURL(blob);

            // Create a link and trigger the download
            const link = document.createElement('a');
            link.href = url;
            link.download = 'boards.csv';
            document.body.appendChild(link);
            link.click();

            // Clean up
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        },

    },
    computed: {
        paginatedBoards() {
            const startIndex = (this.currentPage - 1) * this.itemsPerPage;
            const endIndex = startIndex + this.itemsPerPage;
            return this.filteredBoards.slice(startIndex, endIndex);
        },
    },
    mounted() {
        this.fetchBoards();
    },
};
</script>

<template>
  <div>
    <div
      class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between w-full mb-4">

      <div class="flex gap-4 items-center ">
        <h2 class="mb-0 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Boards</h2>
        <button @click="downloadCSV"
          class="px-2 py-1 bg-blue-500 text-white rounded-md">
          <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512">
            <path
              d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"
              fill="white" />
          </svg>
          
        </button>
      </div>

      <input v-model="searchTerm" @keyup="performSearch" type="text" class="p-2 border rounded"
        placeholder="Search Boards..." />
    </div>
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 rounded-s-lg">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
          <th class="py-2 px-4 border-b">ID</th>
          <th class="py-2 px-4 border-b">Name</th>
          <th class="py-2 px-4 border-b">Owner</th>
          <th class="py-2 px-4 border-b">Status</th>
          <th class="py-2 px-4 border-b">Date Created</th>
          <!-- Add more columns if needed -->
        </tr>
      </thead>
      <tbody>
        <tr
            class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700"
            v-for="board in paginatedBoards" :key="board.id">
          <td class="w-4 p-2 py-4">{{ board.id }}</td>
          <td class="w-4 p-2 py-4">
            <a :href="route('admin.board', board.id)">
                {{ board.name }}
            </a>
          </td>
          <td class="w-4 p-2 py-4">{{ board.user.name }}</td>
          <td class="w-4 p-2 py-4 text-white">
            <span class="uppercase py-2 px-4 rounded" :class="[`${ board.archived ? 'bg-red-800':'bg-green-800'}`]">
              {{ board.archived ? 'Archived' : 'Active' }}
            </span>
          </td>
          <td class="w-4 p-2 py-4">{{ formatDate(board.created_at) }}</td>
          <!-- Add more columns if needed -->
        </tr>
      </tbody>
    </table>
    <nav aria-label="Page navigation example">
        <ul class="inline-flex -space-x-px justify-center mt-4  text-sm">
        <li>
            <button @click="currentPage--" :disabled="currentPage === 1"
            class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</button>
        </li>
        <li>
            <a href="#"
            class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
            <span>Page {{ currentPage }} of {{ Math.ceil(totalItems / itemsPerPage) }}</span>
            </a>
        </li>
        <li>
            <button href="#" @click="currentPage++"
            :disabled="currentPage === Math.ceil(totalItems / itemsPerPage)"
            class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</button>
        </li>
        </ul>
    </nav>
  </div>
</template>

<style scoped>
  /* Add your styles if necessary */

</style>
