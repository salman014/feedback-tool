<template>
  <div class="container mt-5">
    <h2 class="text-center">Register Form</h2>
    <form @submit.prevent="registerUser" class="col-md-6 mx-auto">
      <div class="row mb-3">
        <label for="name" class="col-sm-3 col-form-label">Name:</label>
        <div class="col-sm-9">
          <input type="text" id="name" v-model="formData.name" class="form-control">
          <span v-if="formErrors.name" class="text-danger">{{ formErrors.name[0] }}</span>
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
          <a href="/login">Go to Login</a>
          <div class="text-end">
            <button type="submit" class="btn btn-primary">Register</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import { ref, nextTick } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

export default {
  mounted() {
    nextTick(() => {
      document.title = 'Register';
    });
  },
  setup() {

    const router = useRouter();

    const formData = ref({
      name: '',
      email: '',
      password: '',
    });

    const formErrors = ref({});

    const registerUser = async () => {
      try {
        const axiosConfig = {
          headers: {
            "Access-Control-Allow-Origin": "*",
            'Content-Type': 'application/json;',
          },
        };
        await axios.post('http://127.0.0.1:8000/api/register', formData.value, axiosConfig);
        formErrors.value = {};
        router.push('/login');

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

    return {
      formData,
      formErrors,
      registerUser,
    };
  },
};
</script>


<style scoped>

</style>
