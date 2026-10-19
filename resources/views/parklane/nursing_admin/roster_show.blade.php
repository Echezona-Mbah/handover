@include('parklane.head')
<div class="page-wrapper">@include('parklane.header')<div class="main-container">@include('parklane.sidebar')<div class="app-container">
  <div class="app-hero-header"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('admin.nursing.rosters') }}">Rosters</a></li><li class="breadcrumb-item text-primary">Review</li></ol></div>
  <div class="app-body">
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    <div class="card"><div class="card-header d-flex justify-content-between"><h5 class="card-title">{{ $roster->title }}</h5><span class="badge bg-primary">{{ $roster->status }}</span></div><div class="card-body table-responsive">
      <p><strong>Ward:</strong> {{ $roster->ward->name ?? 'N/A' }} | <strong>Created by:</strong> {{ $roster->creator->full_name ?? 'N/A' }}</p>
      <table class="table table-bordered"><thead><tr><th>Date</th><th>Nurse</th><th>Shift</th><th>Time</th><th>Notes</th></tr></thead><tbody>
        @foreach($roster->assignments as $assignment)<tr><td>{{ $assignment->duty_date?->format('d M Y') }}</td><td>{{ $assignment->nurse->full_name ?? 'N/A' }}</td><td>{{ $assignment->shift_type }}</td><td>{{ $assignment->start_time }} - {{ $assignment->end_time }}</td><td>{{ $assignment->notes }}</td></tr>@endforeach
      </tbody></table>
      <form method="POST" action="{{ route('admin.nursing.rosters.approve', $roster) }}" class="text-end">@csrf
        <select name="status" class="form-select d-inline-block w-auto"><option value="approved">Approve</option><option value="published">Publish</option></select>
        <button class="btn btn-primary">Update Roster</button>
      </form>
    </div></div>
  </div>
</div></div></div>
@include('parklane.script')

