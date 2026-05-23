<?php

namespace App\Services;

use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Illuminate\Support\Facades\Log;

class FirebaseAuthService
{
    /**
     * Verify a Firebase ID token and return its claims.
     *
     * @throws \RuntimeException
     */
    public function verifyIdToken(string $idToken): array
    {
        $projectId = config('firebase.project_id');

        if (! $projectId) {
            throw new RuntimeException('Firebase project ID is not configured.');
        }

        // First try to verify as a Firebase ID token (issued by securetoken.google.com)
        try {
            $jwks = Cache::remember(
                'firebase.jwks',
                now()->addHours((int) config('firebase.jwks_cache_hours', 12)),
                function () {
                    $response = Http::timeout(10)->get(config('firebase.jwks_url'));

                    if (! $response->successful()) {
                        throw new RuntimeException('Unable to download Firebase public keys.');
                    }

                    return $response->json();
                }
            );

            if (! is_array($jwks) || empty($jwks['keys'])) {
                throw new RuntimeException('Firebase public keys are invalid.');
            }

            $decoded = JWT::decode($idToken, JWK::parseKeySet($jwks));
            $claims = json_decode(json_encode($decoded), true) ?: [];

            $expectedIssuer = "https://securetoken.google.com/{$projectId}";

            if (($claims['aud'] ?? null) !== $projectId) {
                throw new RuntimeException('Firebase token audience is invalid.');
            }

            if (($claims['iss'] ?? null) !== $expectedIssuer) {
                throw new RuntimeException('Firebase token issuer is invalid.');
            }

            if (empty($claims['sub'])) {
                throw new RuntimeException('Firebase token is missing the user identifier.');
            }

            return $claims;
        } catch (\Throwable $e) {
            // If the token wasn't a Firebase ID token, attempt to detect if it's a Google ID token
            Log::debug('Firebase ID token verification failed, attempting Google ID token fallback: '.$e->getMessage());

            $payload = $this->decodeJwtPayload($idToken);

            $iss = $payload['iss'] ?? null;
            $aud = $payload['aud'] ?? null;

            // If issuer looks like Google Accounts, verify against Google's certs
            if ($iss && str_contains($iss, 'accounts.google.com')) {
                $googleJwksUrl = 'https://www.googleapis.com/oauth2/v3/certs';

                $jwks = Cache::remember('google.jwks', now()->addHours(12), function () use ($googleJwksUrl) {
                    $response = Http::timeout(10)->get($googleJwksUrl);
                    if (! $response->successful()) {
                        throw new RuntimeException('Unable to download Google public keys.');
                    }
                    return $response->json();
                });

                try {
                    $decoded = JWT::decode($idToken, JWK::parseKeySet($jwks));
                    $claims = json_decode(json_encode($decoded), true) ?: [];

                    // Validate audience against configured Google client id (optional)
                    $expectedClientId = env('FIREBASE_CLIENT_ID', env('VITE_FIREBASE_CLIENT_ID')) ?: null;
                    if ($expectedClientId && ($claims['aud'] ?? null) !== $expectedClientId) {
                        throw new RuntimeException('Google ID token audience does not match configured client id.');
                    }

                    if (empty($claims['sub'])) {
                        throw new RuntimeException('Google ID token is missing the user identifier.');
                    }

                    return $claims;
                } catch (\Throwable $e2) {
                    throw new RuntimeException('Token verification failed: '.$e2->getMessage());
                }
            }

            // Rethrow original exception if no fallback applies
            throw $e;
        }
    }

    /**
     * Decode JWT payload without verifying signature.
     */
    protected function decodeJwtPayload(string $jwt): array
    {
        $parts = explode('.', $jwt);
        if (count($parts) < 2) {
            return [];
        }

        $payload = $parts[1];
        // fix base64 padding
        $remainder = strlen($payload) % 4;
        if ($remainder) {
            $payload .= str_repeat('=', 4 - $remainder);
        }

        $decoded = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

        return is_array($decoded) ? $decoded : [];
    }
}
