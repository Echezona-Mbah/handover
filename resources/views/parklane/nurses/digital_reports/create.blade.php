@include('parklane.nurses.head')
<div class="page-wrapper">
  @include('parklane.nurses.header')
  <div class="main-container">
    @include('parklane.nurses.sidebar')
    <div class="app-container">
      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('nurse.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item text-primary">Submit Nursing Report</li>
        </ol>
      </div>
      <div class="app-body">
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif
        <form method="POST" action="{{ route('nurse.reports.store') }}" class="card">
          @csrf
          <div class="card-header"><h5 class="card-title">Standard Report Template</h5></div>
          <div class="card-body">
            <div class="row gx-3">
              <div class="col-md-4 mb-3">
                <label class="form-label">Report Type</label>
                <select name="report_type" class="form-select" required>
                  <option value="daily_shift">Daily Shift Report</option>
                  <option value="incident">Incident Report</option>
                  <option value="administrative">Administrative Report</option>
                  <option value="referral">Referral Related Report</option>
                </select>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Report Date</label>
                <input type="date" name="report_date" value="{{ old('report_date', now()->toDateString()) }}" class="form-control" required>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Shift</label>
                <select name="shift" class="form-select">
                  <option value="">Select shift</option>
                  <option>Morning</option>
                  <option>Afternoon</option>
                  <option>Night</option>
                </select>
              </div>
              <div class="col-md-12 mb-3">
                <label class="form-label">Patient, if report is patient-specific</label>
                <select name="patient_id" class="form-select">
                  <option value="">Ward or general report</option>
                  @foreach($patients as $patient)
                    <option value="{{ $patient->id }}">{{ $patient->full_name }} - {{ $patient->patient_number ?? $patient->pt_number }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12 mb-3">
                <label class="form-label">Original Nursing Summary</label>
                <textarea name="summary" rows="5" class="form-control" required>{{ old('summary') }}</textarea>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Incidents</label>
                <textarea name="incidents" rows="4" class="form-control">{{ old('incidents') }}</textarea>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Referrals or Follow-up</label>
                <textarea name="referrals" rows="4" class="form-control">{{ old('referrals') }}</textarea>
              </div>
              <div class="col-12 mb-3">
                <label class="form-label">Administrative Notes</label>
                <textarea name="administrative_notes" rows="3" class="form-control">{{ old('administrative_notes') }}</textarea>
              </div>
            </div>
          </div>
          <div class="card-footer text-end">
            <a href="{{ route('nurse.reports.index') }}" class="btn btn-light">Cancel</a>
            <button class="btn btn-primary">Submit Report</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@include('parklane.nurses.script')

