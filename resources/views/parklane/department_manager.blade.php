  @include('parklane.head')

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
            <li class="breadcrumb-item text-primary" aria-current="page">
              Department Manager
            </li>
          </ol>
        </div>

        <div class="app-body">

          {{-- Flash messages --}}
          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
              <i class="ri-checkbox-circle-line me-1"></i> {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif
          @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
              <i class="ri-error-warning-line me-1"></i> {{ $errors->first() }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          <div class="row gx-3">

            {{-- ── Column 1: Add Forms ─────────────────────────────────────── --}}
            <div class="col-lg-4">

              {{-- Add Department --}}
              <div class="card mb-4">
                <div class="card-header">
                  <h5 class="card-title mb-0">
                    <i class="ri-building-2-line me-2 text-primary"></i> Add Department
                  </h5>
                </div>
                <div class="card-body">
                  <form method="POST" action="{{ route('departments.store') }}">
                    @csrf
                    <div class="mb-3">
                      <label class="form-label">Department Name <span class="text-danger">*</span></label>
                      <input type="text" name="name" class="form-control" placeholder="e.g. Cardiology" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Description</label>
                      <textarea name="description" class="form-control" rows="2" placeholder="Optional description">{{ old('description') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                      <i class="ri-add-line me-1"></i> Add Department
                    </button>
                  </form>
                </div>
              </div>

              {{-- Add Unit --}}
              <div class="card mb-4">
                <div class="card-header">
                  <h5 class="card-title mb-0">
                    <i class="ri-git-branch-line me-2 text-success"></i> Add Unit
                  </h5>
                </div>
                <div class="card-body">
                  <form method="POST" action="{{ route('units.store') }}">
                    @csrf
                    <div class="mb-3">
                      <label class="form-label">Department <span class="text-danger">*</span></label>
                      <select name="doctor_department_id" id="dept_for_unit" class="form-select" required>
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                          <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Unit Name <span class="text-danger">*</span></label>
                      <input type="text" name="name" class="form-control" placeholder="e.g. ICU" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                      <i class="ri-add-line me-1"></i> Add Unit
                    </button>
                  </form>
                </div>
              </div>

              {{-- Add Sub Unit --}}
              <div class="card mb-4">
                <div class="card-header">
                  <h5 class="card-title mb-0">
                    <i class="ri-node-tree me-2 text-warning"></i> Add Sub-Unit
                  </h5>
                </div>
                <div class="card-body">
                  <form method="POST" action="{{ route('subunits.store') }}">
                    @csrf
                    <div class="mb-3">
                      <label class="form-label">Department <span class="text-danger">*</span></label>
                      <select id="dept_for_subunit" class="form-select" required>
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                          <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Unit <span class="text-danger">*</span></label>
                      <select name="doctor_unit_id" id="unit_for_subunit" class="form-select" required>
                        <option value="">Select Unit first</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Sub-Unit Name <span class="text-danger">*</span></label>
                      <input type="text" name="name" class="form-control" placeholder="e.g. Ward A" required>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">
                      <i class="ri-add-line me-1"></i> Add Sub-Unit
                    </button>
                  </form>
                </div>
              </div>


              {{-- Add Ward --}}
              <div class="card mb-4">
                <div class="card-header">
                  <h5 class="card-title mb-0">
                    <i class="ri-hotel-bed-line me-2 text-danger"></i> Add Ward
                  </h5>
                </div>
                <div class="card-body">
                  <form method="POST" action="{{ route('wards.store') }}">
                    @csrf
                    <div class="mb-3">
                      <label class="form-label">Assignment name <span class="text-danger">*</span></label>
                      <input type="text" name="name" class="form-control" placeholder="e.g. WD 1, Eye Clinic, Main Theatre" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Type <span class="text-danger">*</span></label>
                      <select name="category" class="form-select" required>
                        @foreach(['Ward', 'Clinic', 'Theatre', 'Unit', 'Other'] as $category)
                          <option value="{{ $category }}" @selected(old('category', 'Ward') === $category)>{{ $category }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Linen colour</label>
                      <input type="text" name="linen_color" class="form-control" placeholder="e.g. Sea Green or Pink / Blue" value="{{ old('linen_color') }}">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Description</label>
                      <textarea name="description" class="form-control" rows="2" placeholder="Optional description">{{ old('description') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">
                      <i class="ri-add-line me-1"></i> Add Ward
                    </button>
                  </form>
                </div>
              </div>

            </div>

            {{-- ── Column 2: Existing Records ──────────────────────────────── --}}
            <div class="col-lg-8">

              {{-- Search --}}
              <div class="mb-3">
                <div class="input-group">
                  <span class="input-group-text bg-white border-end-0">
                    <i class="ri-search-line text-muted"></i>
                  </span>
                  <input
                    type="text"
                    id="deptUnitSearch"
                    class="form-control border-start-0"
                    placeholder="Search department, unit, or sub-unit..."
                  >
                  <button type="button" id="clearSearch" class="btn btn-outline-secondary" style="display:none;">
                    <i class="ri-close-line"></i>
                  </button>
                </div>
                <small class="text-muted" id="searchResultCount"></small>
              </div>

              {{-- Departments Table --}}
              <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                  <h5 class="card-title mb-0">
                    <i class="ri-building-2-line me-2 text-primary"></i> Departments
                  </h5>
                  <span class="badge bg-primary">{{ $departments->count() }}</span>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                      <thead class="table-light">
                        <tr>
                          <th>#</th>
                          <th>Name</th>
                          <th>Description</th>
                          <th>Doctors</th>
                          <th>Units</th>
                          <th class="text-center">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($departments as $dept)
                          <tr data-search="{{ strtolower($dept->name . ' ' . ($dept->description ?? '')) }}">
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $dept->name }}</strong></td>
                            <td class="text-muted small">{{ $dept->description ?? '—' }}</td>
                            <td><span class="badge bg-info-subtle text-info">{{ $dept->doctors_count }}</span></td>
                            <td><span class="badge bg-success-subtle text-success">{{ $dept->units_count }}</span></td>
                            <td class="text-center">
  <div class="d-inline-flex gap-1">
    <button type="button" class="btn btn-outline-primary btn-sm"
      data-bs-toggle="modal"
      data-bs-target="#editDepartmentModal"
      data-id="{{ $dept->id }}"
      data-name="{{ $dept->name }}"
      data-description="{{ $dept->description }}">
      <i class="ri-edit-line"></i>
    </button>

    <form method="POST" action="{{ route('departments.destroy', $dept->id) }}"
      onsubmit="return confirm('Delete department {{ addslashes($dept->name) }}? This cannot be undone.')">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-outline-danger btn-sm">
        <i class="ri-delete-bin-line"></i>
      </button>
    </form>
  </div>
</td>
                          </tr>
                          
                        @empty
                          <tr>
                            <td colspan="6" class="text-center text-muted py-4">No departments yet.</td>
                          </tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              {{-- Units & Sub-Units Tree --}}
              <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                  <h5 class="card-title mb-0">
                    <i class="ri-git-branch-line me-2 text-success"></i> Units & Sub-Units
                  </h5>
                  <span class="badge bg-success">{{ $units->count() }} units</span>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                      <thead class="table-light">
                        <tr>
                          <th>Unit</th>
                          <th>Department</th>
                          <th>Sub-Units</th>
                          <th class="text-center">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($units as $unit)
                          @php
                            $deptName = optional(\App\Models\DoctorDepartment::find($unit->doctor_department_id))->name ?? '';
                            $subNames = $unit->subUnits->pluck('name')->implode(' ');
                          @endphp
                          <tr data-search="{{ strtolower($unit->name . ' ' . $deptName . ' ' . $subNames) }}">
                            <td>
                              <i class="ri-git-branch-line text-success me-1"></i>
                              <strong>{{ $unit->name }}</strong>
                            </td>
                            <td>
                              <span class="badge bg-primary-subtle text-primary">
                                {{ $deptName ?: '—' }}
                              </span>
                            </td>
                            <td>
                            @if($unit->subUnits->count())
                              <div class="d-flex flex-wrap gap-1">
                                @foreach($unit->subUnits as $sub)
                                 <span class="badge bg-warning-subtle text-warning d-inline-flex align-items-center gap-1">
                                  {{ $sub->name }}

                                  <button type="button" class="btn p-0 border-0 bg-transparent text-primary ms-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editSubUnitModal"
                                    data-id="{{ $sub->id }}"
                                    data-name="{{ $sub->name }}"
                                    data-unit-id="{{ $unit->id }}"
                                    style="line-height:1;">
                                    <i class="ri-edit-line" style="font-size:11px;"></i>
                                  </button>

                                  <form method="POST" action="{{ route('subunits.destroy', $sub->id) }}"
                                    onsubmit="return confirm('Delete sub-unit {{ addslashes($sub->name) }}?')"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn p-0 border-0 bg-transparent text-danger ms-1" style="line-height:1;">
                                      <i class="ri-close-line" style="font-size:11px;"></i>
                                    </button>
                                  </form>
                                </span>
                                @endforeach
                              </div>
                            @else
                              <span class="text-muted small">No sub-units</span>
                            @endif
          
                            </td>
                           <td class="text-center">
                              <div class="d-inline-flex gap-1">
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                  data-bs-toggle="modal"
                                  data-bs-target="#editUnitModal"
                                  data-id="{{ $unit->id }}"
                                  data-name="{{ $unit->name }}"
                                  data-department-id="{{ $unit->doctor_department_id }}">
                                  <i class="ri-edit-line"></i>
                                </button>

                                <form method="POST" action="{{ route('units.destroy', $unit->id) }}"
                                  onsubmit="return confirm('Delete unit {{ addslashes($unit->name) }} and all its sub-units?')">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="ri-delete-bin-line"></i>
                                  </button>
                                </form>
                              </div>
                            </td> 
                          </tr>
                        @empty
                          <tr>
                            <td colspan="4" class="text-center text-muted py-4">No units yet.</td>
                          </tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>


              {{-- Wards Table --}}
              <div class="card mt-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                  <h5 class="card-title mb-0">
                    <i class="ri-hotel-bed-line me-2 text-danger"></i> Wards
                  </h5>
                  <span class="badge bg-danger">{{ $wards->count() }} wards</span>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                      <thead class="table-light">
                        <tr>
                          <th>#</th>
                          <th>Assignment</th>
                          <th>Linen colour</th>
                          <th>Description</th>
                          <th class="text-center">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($wards as $ward)
                          <tr data-search="{{ strtolower($ward->name . ' ' . $ward->category . ' ' . $ward->linen_color . ' ' . ($ward->description ?? '')) }}">
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $ward->name }}</strong><br><small class="text-muted">{{ $ward->category }}</small></td>
                            <td>{{ $ward->linen_color ?? '—' }}</td>
                            <td class="text-muted small">{{ $ward->description ?? '—' }}</td>
                            <td class="text-center">
                              <div class="d-inline-flex gap-1">

                                {{-- Edit button --}}
                                <button type="button"
                                  class="btn btn-outline-primary btn-sm"
                                  data-bs-toggle="modal"
                                  data-bs-target="#editWardModal"
                                  data-id="{{ $ward->id }}"
                                  data-name="{{ $ward->name }}"
                                  data-description="{{ $ward->description }}"
                                  data-category="{{ $ward->category }}"
                                  data-linen-color="{{ $ward->linen_color }}">
                                  <i class="ri-edit-line"></i>
                                </button>

                                {{-- Delete button --}}
                                <form method="POST" action="{{ route('wards.destroy', $ward->id) }}"
                                  onsubmit="return confirm('Delete ward {{ addslashes($ward->name) }}?')">
                                  @csrf @method('DELETE')
                                  <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="ri-delete-bin-line"></i>
                                  </button>
                                </form>
                              </div>
                            </td>
                          </tr>
                        @empty
                          <tr>
                            <td colspan="6" class="text-center text-muted py-4">No assignments yet.</td>
                          </tr>
                        @endforelse
                      </tbody>
                    </table>
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


{{-- Edit Department Modal --}}
<div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;">
      <form method="POST" id="editDepartmentForm" action="">
        @csrf
        @method('PUT')

        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold">
            <i class="ri-building-2-line me-2 text-primary"></i> Edit Department
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body pt-3">
          <div class="mb-3">
            <label class="form-label">Department Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="editDepartmentName" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" id="editDepartmentDescription" class="form-control" rows="3"></textarea>
          </div>
        </div>

        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary px-4">
            <i class="ri-save-line me-1"></i> Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Edit Unit Modal --}}
<div class="modal fade" id="editUnitModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;">
      <form method="POST" id="editUnitForm" action="">
        @csrf
        @method('PUT')

        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold">
            <i class="ri-git-branch-line me-2 text-success"></i> Edit Unit
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body pt-3">
          <div class="mb-3">
            <label class="form-label">Department <span class="text-danger">*</span></label>
            <select name="doctor_department_id" id="editUnitDepartment" class="form-select" required>
              <option value="">Select Department</option>
              @foreach($departments as $dept)
                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Unit Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="editUnitName" class="form-control" required>
          </div>
        </div>

        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary px-4">
            <i class="ri-save-line me-1"></i> Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Edit Sub-Unit Modal --}}
