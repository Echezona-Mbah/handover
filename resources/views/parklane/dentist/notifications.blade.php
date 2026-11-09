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
          <li class="breadcrumb-item text-primary" aria-current="page">Notifications</li>
        </ol>
        <span class="ms-auto text-muted">{{ now()->format('l, F j, Y') }}</span>
      </div>

      <div class="app-body">
        @if (session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div>
              <h4 class="card-title mb-1">Your notifications</h4>
              <p class="small text-muted mb-0">Patient and care-team activity updates.</p>
            </div>
            @if ($unreadActivityCount > 0)
              <form method="POST" action="{{ route('dentist.activity-notifications.read-all') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary">
                  <i class="ri-check-double-line me-1"></i> Mark all as read
                </button>
              </form>
            @endif
          </div>

          <div class="list-group list-group-flush">
            @forelse ($notifications as $activityNotification)
              <a href="{{ route('dentist.activity-notifications.open', $activityNotification->id) }}"
                class="list-group-item list-group-item-action p-3 {{ $activityNotification->read_at ? '' : 'bg-light' }}">
                <div class="d-flex align-items-start gap-3">
                  <span class="icon-box bg-primary-subtle rounded-circle flex-shrink-0">
                    <i class="ri-notification-3-line text-primary"></i>
                  </span>
                  <span class="flex-grow-1 min-w-0">
                    <span class="d-flex flex-wrap align-items-center gap-2">
                      <strong>{{ $activityNotification->data['title'] ?? 'Patient update' }}</strong>
                      @unless ($activityNotification->read_at)
                        <span class="badge rounded-pill bg-primary">New</span>
                      @endunless
                    </span>
                    <span class="d-block text-muted mt-1">
                      {{ $activityNotification->data['message'] ?? 'There is a new care-team update.' }}
                    </span>
                    <span class="d-block small text-muted mt-2">
                      {{ $activityNotification->created_at->format('M j, Y g:i A') }}
                      <span class="mx-1">·</span>
                      {{ $activityNotification->created_at->diffForHumans() }}
                    </span>
                  </span>
                  <i class="ri-arrow-right-s-line text-muted align-self-center"></i>
                </div>
              </a>
            @empty
              <div class="text-center py-5">
                <i class="ri-notification-off-line fs-1 text-muted"></i>
                <h5 class="mt-3">No notifications yet</h5>
                <p class="text-muted mb-0">Patient and care-team updates will appear here.</p>
              </div>
            @endforelse
          </div>

          @if ($notifications->hasPages())
            <div class="card-footer bg-white">
              {{ $notifications->links() }}
            </div>
          @endif
        </div>
      </div>

      <div class="app-footer bg-white"><span>© {{ now()->year }} Fandova</span></div>
    </div>
  </div>
</div>

@include('parklane.dentist.script')
