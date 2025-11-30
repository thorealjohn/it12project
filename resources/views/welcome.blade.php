<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CLEARpro Water Station</title>
        
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
        
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: 'Nunito', sans-serif;
                background: linear-gradient(135deg, #00B8D4 0%, #01579B 50%, #0097A7 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                position: relative;
                overflow: hidden;
            }
            
            /* Animated Background Elements */
            .bg-shapes {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 0;
                overflow: hidden;
            }
            
            .shape {
                position: absolute;
                border-radius: 50%;
                opacity: 0.1;
                animation: float 20s infinite ease-in-out;
            }
            
            .shape-1 {
                width: 300px;
                height: 300px;
                background: #00B8D4;
                top: -150px;
                left: -150px;
                animation-delay: 0s;
            }
            
            .shape-2 {
                width: 200px;
                height: 200px;
                background: #0097A7;
                bottom: -100px;
                right: -100px;
                animation-delay: 5s;
            }
            
            .shape-3 {
                width: 150px;
                height: 150px;
                background: #4DD0E1;
                top: 50%;
                right: 10%;
                animation-delay: 10s;
            }
            
            @keyframes float {
                0%, 100% {
                    transform: translate(0, 0) rotate(0deg);
                }
                33% {
                    transform: translate(30px, -30px) rotate(120deg);
                }
                66% {
                    transform: translate(-20px, 20px) rotate(240deg);
                }
            }
            
            /* Water Ripple Effect */
            .water-ripple {
                position: absolute;
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
                z-index: 1;
                overflow: hidden;
            }
            
            .ripple {
                position: absolute;
                border: 2px solid rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                animation: ripple 4s infinite;
            }
            
            @keyframes ripple {
                0% {
                    width: 0;
                    height: 0;
                    opacity: 1;
                }
                100% {
                    width: 500px;
                    height: 500px;
                    opacity: 0;
                    margin-left: -250px;
                    margin-top: -250px;
                }
            }
            
            /* Main Content */
            .welcome-container {
                position: relative;
                z-index: 10;
                width: 100%;
                max-width: 1200px;
                padding: 2rem;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 3rem;
                align-items: center;
            }
            
            
            /* Left Side - Branding */
            .brand-section {
                animation: fadeInLeft 1s ease-out;
            }
            
            .logo-container {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 120px;
                height: 120px;
                background: rgba(255, 255, 255, 0.2);
                backdrop-filter: blur(10px);
                border-radius: 50%;
                margin-bottom: 2rem;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
                border: 3px solid rgba(255, 255, 255, 0.3);
            }
            
            .logo-container .water-drop {
                width: 60px;
                height: 60px;
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
            
            .brand-section h1 {
                font-size: 3.5rem;
                font-weight: 800;
                margin-bottom: 1rem;
                text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
                letter-spacing: -0.02em;
            }
            
            .brand-section .tagline {
                font-size: 1.5rem;
                font-weight: 500;
                margin-bottom: 0.5rem;
                opacity: 0.95;
            }
            
            .brand-section .description {
                font-size: 1.1rem;
                opacity: 0.85;
                line-height: 1.6;
                margin-top: 1.5rem;
            }
            
            /* Right Side - Login Card */
            .welcome-card {
                background: rgba(255, 255, 255, 0.15);
                backdrop-filter: blur(20px);
                border-radius: 2rem;
                padding: 3rem;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                border: 1px solid rgba(255, 255, 255, 0.2);
                animation: fadeInRight 1s ease-out;
            }
            
            @keyframes fadeInLeft {
                from {
                    opacity: 0;
                    transform: translateX(-50px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            
            @keyframes fadeInRight {
                from {
                    opacity: 0;
                    transform: translateX(50px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            
            .welcome-card h2 {
                font-size: 2rem;
                font-weight: 700;
                margin-bottom: 0.5rem;
                text-align: center;
            }
            
            .welcome-card p {
                text-align: center;
                opacity: 0.9;
                margin-bottom: 2rem;
                font-size: 1rem;
            }
            
            .welcome-actions {
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }
            
            .btn-welcome {
                background: white;
                color: #00B8D4;
                border: none;
                font-weight: 700;
                padding: 1rem 2rem;
                border-radius: 1rem;
                font-size: 1.1rem;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                transition: all 0.3s ease;
                text-decoration: none;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
            }
            
            .btn-welcome:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
                background: #F5F5F5;
            }
            
            .btn-welcome:active {
                transform: translateY(-2px);
            }
            
            .btn-welcome i {
                font-size: 1.3rem;
            }
            
            /* Features List */
            .features {
                margin-top: 2rem;
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
            
            
            .feature-item {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 0.75rem;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 0.75rem;
                backdrop-filter: blur(10px);
            }
            
            .feature-item i {
                font-size: 1.5rem;
                color: #4DD0E1;
            }
            
            .feature-item span {
                font-size: 0.95rem;
                font-weight: 500;
            }
            
            /* Decorative Elements */
            .decorative-line {
                width: 80px;
                height: 4px;
                background: white;
                border-radius: 2px;
                margin: 1.5rem auto;
                opacity: 0.6;
            }
        </style>
    </head>
    <body>
        <!-- Background Shapes -->
        <div class="bg-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
        
        <!-- Water Ripple Effect -->
        <div class="water-ripple">
            <div class="ripple" style="left: 20%; top: 30%; animation-delay: 0s;"></div>
            <div class="ripple" style="left: 70%; top: 60%; animation-delay: 2s;"></div>
            <div class="ripple" style="left: 50%; top: 80%; animation-delay: 4s;"></div>
        </div>
        
        <!-- Main Content -->
        <div class="welcome-container">
            <!-- Left Side - Branding -->
            <div class="brand-section">
                <div class="logo-container">
                    <div class="water-drop"></div>
                </div>
                <h1><span style="color: #01579B;">CLEAR</span><span style="color: #00B8D4;">pro</span> Water</h1>
                <p class="tagline">Refilling Station</p>
                <p class="description">
                    Streamline your water refilling business with our comprehensive management system. 
                    Track orders, manage inventory, and grow your business efficiently.
                </p>
                
                <!-- Features -->
                <div class="features">
                    <div class="feature-item">
                        <i class="bi bi-speedometer2"></i>
                        <span>Real-time Dashboard</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-box-seam"></i>
                        <span>Inventory Management</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-truck"></i>
                        <span>Delivery Tracking</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-bar-chart"></i>
                        <span>Sales Reports</span>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Login Card -->
            <div class="welcome-card">
                <h2>Welcome Back</h2>
                <p>Sign in to access your dashboard</p>
                <div class="decorative-line"></div>
                
                <div class="welcome-actions">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-welcome">
                            <i class="bi bi-speedometer2"></i>
                            <span>Go to Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-welcome">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span>Login to System</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </body>
</html>