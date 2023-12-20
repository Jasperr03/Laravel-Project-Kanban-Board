<!-- AddMemberModal.vue -->
<template>
    <div class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close" @click="closeModal">&times;</span>
            <!-- Your form to add a new member goes here -->
            <form @submit.prevent="addMember">
                <!-- Add form fields based on your schema -->
                <label for="name">Name:</label>
                <input type="text" v-model="newMember.name" required>

                <label for="email">Email:</label>
                <input type="email" v-model="newMember.email" required>

                <!-- Add more fields as needed -->

                <button type="submit">Add Member</button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const newMember = ref({
    name: '',
    email: '',
    // Add more properties based on your schema
});

const closeModal = () => {
    // Close the modal
};

const addMember = async () => {
    try {
        const response = await axios.post('/api/members', newMember.value);
        console.log(response.data); // Handle the response as needed

        // Optionally, emit an event to inform the parent component about the new member
        // $emit('memberAdded', response.data.member);

        closeModal();
    } catch (error) {
        console.error('Error adding member:', error);
        // Handle errors
    }
};

</script>

<style scoped>
/* Add your modal styles here */
</style>
