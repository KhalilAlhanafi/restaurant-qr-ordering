<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Restaurant QR Ordering</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            min-height: 100vh;
        }
        
        .login-container {
            background: rgba(26, 26, 26, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(212, 175, 55, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        
        .input-field {
            background: #0f0f0f;
            border: 1px solid rgba(212, 175, 55, 0.2);
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            border-color: rgba(212, 175, 55, 0.5);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
            outline: none;
        }
        
        .login-btn {
            background: linear-gradient(135deg, #d4af37 0%, #b8941f 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
        }
        
        .login-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
        }
        
        .success-message {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #34d399;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-slide-in {
            animation: slideIn 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="login-container rounded-2xl p-8 w-full max-w-md animate-slide-in">
            <!-- Logo/Brand -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white mb-2">Admin Panel</h1>
                <p class="text-gray-400 text-sm">Restaurant QR Ordering System</p>
            </div>

            <!-- Login Form -->
            <form id="loginForm" class="space-y-6">
                @csrf
                
                <!-- Username Field -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-300 mb-2">
                        Username
                    </label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        required
                        class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-gray-500"
                        placeholder="Enter your username"
                        autocomplete="username"
                    >
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                        Password
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        class="input-field w-full px-4 py-3 rounded-lg text-white placeholder-gray-500"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                    >
                </div>

                <!-- Error/Success Messages -->
                <div id="messageContainer" class="hidden">
                    <div id="messageContent" class="px-4 py-3 rounded-lg text-sm"></div>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    id="loginBtn"
                    class="login-btn w-full py-3 px-4 rounded-lg text-white font-semibold"
                >
                    <span id="btnText">Sign In</span>
                    <span id="btnLoader" class="hidden">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Signing in...
                    </span>
                </button>
            </form>

            <!-- Footer Info -->
            <div class="mt-8 text-center">
                <p class="text-xs text-gray-500">
                    Secure admin access • Restaurant Management System
                </p>
            </div>
        </div>
    </div>

    <script>
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        const btnText = document.getElementById('btnText');
        const btnLoader = document.getElementById('btnLoader');
        const messageContainer = document.getElementById('messageContainer');
        const messageContent = document.getElementById('messageContent');

        function showMessage(message, type = 'error') {
            messageContainer.classList.remove('hidden');
            messageContent.className = type === 'error' ? 'error-message' : 'success-message';
            messageContent.textContent = message;
            
            // Auto hide success messages after 3 seconds
            if (type === 'success') {
                setTimeout(() => {
                    messageContainer.classList.add('hidden');
                }, 3000);
            }
        }

        function setLoading(loading) {
            loginBtn.disabled = loading;
            if (loading) {
                btnText.classList.add('hidden');
                btnLoader.classList.remove('hidden');
            } else {
                btnText.classList.remove('hidden');
                btnLoader.classList.add('hidden');
            }
        }

        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            if (!username || !password) {
                showMessage('Please fill in all fields');
                return;
            }

            setLoading(true);
            messageContainer.classList.add('hidden');

            try {
                const response = await fetch('{{ route("admin.login.post") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        username: username,
                        password: password
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showMessage('Login successful! Redirecting...', 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                } else {
                    showMessage(data.message || 'Login failed');
                }
            } catch (error) {
                console.error('Login error:', error);
                showMessage('Network error. Please try again.');
            } finally {
                setLoading(false);
            }
        });

        // Add CSRF meta tag for JavaScript
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = '{{ csrf_token() }}';
        document.head.appendChild(meta);
    </script>
</body>
</html>
