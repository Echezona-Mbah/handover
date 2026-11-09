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
          <li class="breadcrumb-item text-primary" aria-current="page">Handover</li>
        </ol>
      </div>

      <div class="app-body">
        @if ($errors->any())
          <div class="alert alert-danger">
            <strong>Please complete the handover details.</strong>
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
              <span class="small text-white-50">CARE TEAM COMMUNICATION</span>
              <h2 class="text-white mb-1">Patient handover</h2>
              <p class="text-white-50 mb-0">{{ $patient->full_name }} · {{ $patient->pt_number ?: $patient->patient_number }}</p>
            </div>
            <a href="{{ route('dentist.show_patient', $patient) }}" class="btn btn-light text-primary">
              <i class="ri-user-heart-line me-1"></i> Patient record
            </a>
          </div>
        </div>

        <div class="alert alert-info d-flex align-items-start gap-2">
          <i class="ri-information-line fs-5"></i>
          <span>Share a clear, current summary so the next member of the care team can continue treatment safely.</span>
        </div>

        <form method="POST" action="{{ route('handover.store') }}">
          @csrf
          <input type="hidden" name="patient_id" value="{{ $patient->id }}">

          <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white">
              <h5 class="card-title mb-1">Handover details</h5>
              <span class="small text-muted">Include the situation, important alerts, and outstanding work.</span>
            </div>
            <div class="card-body">
              <div class="mb-4">
                <label for="summary" class="form-label">Patient summary <span class="text-danger">*</span></label>
                <textarea id="summary" name="summary" rows="5" required
                  placeholder="Describe the current condition, recent changes, and care provided."
                  class="form-control @error('summary') is-invalid @enderror">{{ old('summary') }}</textarea>
                @error('summary') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="mb-4">
                <label for="alerts" class="form-label">Critical alerts <span class="text-danger">*</span></label>
                <textarea id="alerts" name="alerts" rows="4" required
                  placeholder="Include allergies, risks, deterioration signs, or precautions."
                  class="form-control @error('alerts') is-invalid @enderror">{{ old('alerts') }}</textarea>
                @error('alerts') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <div class="mb-2">
                <label for="pending_tasks" class="form-label">Pending tasks</label>
                <textarea id="pending_tasks" name="pending_tasks" rows="4"
                  placeholder="List follow-up checks, tests, or actions still to be completed."
                  class="form-control @error('pending_tasks') is-invalid @enderror">{{ old('pending_tasks') }}</textarea>
                @error('pending_tasks') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-end flex-wrap gap-2 mb-4">
            <a href="{{ route('dentist.show_patient', $patient) }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="ri-send-plane-line me-1"></i> Submit handover</button>
          </div>
        </form>
      </div>

      <div class="app-footer bg-white"><span>© {{ now()->year }} Fandova</span></div>
    </div>
  </div>
</div>

@include('parklane.dentist.script')
