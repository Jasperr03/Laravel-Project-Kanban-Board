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

export default {
    setup() {
        onMounted (getUserCount), (getBoardCount), (getTaskCount), (getAdminCount);
        return { userCount, boardCount, taskCount, adminCount };
    },
};
</script>


<template>
    <Head title="Admin Dashboard"/>
    <AdminAuthenticatedLayout>

        <div class="flex flex-wrap mb-2 pl-56 h-full overflow-y-hidden">
    <div class="w-full md:w-1/2 xl:w-1/3 pt-3 px-3 md:pr-2">

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
                    <a class="">
                        <span class="text-white text-sm hover:cursor-text hover:text-gray-300">view details</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full md:w-1/2 xl:w-1/3 pt-3 px-3 md:pl-2">
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
                    <a class="">
                        <span class="text-white text-sm hover:cursor-text hover:text-gray-300">view details</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full md:w-1/2 xl:w-1/3 pt-3 px-3 md:pr-2 xl:pr-3 xl:pl-1">
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
                    <a class="">
                        <span class="text-white text-sm hover:cursor-text hover:text-gray-300">view details</span>
                    </a>        
                </div>
            </div>
        </div>
    </div>
    <div class="w-full md:w-1/2 xl:w-1/3 pt-3 px-3 md:pl-2 xl:pl-3 xl:pr-2">
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
                    <a class="">
                        <span class="text-white text-sm hover:cursor-text hover:text-gray-300">view details</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

    </AdminAuthenticatedLayout>
</template>