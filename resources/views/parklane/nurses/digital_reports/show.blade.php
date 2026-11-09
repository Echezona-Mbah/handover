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
            <p><strong>Sent to:</strong>
              @if($report->recipient_role === 'hns_and_unit_head') HNS Office and assigned unit head
              @elseif($report->recipient_role === 'unit_head') Assigned unit head
              @else Historical recipient @endif
            </p>
            <p><strong>Patient:</strong> {{ $report->patient->full_name ?? 'Ward or general report' }}</p>
            @if($report->patient_id)
              <h6>Patient-specific Nursing Notes</h6><p>{{ $report->incidents }}</p>
            @else
              <h6>{{ $report->report_type === 'incident' ? 'Incident Details' : 'Original Summary' }}</h6><p>{{ $report->summary }}</p>
              @if($report->incidents)<h6>Incident Involvement</h6><p>{{ $report->incidents }}</p>@endif
              @if($report->referrals)<h6>Referrals or Follow-up</h6><p>{{ $report->referrals }}</p>@endif
              @if($report->administrative_notes)<h6>Administrative Notes</h6><p>{{ $report->administrative_notes }}</p>@endif
            @endif
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
