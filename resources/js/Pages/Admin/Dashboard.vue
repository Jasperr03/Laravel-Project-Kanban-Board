<script setup>
import AdminAuthenticatedLayout from '@/Layouts/AdminAuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import axios from "axios";
import { ref, onMounted } from 'vue';


const users = ref()
const getUsers = () => {
    axios.get('/api/users')
    .then(res => users.value = res.data)
    .catch(error => console.log(error))
}


onMounted(() => getUsers()
);


const boards = ref()
const getBoards = () => {
    axios.get('/api/boards')
    .then(res => boards.value = res.data)
    .catch(error => console.log(error))
}

onMounted(() => getBoards()
);

const cards = ref()
const getCards = () => {
    axios.get('/api/cards')
    .then(res => cards.value = res.data)
    .catch(error => console.log(error))
}

onMounted(() => getCards()
);

const admins = ref()
const getAdmins = () => {
    axios.get('/api/admins')
    .then(res => admins.value = res.data)
    .catch(error => console.log(error))
}

onMounted(() => getAdmins()
);
</script>

<script>
import { ref, onMounted } from 'vue';
import { getUserCount, userCount } from '@/userCount';
import { getBoardCount, boardCount } from '@/boardCount';
import { getTaskCount, taskCount } from '@/taskCount';
import { getAdminCount, adminCount } from '@/adminCount';
import UserGraph from '@/Components/usersGraph.vue';  // Adjust the path based on your project structure
import cardsGraph from '@/Components/cardsGraph.vue';  // Adjust the path based on your project structure
import boardsGraph from '@/Components/boardsGraph.vue';  // Adjust the path based on your project structure
import boardTable from '@/Components/boardTable.vue';

export default {
    setup() {
        onMounted (getUserCount), (getBoardCount), (getTaskCount), (getAdminCount);
        return { userCount, boardCount, taskCount, adminCount };
    },
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
};
</script>


<template>
    <Head title="Admin Dashboard"/>
    <AdminAuthenticatedLayout>

    <div class="grid grid-cols-4 gap-4 mb-4 pl-64 overflow-y-hidden">
        <div class="w-full">

            <div class="bg-gradient-to-b from-blue-600 via-purple-500 to-indigo-700 border rounded-lg shadow p-2">
                <div class="flex flex-row items-center">
                    <div class="flex-shrink pl-1 pr-4"><i class="fa fa-wallet fa-2x fa-fw fa-inverse"></i></div>
                    <div class="flex-1 text-right">
                        <h5 class="text-white">Total projects</h5>
                        <ul>
                            <li v-for="board in boards" :key="board.id">
                                <h3 class="text-white text-3xl">{{ board }} <span class="text-orange-400"><i class="fas fa-caret-up"></i></span></h3>
                            </li>
                        </ul> 
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full">
            <div class="bg-gradient-to-b from-yellow-300 via-orange-400 to-yellow-500 border rounded-lg shadow p-2">
                <div class="flex flex-row items-center">
                    <div class="flex-shrink pl-1 pr-4"><i class="fas fa-users fa-2x fa-fw fa-inverse"></i></div>
                    <div class="flex-1 text-right">
                        <h5 class="text-white">Total Tasks</h5>
                        <ul>
                            <li v-for="card in cards" :key="card.id">
                                <h3 class="text-white text-3xl">{{ card }} <span class="text-orange-400"><i class="fas fa-caret-up"></i></span></h3>
                            </li>
                        </ul> 
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full">
            <div class="bg-gradient-to-b from-teal-400 via-teal-500 to-teal-600 border rounded-lg shadow p-2">
                <div class="flex flex-row items-center">
                    <div class="flex-shrink pl-1 pr-4"><i class="fas fa-user-plus fa-2x fa-fw fa-inverse"></i></div>
                    <div class="flex-1 text-right pr-1">
                        <h5 class="text-white">Total users</h5>
                        <ul>
                            <li v-for="user in users" :key="user.id">
                                <h3 class="text-white text-3xl">{{ user }} <span class="text-orange-400"><i class="fas fa-caret-up"></i></span></h3>
                            </li>
                        </ul>     
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full">
            <div class="bg-gradient-to-b from-rose-500 via-rose-600 to-rose-700 border rounded-lg shadow p-2">
                <div class="flex flex-row items-center">
                    <div class="flex-shrink pl-1 pr-4"><i class="fas fa-server fa-2x fa-fw fa-inverse"></i></div>
                    <div class="flex-1 text-right">
                        <h5 class="text-white">Total admins</h5>
                        <ul>
                            <li v-for="admin in admins" :key="admin.id">
                                <h3 class="text-white text-3xl">{{ admin }} <span class="text-orange-400"><i class="fas fa-caret-up"></i></span></h3>
                            </li>
                        </ul>  
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-10 pl-64 h-full overflow-y-hidden pb-10">

        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
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