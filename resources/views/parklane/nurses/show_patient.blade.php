@include('parklane.nurses.head')
<style>
  .patient-page{--ink:#16324f;--muted:#718096;--mint:#e9f8f3}.patient-hero{background:linear-gradient(125deg,#0e5967,#1e8a82 58%,#71c9b9);border-radius:24px;color:#fff;overflow:hidden;position:relative}.patient-hero:after{content:'';position:absolute;width:280px;height:280px;border:48px solid #ffffff1a;border-radius:50%;right:-110px;top:-150px}.patient-hero .card-body{position:relative;z-index:1;padding:2rem}.patient-avatar{width:76px;height:76px;display:grid;place-items:center;border:1px solid #ffffff4d;border-radius:22px;background:#ffffff2e;font-size:2.5rem}.patient-chip{padding:.48rem .75rem;border:1px solid #ffffff33;border-radius:99px;background:#ffffff24;font-size:.82rem}.patient-chip i{margin-right:.35rem}.urgency-chip{font-weight:800;letter-spacing:.04em}.urgency-routine{background:#198754;color:#fff}.urgency-urgent{background:#ffc107;color:#212529}.urgency-emergency{background:#dc3545;color:#fff}.emergency-patient-alert{border:2px solid #dc3545;border-radius:16px;background:#fff0f0;color:#842029}.emergency-patient-alert h5{color:#b02a37;font-weight:800}.patient-kpi{height:100%;padding:1rem;border:1px solid #edf0f5;border-radius:18px;background:#fff;box-shadow:0 8px 22px #1e3a5f0d}.kpi-icon{width:38px;height:38px;display:grid;place-items:center;border-radius:12px;background:var(--mint);color:#148271;font-size:1.15rem}.kpi-label{margin-top:.7rem;color:var(--muted);font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em}.kpi-value{color:var(--ink);font-weight:700;overflow-wrap:anywhere}.clinical-card{overflow:hidden;border:1px solid #edf0f5;border-radius:20px;box-shadow:0 8px 25px #1e3a5f0d}.clinical-card .card-header{padding:1.1rem 1.25rem;background:#fff;border-bottom:1px solid #edf0f5}.clinical-card .card-title{margin:0;color:var(--ink);font-weight:750}.clinical-card .card-title i{margin-right:.5rem;color:#1e8a82}.clinical-table th{border-top:0;color:#8391a1;font-size:.72rem;text-transform:uppercase;letter-spacing:.06em;white-space:nowrap}.clinical-table td{padding:.9rem .75rem;color:#31465d;vertical-align:middle}.note-preview{max-width:220px;overflow:hidden;color:#607080;text-overflow:ellipsis;white-space:nowrap}.empty-state{padding:2.75rem 1rem;color:var(--muted);text-align:center}.empty-state i{display:block;margin-bottom:.75rem;color:#b9c6d1;font-size:2rem}.modal-section{height:100%;padding:1rem;border-radius:14px;background:#f7fafb}.modal-section small{display:block;margin-bottom:.3rem;color:var(--muted);font-weight:700;text-transform:uppercase;letter-spacing:.05em}.modal-section p{margin:0;color:var(--ink);white-space:pre-line}@media(max-width:575px){.patient-hero .card-body{padding:1.35rem}.patient-avatar{width:58px;height:58px;font-size:2rem}}
</style>
<div class="page-wrapper patient-page">
  @include('parklane.nurses.header')
  <div class="main-container">@include('parklane.nurses.sidebar')<div class="app-container">
    <div class="app-hero-header d-flex align-items-center"><ol class="breadcrumb mb-0"><li class="breadcrumb-item"><i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i><a href="{{ route('nurse.dashboard') }}">Home</a></li><li class="breadcrumb-item"><a href="{{ route('nurse.patient') }}">Patients</a></li><li class="breadcrumb-item text-primary">Patient profile</li></ol><a href="{{ route('nurse.patient') }}" class="btn btn-light ms-auto"><i class="ri-arrow-left-line me-1"></i>All patients</a></div>
    <div class="app-body">
      @if(session('success'))<div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>@endif
      @if(session('error'))<div class="alert alert-warning border-0 shadow-sm">{{ session('error') }}</div>@endif
      @if($errors->any())<div class="alert alert-danger border-0 shadow-sm">{{ $errors->first() }}</div>@endif
      @php($urgency = strtolower($patient->urgency_status ?? 'routine'))
      @if($urgency === 'emergency')
        <div class="emergency-patient-alert p-3 p-md-4 mb-4" role="alert">
          <div class="d-flex align-items-start gap-3">
            <i class="ri-error-warning-fill fs-2 text-danger" aria-hidden="true"></i>
            <div>
              <h5 class="mb-1">Emergency patient alert</h5>
              <p class="mb-0">This patient is marked as an emergency. Follow your hospital's emergency response procedures.</p>
            </div>
          </div>
        </div>
      @endif
      <section class="card patient-hero mb-4 border-0"><div class="card-body"><div class="d-flex flex-column flex-md-row align-items-md-center gap-3"><div class="patient-avatar"><i class="ri-user-heart-line"></i></div><div class="flex-grow-1"><p class="text-white-50 text-uppercase small fw-bold mb-1" style="letter-spacing:.1em">Patient profile</p><h2 class="mb-1 text-white">{{ $patient->full_name }}</h2><div class="d-flex flex-wrap gap-2 mt-3"><span class="patient-chip"><i class="ri-hospital-line"></i>{{ $patient->ward?->name ?? 'Ward not assigned' }}</span><span class="patient-chip"><i class="ri-fingerprint-line"></i>{{ $patient->pt_number ?? $patient->patient_number ?? 'Patient #'.$patient->id }}</span><span class="patient-chip"><i class="ri-pulse-line"></i>{{ $patient->status ?? 'Active' }}</span><span class="patient-chip urgency-chip urgency-{{ in_array($urgency, ['urgent', 'emergency'], true) ? $urgency : 'routine' }}"><i class="ri-alert-line"></i>Urgency: {{ ucfirst($urgency) }}</span></div></div><div class="d-flex flex-wrap gap-2"><a href="{{ route('nurse.patients.edit', $patient) }}" class="btn btn-light text-primary fw-semibold px-3"><i class="ri-edit-line me-1"></i>Edit patient</a><a href="{{ route('nurse.nursing-process.create', $patient) }}" class="btn btn-light text-primary fw-semibold px-3"><i class="ri-add-line me-1"></i>Nursing process</a><a href="{{ route('nurse.patients.plan',$patient->id) }}" class="btn btn-light text-primary fw-semibold px-3"><i class="ri-capsule-line me-1"></i>Add medication</a><button type="button" class="btn btn-warning fw-semibold px-3" data-bs-toggle="modal" data-bs-target="#patient-disposition"><i class="ri-logout-box-line me-1"></i>Discharge / transfer</button></div></div></div></section>
      <div class="modal fade" id="patient-disposition" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">
          <form method="POST" action="{{ route('nurse.patients.discharge', $patient) }}">
            @csrf
            <div class="modal-header"><h5 class="modal-title">Discharge or transfer {{ $patient->full_name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
              <p class="text-muted">A home discharge removes the patient from ward nursing lists while keeping the record available to nursing administrators. A ward transfer only takes effect after the receiving ward supervisor accepts.</p>
              <div class="mb-3"><label class="form-label" for="patient-disposition">Action</label><select class="form-select" name="disposition" id="patient-disposition" required><option value="home">Discharge home</option><option value="transfer" @disabled($transferWards->isEmpty())>Request transfer to another ward</option></select></div>
              <div class="mb-3" id="destination-ward-field" hidden><label class="form-label" for="destination-ward">Receiving ward</label><select class="form-select" name="destination_ward_id" id="destination-ward"><option value="">Select a ward</option>@foreach($transferWards as $transferWard)<option value="{{ $transferWard->id }}">{{ $transferWard->name }}</option>@endforeach</select><small class="text-muted">Only wards with an assigned supervisor are listed.</small></div>
              <div class="mb-3"><label class="form-label" for="discharge-notes">Discharge or transfer notes</label><textarea class="form-control" name="discharge_notes" id="discharge-notes" rows="3" maxlength="5000"></textarea></div>
              @if($transferWards->isEmpty())<div class="alert alert-warning mb-0">No other ward currently has an assigned supervisor, so a ward transfer cannot be requested.</div>@endif
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Submit</button></div>
          </form>
        </div></div>
      </div>
      <script>
        document.addEventListener('DOMContentLoaded', () => {
          const disposition = document.getElementById('patient-disposition');
          const destinationField = document.getElementById('destination-ward-field');
          const destinationWard = document.getElementById('destination-ward');
          const updateDispositionFields = () => {
            const isTransfer = disposition.value === 'transfer';
            destinationField.hidden = !isTransfer;
            destinationWard.required = isTransfer;
            destinationWard.disabled = !isTransfer;
          };
          disposition.addEventListener('change', updateDispositionFields);
          updateDispositionFields();
        });
      </script>
      <section class="row g-3 mb-4">
        @php($details=[['ri-men-line','Gender',ucfirst($patient->gender ?? 'Not recorded')],['ri-calendar-line','Age',$patient->dob ? \Carbon\Carbon::parse($patient->dob)->age.' years' : 'Not recorded'],['ri-contrast-drop-2-line','Blood group',$patient->blood_group ?? 'Not recorded'],['ri-heart-pulse-line','Blood pressure',$patient->blood_pressure ?? 'Not recorded'],['ri-medicine-bottle-line','Allergies',$patient->allergies ?? 'None recorded'],['ri-stethoscope-line','Consultant',$patient->doctorById?->full_name ?? 'Not assigned']])
        @foreach($details as [$icon,$label,$value])<div class="col-6 col-md-4 col-xl-2"><div class="patient-kpi"><div class="kpi-icon"><i class="{{ $icon }}"></i></div><div class="kpi-label">{{ $label }}</div><div class="kpi-value">{{ $value }}</div></div></div>@endforeach
      </section>
      <section class="card clinical-card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between"><h5 class="card-title"><i class="ri-heart-pulse-line"></i>Nursing process history</h5><a href="{{ route('nurse.nursing-process.create', $patient) }}" class="btn btn-sm btn-primary"><i class="ri-add-line"></i> Add record</a></div>
        <div class="card-body">
          @forelse($nursingProcessRecords as $process)
            <article class="border-bottom pb-3 mb-3">
              <div class="d-flex justify-content-between gap-2"><strong>{{ $process->created_at->format('d M Y, g:i A') }}</strong><small class="text-muted">By {{ $process->nurse?->full_name ?? 'Nursing staff' }}</small></div>
              @foreach(['assessment' => 'Assessment', 'diagnosis' => 'Nursing diagnosis', 'planning' => 'Outcomes and planning', 'implementation' => 'Implementation', 'evaluation' => 'Evaluation'] as $field => $label)
                <p class="mb-1 mt-2"><strong>{{ $label }}:</strong> <span class="text-break">{{ $process->$field }}</span></p>
              @endforeach
            </article>
          @empty
            <div class="empty-state"><i class="ri-heart-pulse-line"></i>No nursing-process records have been saved for this patient.</div>
          @endforelse
        </div>
      </section>
      <div class="row g-3">
        <div class="col-xl-6"><section class="card clinical-card h-100"><div class="card-header d-flex align-items-center justify-content-between"><h5 class="card-title"><i class="ri-file-transfer-line"></i>Nursing handovers</h5><span class="badge text-bg-light">{{ $handoverNotes->count() }}</span></div><div class="card-body p-0">@forelse($handoverNotes as $note)<div class="d-flex gap-3 p-3 border-bottom align-items-center"><div class="rounded-3 text-center px-2 py-1" style="background:#e9f8f3;color:#148271;min-width:52px"><strong class="d-block">{{ $note->created_at->format('d') }}</strong><small>{{ $note->created_at->format('M') }}</small></div><div class="flex-grow-1 overflow-hidden"><div class="fw-semibold">{{ $note->ward?->name ?? 'Ward handover' }}</div><div class="note-preview">{{ $note->summary }}</div></div><div class="btn-group btn-group-sm"><button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#handoverModal{{ $note->id }}"><i class="ri-eye-line"></i></button><a href="{{ route('nurse.handoveredit',$note->id) }}" class="btn btn-outline-secondary"><i class="ri-edit-line"></i></a></div></div>@empty<div class="empty-state"><i class="ri-file-list-3-line"></i>No handover notes have been added yet.</div>@endforelse</div></section></div>
        <div class="col-xl-6"><section class="card clinical-card h-100"><div class="card-header d-flex align-items-center justify-content-between"><h5 class="card-title"><i class="ri-capsule-line"></i>Medication plan</h5><a href="{{ route('nurse.patients.plan',$patient->id) }}" class="btn btn-sm btn-primary"><i class="ri-add-line"></i> Add</a></div><div class="card-body p-0"><div class="table-responsive"><table class="table clinical-table mb-0"><thead><tr><th>Medication</th><th>Dosage</th><th>Schedule</th><th class="text-end">Actions</th></tr></thead><tbody>@forelse($medications as $med)<tr><td><strong>{{ $med->medication }}</strong><br><small class="text-muted">{{ $med->created_at?->format('d M Y') }}</small></td><td>{{ $med->dosage }}</td><td>{{ $med->frequency }}</td><td class="text-end"><div class="btn-group btn-group-sm"><button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#medicationModal{{ $med->id }}"><i class="ri-eye-line"></i></button><a href="{{ route('nurse.edit_medication',$med->id) }}" class="btn btn-outline-secondary"><i class="ri-edit-line"></i></a><form action="{{ route('nursemedications.destroy',$med->id) }}" method="POST" onsubmit="return confirm('Remove this medication plan?')">@csrf @method('DELETE')<button class="btn btn-outline-danger"><i class="ri-delete-bin-line"></i></button></form></div></td></tr>@empty<tr><td colspan="4"><div class="empty-state"><i class="ri-capsule-line"></i>No medication plan has been added yet.</div></td></tr>@endforelse</tbody></table></div></div></section></div>
      </div>
      <section class="card clinical-card mt-4">
        <div class="card-header d-flex align-items-center justify-content-between"><h5 class="card-title"><i class="ri-file-list-3-line"></i>Patient reports in this ward</h5><span class="badge text-bg-light">{{ $nursingReports->count() }}</span></div>
        <div class="card-body">
          <form method="POST" action="{{ route('nurse.patient-reports.store', $patient) }}" class="rounded-3 border p-3 mb-4 bg-light">
            @csrf
            <h6 class="mb-3">Add a patient report</h6>
            <div class="row g-3">
              <div class="col-md-4"><label class="form-label">Report type</label><select name="report_type" class="form-select" required><option value="daily_shift">Daily patient report</option><option value="incident">Incident</option><option value="administrative">Administrative</option><option value="referral">Referral / follow-up</option></select></div>
              <div class="col-12"><label class="form-label">Report details</label><textarea name="summary" class="form-control" rows="4" maxlength="20000" required>{{ old('summary') }}</textarea></div>
              <div class="col-12 text-end"><button class="btn btn-primary">Save in ward</button></div>
            </div>
          </form>
          @forelse($nursingReports as $report)
            <article class="border-bottom pb-3 mb-3">
              <div class="d-flex flex-wrap justify-content-between gap-2"><strong>{{ ucwords(str_replace('_', ' ', $report->report_type)) }} · {{ $report->report_date?->format('d M Y') }}</strong><span class="badge {{ $report->status === 'draft' ? 'bg-warning text-dark' : 'bg-primary' }}">{{ $report->status === 'draft' ? 'In ward' : str_replace('_', ' ', $report->status) }}</span></div>
              <small class="text-muted">Last updated {{ $report->updated_at?->format('d M Y, g:i A') }} by {{ $report->nurse?->full_name ?? 'Nurse' }}{{ $report->shift ? ' · '.$report->shift.' shift' : '' }}</small>
              @if($report->status === 'draft')
                <form method="POST" action="{{ route('nurse.patient-reports.update', $report) }}" class="mt-3">
                  @csrf @method('PUT')
                  <div class="row g-2">
                    <div class="col-md-4"><label class="form-label">Report type</label><select name="report_type" class="form-select" required>@foreach(['daily_shift' => 'Daily patient report', 'incident' => 'Incident', 'administrative' => 'Administrative', 'referral' => 'Referral / follow-up'] as $type => $label)<option value="{{ $type }}" @selected($report->report_type === $type)>{{ $label }}</option>@endforeach</select></div>
                    <div class="col-12"><label class="form-label">Report details</label><textarea name="summary" class="form-control" rows="3" maxlength="20000" required>{{ $report->summary }}</textarea></div>
                    <div class="col-12 d-flex flex-wrap justify-content-end gap-2">
                      <button class="btn btn-outline-primary">Update ward report</button>
                    </div>
                  </div>
                </form>
                <form method="POST" action="{{ route('nurse.patient-reports.send', $report) }}" class="mt-2 d-flex flex-wrap align-items-end justify-content-end gap-2">
                  @csrf
                  <div>
                    <label class="form-label" for="recipient-target-{{ $report->id }}">Send to</label>
                    <select class="form-select" name="recipient_target" id="recipient-target-{{ $report->id }}" required>
                      @if($hasHns)<option value="hns">HNS Office only</option>@endif
                      @if($patient->ward?->unitHead)<option value="unit_head">Unit head only: {{ $patient->ward->unitHead->name }}</option>@endif
                      @if($hasHns && $patient->ward?->unitHead)<option value="both">Both HNS Office and unit head</option>@endif
                    </select>
                  </div>
                  <button class="btn btn-success">Send report</button>
                </form>
              @else
                <p class="mt-2 mb-2 text-break">{{ $report->summary }}</p>
              @endif
              @if($report->incidents)<p class="mb-1"><strong>Incidents:</strong> {{ $report->incidents }}</p>@endif
              @if($report->referrals)<p class="mb-1"><strong>Follow-up:</strong> {{ $report->referrals }}</p>@endif
              @if($report->administrative_notes)<p class="mb-0"><strong>Administrative notes:</strong> {{ $report->administrative_notes }}</p>@endif
            </article>
          @empty
            <div class="empty-state"><i class="ri-file-list-3-line"></i>No nursing reports have been submitted for this patient yet.</div>
          @endforelse
        </div>
      </section>
    </div><div class="app-footer bg-white"><span>© {{ now()->year }} Fandova</span></div>
  </div></div>
</div>
@foreach($handoverNotes as $note)<div class="modal fade" id="handoverModal{{ $note->id }}" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content border-0 shadow"><div class="modal-header border-0"><div><h5 class="modal-title">Nursing handover</h5><small class="text-muted">{{ $note->created_at->format('d M Y, h:i A') }} · {{ $note->ward?->name ?? 'Ward' }}</small></div><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body pt-0"><div class="row g-3"><div class="col-12"><div class="modal-section"><small>General summary</small><p>{{ $note->summary }}</p></div></div><div class="col-md-6"><div class="modal-section" style="background:#fff0f4"><small>Critical alerts</small><p>{{ $note->alerts ?: 'None recorded' }}</p></div></div><div class="col-md-6"><div class="modal-section"><small>Pending tasks</small><p>{{ $note->pending_tasks ?: 'None recorded' }}</p></div></div></div></div><div class="modal-footer border-0"><a href="{{ route('nurse.handoveredit',$note->id) }}" class="btn btn-primary">Edit handover</a></div></div></div></div>@endforeach
@foreach($medications as $med)<div class="modal fade" id="medicationModal{{ $med->id }}" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content border-0 shadow"><div class="modal-header border-0"><div><h5 class="modal-title">{{ $med->medication }}</h5><small class="text-muted">Medication plan</small></div><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body pt-0"><div class="row g-3"><div class="col-md-6"><div class="modal-section"><small>Dosage</small><p>{{ $med->dosage }}</p></div></div><div class="col-md-6"><div class="modal-section"><small>Frequency</small><p>{{ $med->frequency }}</p></div></div><div class="col-md-6"><div class="modal-section"><small>Start date</small><p>{{ $med->start_date ? \Carbon\Carbon::parse($med->start_date)->format('d M Y') : 'Not recorded' }}</p></div></div><div class="col-md-6"><div class="modal-section"><small>End date</small><p>{{ $med->end_date ? \Carbon\Carbon::parse($med->end_date)->format('d M Y') : 'Not recorded' }}</p></div></div><div class="col-12"><div class="modal-section"><small>Instructions</small><p>{{ $med->instructions ?: 'No special instructions.' }}</p></div></div></div></div><div class="modal-footer border-0"><a href="{{ route('nurse.edit_medication',$med->id) }}" class="btn btn-primary">Edit medication</a></div></div></div></div>@endforeach
@include('parklane.nurses.script')
