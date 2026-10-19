<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

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
            <h2>Create Account</h2>
            <p class="text-center text-muted">Already have an account? <a href="{{ route('login') }}" class="text-primary">Sign in here</a></p>
            
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" :value="old('name')"  autofocus autocomplete="name">

                
                <label for="email">Email</label>
                <input type="email" id="email" name="email" :value="old('email')"  autocomplete="username">

                <label for="password">Password</label>
                <input type="password" id="password" name="password"  autocomplete="new-password">

                <label for="password">Confirm Password</label>
                <input type="password" id="password" name="password_confirmation"  autocomplete="new-password">
                <label for="hospital">Select Hospital</label>
                <select id="hospital" name="hospital">
                    <option disabled selected>-- Select Hospital --</option>
                    @foreach($hospitals as $hospital)
                        <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                    @endforeach
                </select>
                
                
                <button type="submit">Register</button>
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
    
    
</body>
</html>