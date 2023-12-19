<template>
    <LeadAuthenticatedLayout>
        <div class="flex gap-2">
            <div class="mt-4 p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <h2>User Details</h2>
                <p>User ID: {{ $page.props.user.id }}</p>
                <p>User Name: {{ $page.props.user.name }}</p>
                <p>User Email: {{ $page.props.user.email }}</p>
                <!-- Fetch and display the rest of the user details here -->
            </div>
            <div class="mt-4 p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <h2>Boards</h2>
                 
                <!-- Fetch and display the rest of the user details here -->
            </div>
        </div>
    </LeadAuthenticatedLayout>  
</template>

<script setup>
    import LeadAuthenticatedLayout from '@/Layouts/LeadAuthenticatedLayout.vue';
    import { ref, computed } from 'vue'; 
    import _ from 'lodash';
</script>

<script>
export default {
    props: {
        user: Object, 
    },
    mounted() {
        this.fetchUserDetails(this.user.id);
    },
    methods: {
        fetchUserDetails(userId) {
            console.log('Fetching user details for user ID:', userId);
        },
    },
};
</script>
