import { createRouter, createWebHistory } from 'vue-router';
// Import your components
import LoginForm from './components/LoginForm.vue';
import RegisterForm from './components/RegisterForm.vue';
import FeedbackForm from './components/FeedbackForm.vue';
import FeedbackListing from './components/FeedbackListing.vue';
import FeedbackDetail from './components/FeedbackDetail.vue';
import { authGuard } from './utils/authGuard';
import { authLogin } from './utils/authLogin';

const routes = [
  {
    path: '/',
    component: LoginForm, 
    beforeEnter: authLogin,
  },
  {
    path: '/login',
    component: LoginForm,
    beforeEnter: authLogin,
  },
  {
    path: '/register',
    component: RegisterForm,
    beforeEnter: authLogin,
  },
  {
    path: '/feedback-form',
    component: FeedbackForm,
    beforeEnter: authGuard,
  },
  {
    path: '/feedback-listing',
    component: FeedbackListing,
    beforeEnter: authGuard,
  },
  {
    path: '/feedback-detail/:id',
    name: 'feedbackDetail',
    component: FeedbackDetail,
    beforeEnter: authGuard,
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
