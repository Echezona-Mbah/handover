@include('parklane.dentist.head')

<div class="page-wrapper">
  @include('parklane.dentist.header')

  <div class="main-container">
    @include('parklane.dentist.sidebar')

    <div class="app-container">
      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('dentist.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('dentist.patient') }}">Patients</a></li>
          <li class="breadcrumb-item text-primary" aria-current="page">Add patient</li>
        </ol>
      </div>

      <div class="app-body">
        @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
          <div class="alert alert-danger">
            <strong>Please check the form.</strong>
            <ul class="mb-0 mt-2">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @if ($wards->isEmpty())
          <div class="alert alert-warning">There are no wards configured for your hospital. Contact your administrator before adding a patient.</div>
        @endif
        @if ($DentistUnits->isEmpty())
          <div class="alert alert-warning">There are no dental units configured for your hospital. Contact your administrator before adding a patient.</div>
        @endif

        <div class="card border-0 shadow-sm mb-3 overflow-hidden"
          style="background: linear-gradient(120deg, #155eef 0%, #243b80 100%);">
          <div class="card-body p-4 text-white">
            <span class="small text-white-50">PATIENT INTAKE</span>
            <h2 class="text-white mb-1">Register a patient</h2>
            <p class="text-white-50 mb-0">Patient and record numbers are generated automatically.</p>
          </div>
        </div>

        <form method="POST" action="{{ route('dentist.store_patient') }}">
          @csrf
          <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white">
              <h5 class="card-title mb-1">Patient information</h5>
              <span class="small text-muted">Enter the patient’s identifying and contact details.</span>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="full_name" class="form-label">Full name</label>
                  <input id="full_name" name="full_name" type="text" maxlength="255"
                    value="{{ old('full_name') }}"
                    class="form-control @error('full_name') is-invalid @enderror">
                  @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                  <label for="dob" class="form-label">Date of birth</label>
                  <input id="dob" name="dob" type="date" max="{{ now()->toDateString() }}"
                    value="{{ old('dob') }}" class="form-control @error('dob') is-invalid @enderror">
                  @error('dob') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                  <label for="gender" class="form-label">Gender</label>
                  <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror">
                    <option value="">Not specified</option>
                    <option value="female" @selected(old('gender') === 'female')>Female</option>
                    <option value="male" @selected(old('gender') === 'male')>Male</option>
                  </select>
                  @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label for="email" class="form-label">Email</label>
                  <input id="email" name="email" type="email" maxlength="255"
                    value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                  @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                  <label for="mobile_number" class="form-label">Mobile number</label>
                  <input id="mobile_number" name="mobile_number" type="tel" maxlength="15"
                    value="{{ old('mobile_number') }}" class="form-control @error('mobile_number') is-invalid @enderror">
                  @error('mobile_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                  <label for="marital_status" class="form-label">Marital status</label>
                  <select id="marital_status" name="marital_status" class="form-select @error('marital_status') is-invalid @enderror">
                    <option value="">Not specified</option>
                    <option value="Married" @selected(old('marital_status') === 'Married')>Married</option>
                    <option value="Single" @selected(old('marital_status') === 'Single')>Single</option>
                  </select>
                  @error('marital_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label for="ward_id" class="form-label">Ward</label>
                  <select id="ward_id" name="ward_id" class="form-select @error('ward_id') is-invalid @enderror">
                    <option value="">Not assigned</option>
                    @foreach ($wards as $ward)
                      <option value="{{ $ward->id }}" @selected(old('ward_id') == $ward->id)>{{ $ward->name }}</option>
                    @endforeach
                  </select>
                  @error('ward_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label for="dentist_unit_id" class="form-label">Dental unit</label>
                  @if ($dentist->dentist_unit_id)
                    @php($assignedUnit = $DentistUnits->firstWhere('id', $dentist->dentist_unit_id))
                    <input type="hidden" name="dentist_unit_id" value="{{ $dentist->dentist_unit_id }}">
                    <input id="dentist_unit_id" type="text" readonly
                      value="{{ $assignedUnit?->name ?? 'Your assigned unit' }}" class="form-control">
                  @else
                    <select id="dentist_unit_id" name="dentist_unit_id"
                      class="form-select @error('dentist_unit_id') is-invalid @enderror">
                      <option value="">Not assigned</option>
                      @foreach ($DentistUnits as $unit)
                        <option value="{{ $unit->id }}" @selected(old('dentist_unit_id') == $unit->id)>{{ $unit->name }}</option>
                      @endforeach
                    </select>
                    @error('dentist_unit_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white">
              <h5 class="card-title mb-1">Clinical details</h5>
              <span class="small text-muted">Record the current concern and relevant care information.</span>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="current_condition" class="form-label">Current condition</label>
                  <input id="current_condition" name="current_condition" type="text" maxlength="255"
                    value="{{ old('current_condition') }}"
                    class="form-control @error('current_condition') is-invalid @enderror">
                  @error('current_condition') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label for="urgency_status" class="form-label">Urgency</label>
                  <select id="urgency_status" name="urgency_status"
                    class="form-select @error('urgency_status') is-invalid @enderror">
                    <option value="routine" @selected(old('urgency_status', 'routine') === 'routine')>Routine</option>
                    <option value="urgent" @selected(old('urgency_status') === 'urgent')>Urgent</option>
                    <option value="emergency" @selected(old('urgency_status') === 'emergency')>Emergency</option>
                  </select>
                  @error('urgency_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label for="current_medications" class="form-label">Current medications</label>
                  <textarea id="current_medications" name="current_medications" rows="3" maxlength="1000"
                    class="form-control @error('current_medications') is-invalid @enderror">{{ old('current_medications') }}</textarea>
                  @error('current_medications') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label for="allergies" class="form-label">Allergies</label>
                  <textarea id="allergies" name="allergies" rows="3" maxlength="1000"
                    class="form-control @error('allergies') is-invalid @enderror">{{ old('allergies') }}</textarea>
                  @error('allergies') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                  <label for="address" class="form-label">Address</label>
                  <textarea id="address" name="address" rows="2" maxlength="255"
                    class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                  @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>
            </div>
          </div>

          <div class="d-flex flex-wrap justify-content-end gap-2 mb-4">
            <a href="{{ route('dentist.patient') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
              <i class="ri-user-add-line me-1"></i> Register patient
            </button>
          </div>
        </form>
      </div>

      <div class="app-footer bg-white"><span>© {{ now()->year }} Fandova</span></div>
    </div>
  </div>
</div>

@include('parklane.dentist.script')
