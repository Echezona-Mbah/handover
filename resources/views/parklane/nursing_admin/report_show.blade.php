@include('parklane.head')
<div class="page-wrapper">@include('parklane.header')<div class="main-container">@include('parklane.sidebar')<div class="app-container">
  <div class="app-hero-header"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('admin.nursing.reports') }}">Reports</a></li><li class="breadcrumb-item text-primary">Review</li></ol></div>
  <div class="app-body">
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    <div class="row gx-3">
      <div class="col-lg-8"><div class="card mb-3"><div class="card-header"><h5 class="card-title">Report from {{ $report->nurse->full_name ?? 'Nurse' }}</h5></div><div class="card-body">
        <p><strong>Date:</strong> {{ $report->report_date?->format('d M Y') }} | <strong>Ward:</strong> {{ $report->ward->name ?? 'N/A' }} | <strong>Status:</strong> {{ $report->status }}</p>
        <p><strong>Recipients:</strong>
          @if($report->recipient_role === 'hns_and_unit_head') HNS Office and assigned unit head
          @elseif($report->recipient_role === 'hns') HNS Office
          @elseif($report->recipient_role === 'unit_head') Assigned unit head
          @else Historical recipient @endif
        </p>
        @if($report->patient_id)
          <h6>Patient-specific Nursing Notes</h6><p>{{ $report->incidents }}</p>
        @else
          <h6>{{ $report->report_type === 'incident' ? 'Incident Details' : 'Summary' }}</h6><p>{{ $report->summary }}</p>
          @if($report->incidents)<h6>Incident Involvement</h6><p>{{ $report->incidents }}</p>@endif
          @if($report->referrals)<h6>Referrals or Follow-up</h6><p>{{ $report->referrals }}</p>@endif
          @if($report->administrative_notes)<h6>Administrative Notes</h6><p>{{ $report->administrative_notes }}</p>@endif
        @endif
      </div></div></div>
      <div class="col-lg-4"><form method="POST" action="{{ route('admin.nursing.reports.review', $report) }}" class="card mb-3">@csrf
        <div class="card-header"><h5 class="card-title">Supervisor Action</h5></div>
        <div class="card-body">
          <select name="action" class="form-select mb-3" required><option value="comment">Comment</option><option value="request_clarification">Send back to ward for clarification</option><option value="reviewed">Mark Reviewed</option></select>
          <textarea name="comment" rows="5" class="form-control" placeholder="Optional for accepting or declining; required for other review actions."></textarea>
        </div>
        <div class="card-footer d-flex justify-content-end gap-2">
          <button type="submit" name="decision" value="declined" class="btn btn-outline-danger">Decline</button>
          <button type="submit" name="decision" value="approved" class="btn btn-success">Accept</button>
          <button type="submit" class="btn btn-primary">Save Review</button>
        </div>
      </form>
      @if(\App\Services\NursingHierarchy::isControl(auth()->user()))
      <form method="POST" action="{{ route('admin.nursing.reports.escalate', $report) }}" class="card">@csrf<div class="card-header"><h5 class="card-title">Escalate to HNS</h5></div><div class="card-body"><textarea name="reason" class="form-control" rows="3" required placeholder="Reason for escalation"></textarea></div><div class="card-footer text-end"><button class="btn btn-warning">Escalate</button></div></form>
      @endif</div>
    </div>
    <div class="card"><div class="card-header"><h5 class="card-title">Audit Trail</h5></div><div class="card-body">
      @forelse($report->reviews as $review)<div class="border-bottom pb-2 mb-2"><strong>{{ $review->action }}</strong> by {{ $review->reviewer->name ?? 'Supervisor' }}<p>{{ $review->comment }}</p></div>@empty<p>No review trail yet.</p>@endforelse
    </div></div>
  </div>
</div></div></div>
@include('parklane.script')
