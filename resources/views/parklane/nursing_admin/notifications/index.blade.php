@include('parklane.head')
<div class="page-wrapper">
  @include('parklane.header')
  <div class="main-container">
    @include('parklane.sidebar')
    <div class="app-container">
      <div class="app-hero-header"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('admin.nursing.dashboard') }}">Dashboard</a></li><li class="breadcrumb-item text-primary">Report Notifications</li></ol></div>
      <div class="app-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <div class="card"><div class="card-header d-flex justify-content-between align-items-center"><h5 class="card-title mb-0">Report Notifications</h5><span class="badge bg-primary">{{ auth()->user()->unreadNotifications()->where('type', \App\Notifications\NursingReportSubmitted::class)->count() }} unread</span></div><div class="card-body">
          @forelse($notifications as $notification)
            @php($data = $notification->data)
            <article class="border rounded p-3 mb-3 {{ $notification->read_at ? 'bg-light' : 'border-primary' }}">
              <div class="d-flex justify-content-between gap-3"><div><h6 class="mb-1">{{ $data['title'] ?? 'New nursing report' }} @if(!$notification->read_at)<span class="badge bg-warning text-dark ms-1">New</span>@endif</h6><p class="mb-1">{{ $data['message'] ?? '' }}</p><small class="text-muted">{{ $notification->created_at->format('M j, Y g:i A') }}</small></div><div class="d-flex flex-column gap-2"><a href="{{ $data['url'] ?? route('admin.nursing.reports') }}" class="btn btn-sm btn-primary">Review report</a>@if(!$notification->read_at)<form method="POST" action="{{ route('admin.nursing.notifications.read', $notification->id) }}">@csrf<button class="btn btn-sm btn-outline-primary">Mark as read</button></form>@endif</div></div>
            </article>
          @empty
            <p class="text-muted mb-0">There are no nursing report notifications yet.</p>
          @endforelse
          {{ $notifications->links() }}
        </div></div>
      </div>
    </div>
  </div>
</div>
@include('parklane.script')
