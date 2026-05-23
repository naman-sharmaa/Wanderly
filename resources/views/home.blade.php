@extends('layouts.app')
@section('title', 'AI Travel Planner')

@push('styles')
    <style>
        /* Specific Hero overrides for home */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 5% 5% 5% 5%;
            margin-top: -70px;
            /* pull under nav */
            overflow: hidden;
        }

        .hero-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(13, 13, 15, 0.3) 0%, rgba(13, 13, 15, 0.7) 60%, rgba(13, 13, 15, 1) 100%);
            z-index: 1;
        }

        .hero-title {
            position: relative;
            margin-top: 20vh;
            margin-bottom: auto;
            font-family: 'Anton', sans-serif;
            font-size: 11vw;
            color: rgba(255, 255, 255, 0.85);
            z-index: 2;
            letter-spacing: 5px;
            pointer-events: none;
            text-shadow: 0px 10px 40px rgba(0, 0, 0, 0.5);
            line-height: 1;
        }

        .hero-cards-wrapper {
            position: relative;
            z-index: 10;
            display: flex;
            align-items: flex-end;
            gap: 1.5rem;
            flex-wrap: nowrap;
            /* Prevent wrapping on desktop */
            overflow-x: auto;
            /* Allow horizontal scroll on mobile */
            padding-bottom: 20px;
            margin-top: auto;
            width: 100%;
        }

        .hero-cards-wrapper::-webkit-scrollbar {
            display: none;
            /* Hide scrollbar for Chrome, Safari and Opera */
        }

        .hero-cards-wrapper {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        .hero-card {
            min-width: 140px;
            height: 200px;
            border-radius: 12px;
            background-size: cover;
            background-position: center;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: flex-end;
            padding: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }

        .hero-card p {
            position: relative;
            z-index: 2;
            font-size: 0.9rem;
            font-weight: 500;
            line-height: 1.2;
            color: #fff;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.8);
        }

        .hero-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 70%;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.9));
        }

        .btn-solid {
            background: #fff;
            color: #000;
            border-radius: 50px;
            padding: 1rem 3rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: opacity 0.3s;
            display: inline-flex;
            text-align: center;
            margin-left: auto;
            margin-bottom: 20px;
            text-decoration: none;
        }

        .btn-solid:hover {
            opacity: 0.9;
            color: #000;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 4rem;
        }

        .section-header::before,
        .section-header::after {
            content: '';
            height: 1px;
            background: rgba(255, 255, 255, 0.2);
            flex-grow: 1;
        }

        .section-header h2 {
            font-size: 2rem;
            font-weight: 400;
            letter-spacing: 2px;
            white-space: nowrap;
        }

        .timeline {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 4rem;
            margin: 4rem auto;
            max-width: 800px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 1px;
            background: rgba(255, 255, 255, 0.2);
        }

        .timeline-item {
            display: flex;
            align-items: center;
            width: 50%;
            position: relative;
        }

        .timeline-item:nth-child(odd) {
            align-self: flex-start;
            padding-right: 2rem;
            text-align: right;
            justify-content: flex-end;
        }

        .timeline-item:nth-child(even) {
            align-self: flex-end;
            padding-left: 2rem;
            text-align: left;
        }

        .timeline-dot {
            position: absolute;
            width: 12px;
            height: 12px;
            background: #fff;
            border-radius: 50%;
            border: 4px solid var(--bg-base);
            z-index: 2;
        }

        .timeline-item:nth-child(odd) .timeline-dot {
            right: -6px;
        }

        .timeline-item:nth-child(even) .timeline-dot {
            left: -6px;
        }

        .included-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .included-card {
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.03);
        }

        .included-card h3 {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
        }

        .included-card i {
            color: var(--accent);
        }

        .contact-section {
            padding: 8rem 5%;
            background: linear-gradient(to bottom, var(--bg-base), rgba(13, 13, 15, 0.8)), url('https://images.unsplash.com/photo-1499856871958-5b9627545d1a?auto=format&fit=crop&w=2000') center/cover;
        }

        .contact-form {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            padding: 3rem;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            max-width: 500px;
            margin: 0 auto;
        }

        /* Small step icons for timeline */
        .step-icon { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; flex-shrink: 0; color: var(--accent); }
        .step-icon svg { width: 100%; height: 100%; display: block; }
    </style>
