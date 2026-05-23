<nav class="navbar" id="mainNav">
    <div class="nav-container">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="nav-logo">
            <span class="logo-icon-wrap" aria-hidden="true">
                <img src="{{ asset('Wanderly.png') }}" alt="" class="logo-icon-img">
            </span>
            <span class="logo-text">Wanderly</span>
        </a>

        {{-- Desktop Navigation --}}
        <ul class="nav-links" id="navLinks">
            @auth
                <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a></li>
                <li><a href="{{ route('trips.index') }}" class="{{ request()->routeIs('trips.*') ? 'active' : '' }}">
                    <i class="bi bi-map"></i> My Trips
                </a></li>
                <li><a href="{{ route('trips.create') }}" class="{{ request()->routeIs('trips.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i> New Trip
                </a></li>
            @else
                <li><a href="{{ route('home') }}#features">Features</a></li>
                <li><a href="{{ route('home') }}#how-it-works">How it Works</a></li>
            @endauth
        </ul>

        {{-- Right Actions --}}
        <div class="nav-actions">

            {{-- Dark Mode Toggle (hidden on front page) --}}
            @unless(request()->routeIs('home'))
                <button class="theme-toggle" id="themeToggle" aria-label="Toggle dark mode">
                    <i class="bi bi-sun-fill" id="themeIcon"></i>
                </button>
            @endunless

            @auth
                {{-- User Menu --}}
                <div class="user-menu" id="userMenu">
                    <button class="user-avatar-btn" id="userMenuBtn">
                        <span class="avatar-initials">{{ Auth::user()->initials }}</span>
                        <span class="user-name d-none d-md-inline">{{ Auth::user()->name }}</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu" id="userDropdown">
                        <div class="dropdown-header">
                            <strong>{{ Auth::user()->name }}</strong>
                            <small>{{ Auth::user()->email }}</small>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('dashboard') }}" class="dropdown-item">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        <a href="{{ route('trips.create') }}" class="dropdown-item">
                            <i class="bi bi-plus-circle"></i> New Trip
                        </a>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn-nav btn-nav-outline">Login</a>
                <a href="{{ route('register') }}" class="btn-nav btn-nav-primary">Get Started</a>
            @endauth

            {{-- Mobile Menu Toggle --}}
            <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Toggle menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</nav>
