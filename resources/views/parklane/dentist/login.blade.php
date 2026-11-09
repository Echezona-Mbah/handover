<x-auth-session-status class="mb-4" :status="session('status')" />

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dentist sign in | Fandova</title>

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
            background: #eef3fa;
        }

        .container {
            display: flex;
            flex-direction: row;
            width: min(1120px, calc(100% - 40px));
            min-height: 680px;
            height: min(760px, calc(100vh - 40px));
            margin: 20px auto;
            background: #fff;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 28px 80px rgba(16, 42, 86, .16);
        }

        .image-section {
            flex: 1;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #102a56;
        }

        .image-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: .82;
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
            padding: clamp(30px, 6vw, 76px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: linear-gradient(145deg, #ffffff, #f7faff);
        }

        .form-section h2 {
            text-align: left;
            color: #172b4d;
            font-size: clamp(28px, 3vw, 38px);
            font-weight: 700;
        }

        .form-section form {
            width: 100%;
        }

        .form-section input {
            border-radius: 10px;
            border-color: #d8e0eb;
        }

        .form-section button {
            margin-top: 8px;
            border-radius: 10px;
            background: #155eef;
            font-weight: 700;
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
            background-color: #0b4bc1;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                width: min(560px, calc(100% - 24px));
                height: auto;
                min-height: calc(100vh - 24px);
                margin: 12px auto;
            }

            .image-section {
                height: 190px;
                order: 2;
            }

            .form-section {
                order: 1;
                padding: 36px 28px;
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
            <h2>Dentist login</h2>
            <p class="text-center text-muted">Access your dental care workspace securely.</p>
            
            <form action="{{ route('dentist.login.submit') }}" method="POST">
                @csrf
                
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="username" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" autocomplete="current-password" required>

                
                <button type="submit">Login</button>
            </form>
        </div>
        <div class="image-section">
            <img src="{{ asset('assets/images/78f00bdb0503d5f6e2.jpg') }}" alt="Dental care team">
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
    @include('partials.password-visibility')
    
    
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
