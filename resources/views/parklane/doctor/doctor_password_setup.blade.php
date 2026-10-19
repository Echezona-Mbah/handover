@include('parklane.doctor.head')
<style>
  body {
    background: #f8f9fa;
  }

  .auth-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
  }

  .auth-card {
    width: 100%;
    max-width: 460px;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    padding: 40px 36px;
  }

  .auth-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    background: linear-gradient(135deg, #007bff, #0056b3);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
  }

  .auth-icon i {
    font-size: 26px;
    color: #fff;
  }

  .auth-title {
    font-size: 22px;
    font-weight: 700;
    text-align: center;
    color: #1f2a37;
    margin-bottom: 6px;
  }

  .auth-subtitle {
    font-size: 14px;
    text-align: center;
    color: #6b7280;
    margin-bottom: 28px;
    line-height: 1.6;
  }

  .auth-subtitle strong {
    color: #374151;
  }

  .form-label {
    font-weight: 600;
    font-size: 0.9rem;
    color: #495057;
  }

  .form-control {
    font-size: 1rem;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    border: 1px solid #ced4da;
    transition: all 0.3s ease-in-out;
  }

  .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
  }

  .password-field-wrapper {
    position: relative;
  }

  .password-toggle-btn {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #9aa3af;
    cursor: pointer;
    font-size: 18px;
    padding: 0;
    line-height: 1;
  }

  .password-toggle-btn:hover {
    color: #6b7280;
  }

  .field-hint {
    font-size: 12px;
    color: #9aa3af;
    margin-top: 6px;
  }

  .btn-submit {
    width: 100%;
    padding: 12px;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 0.5rem;
    background-color: #007bff;
    border: none;
    color: #fff;
    transition: background-color 0.3s ease;
  }

  .btn-submit:hover {
    background-color: #0056b3;
    color: #fff;
  }

  .auth-errors {
    background: #fdecec;
    border: 1px solid #f5c2c2;
    border-radius: 10px;
    padding: 14px 16px;
    margin-bottom: 22px;
  }

  .auth-errors ul {
    margin: 0;
    padding-left: 0;
    list-style: none;
  }

  .auth-errors li {
    font-size: 14px;
    color: #b42318;
    display: flex;
    align-items: flex-start;
    gap: 8px;
  }

  .auth-errors li i {
    margin-top: 2px;
  }

  .auth-footer-note {
    text-align: center;
    font-size: 12.5px;
    color: #9aa3af;
    margin-top: 22px;
  }

  @media (max-width: 576px) {
    .auth-card {
      padding: 28px 22px;
    }
  }
</style>

<div class="auth-wrapper">
  <div class="auth-card">

    <div class="auth-icon">
      <i class="ri-stethoscope-line"></i>
    </div>

    <h2 class="auth-title">Set Your Password</h2>
    <p class="auth-subtitle">
      Hi <strong>{{ $doctor->full_name }}</strong>, choose a password to activate your account.
    </p>

    @if ($errors->any())
      <div class="auth-errors">
        <ul>
          @foreach ($errors->all() as $error)
            <li><i class="ri-error-warning-line"></i> {{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('doctor.password.setup.submit', $token) }}">
      @csrf

      <div class="mb-3">
        <label class="form-label">New Password<span class="text-danger">*</span></label>
        <div class="password-field-wrapper">
          <input
            type="password"
            id="password"
            name="password"
            class="form-control"
            placeholder="At least 8 characters"
            required
            minlength="8"
          >
          <button type="button" class="password-toggle-btn" onclick="togglePassword('password', this)">
            <i class="ri-eye-line"></i>
          </button>
        </div>
        <p class="field-hint">Use 8+ characters with a mix of letters and numbers.</p>
      </div>

      <div class="mb-4">
        <label class="form-label">Confirm Password<span class="text-danger">*</span></label>
        <div class="password-field-wrapper">
          <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            class="form-control"
            placeholder="Re-enter your password"
            required
            minlength="8"
          >
          <button type="button" class="password-toggle-btn" onclick="togglePassword('password_confirmation', this)">
            <i class="ri-eye-line"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="btn-submit">
        Set Password &amp; Activate Account
      </button>
    </form>

    <p class="auth-footer-note">Having trouble? Contact your administrator for help.</p>
  </div>
</div>

<script>
  function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    const isHidden = input.type === 'password';

    input.type = isHidden ? 'text' : 'password';
    icon.classList.toggle('ri-eye-line', !isHidden);
    icon.classList.toggle('ri-eye-off-line', isHidden);
  }
</script>

@include('parklane.script')