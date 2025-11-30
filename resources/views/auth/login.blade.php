@extends('layouts.app')

@section('guest_full_width', 'true')

@section('content')
<style>
    .login-page {
        min-height: calc(100vh - 76px);
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #00B8D4 0%, #01579B 50%, #0097A7 100%);
        position: relative;
        overflow: hidden;
        padding: 2rem 1rem;
    }
    
    .login-page::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
        opacity: 0.3;
        z-index: 0;
    }
    
    .login-container {
        position: relative;
        z-index: 10;
        width: 100%;
        max-width: 450px;
    }
    
    .login-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 2rem;
        padding: 3rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.3);
        animation: slideUp 0.6s ease-out;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .login-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }
    
    .login-logo {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #00B8D4, #01579B);
        border-radius: 50%;
        margin-bottom: 1.5rem;
        box-shadow: 0 8px 25px rgba(0, 184, 212, 0.3);
        position: relative;
    }
    
    .login-logo .water-drop {
        width: 50px;
        height: 50px;
        background-color: white;
        border-radius: 50% 50% 50% 0;
        transform: rotate(45deg);
        animation: dropPulse 2s infinite;
    }
    
    @keyframes dropPulse {
        0%, 100% {
            transform: rotate(45deg) scale(1);
        }
        50% {
            transform: rotate(45deg) scale(1.1);
        }
    }
    
    .login-header h2 {
        font-size: 2rem;
        font-weight: 800;
        color: #01579B;
        margin-bottom: 0.5rem;
        letter-spacing: -0.02em;
    }
    
    .login-header p {
        color: #606f7b;
        font-size: 1rem;
        margin: 0;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }
    
    .input-wrapper {
        position: relative;
    }
    
    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #00B8D4;
        font-size: 1.1rem;
        z-index: 2;
    }
    
    .form-control {
        padding: 0.875rem 1rem 0.875rem 3rem;
        border: 2px solid #CFD8DC;
        border-radius: 0.75rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background-color: #F5F5F5;
    }
    
    .form-control:focus {
        border-color: #00B8D4;
        background-color: white;
        box-shadow: 0 0 0 0.2rem rgba(0, 184, 212, 0.15);
        outline: none;
    }
    
    .form-control.is-invalid {
        border-color: #e3342f;
    }
    
    .invalid-feedback {
        display: block;
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: #e3342f;
    }
    
    .form-check {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }
    
    .form-check-input {
        width: 1.25rem;
        height: 1.25rem;
        border: 2px solid #CFD8DC;
        border-radius: 0.375rem;
        cursor: pointer;
    }
    
    .form-check-input:checked {
        background-color: #00B8D4;
        border-color: #00B8D4;
    }
    
    .form-check-label {
        color: #2c3e50;
        font-size: 0.95rem;
        cursor: pointer;
        user-select: none;
    }
    
    .forgot-password {
        color: #00B8D4;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: color 0.3s ease;
    }
    
    .forgot-password:hover {
        color: #01579B;
        text-decoration: underline;
    }
    
    .btn-login {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #00B8D4, #01579B);
        border: none;
        border-radius: 0.75rem;
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
        box-shadow: 0 4px 15px rgba(0, 184, 212, 0.3);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 184, 212, 0.4);
        background: linear-gradient(135deg, #01579B, #00B8D4);
    }
    
    .btn-login:active {
        transform: translateY(0);
    }
    
    .divider {
        display: flex;
        align-items: center;
        margin: 2rem 0;
        color: #CFD8DC;
    }
    
    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #CFD8DC;
    }
    
    .divider span {
        padding: 0 1rem;
        font-size: 0.875rem;
    }
    
</style>

<div class="login-page">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <div class="water-drop"></div>
                </div>
                <h2>Welcome Back</h2>
                <p>Sign in to access your dashboard</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                    <div class="input-wrapper">
                        <i class="bi bi-envelope input-icon"></i>
                        <input 
                            id="email" 
                            type="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="email" 
                            autofocus
                            placeholder="Enter your email"
                        >
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="d-flex justify-content-between align-items-center">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        @if (Route::has('password.request'))
                            <a class="forgot-password" href="{{ route('password.request') }}">
                                {{ __('Forgot?') }}
                            </a>
                        @endif
                    </div>
                    <div class="input-wrapper">
                        <i class="bi bi-lock input-icon"></i>
                        <input 
                            id="password" 
                            type="password" 
                            class="form-control @error('password') is-invalid @enderror" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            placeholder="Enter your password"
                        >
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>{{ __('Login') }}</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection