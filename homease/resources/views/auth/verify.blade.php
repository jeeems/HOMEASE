@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-body text-center p-5">
                        <!-- Email Icon -->
                        <div class="mb-4">
                            <div class="verification-icon mx-auto">
                                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M21.2 8.4c.5.38.8.97.8 1.6v10a2 2 0 01-2 2H4a2 2 0 01-2-2V10a2 2 0 01.8-1.6l8-6a2 2 0 012.4 0l8 6z" />
                                    <path d="M22 10l-8.97 5.7a1.94 1.94 0 01-2.06 0L2 10" />
                                </svg>
                            </div>
                        </div>

                        <h2 class="fw-bold mb-4">{{ __('Verify Your Email Address') }}</h2>

                        @if (session('resent'))
                            <div class="alert alert-success rounded-pill" role="alert">
                                {{ __('A verification link has been sent to your email address.') }}
                            </div>
                        @endif

                        <p class="text-muted mb-4">
                            {{ __('Before proceeding, please check your email for a verification link.') }}
                        </p>

                        <form class="mb-4" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">
                                {{ __('Resend Verification Email') }}
                            </button>
                        </form>

                        <div class="verification-info mt-4">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-2"></i>
                                    {{ __('The verification link will expire in 60 minutes') }}
                                </small>
                            </div>
                            <div class="d-flex align-items-center justify-content-center">
                                <small class="text-muted">
                                    <i class="fas fa-envelope me-2"></i>
                                    {{ __('Please check your spam folder if you don\'t see the email') }}
                                </small>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="text-decoration-none text-muted">
                                <small>{{ __('Sign out') }}</small>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        body {
            background: url("{{ asset('assets/homepage/covers/HOMEASE COVER.png') }}") no-repeat center center fixed;
            background-size: cover;
            backdrop-filter: blur(8px);
            min-height: 100vh;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            margin-top: 5rem;
        }

        .verification-icon {
            width: 80px;
            height: 80px;
            color: #0d6efd;
            margin-bottom: 1rem;
        }

        .btn-primary {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
        }

        .alert {
            border: none;
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .verification-info {
            background: rgba(13, 110, 253, 0.05);
            padding: 1rem;
            border-radius: 15px;
        }

        @media (max-width: 768px) {
            .card {
                margin: 2rem 1rem;
            }

            .card-body {
                padding: 2rem 1rem !important;
            }
        }
    </style>
@endsection
