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
                        Department Managers
                    </li>
                </ol>
            </div>

            <div class="app-body">
                <div class="row gx-3">

                    {{-- Add manager form --}}
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h6 class="mb-0">Add Administrator</h6>
                            </div>
                            <div class="card-body">

                                <div id="toast-container"></div>

                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif

                                <form action="{{ route('store_admin_user') }}" method="POST">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-6 admin-identity-field">
                                            <label class="form-label">Full name</label>
                                            <input type="text" id="admin-name" name="name" value="{{ old('name') }}"
                                                class="form-control @error('name') is-invalid @enderror"
                                                placeholder="e.g. Jane Okafor" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 admin-identity-field">
                                            <label class="form-label">Email</label>
                                            <input type="email" id="admin-email" name="email" value="{{ old('email') }}"
                                                class="form-control @error('email') is-invalid @enderror"
                                                placeholder="jane@hospital.com" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 admin-identity-field">
                                            <label class="form-label">Password</label>
                                            <input type="password" id="admin-password" name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="At least 6 characters" required>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Administrator area</label>
                                            <select name="admin_area" id="admin_area" class="form-select @error('admin_area') is-invalid @enderror" required>
                                                <option value="">Select responsibility</option>
                                                <option value="doctor_department" {{ old('admin_area') === 'doctor_department' ? 'selected' : '' }}>Doctor Department Manager</option>
                                                <option value="nursing_hns" {{ old('admin_area') === 'nursing_hns' ? 'selected' : '' }}>HNS — Head of Nursing Services</option>
                                                <option value="nursing_162" {{ old('admin_area') === 'nursing_162' ? 'selected' : '' }}>162 Control Unit</option>
                                                <option value="nursing_unit_head" {{ old('admin_area') === 'nursing_unit_head' ? 'selected' : '' }}>Nursing Unit Head</option>
                                                <option value="nursing_ward_supervisor" {{ old('admin_area') === 'nursing_ward_supervisor' ? 'selected' : '' }}>Ward Supervisor — assign existing nurse</option>
                                                {{-- <option value="nursing_691" {{ old('admin_area') === 'nursing_691' ? 'selected' : '' }}>Legacy 691 Nurse Administrator</option> --}}
                                                <option value="nursing_management" {{ old('admin_area') === 'nursing_management' ? 'selected' : '' }}>Nurse Management Administrator</option>
                                            </select>
                                            @error('admin_area')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <input type="hidden" name="hospital_id" value="{{ auth()->user()->hospital_id }}">

                                        <div class="col-md-6" id="department-field">
                                            <label class="form-label">Department to manage</label>
                                            <select name="doctor_department_id"
                                                class="form-select @error('doctor_department_id') is-invalid @enderror">
                                                <option value="">Select department</option>
                                                @foreach($departments as $dept)
                                                    <option value="{{ $dept->id }}" {{ old('doctor_department_id') == $dept->id ? 'selected' : '' }}>
                                                        {{ $dept->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('doctor_department_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6" id="unit-head-unit-field">
                                            <label class="form-label">Nursing unit to manage</label>
                                            <select name="unit_head_department_id" class="form-select @error('unit_head_department_id') is-invalid @enderror">
                                                <option value="">Select a nursing unit</option>
                                                @foreach($nursingUnits as $unit)
                                                    <option value="{{ $unit->id }}" @selected(old('unit_head_department_id') == $unit->id)>{{ $unit->name }}</option>
                                                @endforeach
                                            </select>
                                            <small class="text-muted">The selected unit head will automatically manage every ward assigned to this unit.</small>
                                            @error('unit_head_department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-md-6" id="supervisor-ward-field">
                                            <label class="form-label">Ward supervised</label>
                                            <select name="supervisor_ward_id" id="supervisor-ward" class="form-select">
                                                <option value="">Select a ward</option>
                                                @foreach($wards as $ward)
                                                    <option value="{{ $ward->id }}" @selected(old('supervisor_ward_id') == $ward->id)>{{ $ward->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('supervisor_ward_id')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6" id="supervisor-nurse-field">
                                            <label class="form-label">Existing nurse to assign as supervisor</label>
                                            <select name="supervisor_nurse_id" id="supervisor-nurse" class="form-select">
                                                <option value="">Select the ward first</option>
                                                @foreach($nurses as $nurse)
                                                    <option value="{{ $nurse->id }}" data-ward="{{ $nurse->ward_id }}" @selected(old('supervisor_nurse_id') == $nurse->id)>{{ $nurse->full_name }} — {{ $nurse->ward?->name ?? 'No ward' }}</option>
                                                @endforeach
                                            </select>
                                            @error('supervisor_nurse_id')<div class="text-danger">{{ $message }}</div>@enderror
                                            <small class="text-muted">Assigns this existing nurse to supervise the selected ward.</small>
                                        </div>

                                        <div class="col-12">
                                            <div class="alert alert-info d-flex align-items-center gap-2 mb-0">
                                                <i class="ri-information-line"></i>
                                                <span>Reports can be sent to the assigned nursing unit head or 162 Control Unit. Assigning a unit head gives them responsibility for every ward in the selected unit.</span>
                                            </div>
                                        </div>

                                        <div class="col-12 d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin_user_list') }}" class="btn btn-light">Cancel</a>
                                            <button type="submit" id="admin-submit" class="btn btn-primary">
                                                <i class="ri-add-line"></i> Add manager
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header"><h6 class="mb-0">Ward supervisor assignments</h6></div>
                            <div class="card-body table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead><tr><th>Ward</th><th>Assigned supervisor</th></tr></thead>
                                    <tbody>
                                        @foreach($wardSupervisors as $ward)
                                            <tr>
                                                <td>{{ $ward->name }}</td>
                                                <td>{{ $ward->nurses->pluck('full_name')->join(', ') ?: 'No supervisor assigned' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Existing managers list --}}
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h6 class="mb-0">Admin Managers</h6>
                                <span class="text-muted small">{{ $managers->count() }} accounts</span>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Responsibility</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($managers as $manager)
                                                <tr>
                                                    <td><a href="{{ route('admin_user.show', $manager) }}">{{ $manager->name }}</a></td>
                                                    <td class="text-muted">{{ $manager->email }}</td>
                                                    <td>
                                                        @if($manager->nursing_hierarchy_role === 'hns')
                                                            <span class="badge bg-primary-subtle text-primary">HNS</span>
                                                        @elseif($manager->nursing_hierarchy_role === 'control_162')
                                                            <span class="badge bg-primary-subtle text-primary">162 Control Unit</span>
                                                        @elseif($manager->nursing_hierarchy_role === 'unit_head')
                                                            <span class="badge bg-primary-subtle text-primary">Nursing Unit Head</span>
                                                        @elseif($manager->nursing_admin_role === 'nursing_691')
                                                            <span class="badge bg-primary-subtle text-primary">691 Nurse</span>
                                                        @elseif($manager->nursing_admin_role === 'nursing_management')
                                                            <span class="badge bg-primary-subtle text-primary">Nurse Management</span>
                                                        @else
                                                        <span class="badge bg-primary-subtle text-primary">
                                                            {{ $manager->doctorDepartment->name ?? '—' }}
                                                        </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        @if((int) $manager->hospital_id === (int) auth()->user()->hospital_id)
                                                            <form action="{{ route('admin_user.password-setup-link', $manager) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-outline-warning" title="Resend password setup link">
                                                                    <i class="ri-mail-send-line"></i><span class="visually-hidden">Resend password setup link</span>
                                                                </button>
                                                            </form>
                                                        @endif
                                                        <form action="{{ route('admin_user.destroy', $manager) }}" method="POST"
                                                            onsubmit="return confirm('Remove this manager?');" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted py-4">
                                                        No department managers yet.
                                                    </td>
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

@include('parklane.script')
<script>
    const adminArea = document.getElementById('admin_area');
    const departmentField = document.getElementById('department-field');
    const departmentSelect = departmentField.querySelector('select');
    const unitHeadUnitField = document.getElementById('unit-head-unit-field');
    const unitHeadUnitSelect = unitHeadUnitField.querySelector('select');
    const supervisorWardField = document.getElementById('supervisor-ward-field');
    const supervisorWard = document.getElementById('supervisor-ward');
    const supervisorNurseField = document.getElementById('supervisor-nurse-field');
    const supervisorNurse = document.getElementById('supervisor-nurse');
    const identityFields = document.querySelectorAll('.admin-identity-field input');
    const adminSubmit = document.getElementById('admin-submit');
    const filterNursesToWard = () => {
        const selectedWard = supervisorWard.value;
        Array.from(supervisorNurse.options).forEach(option => {
            if (!option.value) return;
            option.disabled = !selectedWard || option.dataset.ward !== selectedWard;
            option.hidden = option.disabled;
        });
        if (supervisorNurse.selectedOptions[0]?.disabled) supervisorNurse.value = '';
    };
    const toggleDepartment = () => {
        const isSupervisor = adminArea.value === 'nursing_ward_supervisor';
        const isDoctorManager = adminArea.value === 'doctor_department';
        const isUnitHead = adminArea.value === 'nursing_unit_head';
        identityFields.forEach(input => {
            input.disabled = isSupervisor;
            input.required = !isSupervisor;
        });
        document.querySelectorAll('.admin-identity-field').forEach(field => {
            field.style.display = isSupervisor ? 'none' : '';
        });
        departmentField.style.display = isDoctorManager ? '' : 'none';
        departmentSelect.required = isDoctorManager;
        unitHeadUnitField.style.display = isUnitHead ? '' : 'none';
        unitHeadUnitSelect.required = isUnitHead;
        unitHeadUnitSelect.disabled = !isUnitHead;
        supervisorWardField.style.display = isSupervisor ? '' : 'none';
        supervisorNurseField.style.display = isSupervisor ? '' : 'none';
        supervisorWard.required = isSupervisor;
        supervisorNurse.required = isSupervisor;
        supervisorWard.disabled = !isSupervisor;
        supervisorNurse.disabled = !isSupervisor;
        adminSubmit.innerHTML = isSupervisor ? '<i class="ri-user-follow-line"></i> Assign ward supervisor' : '<i class="ri-add-line"></i> Add manager';
        filterNursesToWard();
    };
    adminArea.addEventListener('change', toggleDepartment);
    supervisorWard.addEventListener('change', filterNursesToWard);
    toggleDepartment();
</script>
