@include('parklane.nurses.head')
<div class="page-wrapper">
  @include('parklane.nurses.header')
  <div class="main-container">
    @include('parklane.nurses.sidebar')
    <div class="app-container">
      <div class="app-hero-header"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('nurse.statistics.index') }}">Statistics</a></li><li class="breadcrumb-item text-primary">New</li></ol></div>
      <div class="app-body">
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif
        <form method="POST" action="{{ route('nurse.statistics.store') }}" class="card">
          @csrf
          <div class="card-header"><h5 class="card-title">Daily Nursing Statistics</h5></div>
          <div class="card-body row gx-3">
            <div class="col-12 mb-3">
              <label class="form-label">Send statistics to ward(s)</label>
              <select name="ward_ids[]" class="form-select @error('ward_ids') is-invalid @enderror" multiple size="5" required>
                @foreach($wards as $ward)
                  <option value="{{ $ward->id }}" @selected(in_array($ward->id, old('ward_ids', [])))>{{ $ward->name }}</option>
                @endforeach
              </select>
              <small class="text-muted">Select one or more wards. Nurses in each selected ward and its unit head receive an in-app notification and an email with your name, home ward, and the statistics summary.</small>
              @error('ward_ids')<div class="invalid-feedback">{{ $message }}</div>@enderror
              @error('ward_ids.*')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4 mb-3"><label class="form-label">Date</label><input type="date" name="stats_date" value="{{ old('stats_date', now()->toDateString()) }}" class="form-control" required></div>
            @foreach(['admissions','discharges','transfers','deaths','bed_occupied','bed_vacant','clinic_attendance','specialized_service_count'] as $field)
              <div class="col-md-3 mb-3"><label class="form-label">{{ ucwords(str_replace('_', ' ', $field)) }}</label><input type="number" min="0" name="{{ $field }}" value="{{ old($field, 0) }}" class="form-control" required></div>
            @endforeach
            <div class="col-12 mb-3"><label class="form-label">Notes</label><textarea name="notes" rows="4" class="form-control">{{ old('notes') }}</textarea></div>
          </div>
          <div class="card-footer text-end"><button class="btn btn-primary">Submit Statistics</button></div>
        </form>
      </div>
    </div>
  </div>
</div>
@include('parklane.nurses.script')
