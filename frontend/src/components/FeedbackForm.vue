<template>
  <div class="container mt-5">
    <div class="d-flex justify-content-end mb-5">
      <a @click="logoutUser" href="#">Logout</a>
    </div>
    <h2 class="text-center">Add Feedback</h2>
    
    <form @submit.prevent="submitFeedback" class="col-md-6 mx-auto">
      <div class="mb-4">
        <button @click="feedbackListing" class="btn btn-primary">View All Feedbacks</button>
      </div>
      <div class="row mb-3">
        <label for="title" class="col-sm-3 col-form-label">Title:</label>
        <div class="col-sm-9">
          <input type="text" id="title" v-model="formData.title" class="form-control">
          <span v-if="formErrors.title" class="text-danger">{{ formErrors.title[0] }}</span>
        </div>
      </div>
      

      <div class="row mb-3">
        <label for="description" class="col-sm-3 col-form-label">Description:</label>
        <div class="col-sm-9">
          <textarea id="description" v-model="formData.description" class="form-control"></textarea>
          <span v-if="formErrors.description" class="text-danger">{{ formErrors.description[0] }}</span>
        </div>
      </div>
      

      <div class="row mb-3">
        <label for="category" class="col-sm-3 col-form-label">Category:</label>
        <div class="col-sm-9">
          <select id="category" v-model="formData.category" class="form-select">
            <option value="" disabled>Select category</option>
            <option value="bug report">Bug Report</option>
            <option value="feature request">Feature Request</option>
            <option value="improvement">Improvement</option>
          </select>
          <span v-if="formErrors.category" class="text-danger">{{ formErrors.category[0] }}</span>
        </div>
      </div>
      
      <div class="row mb-3">
        <div class="d-flex align-items-center justify-content-end">
          <div class="text-end">
            <button type="submit" class="btn btn-primary">Submit Feedback</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import { ref, nextTick } from 'vue';
import axios from 'axios';
import { logout } from '../utils/logout';
import { useRouter } from 'vue-router';

export default {
  mounted() {
    nextTick(() => {
      document.title = 'Feedback';
    });
  },
  setup() {

    const router = useRouter();

    const formData = ref({
      title: '',
      description: '',
      category: '',
    });

    const formErrors = ref({});

    const submitFeedback = async () => {
      try {
        const axiosConfig = {
          headers: {
            "Access-Control-Allow-Origin": "*",
            'Content-Type': 'application/json;',
            'Authorization': `Bearer ${localStorage.getItem('access_token')}`
          },
        };

        const response = await axios.post('http://127.0.0.1:8000/api/add-feedback', formData.value, axiosConfig);
        console.log(response);

        // Reset formErrors on successful submission
        formErrors.value = {};

        router.push('/feedback-listing');

      } catch (error) {
        console.error(error.response);

        if (error.response && error.response.status === 422) {
          // Handle validation errors
          formErrors.value = error.response.data.errors;
        } else {
          // Handle other errors
        }
      }

    };

    const logoutUser = async () => {
      await logout();
      location.reload();
    };

    const feedbackListing = async () => {
      router.push('/feedback-listing');
    };

    return {
      formData,
      formErrors,
      submitFeedback,
      logoutUser,
      feedbackListing
    };
  },
};
</script>

<style scoped>
/* Your component styles go here */
</style>
