@include('parklane.doctor.head')

<div class="page-wrapper">

  @include('parklane.doctor.header')

  <div class="main-container">

    @include('parklane.doctor.sidebar')

    <div class="app-container">

      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{ route('doctor.dashboard') }}">Home</a>
          </li>
          <li class="breadcrumb-item text-primary" aria-current="page">
            Change Password
          </li>
        </ol>
      </div>

      <div class="app-body">
        <div class="row gx-3 justify-content-center">
          <div class="col-lg-6 col-md-8 col-sm-12">

            <div class="card">
              <div class="card-header">
                <h5 class="card-title">
                  <i class="ri-lock-password-line me-2"></i> Change Password
                </h5>
              </div>

              <div class="card-body">

                @if(session('success'))
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ri-checkbox-circle-line me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                  </div>
                @endif

                @if($errors->any())
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ri-error-warning-line me-1"></i>
                    <ul class="mb-0 ps-3">
                      @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                  </div>
                @endif

                <form method="POST" action="{{ route('doctor.password.change.store') }}">
                  @csrf

                  {{-- Current Password --}}
                  <div class="mb-3">
                    <label class="form-label">Current Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="ri-lock-line"></i></span>
                      <input
                        type="password"
                        name="current_password"
                        id="current_password"
                        class="form-control @error('current_password') is-invalid @enderror"
                        placeholder="Enter current password"
                        required>
                      <button type="button" class="btn btn-outline-secondary toggle-password" data-target="current_password">
                        <i class="ri-eye-line"></i>
                      </button>
                      @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>

                  {{-- New Password --}}
                  <div class="mb-3">
                    <label class="form-label">New Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="ri-lock-password-line"></i></span>
                      <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Enter new password"
                        required
                        oninput="checkStrength(this.value)">
                      <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password">
                        <i class="ri-eye-line"></i>
                      </button>
                      @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>

                    {{-- Strength bar --}}
                    <div class="mt-2">
                      <div class="progress" style="height: 5px;">
                        <div id="strengthBar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
                      </div>
                      <small id="strengthText" class="text-muted"></small>
                    </div>
                  </div>

                  {{-- Confirm Password --}}
                  <div class="mb-3">
                    <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="ri-lock-password-line"></i></span>
                      <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        class="form-control"
                        placeholder="Confirm new password"
                        required>
                      <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password_confirmation">
                        <i class="ri-eye-line"></i>
                      </button>
                    </div>
                    <small id="matchText" class="text-muted"></small>
                  </div>

                  <div class="d-flex gap-2 justify-content-end mt-4">
                    <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                      <i class="ri-save-line me-1"></i> Update Password
                    </button>
                  </div>

                </form>
              </div>
            </div>

          </div>
        </div>
      </div>

      <div class="app-footer bg-white">
        <span>© 2024</span>
      </div>

    </div>
  </div>
</div>

<script>
// ── Toggle password visibility ──────────────────────────────────────────────
document.querySelectorAll('.toggle-password').forEach(btn => {
  btn.addEventListener('click', function () {
    const input = document.getElementById(this.dataset.target);
    const icon  = this.querySelector('i');
    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.replace('ri-eye-line', 'ri-eye-off-line');
    } else {
      input.type = 'password';
      icon.classList.replace('ri-eye-off-line', 'ri-eye-line');
    }
  });
});

// ── Password strength ───────────────────────────────────────────────────────
function checkStrength(val) {
  const bar   = document.getElementById('strengthBar');
  const text  = document.getElementById('strengthText');
  let score   = 0;

  if (val.length >= 8)            score++;
  if (/[A-Z]/.test(val))         score++;
  if (/[0-9]/.test(val))         score++;
  if (/[^A-Za-z0-9]/.test(val)) score++;

  const levels = [
    { width: '25%',  color: 'bg-danger',  label: 'Weak' },
    { width: '50%',  color: 'bg-warning', label: 'Fair' },
    { width: '75%',  color: 'bg-info',    label: 'Good' },
    { width: '100%', color: 'bg-success', label: 'Strong' },
  ];

  const level       = levels[score - 1] || { width: '0%', color: '', label: '' };
  bar.style.width   = level.width;
  bar.className     = `progress-bar ${level.color}`;
  text.textContent  = level.label;
}

// ── Password match check ────────────────────────────────────────────────────
document.getElementById('password_confirmation')?.addEventListener('input', function () {
  const pw   = document.getElementById('password').value;
  const text = document.getElementById('matchText');

  if (this.value === '') {
    text.textContent = '';
  } else if (this.value === pw) {
    text.textContent = '✓ Passwords match';
    text.className   = 'text-success small';
  } else {
    text.textContent = '✗ Passwords do not match';
    text.className   = 'text-danger small';
  }
});
</script>

@include('parklane.script')