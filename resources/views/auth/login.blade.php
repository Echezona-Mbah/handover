<x-auth-session-status class="mb-4" :status="session('status')" />

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body, html {
            height: 100%;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f4f4;
        }

        .container {
            display: flex;
            flex-direction: row;
            width: 100%;
            height: 100vh;
            background: white;
        }

        .image-section {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .image-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        h2 {
            margin-bottom: 15px;
            text-align: center;
            font-size: 24px;
            color: #333;
        }

        p {
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
            color: #555;
        }
        .text-primary{
            color: #13C5DD;

        }
        .text-secondary{
            color: #13C5DD;
        }
        .form-section {
            flex: 1;
            padding: 50px;
            background: white;
        }

        h2 {
            margin-bottom: 20px;
            text-align: center;
            font-size: 24px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            max-width: 400px;
            margin: 0 auto;
        }

        label {
            margin: 10px 0 5px;
            font-size: 14px;
            color: #555;
            max-width: 600px;

        }

        input, select, button {
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background-color: #13C5DD;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #13C5DD;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .image-section {
                height: 40vh;
                order: 2;
            }

            .form-section {
                order: 1;
                padding: 30px;
            }
        }
        #toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    z-index: 1000;
}

.toast {
    position: relative;
    min-width: 250px;
    background: red;
    color: white;
    padding: 15px;
    border-radius: 5px;
    font-size: 16px;
    animation: fadeIn 0.5s ease-in-out;
    overflow: hidden;
}

.toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    background: white;
    width: 100%;
    animation: progressBar 3s linear forwards;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes progressBar {
    from { width: 100%; }
    to { width: 0%; }
}


    </style>
</head>
<body>
    <div id="toast-container"></div>
    @if ($errors->any())
        <script>
            window.onload = function() {
                @foreach ($errors->all() as $error)
                    showToast("{{ $error }}");
                @endforeach
            };
        </script>
    @endif


    <div class="container">
        <div class="form-section">
            <a href="javascript:history.back();" class="text-decoration-none text-secondary">&larr; Back</a>
            <h2>login to Admin</h2>
            <p class="text-center text-muted">Forgot your password? <a href="{{ route('password.request') }}" class="text-primary">Sign in here</a></p>
            
            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <label for="email">Email</label>
                <input type="email" id="email" name="email" :value="old('email')"  autocomplete="username">

                <label for="password">Password</label>
<div style="position:relative; margin-bottom:15px;">
    <input
        type="password"
        id="password"
        name="password"
        autocomplete="current-password"
        style="width:100%; padding:12px; padding-right:45px; border:1px solid #ccc; border-radius:5px; font-size:16px; margin-bottom:0;">
    <button
        type="button"
        onclick="togglePassword()"
        style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; padding:0; color:#888;">
        <svg id="eyeShow" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        </svg>
        <svg id="eyeHide" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display:none;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
        </svg>
    </button>
</div>

                
                <button type="submit">Login</button>
            </form>
        </div>
        <div class="image-section">
            <img src="img/african.jpg" alt="Register Image">
        </div>
    </div>
    <script>
        function showToast(message) {
            var toastContainer = document.getElementById("toast-container");
    
            var toast = document.createElement("div");
            toast.className = "toast";
            toast.textContent = message;
    
            var progress = document.createElement("div");
            progress.className = "toast-progress";
    
            toast.appendChild(progress);
            toastContainer.appendChild(toast);
    
            setTimeout(() => {
                toast.remove();
            }, 3000); // Toast disappears after 3 seconds
        }
    </script>
    <script>
    function togglePassword() {
        const input   = document.getElementById('password');
        const eyeShow = document.getElementById('eyeShow');
        const eyeHide = document.getElementById('eyeHide');

        if (input.type === 'password') {
            input.type        = 'text';
            eyeShow.style.display = 'none';
            eyeHide.style.display = 'block';
        } else {
            input.type        = 'password';
            eyeShow.style.display = 'block';
            eyeHide.style.display = 'none';
        }
    }
</script>
    
</body>
</html>






{{-- <x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}
