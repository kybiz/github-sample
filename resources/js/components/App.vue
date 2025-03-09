<template>
    <div class="container mx-auto p-6">
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-6">GitHub Open Issues</h1>

        <!-- Error Message -->
        <div v-if="errorMessage" class="alert alert-danger text-center text-lg font-medium py-3">
            {{ errorMessage }}
        </div>

        <!-- No Open Tickets Placeholder -->
        <div v-else-if="tickets.length === 0" class="text-center text-gray-500 text-lg mt-6">
            <h3>No open tickets assigned to you.</h3>
        </div>

        <!-- Responsive Grid -->
        <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <div 
                v-for="(item, index) in tickets" 
                :key="index" 
                class="p-4 bg-white shadow-md rounded-lg transition hover:shadow-lg hover:scale-105 flex flex-col justify-between"
            >
                <!-- Title -->
                <div class="text-center">
                    <h5 class="text-xl font-semibold text-gray-900 mb-2">{{ item.title }}</h5>
                </div>

                <!-- Issue Details -->
                <div class="text-sm text-gray-700 flex-grow">
                    <p><b>Issue#:</b> {{ item.issue_number }}</p>
                    <p><b>Repository:</b> {{ item.repository_name }}</p>
                    <p class="line-clamp-3"><b>Description:</b> {{ item.body || "No description provided." }}</p>
                </div>

                <!-- Assignees -->
                <div v-if="item.assignees.length" class="mt-3">
                    <span class="font-bold text-gray-900">Assignees:</span>
                    <div class="flex items-center gap-2 mt-2">
                        <template v-for="(assignee, i) in item.assignees.slice(0, 2)" :key="i">
                            <img 
                                class="rounded-full border border-gray-300"
                                :src="assignee.avatar_url" 
                                height="40" 
                                width="40" 
                                alt="Assignee Avatar"
                                data-bs-toggle="tooltip" 
                                :data-bs-title="assignee.name"
                            >
                        </template>
                        <span v-if="item.assignees.length > 2" class="text-gray-700 text-sm font-medium">
                            +{{ item.assignees.length - 2 }} more
                        </span>
                    </div>
                </div>

                <!-- View Button -->
                <div class="flex justify-center mt-4">
                    <button 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-4 py-2 rounded-md shadow w-full"
                        @click="openModal(item)"
                    >
                        View Details
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div v-if="selectedTicket" class="modal fade show d-block" tabindex="-1" style="background: rgba(0, 0, 0, 0.5);">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-lg font-bold">Title: {{ selectedTicket.title }}</h5>
                    <button type="button" class="btn-close" @click="closeModal"></button>
                </div>

                <div class="modal-body text-gray-800">
                    <p><b>Issue#:</b> {{ selectedTicket.issue_number }}</p>
                    <p><b>Repository Name:</b> {{ selectedTicket.repository_name }}</p>
                    <p><b>Description:</b> {{ selectedTicket.body || "No description provided." }}</p>

                    <!-- Assignees (Limited to 2) -->
                    <span class="font-bold">Assignees:</span>
                    <div class="flex items-center gap-2 mt-2">
                        <template v-for="(assignee, i) in selectedTicket.assignees.slice(0, 2)" :key="i">
                            <img 
                                class="rounded-full border border-gray-300"
                                :src="assignee.avatar_url" 
                                height="50" 
                                width="50" 
                                alt="Assignee Avatar"
                                data-bs-toggle="tooltip" 
                                :data-bs-title="assignee.name"
                            >
                        </template>
                        <span v-if="selectedTicket.assignees.length > 2" class="text-gray-700 text-sm font-medium">
                            +{{ selectedTicket.assignees.length - 2 }} more
                        </span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="gotoGit(selectedTicket.url)">View on GitHub</button>
                    <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const tickets = ref([]);
const selectedTicket = ref(null);
const errorMessage = ref(null);

const fetchTickets = async () => {
    try {
        const response = await axios.get(`${window.location.origin}/git`);
        tickets.value = response.data.data;
    } catch (err) {
        console.error('Failed to load tickets:', err);
        errorMessage.value = err.response?.message || 'An error occurred while fetching tickets. Check your personal token';
    }
};

const openModal = (ticket) => {
    selectedTicket.value = ticket;
};

const closeModal = () => {
    selectedTicket.value = null;
};

const gotoGit = (url) => {
    const newUrl = url.replace("https://api.github.com/repos/", "https://github.com/");
    window.open(newUrl, "_blank");
}

onMounted(fetchTickets);
</script>
