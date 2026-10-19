@include('parklane.nurses.head')
<div class="page-wrapper">
  @include('parklane.nurses.header')
  <div class="main-container">
    @include('parklane.nurses.sidebar')
    <div class="app-container">
      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('nurse.dashboard') }}">Home</a></li><li class="breadcrumb-item text-primary">Duty Rosters</li></ol>
        <a href="{{ route('nurse.rosters.create') }}" class="btn btn-primary ms-auto">New Roster</a>
      </div>
      <div class="app-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-warning">{{ session('error') }}</div> @endif
        <div class="card"><div class="card-body table-responsive">
          <table class="table table-striped">
            <thead><tr><th>Title</th><th>Period</th><th>Status</th><th></th></tr></thead>
            <tbody>
              @forelse($rosters as $roster)
                <tr>
                  <td>{{ $roster->title }}</td>
                  <td>{{ $roster->start_date?->format('d M Y') }} - {{ $roster->end_date?->format('d M Y') }}</td>
                  <td><span class="badge bg-primary">{{ $roster->status }}</span></td>
                  <td><a class="btn btn-sm btn-outline-primary" href="{{ route('nurse.rosters.show', $roster) }}">View</a></td>
                </tr>
              @empty
                <tr><td colspan="4" class="text-center">No rosters created yet.</td></tr>
              @endforelse
            </tbody>
          </table>
          {{ $rosters->links() }}
        </div></div>
      </div>
    </div>
  </div>
</div>
@include('parklane.nurses.script')
