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
                <select name="report_type" id="report-type" class="form-select" required>
                  <option value="daily_shift" @selected(old('report_type', 'daily_shift') === 'daily_shift')>Daily Shift Report</option>
                  <option value="incident" @selected(old('report_type') === 'incident')>Incident Report</option>
                  <option value="administrative" @selected(old('report_type') === 'administrative')>Administrative Report</option>
                  <option value="referral" @selected(old('report_type') === 'referral')>Referral Related Report</option>
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
                <label class="form-label">Report recipients</label>
                <select name="recipient_target" class="form-select @error('recipient_target') is-invalid @enderror" required>
                  @if($hasHns)<option value="hns" @selected(old('recipient_target', 'both') === 'hns')>HNS Office only</option>@endif
                  @if($unitHead)<option value="unit_head" @selected(old('recipient_target') === 'unit_head')>Unit head only: {{ $unitHead->name }}</option>@endif
                  @if($hasHns && $unitHead)<option value="both" @selected(old('recipient_target', 'both') === 'both')>Both HNS Office and unit head</option>@endif
                </select>
                @error('recipient_target')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">Choose the HNS Office, your unit head, or both. Each recipient can accept or decline.</small>
              </div>
              <div class="col-md-12 mb-3" id="involved-nurses-field" hidden>
                <label class="form-label">Nurses involved in the incident</label>
                <select name="involved_nurse_ids[]" id="involved-nurse-ids" class="form-select" multiple size="4">
                  @foreach($involvedNurses as $involvedNurse)
                    <option value="{{ $involvedNurse->id }}" @selected(in_array($involvedNurse->id, old('involved_nurse_ids', [])))>{{ $involvedNurse->full_name }}</option>
                  @endforeach
                </select>
                @error('involved_nurse_ids')<div class="text-danger">{{ $message }}</div>@enderror
                @error('involved_nurse_ids.*')<div class="text-danger">{{ $message }}</div>@enderror
              </div>
              <div class="col-12 mb-3" id="summary-field">
                <label class="form-label" id="summary-label">Nursing Summary</label>
                <textarea name="summary" id="summary" rows="5" class="form-control">{{ old('summary') }}</textarea>
              </div>
              <div class="col-md-6 mb-3" id="referrals-field">
                <label class="form-label">Referrals or Follow-up</label>
                <textarea name="referrals" rows="4" class="form-control">{{ old('referrals') }}</textarea>
              </div>
              <div class="col-12 mb-3" id="administrative-notes-field">
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
<script>
  const reportType = document.getElementById('report-type');
  const summaryField = document.getElementById('summary-field');
  const summary = document.getElementById('summary');
  const summaryLabel = document.getElementById('summary-label');
  const involvedNursesField = document.getElementById('involved-nurses-field');
  const involvedNurses = document.getElementById('involved-nurse-ids');
  const referralsField = document.getElementById('referrals-field');
  const administrativeNotesField = document.getElementById('administrative-notes-field');

  const updateReportFields = () => {
    const type = reportType.value;
    const isIncident = type === 'incident';

    involvedNursesField.hidden = !isIncident;
    involvedNurses.required = isIncident;
    summary.required = true;
    summaryLabel.textContent = isIncident ? 'Incident Details' : 'Nursing Summary';
    referralsField.hidden = type !== 'referral';
    administrativeNotesField.hidden = type !== 'administrative';
  };

  reportType.addEventListener('change', updateReportFields);
  updateReportFields();
</script>
