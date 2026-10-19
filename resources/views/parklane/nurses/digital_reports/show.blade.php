@include('parklane.nurses.head')
<div class="page-wrapper">
  @include('parklane.nurses.header')
  <div class="main-container">
    @include('parklane.nurses.sidebar')
    <div class="app-container">
      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('nurse.reports.index') }}">Reports</a></li>
          <li class="breadcrumb-item text-primary">Report Details</li>
        </ol>
      </div>
      <div class="app-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        <div class="card mb-3">
          <div class="card-header d-flex justify-content-between">
            <h5 class="card-title">Nursing Report</h5>
            <span class="badge bg-primary">{{ str_replace('_', ' ', $report->status) }}</span>
          </div>
          <div class="card-body">
            <p><strong>Date:</strong> {{ $report->report_date?->format('d M Y') }} | <strong>Shift:</strong> {{ $report->shift ?? 'N/A' }}</p>
            <p><strong>Patient:</strong> {{ $report->patient->full_name ?? 'Ward or general report' }}</p>
            <h6>Original Summary</h6><p>{{ $report->summary }}</p>
            <h6>Incidents</h6><p>{{ $report->incidents ?? 'None recorded' }}</p>
            <h6>Referrals</h6><p>{{ $report->referrals ?? 'None recorded' }}</p>
            <h6>Administrative Notes</h6><p>{{ $report->administrative_notes ?? 'None recorded' }}</p>
          </div>
        </div>
        <div class="card">
          <div class="card-header"><h5 class="card-title">Supervisory Review Trail</h5></div>
          <div class="card-body">
            @forelse($report->reviews as $review)
              <div class="border-bottom pb-2 mb-2">
                <strong>{{ ucwords(str_replace('_', ' ', $review->action)) }}</strong>
                <span class="text-muted">by {{ $review->reviewer->name ?? 'Supervisor' }} on {{ $review->created_at->format('d M Y g:i A') }}</span>
                <p class="mb-0">{{ $review->comment }}</p>
              </div>
            @empty
              <p>No supervisory comments yet.</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@include('parklane.nurses.script')

