@include('parklane.doctor.head')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
  #dropzone {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    border: 2px dashed #007bff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease-in-out;
  }

  #dropzone:hover {
    border-color: #0056b3;
    background-color: #e9ecef;
  }

  .dz-message {
    color: #495057;
  }

  .dz-button {
    font-size: 16px;
    padding: 12px 30px;
    background-color: #007bff;
    color: white;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .dz-button:hover {
    background-color: #0056b3;
  }

  h5 {
    font-size: 16px;
    font-weight: normal;
    margin-top: 20px;
  }

  @media (max-width: 576px) {
    #dropzone {
      padding: 20px;
    }

    .dz-button {
      font-size: 14px;
      padding: 10px 25px;
    }

    h5 {
      font-size: 14px;
    }
  }

  .form-select, .form-control {
    font-size: 1rem;
    padding: 0.75rem;
    border-radius: 0.375rem;
    border: 1px solid #ced4da;
    transition: all 0.3s ease-in-out;
  }

  .form-select:focus, .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
  }

  .form-label {
    font-weight: 600;
    font-size: 1rem;
    color: #495057;
  }

  .mb-3 {
    margin-bottom: 1rem;
  }
  @keyframes pulseDanger {
  0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
  70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
  100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
}

</style>

<div class="page-wrapper">

  @include('parklane.doctor.header')

  <div class="main-container">

    @include('parklane.doctor.sidebar')

    <div class="app-container">

      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="index.html">Home</a>
          </li>
          <li class="breadcrumb-item text-primary" aria-current="page">
            Dashboard
          </li>
        </ol>
      </div>

      <div id="toast-container"></div>

      <div class="app-body">

        <div class="row gx-3">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title">Patients</h5>
                <a href="{{ route('doctor.add_patient') }}" class="btn btn-primary ms-auto">Add Patient</a>
              </div>

              <div class="mb-3 d-flex justify-content-end">
                <input type="text" id="searchInput" class="form-control w-50" placeholder="Search patients...">
              </div>

              <div class="card-body">
                <div class="table-responsive">
                  <table id="basicExample" class="table truncate m-0 align-middle">
                    <thead>
                      <tr>
                        <th>Patient Type</th>
                        <th>No.</th>
                        <th>Patient Name</th>
                        <th>Gender</th>
                        <th>Treatment</th>
                        <th>Current Condition</th>
                        <th>Ward</th>
                        <th>Diagnose</th>
                        <th>Allergies</th>
                        <th>Urgency status</th>
                        <th>Plan</th>
                        <th>HandoverNote</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if(isset($patients) && $patients->count() > 0)
                        @foreach ($patients as $patient)
                          <tr>
                           <td>
                          @php
                            $myInvite = DB::table('patient_unit_invites')
                              ->where('patient_id', $patient->id)
                              ->where(function ($q) use ($doctor) {
                                  if (!empty($doctor->doctor_sub_unit_id)) {
                                      $q->where('doctor_sub_unit_id', $doctor->doctor_sub_unit_id);
                                  } else {
                                      $q->where('doctor_unit_id', $doctor->doctor_unit_id)
                                        ->whereNull('doctor_sub_unit_id');
                                  }
                              })
                              ->first();

                            $isPendingInvite = $myInvite && $myInvite->status === 'pending';
                            $isRejectedInvite = $myInvite && $myInvite->status === 'rejected';
                            $isAcceptedInvite = $myInvite && $myInvite->status === 'accepted';

                            $isHomeUnit = !empty($doctor->doctor_sub_unit_id)
                                ? $doctor->doctor_sub_unit_id == $patient->doctor_sub_unit_id
                                : ($doctor->doctor_unit_id == $patient->doctor_unit_id && empty($patient->doctor_sub_unit_id));

                            // Mirrors PatientController::doctorCanManagePatient() — home unit,
                            // or an accepted invite. Pending/rejected/no-invite = view-only.
                            $canManage = $isHomeUnit || $isAcceptedInvite;

                            $isPendingTransferForThisUnit = $patient->transfer_status === 'pending'
                              && (
                                $patient->pending_transfer_unit_id == $doctor->doctor_unit_id ||
                                (!empty($doctor->doctor_sub_unit_id) && $patient->pending_transfer_sub_unit_id == $doctor->doctor_sub_unit_id)
                              );

                            $isDischarged = !is_null($patient->discharged_at) && $patient->status === 'discharged';
                          @endphp

                          @if ($isPendingTransferForThisUnit)
                            <span class="badge bg-warning text-dark">Pending Acceptance</span>
                          @elseif ($isDischarged)
                            <span class="badge bg-danger">Discharged</span>
                          @elseif ($isPendingInvite)
                            <span class="badge bg-warning">Invite Pending</span>
                          @elseif ($isRejectedInvite)
                            <span class="badge bg-secondary">Invite Rejected — View Only</span>
                          @elseif ($isAcceptedInvite)
                            <span class="badge bg-success">Invited — Accepted</span>
                          @else
                            <span class="badge bg-primary">Assigned</span>
                          @endif
                          </td>

                          <td>{{ $patient->pt_number }}</td>
                          <td>{{ $patient->full_name }}</td>
                          <td>
                            @if($patient->gender == 'male')
                              <span class="badge bg-secondary text-white">{{ ucfirst($patient->gender) }}</span>
                            @elseif($patient->gender == 'female')
                              <span class="badge bg-success text-white">{{ ucfirst($patient->gender) }}</span>
                            @else
                              <span class="badge bg-secondary text-white">{{ ucfirst($patient->gender) }}</span>
                            @endif
                          </td>
                          <td>{{ $patient->sugar_level ?? 'N/A' }}</td>
                          <td>{{ $patient->current_condition }}</td>
                          <td>{{ $patient->ward ? $patient->ward->name : 'N/A' }}</td>
                          <td>{{ $patient->current_medications }}</td>
                          <td>{{ $patient->allergies }}</td>
                          <td>
                            @php $urgency = strtolower($patient->urgency_status); @endphp

                            @if ($urgency === 'emergency')
                              <span class="badge bg-danger text-white px-3 py-2" style="animation:pulseDanger 1s infinite;">
                                  🔴 Emergency Alert
                              </span>
                            @elseif ($urgency === 'urgent')
                              🟠 Urgent
                            @elseif ($urgency === 'normal')
                              🟢 Normal
                            @else
                              {{ $patient->urgency_status ?? 'N/A' }}
                            @endif
                          </td>

                          <td>
                            @if($canManage)
                              <a href="{{ route('doctor.patients.plan', $patient->id) }}" class="btn btn-outline-primary btn-sm">Medication</a>
                            @else
                              <button type="button" class="btn btn-outline-secondary btn-sm" disabled
                                data-bs-toggle="tooltip" title="View only — your unit hasn't accepted this patient yet">
                                <i class="ri-lock-line"></i> Medication
                              </button>
                            @endif
                          </td>
                          <td>
                            @if($canManage)
                              <a href="{{ route('doctor.handover.show', $patient->id) }}" class="btn btn-outline-primary btn-sm">HandoverNote</a>
                            @else
                              <button type="button" class="btn btn-outline-secondary btn-sm" disabled
                                data-bs-toggle="tooltip" title="View only — your unit hasn't accepted this patient yet">
                                <i class="ri-lock-line"></i> HandoverNote
                              </button>
                            @endif
                          </td>

                          <td>
                            <div class="d-inline-flex gap-1 flex-wrap">
                              @if($canManage)
                                <a href="{{ route('doctor.edit_patients', $patient->id) }}" class="btn btn-outline-success btn-sm"
                                  data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Patient Details">
                                  <i class="ri-edit-box-line"></i>
                                </a>
                              @else
                                <button type="button" class="btn btn-outline-secondary btn-sm" disabled
                                  data-bs-toggle="tooltip" data-bs-placement="top" title="View only until your unit accepts this patient">
                                  <i class="ri-lock-line"></i>
                                </button>
                              @endif

                              <a href="{{ route('doctor.show_patient', $patient->id) }}" class="btn btn-outline-info btn-sm"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="View Dashboard">
                                <i class="ri-eye-line"></i>
                              </a>

                              @if($isHomeUnit)
                                <a href="#" class="btn btn-outline-info btn-sm"
                                  data-bs-toggle="modal" data-bs-target="#inviteUnitModal{{ $patient->id }}"
                                  title="Invite Unit">
                                  <i class="ri-user-add-line"></i>
                                </a>
                              @endif

                              @if($isPendingTransferForThisUnit)
                                <form action="{{ route('doctor.accept_transfer', $patient->id) }}" method="POST" class="d-inline">
                                  @csrf
                                  <button type="submit" class="btn btn-success btn-sm">Accept</button>
                                </form>

                                <form action="{{ route('doctor.reject_transfer', $patient->id) }}" method="POST" class="d-inline">
                                  @csrf
                                  <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                </form>
                              @endif

                              @if($isPendingInvite && $myInvite)
                                <form action="{{ route('doctor.invite.accept', $myInvite->id) }}" method="POST" class="d-inline">
                                  @csrf
                                  <button type="submit" class="btn btn-success btn-sm">Accept Invite</button>
                                </form>

                                <form action="{{ route('doctor.invite.reject', $myInvite->id) }}" method="POST" class="d-inline">
                                  @csrf
                                  <button type="submit" class="btn btn-danger btn-sm">Reject Invite</button>
                                </form>
                              @endif
                            </div>
                          </td>
                          </tr>

                          <div class="modal fade" id="inviteUnitModal{{ $patient->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                              <div class="modal-content">

                                <div class="modal-header">
                                  <h5 class="modal-title">Invite Unit for {{ $patient->full_name }}</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <form action="{{ route('doctor.invite.unit') }}" method="POST">
                                  @csrf
                                  <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                                  <div class="modal-body">
                                    <div class="mb-3">
                                      <label class="form-label">Department</label>
                                      <select class="form-select department-select" name="department_id" data-patient-id="{{ $patient->id }}" required>
                                        <option value="">Select</option>
                                        @foreach($departments as $department)
                                          <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                      </select>
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label">Unit</label>
                                      <select class="form-select unit-select" name="doctor_unit_id" data-patient-id="{{ $patient->id }}" required>
                                        <option value="">Select</option>
                                        @foreach($doctorUnits as $unit)
                                          <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                      </select>
                                    </div>

                                    <div class="mb-3" id="subunit-wrapper-{{ $patient->id }}" style="display:none;">
                                      <label class="form-label">Doctor Sub Unit</label>
                                      <select class="form-select subunit-select" name="doctor_sub_unit_id" data-patient-id="{{ $patient->id }}">
                                        <option value="">Select Sub Unit</option>
                                      </select>
                                    </div>

                                    <div class="mb-3" id="no-subunit-msg-{{ $patient->id }}" style="display:none;">
                                      <small class="text-muted">No sub unit for this unit</small>
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label">Instructions</label>
                                      <textarea name="instructions" class="form-control" rows="3" required></textarea>
                                    </div>
                                  </div>

                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Send Invite</button>
                                  </div>
                                </form>

                              </div>
                            </div>
                          </div>

                          @include('partials.request-test-modal', ['patient' => $patient])
                        @endforeach
                      @else
                        <tr>
                          <td colspan="13" class="text-center text-danger">No patients assigned to you at the moment.</td>
                        </tr>
                      @endif
                    </tbody>
                  </table>

                  <div class="mt-4">
                    {{ $patients->links('pagination::bootstrap-5') }}
                  </div>
                </div>

                @foreach ($patients as $patient)
                  @include('partials.request-test-modal', ['patient' => $patient])
                @endforeach

              </div>
            </div>
          </div>
        </div>

      </div>

      <div class="app-footer bg-white">
        <span>© 2024</span>
      </div>

    </div>
  </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(button => {
      button.addEventListener('click', function () {
        const doctorId = this.getAttribute('data-id');

        Swal.fire({
          title: 'Are you sure?',
          text: 'This action cannot be undone!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            document.getElementById('delete-doctor-form-' + doctorId).submit();
          }
        });
      });
    });
  });
