@include('parklane.dentist.head')

@php
  $urgency = strtolower($patient->urgency_status ?? 'routine');
  $urgencyClass = match ($urgency) {
    'emergency' => 'danger',
    'urgent' => 'warning',
    default => 'success',
  };
  $patientAge = $patient->dob ? \Carbon\Carbon::parse($patient->dob)->age : null;
@endphp

<div class="page-wrapper">
  @include('parklane.dentist.header')

  <div class="main-container">
    @include('parklane.dentist.sidebar')

    <div class="app-container">
      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('dentist.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('dentist.patient') }}">Patients</a></li>
          <li class="breadcrumb-item text-primary" aria-current="page">Patient record</li>
        </ol>
        <a href="{{ route('dentist.patient') }}" class="btn btn-outline-secondary btn-sm ms-auto">
          <i class="ri-arrow-left-line me-1"></i> Patient list
        </a>
      </div>

      <div class="app-body">
        @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($urgency === 'emergency')
          <div class="alert alert-danger d-flex align-items-center gap-3 mb-3" role="alert">
            <i class="ri-alarm-warning-line fs-3"></i>
            <div>
              <strong>Emergency patient</strong>
              <div>Review the patient record and provide immediate clinical attention.</div>
            </div>
          </div>
        @elseif ($urgency === 'urgent')
          <div class="alert alert-warning d-flex align-items-center gap-3 mb-3" role="alert">
            <i class="ri-error-warning-line fs-3"></i>
            <div><strong>Urgent attention</strong><div>This patient is marked as urgent.</div></div>
          </div>
        @endif

        <section class="card border-0 shadow-sm mb-3 overflow-hidden"
          style="background: linear-gradient(120deg, #155eef 0%, #243b80 100%);">
          <div class="card-body p-4 p-lg-5 text-white">
            <div class="d-flex flex-wrap align-items-start justify-content-between gap-4">
              <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold fs-2"
                  style="width: 76px; height: 76px;">
                  {{ strtoupper(substr($patient->full_name ?? 'P', 0, 1)) }}
                </div>
                <div>
                  <span class="small text-white-50">PATIENT RECORD</span>
                  <h2 class="text-white mb-1">{{ $patient->full_name ?: 'Patient name not recorded' }}</h2>
                  <div class="d-flex flex-wrap align-items-center gap-2">
                    <span class="text-white-50">Patient No. {{ $patient->pt_number ?: ($patient->patient_number ?: 'Not assigned') }}</span>
                    <span class="badge bg-{{ $urgencyClass }}">{{ ucfirst($urgency) }}</span>
                  </div>
                </div>
              </div>
              <div class="d-flex flex-wrap gap-2">
                @if($canManage)
                  <a href="{{ route('dentist.edit_patients', $patient->id) }}" class="btn btn-light text-primary">
                    <i class="ri-edit-line me-1"></i> Edit record
                  </a>
                  <a href="{{ route('dentist.patients.plan', $patient->id) }}" class="btn btn-outline-light">
                    <i class="ri-capsule-line me-1"></i> Add medication
                  </a>
                  <a href="{{ route('handover.show', $patient->id) }}" class="btn btn-outline-light">
                    <i class="ri-file-transfer-line me-1"></i> New handover
                  </a>
                  <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#inviteDoctorTeamModal">
                    <i class="ri-user-shared-line me-1"></i> Invite doctor team
                  </button>
                  <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#dentistDischargeModal">
                    <i class="ri-logout-box-r-line me-1"></i> Discharge patient
                  </button>
                  <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#transferDoctorTeamModal">
                    <i class="ri-arrow-left-right-line me-1"></i> Transfer to doctor team
                  </button>
                @else
                  <span class="badge bg-light text-dark align-self-center">View only — accept an invitation to manage care</span>
                @endif
              </div>
            </div>
            <div class="row g-3 mt-3">
              <div class="col-sm-6 col-lg-3">
                <div class="small text-white-50">Age</div>
                <div class="fw-semibold">{{ $patientAge !== null ? $patientAge . ' years' : 'Not recorded' }}</div>
              </div>
              <div class="col-sm-6 col-lg-3">
                <div class="small text-white-50">Gender</div>
                <div class="fw-semibold">{{ ucfirst($patient->gender ?? 'Not specified') }}</div>
              </div>
              <div class="col-sm-6 col-lg-3">
                <div class="small text-white-50">Ward</div>
                <div class="fw-semibold">{{ $patient->ward?->name ?? 'Not assigned' }}</div>
              </div>
              <div class="col-sm-6 col-lg-3">
                <div class="small text-white-50">Contact</div>
                <div class="fw-semibold">{{ $patient->mobile_number ?: 'Not recorded' }}</div>
              </div>
            </div>
          </div>
        </section>

        @if($receivedCareInvites->isNotEmpty() || $receivedCareTransfers->isNotEmpty() || $incomingCrossRoleRequests->isNotEmpty())
          <div class="card border-warning mb-4">
            <div class="card-header bg-warning-subtle fw-semibold">Requests awaiting your response</div>
            <div class="card-body">
              @foreach($receivedCareInvites as $careInvite)
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 border-bottom py-2">
                  <span>Invitation to share care for {{ $patient->full_name }}. {{ $careInvite->instructions }}</span>
                  <div class="d-flex gap-2">
                    <form method="POST" action="{{ route('dentist.invite.respond', [$careInvite->id, 'accept']) }}">@csrf<button class="btn btn-sm btn-success">Accept</button></form>
                    <form method="POST" action="{{ route('dentist.invite.respond', [$careInvite->id, 'reject']) }}">@csrf<button class="btn btn-sm btn-outline-danger">Decline</button></form>
                  </div>
                </div>
              @endforeach
              @foreach($receivedCareTransfers as $careTransfer)
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 border-bottom py-2">
                  <span>Transfer request for {{ $patient->full_name }}. {{ $careTransfer->notes }}</span>
                  <div class="d-flex gap-2">
                    <form method="POST" action="{{ route('dentist.patient.transfer.respond', [$careTransfer->id, 'accept']) }}">@csrf<button class="btn btn-sm btn-success">Accept transfer</button></form>
                    <form method="POST" action="{{ route('dentist.patient.transfer.respond', [$careTransfer->id, 'reject']) }}">@csrf<button class="btn btn-sm btn-outline-danger">Decline</button></form>
                  </div>
                </div>
              @endforeach
              @foreach($incomingCrossRoleRequests as $crossRequest)
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 border-bottom py-2">
                  <span>
                    {{ ucfirst($crossRequest->request_type) }} from a doctor team for {{ $patient->full_name }}.
                    {{ $crossRequest->message }}
                  </span>
                  <div class="d-flex gap-2">
                    <form method="POST" action="{{ route('dentist.cross-role.respond', [$crossRequest->id, 'accept']) }}">@csrf<button class="btn btn-sm btn-success">Accept</button></form>
                    <form method="POST" action="{{ route('dentist.cross-role.respond', [$crossRequest->id, 'reject']) }}">@csrf<button class="btn btn-sm btn-outline-danger">Decline</button></form>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif

        @foreach([
          ['id' => 'inviteDoctorTeamModal', 'route' => 'dentist.cross-role.invite', 'title' => 'Invite a doctor team', 'submit' => 'Send invitation'],
          ['id' => 'transferDoctorTeamModal', 'route' => 'dentist.cross-role.transfer', 'title' => 'Transfer to a doctor team', 'submit' => 'Send transfer request'],
        ] as $crossTeamModal)
          <div class="modal fade cross-doctor-team-modal" id="{{ $crossTeamModal['id'] }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <form method="POST" action="{{ route($crossTeamModal['route']) }}">
                  @csrf
                  <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                  <div class="modal-header">
                    <h5 class="modal-title">{{ $crossTeamModal['title'] }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <p class="small text-muted">Choose a doctor department, then optionally a unit and subunit. No individual doctor is selected.</p>
                    <label class="form-label">Doctor department</label>
                    <select class="form-select mb-3 cross-doctor-department" name="target_department_id" required>
                      <option value="">Choose department</option>
                      @foreach($doctorDepartments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                      @endforeach
                    </select>
                    <label class="form-label">Doctor unit (optional)</label>
                    <select class="form-select mb-3 cross-doctor-unit" name="target_unit_id" disabled>
                      <option value="">Target whole department</option>
                    </select>
                    <label class="form-label">Doctor subunit (optional)</label>
                    <select class="form-select mb-3 cross-doctor-subunit" name="target_sub_unit_id" disabled>
                      <option value="">Target whole unit</option>
                    </select>
                    <label class="form-label">Message or handover notes (optional)</label>
                    <textarea name="message" class="form-control" rows="3"></textarea>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">{{ $crossTeamModal['submit'] }}</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        @endforeach

        <div class="modal fade" id="dentistDischargeModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <form method="POST" action="{{ route('dentist.patient.discharge') }}">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                <div class="modal-header">
                  <h5 class="modal-title">Discharge patient</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <label class="form-label" for="dentist-discharge-type">Discharge type</label>
                  <select class="form-select mb-3 dentist-discharge-type" id="dentist-discharge-type" name="discharge_type" required>
                    <option value="">Choose</option>
                    <option value="home">Discharge home</option>
                    <option value="transfer">Transfer to dental department or unit</option>
                  </select>
                  <div id="dentist-transfer-fields" style="display:none;">
                    <div class="mb-3">
                      <label class="form-label" for="dentist-transfer-department">Department</label>
                      <select class="form-select dentist-transfer-department" id="dentist-transfer-department" name="department_id">
                        <option value="">Choose department</option>
                        @foreach($dentistDepartments as $department)
                          <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="mb-3">
                      <label class="form-label" for="dentist-transfer-unit">Unit (optional)</label>
                      <select class="form-select dentist-transfer-unit" id="dentist-transfer-unit" name="dentist_unit_id" disabled>
                        <option value="">Transfer to the whole department</option>
                      </select>
                      <div class="form-text">Select a unit to transfer there, or leave blank for the department.</div>
                    </div>
                    <p class="small text-muted">The receiving dental department or unit must accept before ownership changes.</p>
                  </div>
                  <label for="dentist-care-transfer-notes" class="form-label">Notes (optional)</label>
                  <textarea class="form-control" id="dentist-care-transfer-notes" name="notes" rows="3"></textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-danger">Confirm</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="row gx-3">
          <div class="col-xl-8">
            <div class="card border-0 shadow-sm mb-3">
              <div class="card-header bg-white">
                <h5 class="card-title mb-1">Clinical overview</h5>
                <span class="small text-muted">Current information recorded for this patient</span>
              </div>
              <div class="card-body">
                <div class="row g-3">
                  <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                      <div class="small text-muted mb-1"><i class="ri-stethoscope-line me-1"></i> Current condition</div>
                      <div class="fw-medium">{{ $patient->current_condition ?: 'No condition recorded' }}</div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                      <div class="small text-muted mb-1"><i class="ri-heart-pulse-line me-1"></i> Blood group</div>
                      <div class="fw-medium">{{ $patient->blood_group ?: 'Not recorded' }}</div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                      <div class="small text-muted mb-1"><i class="ri-capsule-line me-1"></i> Current medications</div>
                      <div class="fw-medium text-break">{{ $patient->current_medications ?: 'None recorded' }}</div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100 {{ $patient->allergies ? 'border-warning' : '' }}">
                      <div class="small text-muted mb-1"><i class="ri-alert-line me-1"></i> Allergies</div>
                      <div class="fw-medium text-break">{{ $patient->allergies ?: 'None recorded' }}</div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                      <div class="small text-muted mb-1"><i class="ri-pulse-line me-1"></i> Blood pressure</div>
                      <div class="fw-medium">{{ $patient->blood_pressure ?: 'Not recorded' }}</div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                      <div class="small text-muted mb-1"><i class="ri-drop-line me-1"></i> Sugar level</div>
                      <div class="fw-medium">{{ $patient->sugar_level ?: 'Not recorded' }}</div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                      <div class="small text-muted mb-1"><i class="ri-user-line me-1"></i> Email</div>
                      <div class="fw-medium text-break">{{ $patient->email ?: 'Not recorded' }}</div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                      <div class="small text-muted mb-1"><i class="ri-map-pin-line me-1"></i> Address</div>
                      <div class="fw-medium text-break">{{ $patient->address ?: 'Not recorded' }}</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
              <div class="card-header bg-white d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div>
                  <h5 class="card-title mb-1">Medication plans</h5>
                  <span class="small text-muted">{{ $medications->count() }} recorded</span>
                </div>
                <a href="{{ route('dentist.patients.plan', $patient->id) }}" class="btn btn-sm btn-primary">
                  <i class="ri-add-line me-1"></i> Add medication
                </a>
              </div>
              <div class="card-body">
                @forelse ($medications as $medication)
                  <div class="border rounded-3 p-3 mb-2">
                    <div class="d-flex flex-wrap justify-content-between gap-2">
                      <div>
                        <h6 class="mb-1">{{ $medication->medication }}</h6>
                        <div class="text-muted">{{ $medication->dosage }} · {{ $medication->frequency ?: 'Frequency not specified' }}</div>
                      </div>
                      <div class="small text-muted">
                        @if ($medication->start_date)
                          {{ \Carbon\Carbon::parse($medication->start_date)->format('M j, Y') }}
                          @if ($medication->end_date)
                            – {{ \Carbon\Carbon::parse($medication->end_date)->format('M j, Y') }}
                          @endif
                        @else
                          {{ $medication->created_at?->format('M j, Y') }}
                        @endif
                      </div>
                    </div>
                    @if ($medication->instructions)
                      <p class="mb-0 mt-2 small"><strong>Instructions:</strong> {{ $medication->instructions }}</p>
                    @endif
                    <div class="d-flex justify-content-end gap-2 mt-2">
                      <a href="{{ route('edit_medication', $medication->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="ri-edit-line me-1"></i> Edit
                      </a>
                      <form action="{{ route('medications.destroy', $medication->id) }}" method="POST"
                        onsubmit="return confirm('Delete this medication plan?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                          <i class="ri-delete-bin-line me-1"></i> Delete
                        </button>
                      </form>
                    </div>
                  </div>
                @empty
                  <div class="text-center py-4">
                    <i class="ri-capsule-line fs-1 text-muted"></i>
                    <p class="text-muted mt-2 mb-0">No medication plans recorded.</p>
                  </div>
                @endforelse
              </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
              <div class="card-header bg-white d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div>
                  <h5 class="card-title mb-1">Handover history</h5>
                  <span class="small text-muted">{{ $handoverNotes->count() }} recorded</span>
                </div>
                <a href="{{ route('handover.show', $patient->id) }}" class="btn btn-sm btn-primary">
                  <i class="ri-add-line me-1"></i> Add handover
                </a>
              </div>
              <div class="card-body">
                @forelse ($handoverNotes as $note)
                  <article class="border rounded-3 p-3 mb-2">
                    <div class="d-flex flex-wrap justify-content-between gap-2 mb-2">
                      <h6 class="mb-0">Handover note</h6>
                      <span class="small text-muted">{{ $note->created_at?->format('M j, Y g:i A') }}</span>
                    </div>
                    <p class="mb-2">{{ \Illuminate\Support\Str::limit($note->summary, 240) }}</p>
                    @if ($note->alerts)
                      <div class="small mb-2"><strong class="text-warning-emphasis">Alerts:</strong> {{ \Illuminate\Support\Str::limit($note->alerts, 180) }}</div>
                    @endif
                    @if ($note->pending_tasks)
                      <div class="small"><strong>Pending tasks:</strong> {{ \Illuminate\Support\Str::limit($note->pending_tasks, 180) }}</div>
                    @endif
                    <div class="small text-muted mt-2">
                      Recorded by {{ $note->dentist?->full_name ?? $note->doctor?->full_name ?? 'Care team' }}
                    </div>
                  </article>
                @empty
                  <div class="text-center py-4">
                    <i class="ri-file-list-3-line fs-1 text-muted"></i>
                    <p class="text-muted mt-2 mb-0">No handover notes recorded.</p>
                  </div>
                @endforelse
              </div>
            </div>
          </div>

          <div class="col-xl-4">
            <div class="card border-0 shadow-sm mb-3">
              <div class="card-header bg-white"><h5 class="card-title mb-0">Patient details</h5></div>
              <div class="card-body">
                <div class="d-flex justify-content-between gap-3 mb-3">
                  <span class="text-muted">Date of birth</span>
                  <span class="text-end fw-medium">{{ $patient->dob ? \Carbon\Carbon::parse($patient->dob)->format('M j, Y') : 'Not recorded' }}</span>
                </div>
                <div class="d-flex justify-content-between gap-3 mb-3">
                  <span class="text-muted">Marital status</span>
                  <span class="text-end fw-medium">{{ $patient->marital_status ? ucfirst($patient->marital_status) : 'Not recorded' }}</span>
                </div>
                <div class="d-flex justify-content-between gap-3 mb-3">
                  <span class="text-muted">Ward</span>
                  <span class="text-end fw-medium">{{ $patient->ward?->name ?? 'Not assigned' }}</span>
                </div>
                <div class="d-flex justify-content-between gap-3 mb-3">
                  <span class="text-muted">Consulting doctor</span>
                  <span class="text-end fw-medium">{{ $patient->doctorById?->full_name ?? 'Not assigned' }}</span>
                </div>
                <div class="d-flex justify-content-between gap-3">
                  <span class="text-muted">Record created</span>
                  <span class="text-end fw-medium">{{ $patient->created_at?->format('M j, Y') ?? 'Not recorded' }}</span>
                </div>
              </div>
              <div class="card-footer bg-white">
                <a href="{{ route('dentist.edit_patients', $patient->id) }}" class="btn btn-outline-primary w-100">
                  <i class="ri-edit-line me-1"></i> Update patient details
                </a>
              </div>
            </div>

            <div class="card border-0 shadow-sm">
              <div class="card-header bg-white"><h5 class="card-title mb-0">Quick actions</h5></div>
              <div class="card-body d-grid gap-2">
                <a href="{{ route('dentist.patients.plan', $patient->id) }}" class="btn btn-outline-primary text-start">
                  <i class="ri-capsule-line me-2"></i> Add medication plan
                </a>
                <a href="{{ route('handover.show', $patient->id) }}" class="btn btn-outline-primary text-start">
                  <i class="ri-file-transfer-line me-2"></i> Record handover
                </a>
                <a href="{{ route('dentist.patient') }}" class="btn btn-outline-secondary text-start">
                  <i class="ri-list-check-2 me-2"></i> Back to patient list
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="app-footer bg-white"><span>© {{ now()->year }} Fandova</span></div>
    </div>
  </div>
</div>

@include('parklane.dentist.script')
<script>
const dentistDischargeType = document.getElementById('dentist-discharge-type');
const dentistTransferFields = document.getElementById('dentist-transfer-fields');
const dentistTransferDepartment = document.getElementById('dentist-transfer-department');
const dentistTransferUnit = document.getElementById('dentist-transfer-unit');

document.querySelectorAll('.cross-doctor-department').forEach(select => {
  select.addEventListener('change', async function () {
    const form = this.closest('form');
    const unitSelect = form.querySelector('.cross-doctor-unit');
    const subUnitSelect = form.querySelector('.cross-doctor-subunit');
    unitSelect.innerHTML = '<option value="">Target whole department</option>';
    subUnitSelect.innerHTML = '<option value="">Target whole unit</option>';
    unitSelect.disabled = true;
    subUnitSelect.disabled = true;
    if (!this.value) return;

    try {
      const response = await fetch("{{ route('dentist.cross-role.departments') }}/" + this.value + "/units", {
        headers: { 'Accept': 'application/json' }
      });
      if (!response.ok) throw new Error('Could not load doctor units.');
      const units = await response.json();
      units.forEach(unit => {
        const option = document.createElement('option');
        option.value = unit.id;
        option.textContent = unit.name;
        unitSelect.appendChild(option);
      });
      unitSelect.disabled = false;
    } catch (error) {
      this.value = '';
      alert(error.message);
    }
  });
});

document.querySelectorAll('.cross-doctor-unit').forEach(select => {
  select.addEventListener('change', async function () {
    const form = this.closest('form');
    const subUnitSelect = form.querySelector('.cross-doctor-subunit');
    subUnitSelect.innerHTML = '<option value="">Target whole unit</option>';
    subUnitSelect.disabled = true;
    if (!this.value) return;

    try {
      const response = await fetch("{{ url('/dentist/cross-role/units') }}/" + this.value + "/subunits", {
        headers: { 'Accept': 'application/json' }
      });
      if (!response.ok) throw new Error('Could not load doctor subunits.');
      const subUnits = await response.json();
      subUnits.forEach(subUnit => {
        const option = document.createElement('option');
        option.value = subUnit.id;
        option.textContent = subUnit.name;
        subUnitSelect.appendChild(option);
      });
      subUnitSelect.disabled = false;
    } catch (error) {
      this.value = '';
      alert(error.message);
    }
  });
});

dentistDischargeType?.addEventListener('change', function () {
  const transferring = this.value === 'transfer';
  dentistTransferFields.style.display = transferring ? 'block' : 'none';
  dentistTransferDepartment.required = transferring;
});

dentistTransferDepartment?.addEventListener('change', async function () {
  dentistTransferUnit.innerHTML = '<option value="">Transfer to the whole department</option>';
  dentistTransferUnit.disabled = true;
  if (!this.value) return;

  try {
    const response = await fetch("{{ url('/dentist/departments') }}/" + this.value + "/units", {
      headers: { 'Accept': 'application/json' }
    });
    if (!response.ok) throw new Error('Could not load dental units.');
    const units = await response.json();
    units.forEach(unit => {
      const option = document.createElement('option');
      option.value = unit.id;
      option.textContent = unit.name;
      dentistTransferUnit.appendChild(option);
    });
    dentistTransferUnit.disabled = false;
  } catch (error) {
    dentistTransferDepartment.value = '';
    alert(error.message);
  }
});
</script>
