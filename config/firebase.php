<?php

return [
    'project_id' => env('FIREBASE_PROJECT_ID', env('VITE_FIREBASE_PROJECT_ID')),

    'client' => [
        'apiKey' => env('FIREBASE_API_KEY', env('VITE_FIREBASE_API_KEY')),
        'authDomain' => env('FIREBASE_AUTH_DOMAIN', env('VITE_FIREBASE_AUTH_DOMAIN')),
        'projectId' => env('FIREBASE_PROJECT_ID', env('VITE_FIREBASE_PROJECT_ID')),
        'storageBucket' => env('FIREBASE_STORAGE_BUCKET', env('VITE_FIREBASE_STORAGE_BUCKET')),
        'messagingSenderId' => env('FIREBASE_MESSAGING_SENDER_ID', env('VITE_FIREBASE_MESSAGING_SENDER_ID')),
        'appId' => env('FIREBASE_APP_ID', env('VITE_FIREBASE_APP_ID')),
        'measurementId' => env('FIREBASE_MEASUREMENT_ID', env('VITE_FIREBASE_MEASUREMENT_ID')),
    ],

    'jwks_url' => env(
        'FIREBASE_JWKS_URL',
        'https://www.googleapis.com/service_accounts/v1/jwk/securetoken@system.gserviceaccount.com'
    ),

    'jwks_cache_hours' => env('FIREBASE_JWKS_CACHE_HOURS', 12),
];
