<template>
    <AdminAuthenticatedLayout>
        <div class="flex flex-wrap mb-2 pl-64 h-full overflow-y-hidden " id="userTable">
            <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4 w-full">
                
                <div class="flex gap-2 items-center">
                    <h1 class="text-2xl text-gray-900 m-0">List of Users</h1>
                      <button @click="downloadCSV" class="flex items-center gap-2 p-2 px-4 bg-blue-500 text-white rounded hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512"><path d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z" fill="white"/>
                        </svg>
                        Download as CSV
                    </button>
                </div>
                 
                  <input v-model="searchTerm" @keyup="performSearch" type="text" class="p-2 border rounded" placeholder="Search users..." />
            </div>
            <div class="overflow-x-auto w-full" >
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 rounded-s-lg">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="py-2 px-4 border-b">{{ headers[0].text }}</th>
                            <th class="py-2 px-4 border-b">{{ headers[1].text }}</th>
                            <th class="py-2 px-4 border-b">{{ headers[2].text }}</th>
                            <th class="py-2 px-4 border-b">{{ headers[3].text }}</th>
                            <th class="py-2 px-4 border-b">{{ headers[4].text }}</th>
                             
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700" v-for="user in paginatedFilteredUsers" :key="user.id">
                            <td class="w-4 p-4">{{ user.id }}</td>
                            <td class="w-4 p-4">{{ user.name }}</td>
                            <td class="w-4 p-4">{{ user.email }}</td>
                            <td class="w-4 p-4">{{ user.lead ? user.lead.name : 'N/A' }}</td>
                            <td class="w-4 p-4">{{ dayjs(user.created_at).format('YYYY-MM-DD HH:mm:ss') }}</td>


                        </tr>
                    </tbody>

                </table>

                

                <nav aria-label="Page navigation example">
                    <ul class="inline-flex -space-x-px justify-center mt-4  text-sm">
                        <li>
                        <button  @click="currentPage--" :disabled="currentPage === 1" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</button>
                        </li>
                        <li>
                            <a href="#" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                <span>Page {{ currentPage }} of {{ Math.ceil(filteredUsers.length / itemsPerPage) }}</span>
                            </a>
                        </li>
                        <li> 
                        <button href="#" @click="currentPage++" :disabled="currentPage === Math.ceil(totalItems / itemsPerPage)" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</button>
                        </li>
                    </ul>
                    </nav>

            </div>
        </div>

        
    </AdminAuthenticatedLayout>
</template>

<script setup>
import AdminAuthenticatedLayout from '@/Layouts/AdminAuthenticatedLayout.vue';
import { ref, computed } from 'vue';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import _ from 'lodash';



dayjs.extend(relativeTime);

const formattedUsers = computed(() => {
    return filteredUsers.value.map(user => ({
        ...user,
        created_at: dayjs(user.created_at).fromNow(),
    }));
});

const props = defineProps(['users']); // Expose users as a prop


const performSearch = _.debounce(() => {
    currentPage.value = 1; // Reset current page when performing a new search
}, 300);

const filteredUsers = computed(() => {
    const searchTermLower = searchTerm.value.toLowerCase();
    return props.users.filter(user =>
        user.name.toLowerCase().includes(searchTermLower) ||
        user.email.toLowerCase().includes(searchTermLower)
    );
});

const paginatedFilteredUsers = computed(() => {
    const searchTermLower = searchTerm.value.toLowerCase();
    const filtered = props.users.filter(user =>
        user.name.toLowerCase().includes(searchTermLower) ||
        user.email.toLowerCase().includes(searchTermLower)
    );
    const startIndex = (currentPage.value - 1) * itemsPerPage.value;
    const endIndex = startIndex + itemsPerPage.value;
    return filtered.slice(startIndex, endIndex);
});

const currentPage = ref(1);
const itemsPerPage = ref(10); 

const totalItems = computed(() => props.users.length);

const paginatedUsers = computed(() => {
    console.log('Calculating paginatedUsers for currentPage:', currentPage.value);
    const startIndex = (currentPage.value - 1) * itemsPerPage.value;
    const endIndex = startIndex + itemsPerPage.value;
    return props.users.slice(startIndex, endIndex);
});

const headers = ref([
    { text: 'ID', value: 'id' },
    { text: 'Name', value: 'name' },
    { text: 'Email', value: 'email' },
    { text: 'Lead', value: 'lead' },
    { text: 'Created At', value: 'created_at' },
]);

const searchTerm = ref('');

const downloadCSV = () => {
    const timestamp = new Date().toISOString().replace(/[-T:\.Z]/g, '');
    const filename = `users_${timestamp}.csv`;

    const csvContent = "data:text/csv;charset=utf-8," +
        "ID,Name,Email,Project Leader,Created At\n" +
        filteredUsers.value.map(user =>
            `${user.id},${user.name},${user.email},${ user.lead ? user.lead.name : 'N/A' }, ${user.created_at}`
        ).join("\n");

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", filename);
    document.body.appendChild(link);
    link.click();
};

</script>

<script>
export default {
    props: {
        users: Array,
    },
}
</script>

<style scoped>
/* Add style for the canvas text color */
#userTable { 
    position: relative;
    z-index: 90;

} 
</style>
