@include('parklane.dentist.head')

<div class="page-wrapper">
  @include('parklane.dentist.header')

  <div class="main-container">
    @include('parklane.dentist.sidebar')

    <div class="app-container">
      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('dentist.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item text-primary" aria-current="page">Patients</li>
        </ol>
        <a href="{{ route('dentist.add_patient') }}" class="btn btn-primary ms-auto">
          <i class="ri-user-add-line me-1"></i> Add patient
        </a>
      </div>

      <div class="app-body">
        @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row gx-3 mb-3">
          <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-box xl bg-primary-subtle rounded-4 no-shadow">
                  <i class="ri-user-heart-line fs-2 text-primary"></i>
                </div>
                <div>
                  <div class="small text-muted">Patients in your care</div>
                  <div class="fs-2 fw-bold">{{ $patientCount }}</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
              <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-box xl bg-warning-subtle rounded-4 no-shadow">
                  <i class="ri-alarm-warning-line fs-2 text-warning"></i>
                </div>
                <div>
                  <div class="small text-muted">Urgent or emergency</div>
                  <div class="fs-2 fw-bold">{{ $urgentCount }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
              <h5 class="card-title mb-1">Patient directory</h5>
              <span class="small text-muted">Assigned, shared, and transfer-request patients in your care</span>
            </div>
            <form method="GET" action="{{ route('dentist.patient') }}" class="d-flex gap-2">
              <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                placeholder="Search patient or number" aria-label="Search patients">
              <button type="submit" class="btn btn-outline-primary">Search</button>
              @if (request('search'))
                <a href="{{ route('dentist.patient') }}" class="btn btn-outline-secondary">Clear</a>
              @endif
            </form>
          </div>

          <div class="card-body p-0">
            @if ($patients->isEmpty())
              <div class="text-center py-5 px-3">
                <div class="icon-box xl bg-light rounded-circle mx-auto mb-3">
                  <i class="ri-user-search-line fs-2 text-muted"></i>
                </div>
                <h5>{{ request('search') ? 'No matching patients' : 'No patients assigned yet' }}</h5>
                <p class="text-muted mb-3">
                  {{ request('search') ? 'Try another name or patient number.' : 'Add a patient to begin building your care list.' }}
                </p>
                @unless (request('search'))
                  <a href="{{ route('dentist.add_patient') }}" class="btn btn-primary">
                    <i class="ri-user-add-line me-1"></i> Add first patient
                  </a>
                @endunless
              </div>
            @else
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                  <thead class="table-light">
                    <tr>
                      <th class="ps-4">Patient</th>
                      <th>Patient number</th>
                      <th>Condition</th>
                      <th>Ward</th>
                      <th>Urgency</th>
                      <th class="text-end pe-4">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($patients as $patient)
                      @php
                        $urgencyClass = match (strtolower($patient->urgency_status ?? 'routine')) {
                          'emergency' => 'danger',
                          'urgent' => 'warning',
                          default => 'success',
                        };
                      @endphp
                      <tr>
                        <td class="ps-4">
                          <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-semibold"
                              style="width: 42px; height: 42px;">
                              {{ strtoupper(substr($patient->full_name ?? 'P', 0, 1)) }}
                            </div>
                            <div>
                              <div class="fw-semibold">{{ $patient->full_name ?: 'Name not recorded' }}</div>
                              <div class="small text-muted">{{ ucfirst($patient->gender ?? 'Not specified') }}</div>
                              @if($patient->dentist_transfer_pending)
                                <span class="badge bg-warning text-dark mt-1">Transfer pending</span>
                              @elseif($patient->dentist_invite_pending)
                                <span class="badge bg-warning text-dark mt-1">Invite pending</span>
                              @elseif(!$patient->dentist_can_manage)
                                <span class="badge bg-secondary mt-1">View only</span>
                              @endif
                            </div>
                          </div>
                        </td>
                        <td>{{ $patient->pt_number ?: ($patient->patient_number ?: '—') }}</td>
                        <td>{{ $patient->current_condition ?: 'Not recorded' }}</td>
                        <td>{{ $patient->ward?->name ?? 'Not assigned' }}</td>
                        <td><span class="badge bg-{{ $urgencyClass }}-subtle text-{{ $urgencyClass }}">
                          {{ ucfirst($patient->urgency_status ?? 'routine') }}
                        </span></td>
                        <td class="text-end pe-4">
                          <div class="d-inline-flex flex-wrap justify-content-end gap-1">
                            <a href="{{ route('dentist.show_patient', $patient->id) }}"
                              class="btn btn-sm btn-outline-primary" title="View patient">
                              <i class="ri-eye-line"></i><span class="visually-hidden">View</span>
                            </a>
                            <a href="{{ route('dentist.edit_patients', $patient->id) }}"
                              class="btn btn-sm btn-outline-secondary {{ $patient->dentist_can_manage ? '' : 'disabled' }}" title="Edit patient">
                              <i class="ri-edit-line"></i><span class="visually-hidden">Edit</span>
                            </a>
                            @if($patient->dentist_can_manage)
                              <a href="{{ route('dentist.patients.plan', $patient->id) }}"
                                class="btn btn-sm btn-outline-success">Care plan</a>
                              <a href="{{ route('handover.show', $patient->id) }}"
                                class="btn btn-sm btn-outline-info">Handover</a>
                            @endif
                            @if($patient->dentist_can_invite)
                              <button type="button" class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal" data-bs-target="#inviteDentistUnit{{ $patient->id }}">
                                Invite unit
                              </button>
                            @endif
                          </div>
                        </td>
                      </tr>
                      @if($patient->dentist_can_invite)
                        <div class="modal fade" id="inviteDentistUnit{{ $patient->id }}" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                              <form method="POST" action="{{ route('dentist.invite.unit') }}">
                                @csrf
                                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                                <div class="modal-header">
                                  <h5 class="modal-title">Invite dental team for {{ $patient->full_name }}</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                  <div class="mb-3">
                                    <label class="form-label" for="dentistDepartment{{ $patient->id }}">Department</label>
                                    <select class="form-select dentist-department-select" id="dentistDepartment{{ $patient->id }}"
                                      name="department_id" data-patient="{{ $patient->id }}" required>
                                      <option value="">Choose department</option>
                                      @foreach($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                      @endforeach
                                    </select>
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label" for="dentistUnit{{ $patient->id }}">Unit (optional)</label>
                                    <select class="form-select dentist-unit-select" id="dentistUnit{{ $patient->id }}"
                                      name="dentist_unit_id" data-patient="{{ $patient->id }}" disabled>
                                      <option value="">Invite the whole department</option>
                                    </select>
                                    <div class="form-text">Choose a unit to invite that unit only, or leave it blank to invite the department.</div>
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label">Instructions</label>
                                    <textarea name="instructions" class="form-control" rows="3" required></textarea>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                  <button type="submit" class="btn btn-primary">Send invite</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                      @endif
                    @endforeach
                  </tbody>
                </table>
              </div>
              <div class="card-footer bg-white">{{ $patients->links('pagination::bootstrap-5') }}</div>
            @endif
          </div>
        </div>
      </div>

      <div class="app-footer bg-white"><span>© {{ now()->year }} Fandova</span></div>
    </div>
  </div>
</div>

@include('parklane.dentist.script')
<script>
document.querySelectorAll('.dentist-department-select').forEach(select => {
  select.addEventListener('change', async function () {
    const patientId = this.dataset.patient;
    const unitSelect = document.querySelector('.dentist-unit-select[data-patient="' + patientId + '"]');
    unitSelect.innerHTML = '<option value="">Invite the whole department</option>';
    unitSelect.disabled = true;
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
        unitSelect.appendChild(option);
      });
      unitSelect.disabled = false;
    } catch (error) {
      alert(error.message);
    }
  });
});
</script>
