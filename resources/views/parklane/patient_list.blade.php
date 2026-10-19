@include('parklane.head')
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
.dz-message { color: #495057; }
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
.dz-button:hover { background-color: #0056b3; }
h5 { font-size: 16px; font-weight: normal; margin-top: 20px; }
@media (max-width: 576px) {
    #dropzone { padding: 20px; }
    .dz-button { font-size: 14px; padding: 10px 25px; }
    h5 { font-size: 14px; }
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
.form-label { font-weight: 600; font-size: 1rem; color: #495057; }
.mb-3 { margin-bottom: 1rem; }
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
                    <h5 class="card-title">Patients List</h5>
                    <a href="add-patient.html" class="btn btn-primary ms-auto">Add Patient</a>
                  </div>
                  <div class="card-body">

                    <!-- Filters -->
                    <div class="row g-2 mb-3">
                      <div class="col-md-4">
                        <label class="form-label">Department</label>
                        <select id="filter_department" class="form-select">
                          <option value="">All</option>
                          @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                          @endforeach
                        </select>
                      </div>

                      <div class="col-md-4">
                        <label class="form-label">Unit</label>
                        <select id="filter_unit" class="form-select">
                          <option value="">All</option>
                        </select>
                      </div>

                      <div class="col-md-4">
                        <label class="form-label">Sub Unit</label>
                        <select id="filter_subunit" class="form-select">
                          <option value="">All</option>
                        </select>
                      </div>
                    </div>

                    <div class="table-responsive">
                      <table id="basicExample" class="table truncate m-0 align-middle">
                        <thead>
                          <tr>
                            <th>No.</th>
                            <th>Patient Name</th>
                            <th>Gender</th>
                            <th>Doc Unit</th>
                            <th>Doc Sub Unit</th>
                            <th>Mobile</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($patients as $patient)
                            <tr data-department="{{ $patient->doctorUnit->doctor_department_id ?? '' }}"
                                data-unit="{{ $patient->doctor_unit_id }}"
                                data-subunit="{{ $patient->doctor_sub_unit_id }}">
                              <td>{{ $patient->pt_number }}</td>
                              <td>{{ $patient->full_name }}</td>
                              <td>
                                @if($patient->gender == 'male')
                                    <span class="badge bg-danger text-white">{{ ucfirst($patient->gender) }}</span>
                                @elseif($patient->gender == 'female')
                                    <span class="badge bg-success text-white">{{ ucfirst($patient->gender) }}</span>
                                @else
                                    <span class="badge bg-secondary text-white">{{ ucfirst($patient->gender) }}</span>
                                @endif
                              </td>
                              <td>{{ $patient->doctorUnit->name ?? 'N/A' }}</td>
                              <td>{{ $patient->doctorSubUnit->name ?? 'N/A' }}</td>
                              <td>{{ $patient->mobile_number }}</td>
                              <td>
                                <div class="d-inline-flex gap-1">
                                  <form id="delete-doctor-form-{{ $patient->id }}" action="{{ route('patient.destroy', $patient->id) }}" method="POST" class="d-inline delete-doctor-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-outline-danger btn-sm delete-btn" data-id="{{ $patient->id }}">
                                      <i class="ri-delete-bin-line"></i>
                                    </button>
                                  </form>
                                  <a href="{{ route('edit_patients', $patient->id) }}" class="btn btn-outline-success btn-sm">
                                    <i class="ri-edit-box-line"></i>
                                  </a>
                                  <a href="{{ route('show_patient', $patient->id) }}" class="btn btn-outline-info btn-sm">
                                    <i class="ri-eye-line"></i>
                                  </a>
                                </div>
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>

                      <div class="mt-4">
                        {{ $patients->links('pagination::bootstrap-5') }}
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="app-footer bg-white">
            {{-- <span>©  2024</span> --}}
          </div>

        </div>
      </div>
    </div>

{{-- <script>
document.addEventListener('DOMContentLoaded', function () {
    const deptFilter = document.getElementById('filter_department');
    const unitFilter = document.getElementById('filter_unit');
    const subFilter  = document.getElementById('filter_subunit');
    const rows = document.querySelectorAll('#basicExample tbody tr');

    function applyFilter() {
        const dept = deptFilter.value;
        const unit = unitFilter.value;
        const sub  = subFilter.value;

        rows.forEach(row => {
            const rowDept = row.getAttribute('data-department');
            const rowUnit = row.getAttribute('data-unit');
            const rowSub  = row.getAttribute('data-subunit');

            const matchDept = !dept || dept === rowDept;
            const matchUnit = !unit || unit === rowUnit;
            const matchSub  = !sub  || sub  === rowSub;

            row.style.display = (matchDept && matchUnit && matchSub) ? '' : 'none';
        });
    }

    function loadUnits(deptId) {
        unitFilter.innerHTML = '<option value="">All</option>';
        subFilter.innerHTML = '<option value="">All</option>';
        if (!deptId) return;

        fetch(`/get-units/${deptId}`)
            .then(res => res.json())
            .then(data => {
                data.forEach(unit => {
                    const option = document.createElement('option');
                    option.value = unit.id;
                    option.textContent = unit.name;
                    unitFilter.appendChild(option);
                });
            });
    }

    function loadSubUnits(unitId) {
        subFilter.innerHTML = '<option value="">All</option>';
        if (!unitId) return;

        fetch(`/get-sub-units/${unitId}`)
            .then(res => res.json())
            .then(data => {
                data.forEach(sub => {
                    const option = document.createElement('option');
                    option.value = sub.id;
                    option.textContent = sub.name;
                    subFilter.appendChild(option);
                });
            });
    }

    deptFilter.addEventListener('change', function () {
        loadUnits(this.value);
        applyFilter();
    });

    unitFilter.addEventListener('change', function () {
        loadSubUnits(this.value);
        applyFilter();
    });

    subFilter.addEventListener('change', applyFilter);
});
</script> --}}


<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Delete confirmation ─────────────────────────────────────────────────
    document.querySelectorAll('.delete-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id   = this.getAttribute('data-id');
            const form = document.getElementById('delete-doctor-form-' + id);

            if (confirm('Are you sure you want to delete this patient? This action cannot be undone.')) {
                form.submit();
            }
        });
    });

    // ── Filter logic (your existing code) ──────────────────────────────────
    const deptFilter = document.getElementById('filter_department');
    const unitFilter = document.getElementById('filter_unit');
    const subFilter  = document.getElementById('filter_subunit');
    const rows       = document.querySelectorAll('#basicExample tbody tr');

    function applyFilter() {
        const dept = deptFilter.value;
        const unit = unitFilter.value;
        const sub  = subFilter.value;

        rows.forEach(row => {
            const rowDept = row.getAttribute('data-department');
            const rowUnit = row.getAttribute('data-unit');
            const rowSub  = row.getAttribute('data-subunit');

            const matchDept = !dept || dept === rowDept;
            const matchUnit = !unit || unit === rowUnit;
            const matchSub  = !sub  || sub  === rowSub;

            row.style.display = (matchDept && matchUnit && matchSub) ? '' : 'none';
        });
    }

    function loadUnits(deptId) {
        unitFilter.innerHTML = '<option value="">All</option>';
        subFilter.innerHTML  = '<option value="">All</option>';
        if (!deptId) return;

        fetch(`/get-units/${deptId}`)
            .then(res => res.json())
            .then(data => {
                data.forEach(unit => {
                    const opt = document.createElement('option');
                    opt.value       = unit.id;
                    opt.textContent = unit.name;
                    unitFilter.appendChild(opt);
                });
            });
    }

    function loadSubUnits(unitId) {
        subFilter.innerHTML = '<option value="">All</option>';
        if (!unitId) return;

        fetch(`/get-sub-units/${unitId}`)
            .then(res => res.json())
            .then(data => {
                data.forEach(sub => {
                    const opt = document.createElement('option');
                    opt.value       = sub.id;
                    opt.textContent = sub.name;
                    subFilter.appendChild(opt);
                });
            });
    }

    deptFilter.addEventListener('change', function () {
        loadUnits(this.value);
        applyFilter();
    });

    unitFilter.addEventListener('change', function () {
        loadSubUnits(this.value);
        applyFilter();
    });

    subFilter.addEventListener('change', applyFilter);
});
</script> 

@include('parklane.script')
