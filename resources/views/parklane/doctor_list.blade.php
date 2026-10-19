@include('parklane.head')

    <!-- Page wrapper starts -->
    <div class="page-wrapper">

      <!-- App header starts -->
      @include('parklane.header')
      <!-- App header ends -->

      <!-- Main container starts -->
      <div class="main-container">

        <!-- Sidebar wrapper starts -->
        @include('parklane.sidebar')
        <!-- Sidebar wrapper ends -->

        <!-- App container starts -->
        <div class="app-container">

          <!-- App hero header starts -->
          <div class="app-hero-header d-flex align-items-center">
            <!-- Breadcrumb starts -->
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
                <a href="{{ route('dashboard') }}">Home</a>
              </li>
              <li class="breadcrumb-item text-primary" aria-current="page">
                Doctor List
              </li>
            </ol>
            <!-- Breadcrumb ends -->
          </div>

          <!-- App body starts -->
          <div class="app-body">

            <div class="row gx-3">
              <div class="col-sm-12">
                <div class="card">
                  <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title">Doctors List</h5>
                    <a href="{{ route('add_doctor') }}" class="btn btn-primary ms-auto">Add Doctor</a>
                  </div>
                  <div class="card-body">
                    <div id="toast-container"></div>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

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

                    <div class="mb-3 text-end">
                      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#messageUnitModal">
                        Message Unit/Sub-Unit
                      </button>
                    </div>

                    <!-- Table starts -->
                    <div class="table-responsive">
                      <table id="basicExample" class="table truncate m-0 align-middle">
                          <thead>
                              <tr>
                                  <th>ID</th>
                                  <th>Doctor Name</th>
                                  <th>Department</th>
                                  <th>Unit</th>
                                  <th>Sub Unit</th>
                                  <th>Actions</th>
                              </tr>
                          </thead>
                          <tbody>
                              @forelse ($doctors as $doctor)
                                  <tr data-department="{{ $doctor->doctor_departments }}"
                                      data-unit="{{ $doctor->doctor_unit_id }}"
                                      data-subunit="{{ $doctor->doctor_sub_unit_id }}">
                                      <td>#{{ $doctor->id }}</td>
                                      <td>
                                        <img src="{{ $doctor->profile_image ? asset('storage/' . $doctor->profile_image) : asset('assets/images/user.png') }}" class="img-shadow img-2x rounded-5 me-1" alt="Doctor">
                                        {{ $doctor->full_name }}
                                      </td>
                                      <td>{{ $doctor->doctorDepartment?->name ?? 'N/A' }}</td>
                                      <td>{{ $doctor->doctorUnit?->name ?? 'N/A' }}</td>
                                      <td>{{ $doctor->subUnit?->name ?? 'N/A' }}</td>
                                      <td>
                                          <div class="d-inline-flex gap-1">
                                            <form id="delete-doctor-form-{{ $doctor->id }}" action="{{ route('doctors.destroy', $doctor->id) }}" method="POST" class="d-inline delete-doctor-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-outline-danger btn-sm delete-btn" data-id="{{ $doctor->id }}">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>

                                            <a href="{{ route('doctors.shifts', $doctor->id) }}" class="btn btn-outline-success btn-sm">
                                              <i class="ri-add-line"></i> Add Shift
                                            </a>

                                            <a href="{{ route('doctors.show', $doctor->id) }}" class="btn btn-outline-info btn-sm"
                                              data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View Profile">
                                              <i class="ri-eye-line"></i>
                                            </a>

                                            <button class="btn btn-outline-primary btn-sm"
                                                  data-bs-toggle="modal"
                                                  data-bs-target="#editDoctorDeptModal{{ $doctor->id }}">
                                              <i class="ri-edit-line"></i>
                                            </button>
                                          </div>
                                      </td>
                                  </tr>
                              @empty
                                  <tr>
                                      <td colspan="6" class="text-center text-muted py-4">No doctors found.</td>
                                  </tr>
                              @endforelse
                          </tbody>
                      </table>
                    </div>

                    <!-- Message Unit/Subunit Modal -->
                    <div class="modal fade" id="messageUnitModal" tabindex="-1">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                          <form action="{{ route('message.unit') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                              <h5 class="modal-title">Message Unit/Sub-Unit</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                              <div class="mb-3">
                                <label class="form-label">Department</label>
                                <select id="msg_department" name="department_id" class="form-select" required>
                                  <option value="">Select</option>
                                  @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                  @endforeach
                                </select>
                              </div>

                              <div class="mb-3">
                                <label class="form-label">Unit</label>
                                <select id="msg_unit" name="doctor_unit_id" class="form-select" required>
                                  <option value="">Select</option>
                                </select>
                              </div>

                              <div class="mb-3">
                                <label class="form-label">Sub Unit (optional)</label>
                                <select id="msg_subunit" name="doctor_sub_unit_id" class="form-select">
                                  <option value="">Select Sub Unit</option>
                                </select>
                              </div>

                              <div class="mb-3">
                                <label class="form-label">Message</label>
                                <textarea name="message" class="form-control" rows="4" required></textarea>
                              </div>
                            </div>

                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                              <button type="submit" class="btn btn-primary">Send</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>

                    <!-- Edit Department/Unit Modals -->
                    @foreach($doctors as $doctor)
                    <div class="modal fade" id="editDoctorDeptModal{{ $doctor->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Department & Unit - {{ $doctor->full_name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <form action="{{ route('doctor.update.department') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                                    <div class="modal-body">

                                        <div class="mb-3">
                                            <label class="form-label">Department</label>
                                            <select name="doctor_department_id" class="form-select edit-department" data-doctor-id="{{ $doctor->id }}" required>
                                                <option value="">Select Department</option>
                                                @foreach($departments as $department)
                                                    <option value="{{ $department->id }}"
                                                        {{ $doctor->doctor_departments == $department->id ? 'selected' : '' }}>
                                                        {{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Unit</label>
                                            <select name="doctor_unit_id" class="form-select edit-unit" data-doctor-id="{{ $doctor->id }}" required>
                                                <option value="">Select Unit</option>
                                                @foreach($doctorUnits->where('doctor_department_id', $doctor->doctor_departments) as $unit)
                                                    <option value="{{ $unit->id }}"
                                                        {{ $doctor->doctor_unit_id == $unit->id ? 'selected' : '' }}>
                                                        {{ $unit->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Sub-Unit</label>
                                            <select name="doctor_sub_unit_id" class="form-select edit-subunit" data-doctor-id="{{ $doctor->id }}">
                                                <option value="">Select Sub-Unit</option>
                                                @foreach($doctorSubUnits->where('doctor_unit_id', $doctor->doctor_unit_id) as $sub)
                                                    <option value="{{ $sub->id }}"
                                                        {{ $doctor->doctor_sub_unit_id == $sub->id ? 'selected' : '' }}>
                                                        {{ $sub->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Modal Delete Row (unused placeholder kept for parity with original) -->
                    <div class="modal fade" id="delRow" tabindex="-1" aria-labelledby="delRowLabel" aria-hidden="true">
                      <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="delRowLabel">Confirm</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            Are you sure you want to delete the doctor from list?
                          </div>
                          <div class="modal-footer">
                            <div class="d-flex justify-content-end gap-2">
                              <button class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">No</button>
                              <button class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Yes</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- App body ends -->

          <!-- App footer starts -->
          <div class="app-footer bg-white">
            <span>© 2024</span>
          </div>
          <!-- App footer ends -->

        </div>
        <!-- App container ends -->

      </div>
      <!-- Main container ends -->

    </div>
    <!-- Page wrapper ends -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // ---- Delete confirmation ----
        document.querySelectorAll('.delete-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                const doctorId = this.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
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

        // ---- Reusable department -> unit -> sub-unit cascade ----
        function wireDeptUnitCascade(deptEl, unitEl, subEl) {
            if (!deptEl || !unitEl || !subEl) return;

            deptEl.addEventListener('change', function () {
                unitEl.innerHTML = '<option value="">Select Unit</option>';
                subEl.innerHTML = '<option value="">Select Sub Unit</option>';
                if (!this.value) return;

                fetch(`/get-units/${this.value}`)
                    .then(res => res.json())
                    .then(units => {
                        units.forEach(unit => {
                            unitEl.appendChild(new Option(unit.name, unit.id));
                        });
                    });
            });

            unitEl.addEventListener('change', function () {
                subEl.innerHTML = '<option value="">Select Sub Unit</option>';
                if (!this.value) return;

                fetch(`/get-sub-units/${this.value}`)
                    .then(res => res.json())
                    .then(subUnits => {
                        subUnits.forEach(sub => {
                            subEl.appendChild(new Option(sub.name, sub.id));
                        });
                    });
            });
        }

        // Filters row
        wireDeptUnitCascade(
            document.getElementById('filter_department'),
            document.getElementById('filter_unit'),
            document.getElementById('filter_subunit')
        );

        // Message Unit/Sub-Unit modal
        wireDeptUnitCascade(
            document.getElementById('msg_department'),
            document.getElementById('msg_unit'),
            document.getElementById('msg_subunit')
        );

        // Edit Department/Unit modals — one per doctor row
        document.querySelectorAll('.edit-department').forEach(function (deptSelect) {
            const doctorId = deptSelect.getAttribute('data-doctor-id');
            const unitSelect = document.querySelector(`.edit-unit[data-doctor-id="${doctorId}"]`);
            const subSelect = document.querySelector(`.edit-subunit[data-doctor-id="${doctorId}"]`);
            wireDeptUnitCascade(deptSelect, unitSelect, subSelect);
        });

        // ---- Client-side row filtering ----
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
                const matchSub  = !sub || sub === rowSub;

                row.style.display = (matchDept && matchUnit && matchSub) ? '' : 'none';
            });
        }

        if (deptFilter) deptFilter.addEventListener('change', applyFilter);
        if (unitFilter) unitFilter.addEventListener('change', applyFilter);
        if (subFilter) subFilter.addEventListener('change', applyFilter);

    });
    </script>

@include('parklane.script')