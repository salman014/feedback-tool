import { isAuthenticated } from './auth';

export const authLogin = (to, from, next) => {
  if (isAuthenticated()) {
    next('/feedback-form');
  }
  else{
    next();
  }
};