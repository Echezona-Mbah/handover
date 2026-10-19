@include('parklane.nurses.head')
<div class="page-wrapper">
  @include('parklane.nurses.header')
  <div class="main-container">
    @include('parklane.nurses.sidebar')
    <div class="app-container">
      <div class="app-hero-header"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('nurse.rosters.index') }}">Rosters</a></li><li class="breadcrumb-item text-primary">Details</li></ol></div>
      <div class="app-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        <div class="card">
          <div class="card-header d-flex justify-content-between">
            <h5 class="card-title">{{ $roster->title }}</h5>
            <span class="badge bg-primary">{{ $roster->status }}</span>
          </div>
          <div class="card-body table-responsive">
            <p><strong>Period:</strong> {{ $roster->start_date?->format('d M Y') }} - {{ $roster->end_date?->format('d M Y') }}</p>
            <table class="table table-bordered">
              <thead><tr><th>Date</th><th>Nurse</th><th>Shift</th><th>Time</th><th>Notes</th></tr></thead>
              <tbody>
                @foreach($roster->assignments as $assignment)
                  <tr>
                    <td>{{ $assignment->duty_date?->format('d M Y') }}</td>
                    <td>{{ $assignment->nurse->full_name ?? 'N/A' }}</td>
                    <td>{{ $assignment->shift_type }}</td>
                    <td>{{ $assignment->start_time }} - {{ $assignment->end_time }}</td>
                    <td>{{ $assignment->notes }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            @if($roster->status === 'draft')
              <form method="POST" action="{{ route('nurse.rosters.submit', $roster) }}" class="text-end">
                @csrf
                <button class="btn btn-primary">Submit for Approval</button>
              </form>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@include('parklane.nurses.script')

