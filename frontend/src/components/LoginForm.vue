<template>
  <div class="container mt-5">
    <h2 class="text-center">Login Form</h2>
    
    <form @submit.prevent="loginUser" class="col-md-6 mx-auto">
      <div v-if="formErrors.message" class="row mt-3 mb-2">
        <label class="col-sm-3 col-form-label"></label>
        <div class="col-sm-9">
          <span class="text-danger">{{ formErrors.message }}</span>
        </div>
      </div>
      <div class="row mb-3">
        <label for="email" class="col-sm-3 col-form-label">Email:</label>
        <div class="col-sm-9">
          <input type="email" id="email" v-model="formData.email" class="form-control">
          <span v-if="formErrors.email" class="text-danger">{{ formErrors.email[0] }}</span>
        </div>
      </div>

      <div class="row mb-3">
        <label for="password" class="col-sm-3 col-form-label">Password:</label>
        <div class="col-sm-9">
          <input type="password" id="password" v-model="formData.password" class="form-control">
          <span v-if="formErrors.password" class="text-danger">{{ formErrors.password[0] }}</span>
        </div>
      </div>

      <div class="row mb-3">
        <div class="d-flex align-items-center justify-content-between">
          <a href="/register">Go to Register</a>
          <div class="text-end">
            <button type="submit" class="btn btn-primary">Login</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import { ref, nextTick } from 'vue';
import axios from 'axios';

export default {
  mounted() {
    nextTick(() => {
      document.title = 'Login';
    });
  },
  setup() {
    console.log(localStorage.getItem('access_token'));
    const formData = ref({
      email: '',
      password: '',
    });

    const formErrors = ref({});

    const loginUser = async () => {
      try {
        const axiosConfig = {
          headers: {
            "Access-Control-Allow-Origin": "*",
            'Content-Type': 'application/json;',
          },
        };
        const response = await axios.post('http://127.0.0.1:8000/api/login', formData.value, axiosConfig);        
        const accessToken = response.data.token;
        localStorage.setItem('access_token', accessToken);
        formErrors.value = {};

        location.reload();
        
      } catch (error) {
        console.error(error.response);

        if (error.response && error.response.status === 401) {
          formErrors.value = { message: 'Invalid credentials' };
        } else if (error.response && error.response.status === 422) {
          formErrors.value = error.response.data.errors;
        } 
      }
    };

    return {
      formData,
      formErrors,
      loginUser,
    };
  },
};
</script>

<style scoped>
/* Your component styles go here */
</style>
