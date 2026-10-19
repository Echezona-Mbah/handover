@include('parklane.head')
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

  .assignment-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border-radius: 999px;
    padding: 6px 14px;
    font-size: 13px;
    font-weight: 600;
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

  @include('parklane.header')

  <div class="main-container">

    @include('parklane.sidebar')

    <div class="app-container">

      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{ route('dashboard') }}">Home</a>
          </li>
          <li class="breadcrumb-item">
            <a href="{{ route('patient_list') }}">Patients</a>
          </li>
          <li class="breadcrumb-item text-primary" aria-current="page">
            {{ $patient->full_name }}
          </li>
        </ol>
      </div>

      <div class="app-body">

        @php
          $urgency = strtolower($patient->urgency_status ?? '');
          $age = $patient->dob ? \Carbon\Carbon::parse($patient->dob)->age : 'N/A';
        @endphp

        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

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

        <!-- Hero -->
        <div class="card patient-hero mb-4">
          <div class="card-body p-4 p-lg-5">
            <div class="row align-items-center g-4">
              <div class="col-lg-8">
                <p class="mb-2 opacity-75">Patient Overview</p>
                <h2 class="mb-2 text-white">{{ $patient->full_name }}</h2>
                <p class="mb-3 opacity-75">
                  Patient No: {{ $patient->patient_number ?? 'N/A' }} |
                  Ward: {{ $patient->ward?->name ?? 'N/A' }}
                </p>

                <div class="mb-2">
                  <span class="info-chip"><i class="ri-user-line"></i> {{ ucfirst($patient->gender ?? 'n/a') }}</span>
                  <span class="info-chip"><i class="ri-calendar-line"></i> {{ $age }} years</span>
                  <span class="info-chip"><i class="ri-heart-pulse-line"></i> {{ ucfirst($patient->urgency_status ?? 'n/a') }}</span>
                  <span class="info-chip"><i class="ri-phone-line"></i> {{ $patient->mobile_number ?? 'N/A' }}</span>
                </div>
              </div>

              <div class="col-lg-4">
                <div class="d-flex justify-content-lg-end gap-2 flex-wrap">
                  <a href="{{ route('edit_patients', $patient->id) }}" class="btn btn-light btn-lg">
                    <i class="ri-edit-2-line"></i> Edit
                  </a>
                  <a href="{{ route('admin.patients.assign', $patient->id) }}" class="btn btn-outline-light btn-lg">
                    <i class="ri-stethoscope-line"></i> Assign Doctor
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Department / Unit / Sub Unit + Assignment row -->
        <div class="row gx-3 mb-4">
          <div class="col-xl-3 col-sm-6">
            <div class="card patient-stat-card h-100">
              <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-box bg-primary text-white">
                  <i class="ri-building-line fs-3"></i>
                </div>
                <div>
                  <h6 class="mb-1">Department</h6>
                  <h5 class="mb-0">{{ $patient->doctorUnit?->doctorDepartment?->name ?? 'Not Assigned' }}</h5>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-sm-6">
            <div class="card patient-stat-card h-100">
              <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-box bg-info text-white">
                  <i class="ri-git-branch-line fs-3"></i>
                </div>
                <div>
                  <h6 class="mb-1">Unit</h6>
                  <h5 class="mb-0">{{ $patient->doctorUnit?->name ?? 'Not Assigned' }}</h5>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-sm-6">
            <div class="card patient-stat-card h-100">
              <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-box bg-secondary text-white">
                  <i class="ri-node-tree fs-3"></i>
                </div>
                <div>
                  <h6 class="mb-1">Sub Unit</h6>
                  <h5 class="mb-0">{{ $patient->doctorSubUnit?->name ?? 'None' }}</h5>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-sm-6">
            <div class="card patient-stat-card h-100">
              <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-box bg-success text-white">
                  <i class="ri-hospital-line fs-3"></i>
                </div>
                <div>
                  <h6 class="mb-1">Ward</h6>
                  <h5 class="mb-0">{{ $patient->ward?->name ?? 'N/A' }}</h5>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Assigned Doctor / Dentist -->
        <div class="row gx-3 mb-4">
          <div class="col-md-6">
            <div class="card detail-card h-100">
              <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                  <div class="icon-box bg-primary text-white">
                    <i class="ri-stethoscope-line fs-3"></i>
                  </div>
                  <div>
                    <h6 class="mb-1">Assigned Doctor</h6>
                    <h5 class="mb-0">{{ $patient->doctorById?->full_name ?? 'Not Assigned' }}</h5>
                  </div>
                </div>
                <a href="{{ route('admin.patients.assign', $patient->id) }}" class="btn btn-sm btn-outline-primary">
                  {{ $patient->doctorById ? 'Reassign' : 'Assign' }}
                </a>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="card detail-card h-100">
              <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                  <div class="icon-box bg-info text-white">
                    <i class="ri-tooth-line fs-3"></i>
                  </div>
                  <div>
                    <h6 class="mb-1">Assigned Dentist</h6>
                    <h5 class="mb-0">Not Assigned</h5>
                  </div>
                </div>
                <a href="{{ route('admin.patients.assignDentist', $patient->id) }}" class="btn btn-sm btn-outline-info">
                  Assign
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Full patient details -->
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
                      <small>Patient Number</small>
                      <div class="fw-semibold">{{ $patient->patient_number ?? 'N/A' }}</div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="detail-tile">
                      <small>Hospital</small>
                      <div class="fw-semibold">{{ $patient->hospital?->name ?? 'N/A' }}</div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="detail-tile">
                      <small>Registered On</small>
                      <div class="fw-semibold">{{ $patient->created_at ? $patient->created_at->format('d M Y') : 'N/A' }}</div>
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
                <h5 class="card-title">Care Assignment Summary</h5>
              </div>
              <div class="card-body">
                <div class="detail-tile mb-3">
                  <small>Department</small>
                  <div class="fw-semibold">{{ $patient->doctorUnit?->doctorDepartment?->name ?? 'Not Assigned' }}</div>
                </div>
                <div class="detail-tile mb-3">
                  <small>Unit</small>
                  <div class="fw-semibold">{{ $patient->doctorUnit?->name ?? 'Not Assigned' }}</div>
                </div>
                <div class="detail-tile mb-3">
                  <small>Sub Unit</small>
                  <div class="fw-semibold">{{ $patient->doctorSubUnit?->name ?? 'None' }}</div>
                </div>
                <div class="detail-tile mb-3">
                  <small>Assigned Doctor</small>
                  <div class="fw-semibold">{{ $patient->doctorById?->full_name ?? 'Not Assigned' }}</div>
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
          <div class="col-12">
            <div class="card section-card mb-3">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Actions</h5>
              </div>
              <div class="card-body d-flex flex-wrap gap-2">
                <a href="{{ route('edit_patients', $patient->id) }}" class="btn btn-primary">
                  <i class="ri-edit-2-line"></i> Edit Patient
                </a>
                <a href="{{ route('admin.patients.assign', $patient->id) }}" class="btn btn-outline-primary">
                  <i class="ri-stethoscope-line"></i> Assign / Reassign Doctor
                </a>
                <a href="{{ route('admin.patients.assignDentist', $patient->id) }}" class="btn btn-outline-info">
                  <i class="ri-tooth-line"></i> Assign / Reassign Dentist
                </a>
                <form action="{{ route('patient.destroy', $patient->id) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Are you sure you want to delete this patient? This cannot be undone.');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-outline-danger">
                    <i class="ri-delete-bin-line"></i> Delete Patient
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>


<!-- Doctor Visits / Handover Notes + Medication Plan -->
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
              @forelse($handoverNotes as $handoverNote)
                <tr>
                  <td>{{ $handoverNote->doctor?->full_name ?? 'N/A' }}</td>
                  <td>{{ $handoverNote->created_at->format('d/m/Y') }}</td>
                  <td>{{ $handoverNote->doctor?->doctorDepartment?->name ?? 'N/A' }}</td>
                  <td>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                      data-bs-target="#viewHandoverNoteModal{{ $handoverNote->id }}">
                      View
                    </button>
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
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
              @empty
                <tr>
                  <td colspan="4" class="text-center text-muted">No handover notes yet.</td>
                </tr>
              @endforelse
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
      </div>

      <div class="app-footer bg-white">
        {{-- <span>© 2024</span> --}}
      </div>

    </div>
  </div>
</div>

@include('parklane.script')