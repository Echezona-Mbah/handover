<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
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
    .input-group { position:relative; margin-bottom:15px; }
    .input-group input { width:100%; padding:12px 44px 12px 12px; border:1px solid #ccc; border-radius:5px; font-size:16px; }
    .toggle-pw { position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#888; font-size:18px; padding:0; margin:0; }
    button[type=submit] { padding:12px; background:#13C5DD; color:white; border:none; border-radius:5px; cursor:pointer; font-size:16px; margin-top:5px; }
    button[type=submit]:hover { opacity:0.9; }
    .strength-bar { height:5px; border-radius:3px; margin-top:6px; transition:all 0.3s; }
    .strength-text { font-size:12px; margin-top:3px; }
    .match-text { font-size:12px; margin-top:3px; }
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
      <h2>Set New Password</h2>
      <p>Hello Dr. {{ $doctor->full_name }}. Enter your new password below.</p>

      @if($errors->any())
        <div class="alert-error" style="max-width:400px;margin:0 auto 16px;">
          {{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="{{ route('doctor.password.reset.submit') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <label for="password">New Password</label>
        <div class="input-group">
          <input type="password" id="password" name="password"
            placeholder="Min 8 characters"
            required oninput="checkStrength(this.value)">
          <button type="button" class="toggle-pw" onclick="togglePw('password', this)">👁</button>
        </div>
        <div class="strength-bar" id="strengthBar" style="width:0%;background:#e5e7eb;"></div>
        <div class="strength-text" id="strengthText"></div>

        <label for="password_confirmation">Confirm New Password</label>
        <div class="input-group">
          <input type="password" id="password_confirmation" name="password_confirmation"
            placeholder="Repeat password"
            required oninput="checkMatch()">
          <button type="button" class="toggle-pw" onclick="togglePw('password_confirmation', this)">👁</button>
        </div>
        <div class="match-text" id="matchText"></div>

        <button type="submit" style="margin-top:20px;">Reset Password</button>
      </form>
    </div>

    <div class="image-section">
      <img src="../img/doctor2.jpg" alt="Doctor">
    </div>
  </div>

  <script>
    function togglePw(id, btn) {
      const input = document.getElementById(id);
      input.type = input.type === 'password' ? 'text' : 'password';
      btn.textContent = input.type === 'password' ? '👁' : '🙈';
    }

    function checkStrength(val) {
      const bar  = document.getElementById('strengthBar');
      const text = document.getElementById('strengthText');
      let score  = 0;
      if (val.length >= 8)            score++;
      if (/[A-Z]/.test(val))         score++;
      if (/[0-9]/.test(val))         score++;
      if (/[^A-Za-z0-9]/.test(val)) score++;

      const levels = [
        { w:'25%', c:'#ef4444', l:'Weak' },
        { w:'50%', c:'#f59e0b', l:'Fair' },
        { w:'75%', c:'#3b82f6', l:'Good' },
        { w:'100%',c:'#10b981', l:'Strong' },
      ];
      const lv = levels[score - 1] || { w:'0%', c:'#e5e7eb', l:'' };
      bar.style.width      = lv.w;
      bar.style.background = lv.c;
      text.textContent     = lv.l;
      text.style.color     = lv.c;
    }

    function checkMatch() {
      const pw      = document.getElementById('password').value;
      const confirm = document.getElementById('password_confirmation').value;
      const text    = document.getElementById('matchText');
      if (!confirm) { text.textContent = ''; return; }
      if (pw === confirm) {
        text.textContent  = '✓ Passwords match';
        text.style.color  = '#10b981';
      } else {
        text.textContent  = '✗ Passwords do not match';
        text.style.color  = '#ef4444';
      }
    }
  </script>
</body>
</html>