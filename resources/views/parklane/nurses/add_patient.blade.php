@include('parklane.nurses.head')

<div class="page-wrapper">
  @include('parklane.nurses.header')
  <div class="main-container">
    @include('parklane.nurses.sidebar')

    <div class="app-container">
      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('nurse.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('nurse.patient') }}">My patients</a></li>
          <li class="breadcrumb-item text-primary" aria-current="page">Add patient</li>
        </ol>
      </div>

      <div class="app-body">
        <div class="row gx-3">
          <div class="col-lg-10">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title mb-0">Register patient in {{ $ward->name }}</h5>
              </div>
              <div class="card-body">
                <div class="alert alert-info">
                  This patient will be registered at your hospital and assigned to your ward.
                </div>

                <form method="POST" action="{{ route('nurse.patients.store') }}">
                  @csrf
                  <div class="row gx-3">
                    <div class="col-md-6 mb-3">
                      <label for="full_name" class="form-label">Full name <span class="text-danger">*</span></label>
                      <input id="full_name" name="full_name" type="text" required maxlength="255"
                        value="{{ old('full_name') }}" class="form-control @error('full_name') is-invalid @enderror">
                      @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                      <label for="dob" class="form-label">Date of birth <span class="text-danger">*</span></label>
                      <input id="dob" name="dob" type="date" required max="{{ now()->toDateString() }}"
                        value="{{ old('dob') }}" class="form-control @error('dob') is-invalid @enderror">
                      @error('dob') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                      <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                      <select id="gender" name="gender" required class="form-select @error('gender') is-invalid @enderror">
                        <option value="">Select gender</option>
                        <option value="female" @selected(old('gender') === 'female')>Female</option>
                        <option value="male" @selected(old('gender') === 'male')>Male</option>
                      </select>
                      @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                      <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                      <input id="email" name="email" type="email" required maxlength="255"
                        value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                      @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                      <label for="mobile_number" class="form-label">Mobile number <span class="text-danger">*</span></label>
                      <input id="mobile_number" name="mobile_number" type="tel" required maxlength="15"
                        value="{{ old('mobile_number') }}" class="form-control @error('mobile_number') is-invalid @enderror">
                      @error('mobile_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                      <label for="marital_status" class="form-label">Marital status</label>
                      <select id="marital_status" name="marital_status" class="form-select @error('marital_status') is-invalid @enderror">
                        <option value="">Not specified</option>
                        <option value="Married" @selected(old('marital_status') === 'Married')>Married</option>
                        <option value="Un Married" @selected(old('marital_status') === 'Un Married')>Unmarried</option>
                      </select>
                      @error('marital_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                      <label for="current_condition" class="form-label">Current condition</label>
                      <input id="current_condition" name="current_condition" type="text" maxlength="255"
                        value="{{ old('current_condition') }}" class="form-control @error('current_condition') is-invalid @enderror">
                      @error('current_condition') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                      <label for="urgency_status" class="form-label">Urgency</label>
                      <select id="urgency_status" name="urgency_status" class="form-select @error('urgency_status') is-invalid @enderror">
                        <option value="routine" @selected(old('urgency_status', 'routine') === 'routine')>Routine</option>
                        <option value="urgent" @selected(old('urgency_status') === 'urgent')>Urgent</option>
                        <option value="emergency" @selected(old('urgency_status') === 'emergency')>Emergency</option>
                      </select>
                      @error('urgency_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12 mb-3">
                      <label for="current_medications" class="form-label">Current medications</label>
                      <textarea id="current_medications" name="current_medications" rows="2" maxlength="1000"
                        class="form-control @error('current_medications') is-invalid @enderror">{{ old('current_medications') }}</textarea>
                      @error('current_medications') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12 mb-3">
                      <label for="allergies" class="form-label">Allergies</label>
                      <textarea id="allergies" name="allergies" rows="2" maxlength="1000"
                        class="form-control @error('allergies') is-invalid @enderror">{{ old('allergies') }}</textarea>
                      @error('allergies') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12 mb-3">
                      <label for="address" class="form-label">Address</label>
                      <textarea id="address" name="address" rows="2" maxlength="255"
                        class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                      @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                  </div>

                  <button type="submit" class="btn btn-primary">Register patient</button>
                  <a href="{{ route('nurse.patient') }}" class="btn btn-outline-secondary">Cancel</a>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

      @include('parklane.nurses.script')
    </div>
  </div>
</div>