@endpush

@section('content')
    <section class="hero">
        <video class="hero-video" autoplay loop muted playsinline>
            <source src="{{ asset('videos/landing.mp4') }}" type="video/mp4">
        </video>
        <div class="hero-overlay"></div>
        <div class="hero-title" style="pointer-events: auto; text-shadow: 0px 10px 40px rgba(0,0,0,0.5);">
            EXPLORE<br>THE WORLD
            <br>
            <a href="{{ route('register') }}" class="btn-solid"
                style="margin-top: 50px; font-size: 1.5rem; text-decoration: none; padding: 1rem 3rem; display: inline-block;">Start
                Planning -></a>
        </div>
    </section>

    <div class="container" style="padding: 5% 5%;">
        <section id="about">
            <div class="section-header">
                <h2>YOUR JOURNEY, PLANNED</h2>
            </div>
            <div class="text-center"
                style="max-width: 600px; margin: 0 auto; color: var(--text-muted); font-size: 1.1rem; line-height: 1.6;">
                We organize your complete itinerary. Decide your vibe, your destinations, and dates. <br>
                <span style="color: var(--accent);">Our AI structures the perfect flow, so you just enjoy it.</span>
            </div>

            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <span style="color: #fff; display:flex; align-items:center; gap:10px;">
                            <span class="step-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/>
                                </svg>
                            </span>
                            1. Choose<br><strong style="font-size:1.2rem;">Destination</strong>
                        </span>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <span style="color: #fff; display:flex; align-items:center; gap:10px;">
                            <span class="step-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 13v6h2v-4h14v4h2v-6H3zm16-7h-2V4h-2v2H9V4H7v2H5a2 2 0 0 0-2 2v4h18V8a2 2 0 0 0-2-2z"/>
                                </svg>
                            </span>
                            2. Manage<br><strong style="font-size:1.2rem;">Activities &amp; Hotels</strong>
                        </span>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <span style="color: #fff; display:flex; align-items:center; gap:10px;">
                            <span class="step-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19 4h-1V2h-2v2H8V2H6v2H5a2 2 0 0 0-2 2v12h18V6a2 2 0 0 0-2-2zM7 10h10v8H7v-8z"/>
                                </svg>
                            </span>
                            3. Get your<br><strong style="font-size:1.2rem;">Timeline</strong>
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <section id="included">
            <div class="section-header">
                <h2>WHAT WE HANDLE</h2>
            </div>
            <div class="included-grid">
                <div class="included-card">
                    <h3><i class="bi bi-calendar2-day"></i> Itinerary</h3>
                    <p style="color: var(--text-muted); margin-top:10px;">Day-by-day mapping of your entire experience.</p>
                </div>
                <div class="included-card">
                    <h3><i class="bi bi-building"></i> Lodging</h3>
                    <p style="color: var(--text-muted); margin-top:10px;">Track booking references and check-ins easily.</p>
                </div>
                <div class="included-card">
                    <h3><i class="bi bi-compass"></i> Activities</h3>
                    <p style="color: var(--text-muted); margin-top:10px;">Sightseeing, dining, and transit all scheduled.
                    </p>
                </div>
                <div class="included-card">
                    <h3><i class="bi bi-wallet2"></i> Budgets</h3>
                    <p style="color: var(--text-muted); margin-top:10px;">Keep track of costs per person and overall
                        spending.</p>
                </div>
            </div>
        </section>
    </div>

    <section class="contact-section">
        <div class="contact-form">
            <h2 style="color: #fff; margin-bottom: 2rem;">Ready to travel?</h2>
            <a href="{{ route('register') }}" class="btn-solid" style="width: 100%; display:block; padding: 1rem;">Create
                Free Account</a>
            <div style="text-align: center; margin-top: 15px;">
                <a href="{{ route('login') }}" style="color: var(--text-muted);">Already have an account? Login</a>
            </div>
        </div>
    </section>
@endsection