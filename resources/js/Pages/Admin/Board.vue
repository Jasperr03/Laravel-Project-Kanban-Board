<template>
    <AdminAuthenticatedLayout>
        <div class="flex flex-wrap mb-2 pl-64 h-full overflow-y-hidden " id="userTable">
                <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4 w-full">
                
                    <div class="flex gap-2 items-center">
                        <h1 class="text-2xl text-gray-900 m-0">List of Cards</h1>
                          <button @click="downloadCSV" class="flex items-center gap-2 p-2 px-4 bg-blue-500 text-white rounded hover:bg-blue-700">
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512"><path d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z" fill="white"/>
                            </svg>
                            Download as CSV
                        </button>
                    </div>
                 
                      <input v-model="searchTerm" @keyup="performSearch" type="text" class="p-2 border rounded" placeholder="Search Card List..." />
                </div>
                <div class="overflow-x-auto w-full" >
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 rounded-s-lg">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="py-2 px-4 border-b">{{ headers[0].text }}</th>
                                <th class="py-2 px-4 border-b">{{ headers[1].text }}</th>
                              
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700" v-for="card in paginatedFilteredCards" :key="card.id">
                                <td class="w-4 p-4">{{ card.id }}</td>
                                <td class="w-4 p-4"  @click="openListModal(card.name, card.id, card.user_id, card.board_id )" >{{ card.name }}</td>  

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
                                    <span>Page {{ currentPage }} of {{ Math.ceil(filteredCards.length / itemsPerPage) }}</span>
                                </a>
                            </li>
                            <li> 
                            <button href="#" @click="currentPage++" :disabled="currentPage === Math.ceil(totalItems / itemsPerPage)" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</button>
                            </li>
                        </ul>
                    </nav>

                </div>
            </div>

            <div v-if="listModal" class="fixed  inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex  items-center justify-center">
              <!-- Modal content -->
              <div class="bg-white p-6 rounded-lg" style="width: 768px;">
                <div class="flex gap-2 items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold">{{ modalContent }}</h2>
                    <button @click="downloadListCSV(modalContent)" class="flex items-center gap-2 p-2 px-4 bg-blue-500 text-white rounded hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512"><path d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z" fill="white"/>
                        </svg> 
                    </button>
                </div>
                
                <table class=" w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 rounded-s-lg">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="py-2 px-4 border-b">{{ listTableheaders[0].text }}</th>
                            <th class="py-2 px-4 border-b">{{ listTableheaders[1].text }}</th>
                            <th class="py-2 px-4 border-b">{{ listTableheaders[2].text }}</th>
                            <th class="py-2 px-4 border-b">{{ listTableheaders[3].text }}</th>
                            <th class="py-2 px-4 border-b">{{ listTableheaders[4].text }}</th>
                            <th class="py-2 px-4 border-b">{{ listTableheaders[5].text }}</th>
                        </tr>
                    </thead>
                    <tbody class="h-50">
                        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700" v-for="listItem in paginatedLists" :key="listItem.id">
                            <td class="p-4">{{ listItem.id }}</td>
                            <td class="p-4">{{ listItem.title }}</td>
                            <td class="p-4">{{ listItem.description }}</td>
                            <td class="p-4">{{ listItem.created_at }}</td>
                            <td class="p-4">{{ listItem.completed_at }}</td>    
                            <td class="p-4">
                                <span class="py-2 px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700" :class="[listItem.status ? 'dark:bg-blue-800' : 'dark:bg-red-800']">
                                    {{ listItem.status }}
                                </span>
                            </td>
                        </tr>
                    </tbody>

                </table>
 

                <nav aria-label="Page navigation example">
                    <ul class="inline-flex -space-x-px justify-center mt-4 mb-4 text-sm">
                        <li>
                            <button @click="currentPageLists--" :disabled="currentPageLists === 1" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</button>
                        </li>
                        <li>
                            <span class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                <span>Page {{ currentPageLists }} of {{ Math.ceil(lists.length / itemsPerPageLists) }}</span>
                            </span>
                        </li>
                        <li>
                            <button @click="currentPageLists++" :disabled="currentPageLists === Math.ceil(lists.length / itemsPerPageLists)" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</button>
                        </li>
                    </ul>
                </nav>


                
                <button type="button" @click="closeListModal" class="mr-2 float-right px-4 py-2 text-white border bg-red-500 rounded">Cancel</button>
              </div>
            </div>

    </AdminAuthenticatedLayout>
