<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; font-family:Arial,sans-serif; }
    body, html { height:100%; display:flex; justify-content:center; align-items:center; background:#f4f4f4; }
    .container { display:flex; flex-direction:row; width:100%; height:100vh; background:white; }
    .image-section { flex:1; display:flex; justify-content:center; align-items:center; }
    .image-section img { width:100%; height:100%; object-fit:cover; }
    .form-section { flex:1; padding:50px; background:white; display:flex; flex-direction:column; justify-content:center; }
    h2 { margin-bottom:10px; text-align:center; font-size:24px; color:#333; }
    p { text-align:center; margin-bottom:15px; font-size:14px; color:#555; }
    form { display:flex; flex-direction:column; max-width:400px; margin:0 auto; width:100%; }
    label { margin:10px 0 5px; font-size:14px; color:#555; }
    input { padding:12px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px; font-size:16px; }
    button { padding:12px; background:#13C5DD; color:white; border:none; border-radius:5px; cursor:pointer; font-size:16px; }
    button:hover { opacity:0.9; }
    .text-primary { color:#13C5DD; }
    .alert-success { background:#d1fae5; color:#065f46; padding:12px 16px; border-radius:6px; margin-bottom:16px; font-size:14px; }
    .alert-error { background:#fee2e2; color:#991b1b; padding:12px 16px; border-radius:6px; margin-bottom:16px; font-size:14px; }
    @media(max-width:768px) {
      .container { flex-direction:column; }
      .image-section { height:30vh; }
      .form-section { padding:30px; }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="form-section">
      <a href="{{ route('doctor.login') }}" style="text-decoration:none;color:#13C5DD;margin-bottom:20px;display:inline-block;">
        &larr; Back to Login
      </a>

      <h2>Forgot Password</h2>
      <p>Enter your email address and we'll send you a reset link.</p>

      @if(session('success'))
        <div class="alert-success" style="max-width:400px;margin:0 auto 16px;">
          ✓ {{ session('success') }}
        </div>
      @endif

      @if($errors->any())
        <div class="alert-error" style="max-width:400px;margin:0 auto 16px;">
          {{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="{{ route('doctor.password.forgot.send') }}">
        @csrf
        <label for="email">Email Address</label>
        <input
          type="email"
          id="email"
          name="email"
          value="{{ old('email') }}"
          placeholder="doctor@hospital.com"
          required
          autocomplete="email">

        <button type="submit">Send Reset Link</button>
      </form>
    </div>

    <div class="image-section">
      <img src="../img/doctor2.jpg" alt="Doctor">
    </div>
  </div>
</body>
</html>