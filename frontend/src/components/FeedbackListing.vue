<template>
  <div class="container mt-5">
    <div class="d-flex justify-content-end mb-5">
      <a @click="logoutUser" href="#">Logout</a>
    </div>
    <h2 class="text-center">Feedback Listing</h2>
    <div class="">
      <button @click="addFeedback" class="btn btn-primary">Add Feedback</button>
    </div>
    <table class="table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Category</th>
          <th>User</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="feedback in feedbacks" :key="feedback.id">
          <td>
            <router-link :to="{ name: 'feedbackDetail', params: { id: feedback.id } }">
              <strong>{{ feedback.title }}</strong>
            </router-link>
          </td>
          <td>{{ feedback.category }}</td>
          <td>{{ feedback.user.name }}</td>
        </tr>
      </tbody>
    </table>

    <nav class="d-flex justify-content-center">
      <ul class="pagination">
        <!-- Previous Page Button -->
        <li class="page-item" :class="{ disabled: currentPage === 1 }">
          <a class="page-link" @click.prevent="loadFeedbacks(currentPage - 1)" href="#" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>

        <!-- Page Buttons -->
        <li class="page-item" v-for="page in totalPages" :key="page" :class="{ active: page === currentPage }">
          <a class="page-link" @click.prevent="loadFeedbacks(page)" href="#">
            {{ page }}
          </a>
        </li>

        <!-- Next Page Button -->
        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
          <a class="page-link" @click.prevent="loadFeedbacks(currentPage + 1)" href="#" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { logout } from '../utils/logout';
import { useRouter } from 'vue-router';

export default {
  setup() {
    const feedbacks = ref([]);
    const currentPage = ref(1);
    const totalPages = ref(1);

    const router = useRouter();

    const loadFeedbacks = async (page) => {
      try {
        const axiosConfig = {
          headers: {
            "Access-Control-Allow-Origin": "*",
            'Content-Type': 'application/json;',
            'Authorization': `Bearer ${localStorage.getItem('access_token')}`
          },
        };

        const response = await axios.get(`http://127.0.0.1:8000/api/feedback-listing`, {
          params: {
              page_number: page
          },
          ...axiosConfig
        });
        feedbacks.value = response.data.feedbacks;
        totalPages.value = response.data.total_pages;
        currentPage.value = page;
      } catch (error) {
        console.error(error.response);
      }
    };

    const logoutUser = async () => {
      await logout();
      location.reload();
    };

    const addFeedback = () => {
      router.push('/feedback-form');
    };

    onMounted(() => {
      loadFeedbacks(currentPage.value);
    });

    return {
      feedbacks,
      currentPage,
      totalPages,
      loadFeedbacks,
      logoutUser,
      addFeedback
    };
  },
};
</script>

<style scoped>
/* Your component styles go here */
</style>
