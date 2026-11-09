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
          <li class="breadcrumb-item"><a href="{{ route('dentist.show_patient', $patient) }}">{{ $patient->full_name }}</a></li>
          <li class="breadcrumb-item text-primary" aria-current="page">Medication plan</li>
        </ol>
      </div>

      <div class="app-body">
        @if ($errors->any())
          <div class="alert alert-danger">
            <strong>Please check the medication details.</strong>
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
              <span class="small text-white-50">CARE PLAN</span>
              <h2 class="text-white mb-1">Add medication</h2>
              <p class="text-white-50 mb-0">{{ $patient->full_name }} · {{ $patient->pt_number ?: $patient->patient_number }}</p>
            </div>
            <a href="{{ route('dentist.show_patient', $patient) }}" class="btn btn-light text-primary">
              <i class="ri-user-heart-line me-1"></i> Patient record
            </a>
          </div>
        </div>

        @if ($patient->allergies)
          <div class="alert alert-warning d-flex gap-2 align-items-start">
            <i class="ri-alert-line fs-5"></i>
            <div><strong>Recorded allergies:</strong> {{ $patient->allergies }}</div>
          </div>
        @endif

        <form method="POST" action="{{ route('medications.store') }}">
          @csrf
          <input type="hidden" name="patient_id" value="{{ $patient->id }}">

          <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white">
              <h5 class="card-title mb-1">Prescription details</h5>
              <span class="small text-muted">Add the medication and directions to the patient’s care plan.</span>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="medication" class="form-label">Medication <span class="text-danger">*</span></label>
                  <input id="medication" name="medication" type="text" maxlength="255" required
                    value="{{ old('medication') }}" placeholder="Medication name"
                    class="form-control @error('medication') is-invalid @enderror">
                  @error('medication') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                  <label for="dosage" class="form-label">Dosage <span class="text-danger">*</span></label>
                  <input id="dosage" name="dosage" type="text" maxlength="255" required
                    value="{{ old('dosage') }}" placeholder="e.g. 500 mg"
                    class="form-control @error('dosage') is-invalid @enderror">
                  @error('dosage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                  <label for="frequency" class="form-label">Frequency <span class="text-danger">*</span></label>
                  <input id="frequency" name="frequency" type="text" maxlength="255" required
                    value="{{ old('frequency') }}" placeholder="e.g. Every 8 hours"
                    class="form-control @error('frequency') is-invalid @enderror">
                  @error('frequency') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                  <label for="start_date" class="form-label">Start date <span class="text-danger">*</span></label>
                  <input id="start_date" name="start_date" type="date" required value="{{ old('start_date') }}"
                    class="form-control @error('start_date') is-invalid @enderror">
                  @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                  <label for="end_date" class="form-label">End date <span class="text-danger">*</span></label>
                  <input id="end_date" name="end_date" type="date" required value="{{ old('end_date') }}"
                    class="form-control @error('end_date') is-invalid @enderror">
                  @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-12">
                  <label for="instructions" class="form-label">Instructions</label>
                  <textarea id="instructions" name="instructions" rows="4" placeholder="Additional directions or precautions"
                    class="form-control @error('instructions') is-invalid @enderror">{{ old('instructions') }}</textarea>
                  @error('instructions') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-end flex-wrap gap-2 mb-4">
            <a href="{{ route('dentist.show_patient', $patient) }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="ri-save-line me-1"></i> Save medication plan</button>
          </div>
        </form>
      </div>

      <div class="app-footer bg-white"><span>© {{ now()->year }} Fandova</span></div>
    </div>
  </div>
</div>

@include('parklane.dentist.script')