</script>

<script>
  document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchValue = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('#basicExample tbody tr');

    tableRows.forEach(row => {
      let textMatch = false;

      row.querySelectorAll('td').forEach(cell => {
        if (cell.querySelector('a') || cell.querySelector('button') || cell.querySelector('form')) return;

        const originalText = cell.textContent;
        cell.innerHTML = originalText;

        if (searchValue && originalText.toLowerCase().includes(searchValue)) {
          textMatch = true;
          const regex = new RegExp(`(${searchValue})`, 'gi');
          cell.innerHTML = originalText.replace(regex, "<span style='background-color: yellow;'>$1</span>");
        }
      });

      row.style.display = textMatch || searchValue === '' ? '' : 'none';
    });
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.department-select').forEach(departmentSelect => {
      departmentSelect.addEventListener('change', function () {
        let patientId = this.dataset.patientId;
        let unitSelect = document.querySelector(`.unit-select[data-patient-id="${patientId}"]`);
        let subUnitSelect = document.querySelector(`.subunit-select[data-patient-id="${patientId}"]`);
        let subUnitWrapper = document.getElementById(`subunit-wrapper-${patientId}`);
        let noSubMsg = document.getElementById(`no-subunit-msg-${patientId}`);

        unitSelect.innerHTML = '<option value="">Select Unit</option>';
        subUnitSelect.innerHTML = '<option value="">Select Sub Unit</option>';
        subUnitWrapper.style.display = 'none';
        noSubMsg.style.display = 'none';

        let departmentId = this.value;
        if (departmentId) {
          fetch(`/get-units-doctor/${departmentId}`)
            .then(res => res.json())
            .then(data => {
              data.forEach(unit => {
                let option = document.createElement('option');
                option.value = unit.id;
                option.textContent = unit.name;
                unitSelect.appendChild(option);
              });
            });
        }
      });
    });

    document.querySelectorAll('.unit-select').forEach(unitSelect => {
      unitSelect.addEventListener('change', function () {
        let patientId = this.dataset.patientId;
        let subUnitSelect = document.querySelector(`.subunit-select[data-patient-id="${patientId}"]`);
        let subUnitWrapper = document.getElementById(`subunit-wrapper-${patientId}`);
        let noSubMsg = document.getElementById(`no-subunit-msg-${patientId}`);

        subUnitSelect.innerHTML = '<option value="">Select Sub Unit</option>';
        subUnitWrapper.style.display = 'none';
        noSubMsg.style.display = 'none';

        let unitId = this.value;
        if (unitId) {
          fetch(`/get-sub-units-doctor/${unitId}`)
            .then(res => res.json())
            .then(data => {
              if (data.length > 0) {
                subUnitWrapper.style.display = 'block';
                data.forEach(sub => {
                  let option = document.createElement('option');
                  option.value = sub.id;
                  option.textContent = sub.name;
                  subUnitSelect.appendChild(option);
                });
              } else {
                noSubMsg.style.display = 'block';
              }
            });
        }
      });
    });
  });
</script>

@include('parklane.doctor.script')
