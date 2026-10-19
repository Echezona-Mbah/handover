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
                                <h6 class="mb-0">Add Doctor Department Manager</h6>
                            </div>
                            <div class="card-body">

                                <div id="toast-container"></div>

                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif

                                <form action="{{ route('store_admin_user') }}" method="POST">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Full name</label>
                                            <input type="text" name="name" value="{{ old('name') }}"
                                                class="form-control @error('name') is-invalid @enderror"
                                                placeholder="e.g. Jane Okafor" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" value="{{ old('email') }}"
                                                class="form-control @error('email') is-invalid @enderror"
                                                placeholder="jane@hospital.com" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Password</label>
                                            <input type="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="At least 6 characters" required>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <input type="hidden" name="hospital_id" value="{{ auth()->user()->hospital_id }}">

                                        <div class="col-md-6">
                                            <label class="form-label">Department to manage</label>
                                            <select name="doctor_department_id"
                                                class="form-select @error('doctor_department_id') is-invalid @enderror" required>
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

                                        <div class="col-12">
                                            <div class="alert alert-info d-flex align-items-center gap-2 mb-0">
                                                <i class="ri-information-line"></i>
                                                <span>This account will only see doctors, shifts, and patients within the selected department.</span>
                                            </div>
                                        </div>

                                        <div class="col-12 d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin_user_list') }}" class="btn btn-light">Cancel</a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ri-add-line"></i> Add manager
                                            </button>
                                        </div>
                                    </div>
                                </form>
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
                                                <th>Department</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($managers as $manager)
                                                <tr>
                                                    <td>{{ $manager->name }}</td>
                                                    <td class="text-muted">{{ $manager->email }}</td>
                                                    <td>
                                                        <span class="badge bg-primary-subtle text-primary">
                                                            {{ $manager->doctorDepartment->name ?? '—' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
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