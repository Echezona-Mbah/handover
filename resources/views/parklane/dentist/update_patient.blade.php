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
          <li class="breadcrumb-item text-primary" aria-current="page">Update patient</li>
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

        <div class="card border-0 shadow-sm mb-3 overflow-hidden"
          style="background: linear-gradient(120deg, #155eef 0%, #243b80 100%);">
          <div class="card-body p-4 text-white d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
              <span class="small text-white-50">PATIENT RECORD</span>
              <h2 class="text-white mb-1">Update patient details</h2>
              <p class="text-white-50 mb-0">{{ $patient->full_name }} · {{ $patient->pt_number ?: $patient->patient_number }}</p>
            </div>
            <a href="{{ route('dentist.show_patient', $patient->id) }}" class="btn btn-light text-primary">
              <i class="ri-eye-line me-1"></i> View patient
            </a>
          </div>
        </div>

        <form method="POST" action="{{ route('dentist.patientUpdate', $patient->id) }}">
          @csrf
          @method('PUT')

          <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white">
              <h5 class="card-title mb-1">Personal information</h5>
              <span class="small text-muted">Review and update patient contact and ward information.</span>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="full_name" class="form-label">Full name <span class="text-danger">*</span></label>
                  <input id="full_name" name="full_name" type="text" maxlength="255" required
                    value="{{ old('full_name', $patient->full_name) }}"
                    class="form-control @error('full_name') is-invalid @enderror">
                  @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                  <label for="dob" class="form-label">Date of birth <span class="text-danger">*</span></label>
                  <input id="dob" name="dob" type="date" max="{{ now()->toDateString() }}" required
                    value="{{ old('dob', $patient->dob) }}" class="form-control @error('dob') is-invalid @enderror">
                  @error('dob') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                  <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                  <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                    <option value="female" @selected(old('gender', $patient->gender) === 'female')>Female</option>
                    <option value="male" @selected(old('gender', $patient->gender) === 'male')>Male</option>
                  </select>
                  @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                  <input id="email" name="email" type="email" maxlength="255" required
                    value="{{ old('email', $patient->email) }}" class="form-control @error('email') is-invalid @enderror">
                  @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                  <label for="mobile_number" class="form-label">Mobile number</label>
                  <input id="mobile_number" name="mobile_number" type="tel" maxlength="15"
                    value="{{ old('mobile_number', $patient->mobile_number) }}"
                    class="form-control @error('mobile_number') is-invalid @enderror">
                  @error('mobile_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                  <label for="marital_status" class="form-label">Marital status</label>
                  <select id="marital_status" name="marital_status" class="form-select @error('marital_status') is-invalid @enderror">
                    <option value="">Not specified</option>
                    <option value="Married" @selected(old('marital_status', $patient->marital_status === 'married' ? 'Married' : '') === 'Married')>Married</option>
                    <option value="Single" @selected(old('marital_status', $patient->marital_status === 'unmarried' ? 'Single' : '') === 'Single')>Single</option>
                  </select>
                  @error('marital_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label for="ward_id" class="form-label">Ward</label>
                  <select id="ward_id" name="ward_id" class="form-select @error('ward_id') is-invalid @enderror">
                    @if (! $patient->ward_id)
                      <option value="">Not assigned</option>
                    @endif
                    @foreach ($wards as $ward)
                      <option value="{{ $ward->id }}" @selected(old('ward_id', $patient->ward_id) == $ward->id)>{{ $ward->name }}</option>
                    @endforeach
                  </select>
                  @error('ward_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label for="address" class="form-label">Address</label>
                  <input id="address" name="address" type="text" maxlength="255"
                    value="{{ old('address', $patient->address) }}" class="form-control @error('address') is-invalid @enderror">
                  @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                  <label for="city" class="form-label">City</label>
                  <input id="city" name="city" type="text" maxlength="255"
                    value="{{ old('city', $patient->city) }}" class="form-control @error('city') is-invalid @enderror">
                  @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                  <label for="state" class="form-label">State</label>
                  <input id="state" name="state" type="text" maxlength="255"
                    value="{{ old('state', $patient->state) }}" class="form-control @error('state') is-invalid @enderror">
                  @error('state') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                  <label for="occupation" class="form-label">Occupation</label>
                  <input id="occupation" name="occupation" type="text" maxlength="255"
                    value="{{ old('occupation', $patient->occupation) }}" class="form-control @error('occupation') is-invalid @enderror">
                  @error('occupation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>
            </div>
          </div>

          <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white">
              <h5 class="card-title mb-1">Clinical information</h5>
              <span class="small text-muted">Keep active care information accurate and up to date.</span>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="current_condition" class="form-label">Current condition</label>
                  <input id="current_condition" name="current_condition" type="text" maxlength="255"
                    value="{{ old('current_condition', $patient->current_condition) }}"
                    class="form-control @error('current_condition') is-invalid @enderror">
                  @error('current_condition') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                  <label for="urgency_status" class="form-label">Urgency</label>
                  <select id="urgency_status" name="urgency_status" class="form-select @error('urgency_status') is-invalid @enderror">
                    @foreach (['routine' => 'Routine', 'urgent' => 'Urgent', 'emergency' => 'Emergency'] as $value => $label)
                      <option value="{{ $value }}" @selected(old('urgency_status', $patient->urgency_status ?? 'routine') === $value)>{{ $label }}</option>
                    @endforeach
                  </select>
                  @error('urgency_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-3">
                  <label for="blood_group" class="form-label">Blood group</label>
                  <select id="blood_group" name="blood_group" class="form-select @error('blood_group') is-invalid @enderror">
                    <option value="">Not recorded</option>
                    @foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $group)
                      <option value="{{ $group }}" @selected(old('blood_group', $patient->blood_group) === $group)>{{ $group }}</option>
                    @endforeach
                  </select>
                  @error('blood_group') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label for="current_medications" class="form-label">Current medications</label>
                  <textarea id="current_medications" name="current_medications" rows="3" maxlength="1000"
                    class="form-control @error('current_medications') is-invalid @enderror">{{ old('current_medications', $patient->current_medications) }}</textarea>
                  @error('current_medications') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label for="allergies" class="form-label">Allergies</label>
                  <textarea id="allergies" name="allergies" rows="3" maxlength="1000"
                    class="form-control @error('allergies') is-invalid @enderror">{{ old('allergies', $patient->allergies) }}</textarea>
                  @error('allergies') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label for="blood_pressure" class="form-label">Blood pressure</label>
                  <input id="blood_pressure" name="blood_pressure" type="text" maxlength="255"
                    value="{{ old('blood_pressure', $patient->blood_pressure) }}"
                    class="form-control @error('blood_pressure') is-invalid @enderror">
                  @error('blood_pressure') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label for="sugar_level" class="form-label">Sugar level</label>
                  <input id="sugar_level" name="sugar_level" type="text" maxlength="255"
                    value="{{ old('sugar_level', $patient->sugar_level) }}"
                    class="form-control @error('sugar_level') is-invalid @enderror">
                  @error('sugar_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>
            </div>
          </div>

          <div class="d-flex flex-wrap justify-content-end gap-2 mb-4">
            <a href="{{ route('dentist.patient') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
              <i class="ri-save-line me-1"></i> Save patient details
            </button>
          </div>
        </form>
      </div>

      <div class="app-footer bg-white"><span>© {{ now()->year }} Fandova</span></div>
    </div>
  </div>
</div>

@include('parklane.dentist.script')
