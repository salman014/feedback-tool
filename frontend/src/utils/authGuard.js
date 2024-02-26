import { isAuthenticated } from './auth';

export const authGuard = (to, from, next) => {
  if (isAuthenticated()) {
    next();
  } else {
    next('/login');
  }
};