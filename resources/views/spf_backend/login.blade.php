<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login – SPF</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #1a237e 0%, #0d47a1 60%, #1565c0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-landscape {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.4);
            display: flex;
            width: 100%;
            max-width: 900px;
            overflow: hidden;
            min-height: 500px;
        }

        .login-brand {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            width: 45%;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
        }

        .login-brand img.logo {
            width: 180px;
            height: auto;
            margin-bottom: 25px;
            filter: drop-shadow(0 10px 15px rgba(0,0,0,0.1));
        }

        .login-brand h2 {
            font-size: 24px;
            font-weight: 700;
            color: #1a237e;
            margin-bottom: 10px;
        }

        .login-brand p {
            font-size: 14px;
            color: #555;
            line-height: 1.5;
        }

        .login-form-container {
            width: 55%;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-form-container h1 {
            font-size: 28px;
            font-weight: 700;
            color: #222;
            margin-bottom: 8px;
        }

        .login-form-container > p {
            font-size: 14px;
            color: #666;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #444;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            color: #333;
            transition: all 0.3s ease;
            outline: none;
            background: #fafafa;
        }

        .form-group input:focus {
            border-color: #1a237e;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(26,35,126,0.1);
        }

        .form-group input.is-invalid {
            border-color: #e53935;
        }

        .invalid-feedback {
            color: #e53935;
            font-size: 12px;
            margin-top: 5px;
        }

        .password-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-container input {
            padding-right: 45px;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #888;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px;
            border-radius: 4px;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: #1a237e;
        }

        .toggle-password svg {
            width: 18px;
            height: 18px;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 30px;
        }

        .remember-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #1a237e;
            cursor: pointer;
        }

        .remember-row label {
            font-size: 14px;
            color: #555;
            cursor: pointer;
            user-select: none;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #1a237e, #1565c0);
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(26, 35, 126, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 35, 126, 0.4);
        }

        .alert-error {
            background: #fdecea;
            border-left: 4px solid #c62828;
            color: #c62828;
            border-radius: 4px;
            padding: 12px 16px;
            font-size: 13.5px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .login-footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12.5px;
            color: #999;
        }

        @media (max-width: 768px) {
            .login-landscape {
                flex-direction: column;
                max-width: 450px;
            }
            .login-brand {
                width: 100%;
                padding: 30px 20px;
            }
            .login-form-container {
                width: 100%;
                padding: 40px 30px;
            }
            .login-brand img.logo {
                width: 120px;
            }
        }
    </style>
</head>
<body>

<div class="login-landscape">
    <div class="login-brand">
        <!-- Brand Logo -->
        <img src="{{ asset('uploads/SPF_logo.png') }}" alt="SPF Logo" class="logo">
        <h2>SPF Admin Panel</h2>
        <p>Empowering the Sadhumargi Professional Forum community with efficient management.</p>
    </div>

    <div class="login-form-container">
        <h1>Welcome Back</h1>
        <p>Please enter your credentials to login.</p>

        @if($errors->any())
            <div class="alert-error">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="admin@example.com"
                    class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                    required
                    autofocus
                >
                @if($errors->has('email'))
                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-container">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        required
                    >
                    <button type="button" class="toggle-password" id="togglePasswordBtn" aria-label="Toggle password visibility">
                        <!-- Eye SVG (default view) -->
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <!-- Eye-off SVG (hidden by default) -->
                        <svg id="eyeOffIcon" style="display: none;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn-login">Login to Dashboard</button>
        </form>

        <div class="login-footer">&copy; {{ date('Y') }} SABSJS IT Department. All rights reserved.</div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOffIcon = document.getElementById('eyeOffIcon');

        toggleBtn.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            if (type === 'text') {
                eyeIcon.style.display = 'none';
                eyeOffIcon.style.display = 'block';
                eyeOffIcon.style.color = '#1a237e'; // Active state color
            } else {
                eyeIcon.style.display = 'block';
                eyeOffIcon.style.display = 'none';
                eyeIcon.style.color = '#888'; // Default state color
            }
        });
    });
</script>
</body>
</html>
