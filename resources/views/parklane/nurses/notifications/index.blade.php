@include('parklane.nurses.head')

<div class="page-wrapper">
  @include('parklane.nurses.header')
  <div class="main-container">
    @include('parklane.nurses.sidebar')

    <div class="app-container">
      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('nurse.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item text-primary" aria-current="page">Notifications</li>
        </ol>
      </div>

      <div class="app-body">
        @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">Notifications</h5>
            <span class="badge bg-primary">{{ Auth::guard('nurse')->user()->unreadNotifications()->count() }} unread</span>
          </div>
          <div class="card-body">
            @forelse ($notifications as $notification)
              @php($notificationData = $notification->data)
              @php($isUrgent = (bool) ($notificationData['urgent'] ?? false))
              @php($urgency = strtolower($notificationData['urgency'] ?? ''))
              <article class="border rounded p-3 mb-3 {{ $isUrgent ? 'border-danger border-2 bg-danger-subtle' : ($urgency === 'urgent' ? 'border-warning border-2' : ($notification->read_at ? 'bg-light' : '')) }}">
                <div class="d-flex justify-content-between align-items-start gap-3">
                  <div>
                    <h6 class="mb-1">
                      @if ($isUrgent)<i class="ri-error-warning-line text-danger me-1" aria-label="Urgent"></i>@endif
                      {{ $notificationData['title'] ?? 'Admin notification' }}
                      @unless ($notification->read_at)
                        <span class="badge bg-warning text-dark ms-1">New</span>
                      @endunless
                    </h6>
                    @if ($isUrgent)<span class="badge bg-danger mb-2">URGENT</span>@endif
                    <p class="mb-2">{{ $notificationData['message'] ?? '' }}</p>
                    @if ($urgency === 'urgent')
                      <span class="badge bg-warning text-dark">Patient urgency: Urgent</span>
                    @elseif ($urgency === 'emergency')
                      <span class="badge bg-danger">Patient urgency: Emergency</span>
                    @endif
                    <p class="small text-muted mb-0">
                      From {{ $notificationData['sender_name'] ?? 'Administrator' }}
                      · {{ $notification->created_at->format('M j, Y g:i A') }}
                    </p>
                  </div>
                  <div class="d-flex flex-column gap-2">
                    @if (!empty($notificationData['url']))
                      <a href="{{ $notificationData['url'] }}" class="btn btn-sm {{ $isUrgent ? 'btn-danger' : 'btn-outline-primary' }}">
                        {{ $isUrgent ? 'View emergency' : 'View details' }}
                      </a>
                    @endif
                    @if (($notificationData['requires_acknowledgement'] ?? false) && !$notification->acknowledged_at)
                      <form method="POST" action="{{ route('nurse.notifications.acknowledge', $notification->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm">Acknowledge</button>
                      </form>
                    @elseif (!$notification->read_at)
                      <form method="POST" action="{{ route('nurse.notifications.read', $notification->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary btn-sm">Mark as read</button>
                      </form>
                    @elseif ($notification->acknowledged_at)
                      <span class="badge bg-success">Acknowledged</span>
                    @endif
                  </div>
                </div>
              </article>
            @empty
              <p class="text-muted mb-0">You do not have any notifications yet.</p>
            @endforelse

            {{ $notifications->links() }}
          </div>
        </div>
      </div>

      @include('parklane.nurses.script')
    </div>
  </div>
</div>
