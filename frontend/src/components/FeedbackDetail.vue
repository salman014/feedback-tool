<template>
  <div class="container mt-5">
    <div class="col-md-6 m-auto">
      <div class="d-flex justify-content-end mb-5">
        <a @click="logoutUser" href="#">Logout</a>
      </div>
      <h2 class="text-center">Feedback Detail</h2>

      <div class="mb-4">
        <button @click="feedbackListing" class="btn btn-primary">Go Back</button>
      </div>

      <div>
        <!-- Display Feedback Details -->
        <h3 class="text-center">{{ feedback.title }}</h3>
        <p>{{ feedback.description }}</p>
        <p class="badge bg-info">{{ feedback.category }}</p>
        <p v-if="feedback.user">Submited By: <i><small>{{ feedback.user.name }}</small></i></p>

        <!-- Quill Editor for Adding Comments -->
        

        <!-- Display Comments -->
        <h4>Comments</h4>
        <div>
          <div v-for="comment in comments" :key="comment.id" class="bg-light p-3 rounded mb-3 single-comment">
            <div class="d-flex justify-content-between">
              <h6>{{ comment.user.name }}</h6>
              <small class="text-muted">{{ formatDateTime(comment.created_at) }}</small>
            </div>
            <div class="content" v-html="comment.content"></div>
            
          </div>
        </div>
        <div>
          <QuillEditor v-model:content="newComment" contentType="html" theme="snow" :options="editorOptions" />
          <button @click="addComment" class="btn btn-success mt-2">Add Comment</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { logout } from '../utils/logout';
import { useRouter } from 'vue-router';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import 'quill-mention';
import 'quill-mention/dist/quill.mention.css';

export default {
  components: {
    QuillEditor,
  },
  setup() {
    const router = useRouter();

    const feedbackId = ref(null);
    const feedback = ref({});
    const comments = ref([]);
    const newComment = ref('');

    const logoutUser = async () => {
      await logout();
      location.reload();
    };

    const formatDateTime = (timestamp) => {
      const options = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric' };
      return new Date(timestamp).toLocaleDateString('en-US', options);
    };

    const feedbackListing = () => {
      router.push('/feedback-listing');
    };

    const loadFeedbackDetail = async () => {
      try {
        const axiosConfig = {
          headers: {
            'Access-Control-Allow-Origin': '*',
            'Content-Type': 'application/json;',
            'Authorization': `Bearer ${localStorage.getItem('access_token')}`,
          },
        };

        const response = await axios.get(`http://127.0.0.1:8000/api/feedback-detail?id=${feedbackId.value}`, axiosConfig);

        feedback.value = response.data.feedback;
        comments.value = response.data.comments;
      } catch (error) {
        console.error(error.response);
      }
    };

    const addComment = async () => {
      try {
        
        if(newComment.value.length){
          const axiosConfig = {
            headers: {
              'Access-Control-Allow-Origin': '*',
              'Content-Type': 'application/json;',
              'Authorization': `Bearer ${localStorage.getItem('access_token')}`,
            },
          };

          const commentData = {
            content: newComment.value,
            feedback_id: feedbackId.value,
          };

          await axios.post('http://127.0.0.1:8000/api/add-feedback-comment', commentData, axiosConfig);

          loadFeedbackDetail();
          
          newComment.value = '<p></p>';

        }
        
      } catch (error) {
        console.error(error.response);
      }
    };

    const editorOptions = {
      theme: 'snow',
      modules: {
        mention: {
          allowedChars: /^[A-Za-z\sÅÄÖåäö]*$/,
          mentionDenotationChars: ['@'],
          source: async (searchTerm, renderList, mentionChar) => {
            // Fetch user mentions based on the searchTerm
            if(searchTerm.length){
              const response = await axios.get(`http://127.0.0.1:8000/api/mention-users?search=${searchTerm}`,
              {
                headers: {
                  'Content-Type': 'application/json',
                  'Authorization': `Bearer ${localStorage.getItem('access_token')}`,
                },
              });
              const users = response.data.users;
              renderList(users, searchTerm, mentionChar);
            }
            else{
              renderList([], searchTerm, mentionChar);
            }
            
          },
        },
      },
    };

    // Get feedback details and comments when component is mounted
    onMounted(() => {
      feedbackId.value = router.currentRoute.value.params.id;
      loadFeedbackDetail();
    });

    return {
      feedback,
      comments,
      newComment,
      logoutUser,
      feedbackListing,
      addComment,
      formatDateTime,
      editorOptions
    };
  },
};
</script>

<style>
  .single-comment .content p {
      margin: 0px;
  }
</style>
