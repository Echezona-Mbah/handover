@include('parklane.doctor.head')
<style>
  .patient-hero {
    background: linear-gradient(135deg, #0f172a, #1d4ed8 55%, #38bdf8);
    border-radius: 24px;
    color: #fff;
    overflow: hidden;
    position: relative;
  }

  .patient-hero::after {
    content: "";
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at top right, rgba(255,255,255,0.18), transparent 35%);
    pointer-events: none;
  }

  .patient-stat-card,
  .detail-card,
  .section-card {
    border: 0;
    border-radius: 20px;
    box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
  }

  .patient-stat-card .icon-box,
  .detail-card .icon-box {
    width: 52px;
    height: 52px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 16px;
  }

  .detail-tile {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 16px;
    height: 100%;
  }

  .detail-tile small {
    display: block;
    color: #64748b;
    margin-bottom: 6px;
    font-weight: 600;
  }

  .emergency-banner {
    background: linear-gradient(135deg, #7f1d1d, #dc2626);
    color: #fff;
    border-radius: 18px;
    padding: 16px 18px;
    box-shadow: 0 12px 24px rgba(220, 38, 38, 0.22);
    animation: pulseDanger 1.4s infinite;
  }

  @keyframes pulseDanger {
    0% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.45); }
    70% { box-shadow: 0 0 0 14px rgba(220, 38, 38, 0); }
    100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
  }

  .info-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.18);
    border-radius: 999px;
    padding: 8px 14px;
    font-size: 13px;
    margin: 0 10px 10px 0;
  }

  .table thead th {
    white-space: nowrap;
  }

  .section-card .card-header {
    background: transparent;
    border-bottom: 1px solid #eef2f7;
    padding-bottom: 10px;
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

      <div class="app-body">

        @php
          $urgency = strtolower($patient->urgency_status ?? '');
          $age = $patient->dob ? \Carbon\Carbon::parse($patient->dob)->age : 'N/A';
        @endphp

        @if($urgency === 'emergency')
          <div class="emergency-banner mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
              <div>
                <h5 class="mb-1 text-white">Emergency Patient Alert</h5>
                <p class="mb-0">This patient requires urgent attention from the care team.</p>
              </div>
              <div class="fw-bold fs-5">🔴 EMERGENCY</div>
            </div>
          </div>
        @endif

        <div class="card patient-hero mb-4">
          <div class="card-body p-4 p-lg-5">
            <div class="row align-items-center g-4">
              <div class="col-lg-8">
                <p class="mb-2 opacity-75">Patient Overview</p>
                <h2 class="mb-2 text-white">{{ $patient->full_name }}</h2>
                <p class="mb-3 opacity-75">
                  Patient No: {{ $patient->pt_number ?? 'N/A' }} |
                  Ward: {{ $patient->ward?->name ?? 'N/A' }}
                </p>

                <div class="mb-2">
                  <span class="info-chip"><i class="ri-user-line"></i> {{ ucfirst($patient->gender ?? 'n/a') }}</span>
                  <span class="info-chip"><i class="ri-calendar-line"></i> {{ $age }} years</span>
                  <span class="info-chip"><i class="ri-heart-pulse-line"></i> {{ ucfirst($patient->urgency_status ?? 'n/a') }}</span>
                  <span class="info-chip"><i class="ri-phone-line"></i> {{ $patient->mobile_number ?? 'N/A' }}</span>
                </div>

                <div class="mb-0">
                  <span class="info-chip"><i class="ri-hospital-line"></i> {{ $patient->doctorUnit?->name ?? 'No Unit' }}</span>
                  <span class="info-chip"><i class="ri-git-branch-line"></i> {{ $patient->doctorSubUnit?->name ?? 'No Sub Unit' }}</span>
                  <span class="info-chip"><i class="ri-mail-line"></i> {{ $patient->email ?? 'No Email' }}</span>
                </div>
              </div>

              <div class="col-lg-4">
                <div class="d-flex justify-content-lg-end">
                  @if($canManage)
                    <button class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#dischargeModal{{ $patient->id }}">
                      <i class="ri-logout-box-r-line"></i> Discharge Patient
                    </button>
                  @else
                    <button class="btn btn-secondary btn-lg" disabled
                      data-bs-toggle="tooltip" data-bs-placement="left"
                      title="View only — your unit hasn't accepted this patient yet">
                      <i class="ri-lock-line"></i> Discharge Patient
                    </button>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>

        <div id="toast-container"></div>

        <div class="modal fade" id="dischargeModal{{ $patient->id }}" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

              @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                  {{ session('success') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              @endif

              @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                  {{ session('error') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              @endif

              @if ($errors->any())
                <div class="alert alert-danger m-3">
                  <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

              <div class="modal-header">
                <h5 class="modal-title">Discharge Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <form action="{{ route('doctor.patient.discharge') }}" method="POST">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                <div class="modal-body">
                  <p class="fw-bold">Discharge <strong>{{ $patient->full_name }}</strong></p>

                  <div class="mb-3">
                    <label class="form-label">Discharge Type</label>
                    <select class="form-select discharge-type" name="discharge_type" data-patient="{{ $patient->id }}" required>
                      <option value="">Select</option>
                      <option value="home">Discharge Home</option>
                      <option value="transfer">Transfer to Another Unit</option>
                    </select>
                  </div>

                  <div class="transfer-fields" id="transfer_fields_{{ $patient->id }}" style="display:none;">
                    <div class="mb-3">
                      <label class="form-label">Department</label>
                      <select class="form-select department_select" data-patient="{{ $patient->id }}" name="department_id">
                        <option value="">Select</option>
                        @foreach($departments as $department)
                          <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Unit</label>
                      <select class="form-select unit_select" data-patient="{{ $patient->id }}" name="doctor_unit_id">
                        <option value="">Select</option>
                      </select>
                    </div>

                    <div class="mb-3" id="sub_unit_wrapper_{{ $patient->id }}" style="display:none;">
                      <label class="form-label">Sub Unit</label>
                      <select class="form-select sub_unit_select" data-patient="{{ $patient->id }}" name="doctor_sub_unit_id">
                        <option value="">Select</option>
                      </select>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Discharge Notes (Optional)</label>
                    <textarea name="notes" rows="3" class="form-control" placeholder="Reason, summary, recommendations..."></textarea>
                  </div>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-danger">Confirm</button>
                </div>
              </form>

            </div>
          </div>
        </div>

        <div class="row gx-3 mb-4">
          <div class="col-xl-3 col-sm-6">
            <div class="card patient-stat-card h-100">
              <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-box bg-primary text-white">
                  <i class="ri-account-circle-line fs-3"></i>
                </div>
                <div>
                  <h6 class="mb-1">Patient Age</h6>
                  <h4 class="mb-0">{{ $age }}</h4>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-sm-6">
            <div class="card patient-stat-card h-100">
              <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-box bg-success text-white">
                  <i class="ri-heart-pulse-line fs-3"></i>
                </div>
                <div>
                  <h6 class="mb-1">Current Condition</h6>
                  <h4 class="mb-0">{{ $patient->current_condition ?? 'N/A' }}</h4>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-sm-6">
            <div class="card patient-stat-card h-100">
              <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-box bg-warning text-dark">
                  <i class="ri-medicine-bottle-line fs-3"></i>
                </div>
                <div>
                  <h6 class="mb-1">Medication Count</h6>
                  <h4 class="mb-0">{{ $medications->count() }}</h4>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-sm-6">
            <div class="card patient-stat-card h-100">
              <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-box bg-danger text-white">
                  <i class="ri-file-list-3-line fs-3"></i>
                </div>
                <div>
                  <h6 class="mb-1">Handover Notes</h6>
                  <h4 class="mb-0">{{ $handoverNotes->count() }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row gx-3 mb-4">
          <div class="col-xl-8">
            <div class="card detail-card h-100">
              <div class="card-header border-0 pb-0">
                <h5 class="card-title">Patient Details</h5>
              </div>
              <div class="card-body">
                <div class="row g-3">
                  <div class="col-md-4">
                    <div class="detail-tile">
                      <small>Full Name</small>
                      <div class="fw-semibold">{{ $patient->full_name }}</div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="detail-tile">
                      <small>Date of Birth</small>
                      <div class="fw-semibold">{{ $patient->dob ? \Carbon\Carbon::parse($patient->dob)->format('d M Y') : 'N/A' }}</div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="detail-tile">
                      <small>Gender</small>
                      <div class="fw-semibold">{{ ucfirst($patient->gender ?? 'n/a') }}</div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="detail-tile">
                      <small>Email</small>
                      <div class="fw-semibold">{{ $patient->email ?? 'N/A' }}</div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="detail-tile">
                      <small>Mobile Number</small>
                      <div class="fw-semibold">{{ $patient->mobile_number ?? 'N/A' }}</div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="detail-tile">
                      <small>Marital Status</small>
                      <div class="fw-semibold">{{ ucfirst($patient->marital_status ?? 'n/a') }}</div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="detail-tile">
                      <small>Ward</small>
                      <div class="fw-semibold">{{ $patient->ward?->name ?? 'N/A' }}</div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="detail-tile">
                      <small>Unit</small>
                      <div class="fw-semibold">{{ $patient->doctorUnit?->name ?? 'N/A' }}</div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="detail-tile">
                      <small>Sub Unit</small>
                      <div class="fw-semibold">{{ $patient->doctorSubUnit?->name ?? 'N/A' }}</div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="detail-tile">
                      <small>Current Medications</small>
                      <div class="fw-semibold">{{ $patient->current_medications ?? 'N/A' }}</div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="detail-tile">
                      <small>Allergies</small>
                      <div class="fw-semibold">{{ $patient->allergies ?? 'N/A' }}</div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="detail-tile">
                      <small>Urgency Status</small>
                      <div class="fw-semibold">
                        @if($urgency === 'emergency')
                          <span class="badge bg-danger">Emergency</span>
                        @elseif($urgency === 'urgent')
                          <span class="badge bg-warning text-dark">Urgent</span>
                        @else
                          <span class="badge bg-success">Normal</span>
                        @endif
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="detail-tile">
                      <small>Address</small>
                      <div class="fw-semibold">{{ $patient->address ?? 'N/A' }}</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-4">
            <div class="card detail-card h-100">
              <div class="card-header border-0 pb-0">
                <h5 class="card-title">KEY INVESTIGATIONS FINDINGS</h5>
              </div>
              <div class="card-body">
                <div class="detail-tile mb-3">
                  <small>Treatment / Sugar Level</small>
                  <div class="fw-semibold">{{ $patient->sugar_level ?? 'N/A' }}</div>
                </div>
                <div class="detail-tile mb-3">
                  <small>Condition Summary</small>
                  <div class="fw-semibold">{{ $patient->current_condition ?? 'N/A' }}</div>
                </div>
                <div class="detail-tile mb-3">
                  <small>Latest Discharge Notes</small>
                  <div class="fw-semibold">{{ $patient->discharge_notes ?? 'No discharge notes yet' }}</div>
                </div>
                <div class="detail-tile">
                  <small>Last Updated</small>
                  <div class="fw-semibold">{{ $patient->updated_at ? $patient->updated_at->format('d M Y, h:i A') : 'N/A' }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row gx-3">
          <div class="col-xl-6 col-sm-12">
            <div class="card section-card mb-3">
              <div class="card-header">
                <h5 class="card-title">Doctor Visits / Handover Notes</h5>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table align-middle truncate m-0">
                    <thead>
                      <tr>
                        <th>Doctor</th>
                        <th>Date</th>
                        <th>Department</th>
                        <th>View</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($handoverNotes as $handoverNote)
                        <tr>
                          <td>{{ $handoverNote->doctor?->full_name }}</td>
                          <td>{{ $handoverNote->created_at->format('d/m/Y') }}</td>
                          <td>{{ $handoverNote->doctor?->doctorDepartment?->name }}</td>
                          <td>
                            <div class="d-inline-flex gap-1">
                              <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#viewHandoverNoteModal{{ $handoverNote->id }}">
                                View HandoverNotes
                              </button>

                              @if($canManage)
                                <a href="{{ route('doctor.handover.edit', $handoverNote->id) }}" class="btn btn-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Handover Note">
                                  <i class="ri-edit-2-line"></i> Edit
                                </a>
                              @else
                                <button type="button" class="btn btn-secondary btn-sm" disabled
                                  data-bs-toggle="tooltip" data-bs-placement="top" title="View only until your unit accepts this patient">
                                  <i class="ri-lock-line"></i> Edit
                                </button>
                              @endif
                            </div>
                          </td>
                        </tr>

                        <div class="modal fade" id="viewHandoverNoteModal{{ $handoverNote->id }}" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title">Handover Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                              </div>

                              <div class="modal-body">
                                <p><strong>Doctor:</strong> {{ $handoverNote->doctor?->full_name }}</p>
                                <p><strong>Department:</strong> {{ $handoverNote->doctor?->doctorDepartment?->name }}</p>
                                <p><strong>Date:</strong> {{ $handoverNote->created_at->format('d/m/Y') }}</p>
                                <hr>
                                <p><strong>General Summary:</strong><br>{{ $handoverNote->summary }}</p>
                                <p><strong>Critical Alerts:</strong><br>{{ $handoverNote->alerts }}</p>
                                <p><strong>Pending Tasks:</strong><br>{{ $handoverNote->pending_tasks }}</p>
                                <p><strong>Medication:</strong><br>{{ $handoverNote->medication }}</p>
                              </div>

                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-6 col-sm-12">
            <div class="card section-card mb-3">
              <div class="card-header">
                <h5 class="card-title">Medication Plan</h5>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table align-middle truncate m-0">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Medication</th>
                        <th>Dosage</th>
                        <th>Date</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($medications->count() > 0)
                        <tr>
                          <td>1</td>
                          <td>
                            @if($medications->count() === 1)
                              {{ $medications->first()->medication }}
                            @else
                              <strong>{{ $medications->count() }} Medication Plans</strong>
                              <div class="small text-muted">
                                {{ $medications->pluck('medication')->take(2)->implode(', ') }}
                                @if($medications->count() > 2) ... @endif
                              </div>
                            @endif
                          </td>
                          <td>
                            @if($medications->count() === 1)
                              {{ $medications->first()->dosage }}
                            @else
                              Multiple
                            @endif
                          </td>
                          <td>{{ $medications->sortByDesc('created_at')->first()?->created_at?->format('M d, Y') }}</td>
                          <td>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#viewAllMedicationModal">
                              View Medical Plan
                            </button>
                          </td>
                        </tr>
                      @else
                        <tr>
                          <td colspan="5" class="text-center text-muted">No medication plan added yet.</td>
                        </tr>
                      @endif
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        @if($medications->count() > 0)
          <div class="modal fade" id="viewAllMedicationModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
              <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                  <h5 class="modal-title">Medication Plan Details</h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                  @foreach($medications as $med)
                    <div class="border rounded-3 p-3 mb-3">
                      <div class="row g-3">
                        <div class="col-md-6">
                          <strong>Medication:</strong>
                          <p class="mb-0">{{ $med->medication }}</p>
                        </div>
                        <div class="col-md-6">
                          <strong>Dosage:</strong>
                          <p class="mb-0">{{ $med->dosage }}</p>
                        </div>
                        <div class="col-md-6">
                          <strong>Frequency:</strong>
                          <p class="mb-0">{{ $med->frequency }}</p>
                        </div>
                        <div class="col-md-6">
                          <strong>Start Date:</strong>
                          <p class="mb-0">{{ \Carbon\Carbon::parse($med->start_date)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6">
                          <strong>End Date:</strong>
                          <p class="mb-0">{{ \Carbon\Carbon::parse($med->end_date)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-12">
                          <strong>Special Instructions:</strong>
                          <p class="mb-0">{{ $med->instructions ?: 'None' }}</p>
                        </div>
                        <div class="col-md-6">
                          <strong>Prescribed On:</strong>
                          <p class="mb-0">{{ $med->created_at->format('d M, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                          <strong>Actions:</strong>
                          <div class="d-flex gap-2 mt-1">
                            @if($canManage)
                              <form id="delete-medication-form-{{ $med->id }}" action="{{ route('medications.destroy', $med->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-outline-danger btn-sm delete-btn" data-id="{{ $med->id }}">
                                  <i class="ri-delete-bin-line"></i>
                                </button>
                              </form>

                              <a href="{{ route('doctor.edit_medication', $med->id) }}" class="btn btn-info btn-sm">
                                <i class="ri-edit-line"></i>
                              </a>
                            @else
                              <button type="button" class="btn btn-secondary btn-sm" disabled
                                data-bs-toggle="tooltip" title="View only until your unit accepts this patient">
                                <i class="ri-lock-line"></i>
                              </button>
                            @endif
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
        @endif

        <div class="modal fade" id="delRow" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Confirm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                Are you sure you want to delete this report?
              </div>
              <div class="modal-footer">
                <div class="d-flex justify-content-end gap-2">
                  <button class="btn btn-outline-secondary" data-bs-dismiss="modal">No</button>
                  <button class="btn btn-danger" data-bs-dismiss="modal">Yes</button>
                </div>
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
  document.querySelectorAll('.discharge-type').forEach(select => {
    select.addEventListener('change', function () {
      const patientId = this.dataset.patient;
      const transferFields = document.getElementById('transfer_fields_' + patientId);
      transferFields.style.display = this.value === 'transfer' ? 'block' : 'none';
    });
  });

  document.querySelectorAll('.department_select').forEach(dept => {
    dept.addEventListener('change', function () {
      const patientId = this.dataset.patient;
      const unitSelect = document.querySelector('.unit_select[data-patient="' + patientId + '"]');
      const subSelect = document.querySelector('.sub_unit_select[data-patient="' + patientId + '"]');
      const subWrap = document.getElementById('sub_unit_wrapper_' + patientId);

      unitSelect.innerHTML = '<option value="">Select</option>';
      subSelect.innerHTML = '<option value="">Select</option>';
      subWrap.style.display = 'none';

      if (this.value) {
        fetch(`/get-units-doctor/${this.value}`)
          .then(res => res.json())
          .then(data => {
            data.forEach(unit => {
              let opt = document.createElement('option');
              opt.value = unit.id;
              opt.textContent = unit.name;
              unitSelect.appendChild(opt);
            });
          });
      }
    });
  });

  document.querySelectorAll('.unit_select').forEach(unit => {
    unit.addEventListener('change', function () {
      const patientId = this.dataset.patient;
      const subSelect = document.querySelector('.sub_unit_select[data-patient="' + patientId + '"]');
      const subWrap = document.getElementById('sub_unit_wrapper_' + patientId);

      subSelect.innerHTML = '<option value="">Select</option>';
      subWrap.style.display = 'none';

      if (this.value) {
        fetch(`/get-sub-units-doctor/${this.value}`)
          .then(res => res.json())
          .then(data => {
            if (data.length > 0) {
              subWrap.style.display = 'block';
              data.forEach(sub => {
                let opt = document.createElement('option');
                opt.value = sub.id;
                opt.textContent = sub.name;
                subSelect.appendChild(opt);
              });
            }
          });
      }
    });
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const deleteButtons = document.querySelectorAll('.delete-btn');

  deleteButtons.forEach(button => {
    button.addEventListener('click', function () {
      const medId = this.getAttribute('data-id');

      Swal.fire({
        title: 'Are you sure?',
        text: 'This will permanently delete the medication record.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('delete-medication-form-' + medId).submit();
        }
      });
    });
  });
});
</script>

<script>
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  });
</script>

@include('parklane.doctor.script')
