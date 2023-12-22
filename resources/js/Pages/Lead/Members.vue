<template>
    <LeadAuthenticatedLayout>
        <div class="">
            <div class="max-w-5xl mx-auto mt-4 p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4 w-full">
                    <div class="flex gap-2 items-center">
                        <h1 class="text-2xl text-gray-900 m-0">List of Users</h1>
                          <button @click="openCreateMemberModal" class="flex items-center gap-2 p-2 px-4 bg-blue-500 text-white rounded hover:bg-blue-700">
                            Create Member
                        </button>
                    </div>

                      <input v-model="searchTerm" @keyup="performSearch" type="text" class="p-2 border rounded" placeholder="Search users..." />
                </div>
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 rounded-s-lg">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="py-2 px-4 border-b">{{ headers[0].text }}</th>
                                <th class="py-2 px-4 border-b">{{ headers[1].text }}</th>
                                <th class="py-2 px-4 border-b">{{ headers[2].text }}</th>
                                <th class="py-2 px-4 border-b">{{ headers[3].text }}</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700"
                                v-for="user in paginatedFilteredUsers" :key="user.id">
                                <td class="w-4 p-4">{{ user.id }}</td>
                                <td class="w-4 p-4">
                                    <a :href="route('lead.member.show', user.id)">
                                        {{ user.name }}
                                    </a>
                                </td>
                                <td class="w-4 p-4">{{ user.email }}</td>
                                <td class="w-4 p-4">{{ user.created_at }}</td>
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
        </div>

        <!-- Modal overlay -->
        <div v-if="isCreateMemberModalOpen" class="fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center">
          <!-- Modal content -->
          <div class="bg-white p-6 rounded-lg w-96">
            <h2 class="text-lg font-semibold mb-4">Create Member</h2>
            <!-- Your form fields go here -->
            <form @submit.prevent="submitCreateMemberForm"> 

              <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input v-model="newMember.name" type="text" id="name" name="name" class="mt-1 p-2 border rounded w-full">
              </div>
 
              <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input v-model="newMember.email" type="email" id="email" name="email" class="mt-1 p-2 border rounded w-full">
              </div>

              <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input v-model="newMember.password" type="password" id="password" name="password" class="mt-1 p-2 border rounded w-full">
              </div>

              <!-- Add other form fields as needed -->

              <div class="flex justify-end">
                <button type="button" @click="closeCreateMemberModal" class="mr-2 px-4 py-2 text-gray-500 border rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Create</button>
              </div>
            </form>
          </div>
        </div>
    </LeadAuthenticatedLayout>
</template>


<script setup>
import LeadAuthenticatedLayout from '@/Layouts/LeadAuthenticatedLayout.vue';
import { ref, computed  } from 'vue';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import _ from 'lodash';
import axios from 'axios';

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
    { text: 'Created At', value: 'created_at' },
]);

const searchTerm = ref('');

// Modal 
const isCreateMemberModalOpen = ref(false);

const openCreateMemberModal = () => {
    isCreateMemberModalOpen.value = true;
};

const closeCreateMemberModal = () => {
    isCreateMemberModalOpen.value = false;
};

const newMember = ref({
    name: '',
    email: '', 
    password: '', 
});

const submitCreateMemberForm = async () => {
    try {
        // Make an API call to create a new member using newMember.value data
        const response = await axios.post('/lead/member/create', newMember.value); // Replace with your actual API endpoint
        const createdMember = response.data;

        // Assuming the API returns the created member details, you can handle the response here
        console.log('Created Member:', createdMember);
 
 
        // Close the modal after successful submission
        closeCreateMemberModal();
    } catch (error) {
        console.error('Error creating member:', error);
        // Handle errors
    }
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
#userTable {
    position: relative;
    z-index: 90;
}
</style>