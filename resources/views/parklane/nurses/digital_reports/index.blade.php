@include('parklane.nurses.head')
<div class="page-wrapper">
  @include('parklane.nurses.header')
  <div class="main-container">
    @include('parklane.nurses.sidebar')
    <div class="app-container">
      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('nurse.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item text-primary">Nursing Reports</li>
        </ol>
        <a href="{{ route('nurse.reports.create') }}" class="btn btn-primary ms-auto">New Report</a>
      </div>
      <div class="app-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-warning">{{ session('error') }}</div> @endif
        <div class="card">
          <div class="card-header"><h5 class="card-title">Electronic Nursing Reports</h5></div>
          <div class="card-body table-responsive">
            <table class="table table-striped align-middle">
              <thead><tr><th>Date</th><th>Type</th><th>Patient</th><th>Ward</th><th>Status</th><th></th></tr></thead>
              <tbody>
                @forelse($reports as $report)
                  <tr>
                    <td>{{ $report->report_date?->format('d M Y') }}</td>
                    <td>{{ ucwords(str_replace('_', ' ', $report->report_type)) }}</td>
                    <td>{{ $report->patient->full_name ?? 'Ward report' }}</td>
                    <td>{{ $report->ward->name ?? 'N/A' }}</td>
                    <td><span class="badge bg-primary">{{ str_replace('_', ' ', $report->status) }}</span></td>
                    <td><a class="btn btn-sm btn-outline-primary" href="{{ route('nurse.reports.show', $report) }}">View</a></td>
                  </tr>
                @empty
                  <tr><td colspan="6" class="text-center">No reports submitted yet.</td></tr>
                @endforelse
              </tbody>
            </table>
            {{ $reports->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@include('parklane.nurses.script')