</template>

<script setup>
    import AdminAuthenticatedLayout from '@/Layouts/AdminAuthenticatedLayout.vue';

    import { ref, computed } from 'vue';
    import _ from 'lodash';

    const props = defineProps(['cardlist']); // Expose users as a prop

    const cardList = props.cardlist;

    const performSearch = _.debounce(() => {
        currentPage.value = 1; // Reset current page when performing a new search
    }, 300);

    const filteredCards = computed(() => {
        const searchTermLower = searchTerm.value.toLowerCase();
        return cardList.filter(card =>
            card.name.toLowerCase().includes(searchTermLower) 
        );
    });

    const paginatedFilteredCards = computed(() => {
        const searchTermLower = searchTerm.value.toLowerCase();
        const filtered = cardList.filter(card =>
            card.name.toLowerCase().includes(searchTermLower)  
        );
        const startIndex = (currentPage.value - 1) * itemsPerPage.value;
        const endIndex = startIndex + itemsPerPage.value;
        return filtered.slice(startIndex, endIndex);
    });

    const currentPage = ref(1);
    const itemsPerPage = ref(10);
    const totalItems = computed(() => cardList.length);

    const paginatedCards = computed(() => {
        console.log('Calculating paginatedCards for currentPage:', currentPage.value);
        const startIndex = (currentPage.value - 1) * itemsPerPage.value;
        const endIndex = startIndex + itemsPerPage.value;
        return filteredCards.value.slice(startIndex, endIndex);
    });



    const headers = ref([
        { text: 'ID', value: 'id' },
        { text: 'Name', value: 'name' },  
    ]);

    const listTableheaders = ref([
        { text: 'ID', value: 'id' },
        { text: 'Title', value: 'title' },
        { text: 'Description', value: 'description' }, 
        { text: 'Date Created', value: 'created_at' },
        { text: 'Date Completed', value: 'completed_at' },
        { text: 'Status', value: 'status' }, 
    ]);

    const searchTerm = ref('');

    const downloadCSV = () => {
        let csvContent = "data:text/csv;charset=utf-8,"; // Change const to let

        // Add CSV header
        const headerRow = headers.value.map(header => header.text);
        csvContent += headerRow.join(",") + "\n";

        // Add CSV data rows
        filteredCards.value.forEach(card => {
            const rowData = headers.value.map(header => card[header.value]);
            csvContent += rowData.join(",") + "\n";
        });

        // Create a data URI and trigger a download
        const encodedURI = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedURI);
        link.setAttribute("download", "cards.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };

    // Modal 
    const modalContent = ref('');
    const listModal = ref(false);
    const lists = ref([]);

    // Function to fetch card list data
    const fetchList = (boardId, userID, cardListID) => {
        fetch(`/api/card/${boardId}/${userID}/${cardListID}`)
            .then(response => response.json())
            .then(data => {
                // Assuming `lists` and `modalContent` are reactive variables
                lists.value = data.card;
                console.log(data.card);
            })
            .catch(error => console.error('Error fetching card list:', error));
    };

    // Function to open the list modal
    const openListModal = (cardName, cardID, userID, cardListID) => {
        // Assuming `modalContent` and `listModal` are reactive variables
        modalContent.value = cardName;

        fetchList(cardListID, userID, cardID);  
        listModal.value = true;
    };


    const closeListModal = () => {
        listModal.value = false;
    };
 
    const currentPageLists = ref(1);
    const itemsPerPageLists = ref(5);

    const paginatedLists = computed(() => {
        const startIndex = (currentPageLists.value - 1) * itemsPerPageLists.value;
        const endIndex = startIndex + itemsPerPageLists.value;
        return lists.value.slice(startIndex, endIndex);
    });
    
    const downloadListCSV = (filename) => {
        let csvContent = "data:text/csv;charset=utf-8,"; // Change const to let

        // Add CSV header
        const headerRow = listTableheaders.value.map(header => header.text);
        csvContent += headerRow.join(",") + "\n";

        // Add CSV data rows
        paginatedLists.value.forEach(listItem => {
            const rowData = listTableheaders.value.map(header => listItem[header.value]); // Change headers to listTableheaders
            csvContent += rowData.join(",") + "\n";
        });

        // Create a data URI and trigger a download
        const encodedURI = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedURI);
        link.setAttribute("download", filename + "_lists.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };


</script>

<script>
export default {
    props: {
        cardlist: Array,
    },
}
</script>
 
