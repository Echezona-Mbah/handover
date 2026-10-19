@include('parklane.head')
<div class="page-wrapper">@include('parklane.header')<div class="main-container">@include('parklane.sidebar')<div class="app-container">
  <div class="app-hero-header"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('admin.nursing.reports') }}">Reports</a></li><li class="breadcrumb-item text-primary">Review</li></ol></div>
  <div class="app-body">
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    <div class="row gx-3">
      <div class="col-lg-8"><div class="card mb-3"><div class="card-header"><h5 class="card-title">Report from {{ $report->nurse->full_name ?? 'Nurse' }}</h5></div><div class="card-body">
        <p><strong>Date:</strong> {{ $report->report_date?->format('d M Y') }} | <strong>Ward:</strong> {{ $report->ward->name ?? 'N/A' }} | <strong>Status:</strong> {{ $report->status }}</p>
        <h6>Summary</h6><p>{{ $report->summary }}</p>
        <h6>Incidents</h6><p>{{ $report->incidents ?? 'None' }}</p>
        <h6>Referrals</h6><p>{{ $report->referrals ?? 'None' }}</p>
        <h6>Administrative Notes</h6><p>{{ $report->administrative_notes ?? 'None' }}</p>
      </div></div></div>
      <div class="col-lg-4"><form method="POST" action="{{ route('admin.nursing.reports.review', $report) }}" class="card mb-3">@csrf
        <div class="card-header"><h5 class="card-title">Supervisor Action</h5></div>
        <div class="card-body">
          <select name="action" class="form-select mb-3" required><option value="comment">Comment</option><option value="request_clarification">Request Clarification</option><option value="reviewed">Mark Reviewed</option><option value="approved">Approve</option></select>
          <textarea name="comment" rows="5" class="form-control" placeholder="Review comment" required></textarea>
        </div>
        <div class="card-footer text-end"><button class="btn btn-primary">Save Review</button></div>
      </form></div>
    </div>
    <div class="card"><div class="card-header"><h5 class="card-title">Audit Trail</h5></div><div class="card-body">
      @forelse($report->reviews as $review)<div class="border-bottom pb-2 mb-2"><strong>{{ $review->action }}</strong> by {{ $review->reviewer->name ?? 'Supervisor' }}<p>{{ $review->comment }}</p></div>@empty<p>No review trail yet.</p>@endforelse
    </div></div>
  </div>
</div></div></div>
@include('parklane.script')

