@extends('layouts.app')

@section('title', 'Create Account — Wanderly')

@section('content')
    <div class="auth-page">
        <div class="auth-illustration" style="position: relative; background: #000;">
            <video autoplay loop muted playsinline
                style="position: absolute; top:0; left:0; width:100%; height:100%; object-fit: cover; z-index: 0; opacity: 0.6;">
                <source src="{{ asset('videos/login.mp4') }}" type="video/mp4">
            </video>
            <div class="auth-illustration-inner" style="position: relative; z-index: 2;">
                <div class="auth-quote"
                    style="background: rgba(0,0,0,0.4); padding: 20px; border-radius: 12px; backdrop-filter: blur(10px);">
                    <blockquote style="font-size: 1.5rem; text-shadow: 0 2px 4px rgba(0,0,0,0.8); color: #fff;">"The world
                        is a book and those who do not travel read only one page."</blockquote>
                    <cite style="color: #ccc;">â€” Saint Augustine</cite>
                </div>
            </div>
        </div>

        <div class="auth-form-side">
            <div class="auth-card">
                <div class="auth-header">
                    <a href="{{ route('home') }}" class="auth-logo">
                        <span class="logo-icon">✈</span>
                        <span class="logo-text">Wanderly</span>
                    </a>
                    <h1 class="auth-title">Start Your Journey</h1>
                    <p class="auth-subtitle">Create your free account and plan your first trip</p>
                </div>

                @include('components.errors')

                <form action="{{ route('register') }}" method="POST" class="auth-form" id="registerForm">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="bi bi-person"></i> Full Name
                        </label>
                        <input type="text" id="name" name="name" class="form-input @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="Jane Smith" autocomplete="name" required autofocus>
                        @error('name')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope"></i> Email Address
                        </label>
                        <input type="email" id="email" name="email" class="form-input @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="you@example.com" autocomplete="email" required>
                        @error('email')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock"></i> Password
                        </label>
                        <div class="input-with-action">
                            <input type="password" id="password" name="password"
                                class="form-input @error('password') is-invalid @enderror" placeholder="Min. 8 characters"
                                autocomplete="new-password" required oninput="checkPasswordStrength(this.value)">
                            <button type="button" class="input-action-btn" onclick="togglePassword('password')">
                                <i class="bi bi-eye" id="password-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength" id="strengthBar">
                            <div class="strength-track">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <span class="strength-label" id="strengthLabel">Enter password</span>
                        </div>
                        @error('password')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            <i class="bi bi-lock-fill"></i> Confirm Password
                        </label>
                        <div class="input-with-action">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-input" placeholder="Repeat password" autocomplete="new-password" required>
                            <button type="button" class="input-action-btn"
                                onclick="togglePassword('password_confirmation')">
                                <i class="bi bi-eye" id="password_confirmation-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-auth-submit" id="registerBtn">
                        <span class="btn-text">
                            <i class="bi bi-rocket-takeoff"></i>
                            Create My Account
                        </span>
                        <span class="btn-loading" style="display:none;">
                            <i class="bi bi-arrow-repeat spin"></i> Creating account...
                        </span>
                    </button>
                </form>

                @include('components.firebase-google-auth')

                <div class="auth-footer">
                    <p>Already have an account? <a href="{{ route('login') }}" class="auth-link">Sign in</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const eye = document.getElementById(id + '-eye');
            if (input.type === 'password') {
                input.type = 'text';
                eye.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                eye.className = 'bi bi-eye';
            }
        }

        function checkPasswordStrength(val) {
            const fill = document.getElementById('strengthFill');
            const label = document.getElementById('strengthLabel');
            let strength = 0;
            if (val.length >= 8) strength++;
            if (/[A-Z]/.test(val)) strength++;
            if (/[0-9]/.test(val)) strength++;
            if (/[^A-Za-z0-9]/.test(val)) strength++;

            const pct = (strength / 4) * 100;
            const colors = ['#ef4444', '#f97316', '#eab308', '#22c55e'];
            const labels = ['Weak', 'Fair', 'Good', 'Strong'];
            fill.style.width = pct + '%';
            fill.style.backgroundColor = colors[Math.max(0, strength - 1)];
            label.textContent = val.length ? labels[Math.max(0, strength - 1)] : 'Enter password';
        }

        document.getElementById('registerForm').addEventListener('submit', function () {
            const btn = document.getElementById('registerBtn');
            btn.querySelector('.btn-text').style.display = 'none';
            btn.querySelector('.btn-loading').style.display = 'inline-flex';
            btn.disabled = true;
        });
    </script>
@endpush