<div class="modal fade" id="editSubUnitModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;">
      <form method="POST" id="editSubUnitForm" action="">
        @csrf
        @method('PUT')

        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold">
            <i class="ri-node-tree me-2 text-warning"></i> Edit Sub-Unit
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body pt-3">
          <div class="mb-3">
            <label class="form-label">Unit <span class="text-danger">*</span></label>
            <select name="doctor_unit_id" id="editSubUnitUnit" class="form-select" required>
              <option value="">Select Unit</option>
              @foreach($units as $unit)
                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Sub-Unit Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="editSubUnitName" class="form-control" required>
          </div>
        </div>

        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary px-4">
            <i class="ri-save-line me-1"></i> Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>



{{-- ── Edit Ward Modal ──────────────────────────────────────────────────────── --}}
<div class="modal fade" id="editWardModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px;">
      <form method="POST" id="editWardForm" action="">
        @csrf @method('PUT')

        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold">
            <i class="ri-hotel-bed-line me-2 text-danger"></i> Edit Ward
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body pt-3">
          <div class="mb-3">
            <label class="form-label">Assignment name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="editWardName"
              class="form-control" required placeholder="e.g. Female Medical Ward">
          </div>
          <div class="mb-3">
            <label class="form-label">Type <span class="text-danger">*</span></label>
            <select name="category" id="editWardCategory" class="form-select" required>
              @foreach(['Ward', 'Clinic', 'Theatre', 'Unit', 'Other'] as $category)
                <option value="{{ $category }}">{{ $category }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Linen colour</label>
            <input type="text" name="linen_color" id="editWardLinenColor" class="form-control" placeholder="e.g. Sea Green">
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" id="editWardDescription"
              class="form-control" rows="3" placeholder="Optional description"></textarea>
          </div>
        </div>

        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary px-4">
            <i class="ri-save-line me-1"></i> Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>  









  <script>
  // ── Load units when department is selected for sub-unit form ────────────────
  document.getElementById('dept_for_subunit')?.addEventListener('change', function () {
    const deptId  = this.value;
    const unitSel = document.getElementById('unit_for_subunit');

    unitSel.innerHTML = '<option value="">Loading...</option>';

    if (!deptId) {
      unitSel.innerHTML = '<option value="">Select Unit first</option>';
      return;
    }

    fetch(`/get-units/${deptId}`)
      .then(res => res.json())
      .then(data => {
        unitSel.innerHTML = '<option value="">Select Unit</option>';
        data.forEach(unit => {
          const opt = document.createElement('option');
          opt.value       = unit.id;
          opt.textContent = unit.name;
          unitSel.appendChild(opt);
        });
        if (!data.length) {
          unitSel.innerHTML = '<option value="">No units in this department</option>';
        }
      })
      .catch(() => {
        unitSel.innerHTML = '<option value="">Error loading units</option>';
      });
  });

  // ── Search across Departments + Units/Sub-Units tables ──────────────────────
  document.addEventListener('DOMContentLoaded', function () {
    const searchInput    = document.getElementById('deptUnitSearch');
    const clearBtn       = document.getElementById('clearSearch');
    const resultCount    = document.getElementById('searchResultCount');
    const searchableRows = Array.from(document.querySelectorAll('tr[data-search]'));

    function runSearch() {
      const query = searchInput.value.trim().toLowerCase();
      clearBtn.style.display = query ? 'inline-block' : 'none';

      if (!query) {
        searchableRows.forEach(row => row.style.display = '');
        resultCount.textContent = '';
        return;
      }

      let matches = 0;
      searchableRows.forEach(row => {
        const haystack = row.getAttribute('data-search') || '';
        const isMatch  = haystack.includes(query);
        row.style.display = isMatch ? '' : 'none';
        if (isMatch) matches++;
      });

      resultCount.textContent = `${matches} match${matches === 1 ? '' : 'es'} found`;
    }

    searchInput?.addEventListener('input', runSearch);

    clearBtn?.addEventListener('click', () => {
      searchInput.value = '';
      runSearch();
      searchInput.focus();
    });
  });
  </script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const editWardModal = document.getElementById('editWardModal');
  const editWardForm = document.getElementById('editWardForm');

  editWardModal?.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;

    const id = button.getAttribute('data-id');
    const name = button.getAttribute('data-name') || '';
    const description = button.getAttribute('data-description') || '';
    const category = button.getAttribute('data-category') || 'Ward';
    const linenColor = button.getAttribute('data-linen-color') || '';

    editWardForm.action = `/wards/${id}`;

    document.getElementById('editWardName').value = name;
    document.getElementById('editWardDescription').value = description;
    document.getElementById('editWardCategory').value = category;
    document.getElementById('editWardLinenColor').value = linenColor;
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  document.getElementById('editDepartmentModal')?.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;

    document.getElementById('editDepartmentForm').action =
      "{{ url('/departments') }}/" + button.getAttribute('data-id');

    document.getElementById('editDepartmentName').value = button.getAttribute('data-name') || '';
    document.getElementById('editDepartmentDescription').value = button.getAttribute('data-description') || '';
  });

  document.getElementById('editUnitModal')?.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;

    document.getElementById('editUnitForm').action =
      "{{ url('/units') }}/" + button.getAttribute('data-id');

    document.getElementById('editUnitName').value = button.getAttribute('data-name') || '';
    document.getElementById('editUnitDepartment').value = button.getAttribute('data-department-id') || '';
  });

  document.getElementById('editSubUnitModal')?.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;

    document.getElementById('editSubUnitForm').action =
      "{{ url('/sub-units') }}/" + button.getAttribute('data-id');

    document.getElementById('editSubUnitName').value = button.getAttribute('data-name') || '';
    document.getElementById('editSubUnitUnit').value = button.getAttribute('data-unit-id') || '';
  });
});
</script>
  @include('parklane.script')
