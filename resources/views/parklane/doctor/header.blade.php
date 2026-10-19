<div class="app-header d-flex align-items-center">

  <div class="d-flex">
    <button class="toggle-sidebar">
      <i class="ri-menu-line"></i>
    </button>
    <button class="pin-sidebar">
      <i class="ri-menu-line"></i>
    </button>
  </div>

  <div class="app-brand ms-3">
    <a href="{{ asset('dashboard') }}" class="d-lg-block d-none">
      <img src="{{ asset('img/unthlogo.jpg') }}" class="logo" alt="Medicare Admin Template">
    </a>
    <a href="{{ asset('dashboard') }}" class="d-lg-none d-md-block">
      <img src="{{ asset('img/unthlogo.jpg') }}" class="logo" alt="Medicare Admin Template">
    </a>
  </div>

  <div class="header-actions">

    <div class="search-container d-lg-block d-none mx-3">
      <input type="text" class="form-control" id="searchId" placeholder="Search">
      <i class="ri-search-line"></i>
    </div>

    {{-- Notifications --}}
    <div class="d-flex gap-2 align-items-center">
      @php
        use App\Models\DoctorNotification;

        $doctor    = Auth::guard('doctor')->user();
        $unitId    = $doctor->doctor_unit_id    ?? null;
        $subUnitId = $doctor->doctor_sub_unit_id ?? null;

        $notifications = DoctorNotification::query()
            ->where('doctor_unit_id', $unitId)
            ->when($subUnitId, fn ($q) => $q->where(function ($qq) use ($subUnitId) {
                $qq->whereNull('doctor_sub_unit_id')
                   ->orWhere('doctor_sub_unit_id', $subUnitId);
            }))
            ->latest()
            ->get();

        $unseenCount = $notifications->whereNull('seen_at')->count();

        $doctorInitials = collect(explode(' ', $doctor->full_name ?? 'D O'))
            ->filter()
            ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
            ->take(2)
            ->implode('');
      @endphp

      <div class="dropdown">
        <a class="dropdown-toggle header-icon position-relative" href="#!" role="button"
          data-bs-toggle="dropdown" aria-expanded="false" id="doctorMessageDropdown">
          <i class="ri-message-3-line"></i>
          @if($unseenCount > 0)
            <span id="doctorNotificationCount"
              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
              style="font-size:10px; min-width:18px;">
              {{ $unseenCount }}
            </span>
          @else
            <span id="doctorNotificationCount"></span>
          @endif
        </a>

        <div class="dropdown-menu dropdown-menu-end dropdown-300">
          <h5 class="fw-semibold px-3 py-2 text-primary border-bottom mb-0">
            <i class="ri-notification-3-line me-1"></i> Notifications
            @if($unseenCount > 0)
              <span class="badge bg-danger ms-1" style="font-size:11px;">{{ $unseenCount }}</span>
            @endif
          </h5>

          <div class="scroll300">
            <div class="p-2">
              @forelse ($notifications as $n)
                <div class="d-flex align-items-start py-2 px-2 rounded mb-1
                  {{ is_null($n->seen_at) ? 'bg-light' : '' }}">
                    <img src="{{ $doctor->profile_image ? asset('storage/' . $doctor->profile_image) : asset('assets/images/user2.png') }}"
                      class="img-shadow me-2 rounded-circle flex-shrink-0"
                      style="width:38px; height:38px; object-fit:cover;"
                      alt="Notification">
                  <div class="flex-1 min-w-0">
                    <div class="d-flex justify-content-between align-items-center">
                      <h6 class="mb-0 fw-semibold" style="font-size:13px;">{{ $n->title }}</h6>
                      @if(is_null($n->seen_at))
                        <span class="badge bg-primary ms-2" style="font-size:9px;">New</span>
                      @endif
                    </div>
                    <p class="mb-1 text-muted" style="font-size:12px; line-height:1.4;">
                      {{ $n->message }}
                    </p>
                    <p class="small m-0 text-muted opacity-75" style="font-size:11px;">
                      <i class="ri-time-line me-1"></i>
                      {{ $n->created_at->format('M d, Y h:i A') }}
                    </p>
                  </div>
                </div>
              @empty
                <div class="text-center py-4">
                  <i class="ri-notification-off-line text-muted" style="font-size:2rem;"></i>
                  <p class="small text-muted mt-2 mb-0">No notifications yet</p>
                </div>
              @endforelse
            </div>
          </div>

          <div class="d-grid m-3">
            <a href="#!" class="btn btn-primary btn-sm" id="viewAllNotifications">
              <i class="ri-check-double-line me-1"></i> Mark all as read
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- User dropdown --}}
    <div class="dropdown ms-2">
      <a id="userSettings" class="dropdown-toggle d-flex align-items-center" href="#!" role="button"
        data-bs-toggle="dropdown" aria-expanded="false">
        <div class="avatar-box">{{ $doctorInitials }}<span class="status busy"></span></div>
      </a>

      <div class="dropdown-menu dropdown-menu-end shadow-lg">
        <div class="px-3 py-2">
          <span class="small text-muted">Logged in as</span>
          <h6 class="m-0">Dr. {{ $doctor->full_name }}</h6>
        </div>
        <div class="dropdown-divider"></div>
        <div class="mx-3 my-2 d-grid">
          <form method="POST" action="{{ route('doctor.logout') }}">
            @csrf
            <a href="{{ route('doctor.logout') }}"
              onclick="event.preventDefault(); this.closest('form').submit();"
              class="btn btn-danger btn-sm w-100">
              <i class="ri-logout-box-line me-1"></i> Logout
            </a>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>

<style>
  @media (max-width: 576px) {
    .dropdown-300 {
      width: 290px !important;
      right: 0 !important;
      left: auto !important;
    }
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const viewAllBtn  = document.getElementById('viewAllNotifications');
  const countLabel  = document.getElementById('doctorNotificationCount');

  if (!viewAllBtn) return;

  viewAllBtn.addEventListener('click', function (e) {
    e.preventDefault();

    fetch("{{ route('doctor.notifications.seen') }}", {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept':       'application/json',
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({})
    })
    .then(res => res.json())
    .then(() => {
      // Clear badge
      if (countLabel) countLabel.textContent = '';

      // Remove "New" badges and bg-light from all notification rows
      document.querySelectorAll('.badge.bg-primary').forEach(el => el.remove());
      document.querySelectorAll('.dropdown-menu .bg-light').forEach(el => {
        el.classList.remove('bg-light');
      });

      // Update button
      viewAllBtn.innerHTML = '<i class="ri-check-double-line me-1"></i> All read';
      viewAllBtn.classList.replace('btn-primary', 'btn-success');
      viewAllBtn.disabled = true;
    })
    .catch(err => console.error('Notification error:', err));
  });
});
</script>