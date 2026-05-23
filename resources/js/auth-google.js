/**
 * Google Sign-In with Firebase
 *
 * Handle Firebase Google authentication flow and backend token exchange
 */

import { auth } from './firebase.js';
import { GoogleAuthProvider, signInWithPopup } from 'firebase/auth';

const runtimeConfig = window.WANDERLY_CONFIG || {};

export async function initGoogleAuth() {
  const button = document.getElementById('firebaseGoogleButton');
  const status = document.getElementById('firebaseAuthStatus');
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  if (!button) {
    return;
  }

  const setStatus = (message, type = '') => {
    if (!status) {
      return;
    }
    status.textContent = message;
    status.dataset.type = type;
  };

  // Check if Firebase is properly configured
  if (
    !(runtimeConfig.firebaseApiKey || import.meta.env.VITE_FIREBASE_API_KEY) ||
    !(runtimeConfig.firebaseAuthDomain || import.meta.env.VITE_FIREBASE_AUTH_DOMAIN) ||
    !(runtimeConfig.firebaseProjectId || import.meta.env.VITE_FIREBASE_PROJECT_ID) ||
    !(runtimeConfig.firebaseAppId || import.meta.env.VITE_FIREBASE_APP_ID)
  ) {
    button.disabled = true;
    setStatus('Google sign-in is not configured. Add Firebase keys to Render or .env first.', 'error');
    return;
  }

  if (!csrfToken) {
    button.disabled = true;
    setStatus('CSRF protection is missing.', 'error');
    return;
  }

  const provider = new GoogleAuthProvider();
  provider.setCustomParameters({ prompt: 'select_account' });

  button.addEventListener('click', async () => {
    button.disabled = true;
    setStatus('Opening Google sign-in...');

    try {
      const result = await signInWithPopup(auth, provider);
      const idToken = await result.user.getIdToken();

      setStatus('Completing sign-in on server...');

      const response = await fetch('/auth/firebase/google', {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ id_token: idToken }),
      });

      const payload = await response.json().catch(() => ({}));

      if (!response.ok) {
        throw new Error(payload.message || 'Unable to finish Google sign-in.');
      }

      window.location.href = payload.redirect_url || '/dashboard';
    } catch (error) {
      console.error('Google sign-in error:', error);
      setStatus(error?.message || 'Google sign-in failed. Please try again.', 'error');
      button.disabled = false;
    }
  });
}

// Auto-init when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initGoogleAuth);
} else {
  initGoogleAuth();
}
