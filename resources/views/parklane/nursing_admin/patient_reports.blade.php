@include('parklane.head')
<div class="page-wrapper">
  @include('parklane.header')
  <div class="main-container">
    @include('parklane.sidebar')
    <div class="app-container">
      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('admin.nursing.patients') }}">Ward Patients</a></li><li class="breadcrumb-item text-primary">{{ $patient->full_name }}</li></ol>
        <a href="{{ route('admin.nursing.patients') }}" class="btn btn-light ms-auto">Back to patients</a>
      </div>
      <div class="app-body">
        <div class="card mb-3">
          <div class="card-body">
            <h4>{{ $patient->full_name }}</h4>
            <p class="text-muted">Patient details available to your nursing role</p>
            <div class="row g-3">
              <div class="col-md-4"><strong>Ward:</strong> {{ $patient->ward?->name ?? '—' }}</div>
              <div class="col-md-4"><strong>Patient number:</strong> {{ $patient->pt_number ?? $patient->patient_number ?? '—' }}</div>
              <div class="col-md-4"><strong>Status:</strong> {{ $patient->status ?? 'Active' }}</div>
              <div class="col-md-4"><strong>Date of birth:</strong> {{ $patient->dob ?? '—' }}</div>
              <div class="col-md-4"><strong>Gender:</strong> {{ ucfirst($patient->gender ?? '—') }}</div>
              <div class="col-md-4"><strong>Phone:</strong> {{ $patient->mobile_number ?? '—' }}</div>
              <div class="col-md-4"><strong>Email:</strong> {{ $patient->email ?? '—' }}</div>
              <div class="col-md-4"><strong>Blood group:</strong> {{ $patient->blood_group ?? '—' }}</div>
              <div class="col-md-4"><strong>Blood pressure:</strong> {{ $patient->blood_pressure ?? '—' }}</div>
              <div class="col-md-4"><strong>Urgency:</strong> {{ ucfirst($patient->urgency_status ?? 'routine') }}</div>
              <div class="col-md-4"><strong>Marital status:</strong> {{ $patient->marital_status ?? '—' }}</div>
              <div class="col-md-4"><strong>Occupation:</strong> {{ $patient->occupation ?? '—' }}</div>
              <div class="col-md-4"><strong>Doctor:</strong> {{ $patient->doctorById?->full_name ?? 'Not assigned' }}</div>
              <div class="col-md-4"><strong>Doctor unit:</strong> {{ $patient->doctorUnit?->name ?? '—' }}</div>
              <div class="col-md-4"><strong>Doctor sub-unit:</strong> {{ $patient->doctorSubUnit?->name ?? '—' }}</div>
              <div class="col-12"><strong>Current condition:</strong><p class="mb-0">{{ $patient->current_condition ?: '—' }}</p></div>
              <div class="col-md-6"><strong>Allergies:</strong><p class="mb-0">{{ $patient->allergies ?: 'None recorded' }}</p></div>
              <div class="col-md-6"><strong>Current medications:</strong><p class="mb-0">{{ $patient->current_medications ?: 'None recorded' }}</p></div>
              <div class="col-12"><strong>Address:</strong><p class="mb-0">{{ $patient->address ?: '—' }}{{ $patient->city ? ', '.$patient->city : '' }}{{ $patient->state ? ', '.$patient->state : '' }}</p></div>
            </div>
          </div>
        </div>
        <div class="row g-3 mb-4">
          <div class="col-lg-6"><div class="card h-100"><div class="card-header"><h5 class="card-title mb-0">Medication records ({{ $medications->count() }})</h5></div><div class="card-body">
            @forelse($medications as $medication)
              <div class="border-bottom pb-2 mb-2"><strong>{{ $medication->medication }}</strong><br><span>{{ $medication->dosage }} · {{ $medication->frequency }}</span><br><small class="text-muted">{{ $medication->instructions }}</small></div>
            @empty<p class="text-muted mb-0">No medication records.</p>@endforelse
          </div></div></div>
          <div class="col-lg-6"><div class="card h-100"><div class="card-header"><h5 class="card-title mb-0">Nursing handovers ({{ $handoverNotes->count() }})</h5></div><div class="card-body">
            @forelse($handoverNotes as $handover)
              <div class="border-bottom pb-2 mb-2"><strong>{{ $handover->created_at?->format('d M Y, g:i A') }}</strong><p class="mb-1">{{ $handover->summary }}</p><small><strong>Alerts:</strong> {{ $handover->alerts ?: 'None' }} · <strong>Tasks:</strong> {{ $handover->pending_tasks ?: 'None' }}</small></div>
            @empty<p class="text-muted mb-0">No handover records.</p>@endforelse
          </div></div></div>
        </div>
        <div class="card mb-4">
          <div class="card-header"><h5 class="card-title mb-0">Nursing process history ({{ $nursingProcessRecords->count() }})</h5></div>
          <div class="card-body">
            @forelse($nursingProcessRecords as $process)
              <article class="border-bottom pb-3 mb-3">
                <div class="d-flex justify-content-between gap-2"><strong>{{ $process->created_at->format('d M Y, g:i A') }}</strong><small class="text-muted">By {{ $process->nurse?->full_name ?? 'Nursing staff' }}</small></div>
                @foreach(['assessment' => 'Assessment', 'diagnosis' => 'Nursing diagnosis', 'planning' => 'Outcomes and planning', 'implementation' => 'Implementation', 'evaluation' => 'Evaluation'] as $field => $label)
                  <p class="mb-1 mt-2"><strong>{{ $label }}:</strong> <span class="text-break">{{ $process->$field }}</span></p>
                @endforeach
              </article>
            @empty<p class="text-muted mb-0">No nursing-process records have been saved for this patient.</p>@endforelse
          </div>
        </div>
        <h5 class="mb-3">All nursing reports for this patient ({{ $reports->count() }})</h5>
        @forelse($reports as $report)
          <div class="card mb-3">
            <div class="card-header d-flex flex-wrap justify-content-between gap-2">
              <strong>{{ ucwords(str_replace('_', ' ', $report->report_type)) }}</strong>
              <span>{{ $report->report_date?->format('d M Y') }}{{ $report->shift ? ' · '.$report->shift.' shift' : '' }} · {{ str_replace('_', ' ', $report->status) }}</span>
            </div>
            <div class="card-body">
              <p class="text-muted mb-2">Submitted by {{ $report->nurse?->full_name ?? 'Nurse' }}{{ $report->submitted_at ? ' on '.$report->submitted_at->format('d M Y, g:i A') : '' }}</p>
              <h6>Summary</h6><p class="text-break">{{ $report->summary }}</p>
              @if($report->incidents)<h6>Incidents</h6><p class="text-break">{{ $report->incidents }}</p>@endif
              @if($report->referrals)<h6>Referrals / follow-up</h6><p class="text-break">{{ $report->referrals }}</p>@endif
              @if($report->administrative_notes)<h6>Administrative notes</h6><p class="text-break">{{ $report->administrative_notes }}</p>@endif
              @if($report->reviews->isNotEmpty())
                <hr><h6>Review history</h6>
                @foreach($report->reviews as $review)
                  <p class="mb-2"><strong>{{ str_replace('_', ' ', $review->action) }}</strong> — {{ $review->reviewer?->name ?? 'Reviewer' }}<br>{{ $review->comment }}</p>
                @endforeach
              @endif
            </div>
          </div>
        @empty
          <div class="card"><div class="card-body text-center text-muted">No nursing reports have been submitted for this patient yet.</div></div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@include('parklane.script')
