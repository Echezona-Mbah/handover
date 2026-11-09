@include('parklane.dentist.head')

<div class="page-wrapper">
  @include('parklane.dentist.header')

  <div class="main-container">
    @include('parklane.dentist.sidebar')

    <div class="app-container">
      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{ route('dentist.dashboard') }}">Home</a>
          </li>
          <li class="breadcrumb-item text-primary" aria-current="page">Dashboard</li>
        </ol>
        <span class="ms-auto text-muted">{{ now()->format('l, F j, Y') }}</span>
      </div>

      <div class="app-body">
        <section class="card border-0 shadow-sm mb-4 overflow-hidden"
          style="background: linear-gradient(120deg, #155eef 0%, #243b80 100%);">
          <div class="card-body p-4 p-lg-5 text-white">
            <div class="row align-items-center gy-4">
              <div class="col-lg-8">
                <div class="d-flex align-items-center gap-3">
                  @if ($dentist->profile_image)
                    <img src="{{ asset('storage/' . $dentist->profile_image) }}"
                      class="rounded-circle border border-3 border-white" width="76" height="76"
                      style="object-fit: cover;" alt="Dr. {{ $dentist->full_name }}">
                  @else
                    <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold fs-2"
                      style="width: 76px; height: 76px;">
                      {{ strtoupper(substr($dentist->full_name ?? 'D', 0, 1)) }}
                    </div>
                  @endif
                  <div>
                    <span class="small text-white-50">DENTIST DASHBOARD</span>
                    <h2 class="mb-1 text-white">Welcome, Dr. {{ $dentist->full_name }}</h2>
                    <p class="mb-0 text-white-50">
                      {{ $dentist->DentistDepartment?->name ?? 'Dental care' }}
                      @if ($dentist->DentistRole?->name)
                        <span class="mx-1">·</span>{{ $dentist->DentistRole->name }}
                      @endif
                      @if ($dentist->hospitals?->name)
                        <span class="mx-1">·</span>{{ $dentist->hospitals->name }}
                      @endif
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 text-lg-end">
                <a href="{{ route('dentist.add_patient') }}" class="btn btn-light text-primary me-2 mb-2">
                  <i class="ri-user-add-line me-1"></i> Add patient
                </a>
                <a href="{{ route('dentist.patient') }}" class="btn btn-outline-light mb-2">
                  <i class="ri-user-heart-line me-1"></i> View patients
                </a>
              </div>
            </div>
          </div>
        </section>

        @if ($urgentPatientCount > 0)
          <a href="{{ route('dentist.patient') }}" class="alert alert-warning d-flex align-items-center gap-2 mb-4">
            <i class="ri-alarm-warning-line fs-4"></i>
            <span><strong>{{ $urgentPatientCount }}</strong> of your assigned patients have urgent or emergency status. Review their records.</span>
            <i class="ri-arrow-right-line ms-auto"></i>
          </a>
        @endif

        <div class="row gx-3 mb-3">
          <div class="col-md-6 col-xl-4">
            <a href="{{ route('dentist.patient') }}" class="text-decoration-none">
              <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                  <div class="icon-box xl bg-primary-subtle rounded-4 no-shadow">
                    <i class="ri-user-heart-line fs-2 text-primary"></i>
                  </div>
                  <div>
                    <div class="text-muted small">Assigned to you</div>
                    <div class="fs-2 fw-bold text-dark">{{ $patientCount }}</div>
                    <div class="small text-primary">View your patients <i class="ri-arrow-right-line"></i></div>
                  </div>
                </div>
              </div>
            </a>
          </div>
          <div class="col-md-6 col-xl-4">
            <a href="{{ route('dentist.available') }}" class="text-decoration-none">
              <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                  <div class="icon-box xl bg-success-subtle rounded-4 no-shadow">
                    <i class="ri-time-line fs-2 text-success"></i>
                  </div>
                  <div>
                    <div class="text-muted small">Work schedule</div>
                    <div class="fs-5 fw-semibold text-dark">{{ $dentist->status ?? 'Status not set' }}</div>
                    <div class="small text-success">Manage availability <i class="ri-arrow-right-line"></i></div>
                  </div>
                </div>
              </div>
            </a>
          </div>
          <div class="col-md-6 col-xl-4">
            <a href="{{ route('dentist.profile') }}" class="text-decoration-none">
              <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                  <div class="icon-box xl bg-info-subtle rounded-4 no-shadow">
                    <i class="ri-id-card-line fs-2 text-info"></i>
                  </div>
                  <div>
                    <div class="text-muted small">Professional profile</div>
                    <div class="fs-5 fw-semibold text-dark">{{ $dentist->qualification ?: 'Add your qualification' }}</div>
                    <div class="small text-info">Update your profile <i class="ri-arrow-right-line"></i></div>
                  </div>
                </div>
              </div>
            </a>
          </div>
        </div>

        <div class="row gx-3">
          <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
              <div class="card-header bg-white d-flex align-items-center justify-content-between">
                <div>
                  <h5 class="card-title mb-1">Weekly shift</h5>
                  <span class="text-muted small">Your scheduled working hours</span>
                </div>
                <a href="{{ route('dentist.available') }}" class="btn btn-sm btn-outline-primary">Edit availability</a>
              </div>
              <div class="card-body">
                @forelse ($dentist->shiftSchedules as $shift)
                  <div class="row g-2">
                    @foreach (['mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thu' => 'Thursday', 'fri' => 'Friday', 'sat' => 'Saturday', 'sun' => 'Sunday'] as $day => $label)
                      @php
                        $start = $shift->{$day . '_start'};
                        $end = $shift->{$day . '_end'};
                      @endphp
                      <div class="col-sm-6 col-xl-4">
                        <div class="rounded-3 border p-3 h-100">
                          <div class="small text-muted">{{ $label }}</div>
                          @if ($start && $end)
                            <div class="fw-semibold mt-1">
                              {{ \Carbon\Carbon::parse($start)->format('g:i A') }}
                              <span class="text-muted mx-1">to</span>
                              {{ \Carbon\Carbon::parse($end)->format('g:i A') }}
                            </div>
                          @else
                            <div class="text-muted mt-1">No shift scheduled</div>
                          @endif
                        </div>
                      </div>
                    @endforeach
                  </div>
                @empty
                  <div class="text-center py-4">
                    <i class="ri-calendar-line fs-1 text-muted"></i>
                    <p class="text-muted mt-2 mb-3">You do not have a weekly shift schedule yet.</p>
                    <a href="{{ route('dentist.available') }}" class="btn btn-primary">Set availability</a>
                  </div>
                @endforelse
              </div>
            </div>

            <div class="card border-0 shadow-sm">
              <div class="card-header bg-white d-flex align-items-center justify-content-between">
                <div>
                  <h5 class="card-title mb-1">Recent care activity</h5>
                  <span class="text-muted small">Patient and care-team updates</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                  @if ($unreadActivityCount > 0)
                    <span class="badge rounded-pill bg-danger">{{ $unreadActivityCount }} unread</span>
                  @endif
                  <a href="{{ route('dentist.activity-notifications.index') }}" class="btn btn-sm btn-outline-primary">
                    View all
                  </a>
                </div>
              </div>
              <div class="card-body py-2">
                @forelse ($recentActivity as $activityNotification)
                  @php
                    $activityType = $activityNotification->data['activity'] ?? '';
                    $activityIcon = match (true) {
                      str_contains($activityType, 'medication') => 'ri-capsule-line',
                      str_contains($activityType, 'handover') => 'ri-file-transfer-line',
                      str_contains($activityType, 'transfer') => 'ri-arrow-left-right-line',
                      str_contains($activityType, 'invite') => 'ri-user-shared-line',
                      str_contains($activityType, 'discharg') => 'ri-logout-box-line',
                      default => 'ri-heart-pulse-line',
                    };
                  @endphp
                  <a href="{{ route('dentist.activity-notifications.open', $activityNotification->id) }}"
                    class="dentist-activity-item text-decoration-none text-reset">
                    <span class="dentist-activity-icon"><i class="{{ $activityIcon }}"></i></span>
                    <span class="flex-grow-1 min-w-0">
                      <span class="d-flex align-items-center gap-2">
                        <strong class="text-truncate">{{ $activityNotification->data['title'] ?? 'Patient update' }}</strong>
                        @if (! $activityNotification->read_at)
                          <span class="badge rounded-pill bg-primary-subtle text-primary">New</span>
                        @endif
                      </span>
                      <span class="d-block small text-muted">{{ $activityNotification->data['message'] ?? '' }}</span>
                      <span class="d-block small text-muted mt-1">{{ $activityNotification->created_at->diffForHumans() }}</span>
                    </span>
                    <i class="ri-arrow-right-s-line text-muted align-self-center"></i>
                  </a>
                @empty
                  <div class="text-center py-4">
                    <i class="ri-notification-3-line fs-2 text-muted"></i>
                    <p class="text-muted mb-0 mt-2">No care activity has been recorded yet.</p>
                  </div>
                @endforelse
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
              <div class="card-header bg-white">
                <h5 class="card-title mb-0">Quick actions</h5>
              </div>
              <div class="card-body d-grid gap-2">
                <a href="{{ route('dentist.add_patient') }}" class="btn btn-outline-primary text-start">
                  <i class="ri-user-add-line me-2"></i> Register a patient
                </a>
                <a href="{{ route('dentist.patient') }}" class="btn btn-outline-primary text-start">
                  <i class="ri-search-line me-2"></i> Find an assigned patient
                </a>
                <a href="{{ route('dentist.news') }}" class="btn btn-outline-primary text-start">
                  <i class="ri-newspaper-line me-2"></i> Hospital news & updates
                </a>
                <a href="{{ route('dentist.profile') }}" class="btn btn-outline-primary text-start">
                  <i class="ri-user-settings-line me-2"></i> Update professional profile
                </a>
              </div>
            </div>

            <div class="card border-0 shadow-sm">
              <div class="card-header bg-white">
                <h5 class="card-title mb-0">Professional details</h5>
              </div>
              <div class="card-body">
                <div class="d-flex justify-content-between gap-3 mb-3">
                  <span class="text-muted">Department</span>
                  <span class="text-end fw-medium">{{ $dentist->DentistDepartment?->name ?? 'Not assigned' }}</span>
                </div>
                <div class="d-flex justify-content-between gap-3 mb-3">
                  <span class="text-muted">Role</span>
                  <span class="text-end fw-medium">{{ $dentist->DentistRole?->name ?? 'Not assigned' }}</span>
                </div>
                <div class="d-flex justify-content-between gap-3">
                  <span class="text-muted">Hospital</span>
                  <span class="text-end fw-medium">{{ $dentist->hospitals?->name ?? 'Not assigned' }}</span>
                </div>
              </div>
            </div>

            <div class="card border-0 shadow-sm mt-3">
              <div class="card-header bg-white d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">Hospital updates</h5>
                <a href="{{ route('dentist.news') }}" class="small text-decoration-none">View all</a>
              </div>
              <div class="card-body">
                @forelse ($hospitalUpdates as $update)
                  <a href="{{ route('dentist.show', $update->id) }}" class="d-block text-decoration-none text-reset py-2 {{ $loop->last ? '' : 'border-bottom' }}">
                    <div class="fw-semibold">{{ $update->title }}</div>
                    <div class="small text-muted mt-1">
                      {{ \Illuminate\Support\Str::limit(strip_tags($update->content ?? ''), 100) }}
                    </div>
                    <div class="small text-muted mt-1">{{ $update->created_at?->diffForHumans() }}</div>
                  </a>
                @empty
                  <p class="small text-muted mb-0">There are no recent hospital announcements.</p>
                @endforelse
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="app-footer bg-white"><span>© {{ now()->year }} Fandova</span></div>
    </div>
  </div>
</div>

@include('parklane.dentist.script')
