@include('parklane.nurses.head')
<div class="page-wrapper">
  @include('parklane.nurses.header')
  <div class="main-container">
    @include('parklane.nurses.sidebar')
    <div class="app-container">
      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('nurse.patient') }}">Ward patients</a></li>
          <li class="breadcrumb-item text-primary">Edit patient</li>
        </ol>
        <a href="{{ route('nurse.show_patient', $patient) }}" class="btn btn-light ms-auto">Back to patient</a>
      </div>
      <div class="app-body">
        @if($errors->any())
          <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        <div class="card">
          <div class="card-header"><h5 class="card-title mb-0">Edit {{ $patient->full_name }}</h5></div>
          <form method="POST" action="{{ route('nurse.patients.update', $patient) }}">
            @csrf
            @method('PUT')
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-6"><label class="form-label" for="full_name">Full name</label><input id="full_name" name="full_name" class="form-control" value="{{ old('full_name', $patient->full_name) }}" required></div>
                <div class="col-md-3"><label class="form-label" for="dob">Date of birth</label><input id="dob" type="date" name="dob" class="form-control" value="{{ old('dob', $patient->dob) }}"></div>
                <div class="col-md-3"><label class="form-label" for="gender">Gender</label><select id="gender" name="gender" class="form-select"><option value="">Select</option>@foreach(['male' => 'Male', 'female' => 'Female'] as $value => $label)<option value="{{ $value }}" @selected(old('gender', $patient->gender) === $value)>{{ $label }}</option>@endforeach</select></div>
                <div class="col-md-6"><label class="form-label" for="email">Email</label><input id="email" type="email" name="email" class="form-control" value="{{ old('email', $patient->email) }}"></div>
                <div class="col-md-6"><label class="form-label" for="mobile_number">Phone number</label><input id="mobile_number" name="mobile_number" class="form-control" value="{{ old('mobile_number', $patient->mobile_number) }}"></div>
                <div class="col-md-4"><label class="form-label" for="marital_status">Marital status</label><select id="marital_status" name="marital_status" class="form-select"><option value="">Select</option>@foreach(['married' => 'Married', 'unmarried' => 'Single / unmarried'] as $value => $label)<option value="{{ $value }}" @selected(old('marital_status', $patient->marital_status) === $value)>{{ $label }}</option>@endforeach</select></div>
                <div class="col-md-4"><label class="form-label" for="blood_group">Blood group</label><input id="blood_group" name="blood_group" class="form-control" value="{{ old('blood_group', $patient->blood_group) }}"></div>
                <div class="col-md-4"><label class="form-label" for="urgency_status">Urgency</label><select id="urgency_status" name="urgency_status" class="form-select">@foreach(['routine' => 'Routine', 'urgent' => 'Urgent', 'emergency' => 'Emergency'] as $value => $label)<option value="{{ $value }}" @selected(old('urgency_status', $patient->urgency_status ?? 'routine') === $value)>{{ $label }}</option>@endforeach</select></div>
                <div class="col-md-4"><label class="form-label" for="occupation">Occupation</label><input id="occupation" name="occupation" class="form-control" value="{{ old('occupation', $patient->occupation) }}"></div>
                <div class="col-md-4"><label class="form-label" for="city">City</label><input id="city" name="city" class="form-control" value="{{ old('city', $patient->city) }}"></div>
                <div class="col-md-4"><label class="form-label" for="state">State</label><input id="state" name="state" class="form-control" value="{{ old('state', $patient->state) }}"></div>
                <div class="col-md-6"><label class="form-label" for="blood_pressure">Blood pressure</label><input id="blood_pressure" name="blood_pressure" class="form-control" value="{{ old('blood_pressure', $patient->blood_pressure) }}"></div>
                <div class="col-md-6"><label class="form-label" for="sugar_level">Blood sugar</label><input id="sugar_level" name="sugar_level" class="form-control" value="{{ old('sugar_level', $patient->sugar_level) }}"></div>
                <div class="col-12"><label class="form-label" for="current_condition">Current condition</label><textarea id="current_condition" name="current_condition" class="form-control" rows="2">{{ old('current_condition', $patient->current_condition) }}</textarea></div>
                <div class="col-md-6"><label class="form-label" for="allergies">Allergies</label><textarea id="allergies" name="allergies" class="form-control" rows="3">{{ old('allergies', $patient->allergies) }}</textarea></div>
                <div class="col-md-6"><label class="form-label" for="current_medications">Current medications</label><textarea id="current_medications" name="current_medications" class="form-control" rows="3">{{ old('current_medications', $patient->current_medications) }}</textarea></div>
                <div class="col-12"><label class="form-label" for="address">Address</label><textarea id="address" name="address" class="form-control" rows="2">{{ old('address', $patient->address) }}</textarea></div>
              </div>
            </div>
            <div class="card-footer d-flex justify-content-end gap-2"><a href="{{ route('nurse.show_patient', $patient) }}" class="btn btn-light">Cancel</a><button class="btn btn-primary">Save patient details</button></div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@include('parklane.nurses.script')
