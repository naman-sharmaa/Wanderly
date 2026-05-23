<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-brand">
            <span class="logo-icon">✈</span>
            <span class="logo-text">Wanderly</span>
            <p class="footer-tagline">Your AI-Powered Travel Companion</p>
        </div>
        <div class="footer-links">
            <a href="{{ route('home') }}">Home</a>
            @auth
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('trips.index') }}">My Trips</a>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endauth
        </div>
        <div class="footer-copy">
            <p>&copy; {{ date('Y') }} Wanderly. Built with ❤️ for explorers.</p>
        </div>
    </div>
</footer>
