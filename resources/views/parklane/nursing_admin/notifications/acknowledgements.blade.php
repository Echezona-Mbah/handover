@include('parklane.head')

<div class="page-wrapper">
  @include('parklane.header')
  <div class="main-container">
    @include('parklane.sidebar')

    <div class="app-container">
      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item text-primary" aria-current="page">Announcement acknowledgements</li>
        </ol>
      </div>

      <div class="app-body">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Important announcements</h5>
            <a href="{{ route('admin.nurse-notifications.create') }}" class="btn btn-primary btn-sm">Send announcement</a>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table align-middle">
                <thead>
                  <tr>
                    <th>Announcement</th>
                    <th>Nurse</th>
                    <th>Sent</th>
                    <th>Read</th>
                    <th>Acknowledged</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($announcements as $announcement)
                    <tr>
                      <td>
                        <strong>{{ $announcement->data['title'] ?? 'Important announcement' }}</strong>
                        <div class="small text-muted">{{ $announcement->data['message'] ?? '' }}</div>
                      </td>
                      <td>{{ $announcement->full_name }}<div class="small text-muted">{{ $announcement->email }}</div></td>
                      <td>{{ \Illuminate\Support\Carbon::parse($announcement->created_at)->format('M j, Y g:i A') }}</td>
                      <td>{{ $announcement->read_at ? \Illuminate\Support\Carbon::parse($announcement->read_at)->format('M j, Y g:i A') : 'Not read' }}</td>
                      <td>
                        @if ($announcement->acknowledged_at)
                          <span class="badge bg-success">Acknowledged</span>
                          <div class="small text-muted">{{ \Illuminate\Support\Carbon::parse($announcement->acknowledged_at)->format('M j, Y g:i A') }}</div>
                        @else
                          <span class="badge bg-warning text-dark">Awaiting acknowledgement</span>
                        @endif
                      </td>
                    </tr>
                  @empty
                    <tr><td colspan="5" class="text-center text-muted">No important announcements have been sent yet.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
            {{ $announcements->links('pagination::bootstrap-5') }}
          </div>
        </div>
      </div>

      @include('parklane.script')
    </div>
  </div>
</div>
