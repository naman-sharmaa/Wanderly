/**
 * Firebase Initialization Module
 *
 * Initialize Firebase with credentials from .env (exposed via Vite)
 * Export auth and other Firebase services for use across the app
 */

import { initializeApp } from 'firebase/app';
import { getAuth } from 'firebase/auth';
import { getAnalytics } from 'firebase/analytics';

const runtimeConfig = window.WANDERLY_CONFIG || {};

const firebaseConfig = {
  apiKey: runtimeConfig.firebaseApiKey || import.meta.env.VITE_FIREBASE_API_KEY,
  authDomain: runtimeConfig.firebaseAuthDomain || import.meta.env.VITE_FIREBASE_AUTH_DOMAIN,
  projectId: runtimeConfig.firebaseProjectId || import.meta.env.VITE_FIREBASE_PROJECT_ID,
  storageBucket: runtimeConfig.firebaseStorageBucket || import.meta.env.VITE_FIREBASE_STORAGE_BUCKET,
  messagingSenderId: runtimeConfig.firebaseMessagingSenderId || import.meta.env.VITE_FIREBASE_MESSAGING_SENDER_ID,
  appId: runtimeConfig.firebaseAppId || import.meta.env.VITE_FIREBASE_APP_ID,
  measurementId: runtimeConfig.firebaseMeasurementId || import.meta.env.VITE_FIREBASE_MEASUREMENT_ID,
};

const isConfigured = Boolean(
  firebaseConfig.apiKey &&
  firebaseConfig.authDomain &&
  firebaseConfig.projectId &&
  firebaseConfig.appId
);

let app = null;
export let auth = null;
export let analytics = null;

if (isConfigured) {
  app = initializeApp(firebaseConfig);
  auth = getAuth(app);

  if (firebaseConfig.measurementId) {
    analytics = getAnalytics(app);
  }
} else {
  console.warn('Firebase client config is missing. Google sign-in will be disabled until VITE_FIREBASE_* variables are set.');
}

export default app;
