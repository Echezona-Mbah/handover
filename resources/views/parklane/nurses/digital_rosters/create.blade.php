@include('parklane.nurses.head')
<div class="page-wrapper">
  @include('parklane.nurses.header')
  <div class="main-container">
    @include('parklane.nurses.sidebar')
    <div class="app-container">
      <div class="app-hero-header"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('nurse.rosters.index') }}">Rosters</a></li><li class="breadcrumb-item text-primary">New</li></ol></div>
      <div class="app-body">
        @if($errors->any()) <div class="alert alert-danger">{{ $errors->first() }}</div> @endif
        <form method="POST" action="{{ route('nurse.rosters.store') }}" class="card">
          @csrf
          <div class="card-header"><h5 class="card-title">Create Duty Roster</h5></div>
          <div class="card-body">
            <div class="row gx-3">
              <div class="col-md-4 mb-3"><label class="form-label">Title</label><input name="title" class="form-control" value="{{ old('title') }}" required></div>
              <div class="col-md-4 mb-3"><label class="form-label">Start Date</label><input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required></div>
              <div class="col-md-4 mb-3"><label class="form-label">End Date</label><input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required></div>
            </div>
            <h6 class="mt-3">Assignments</h6>
            <div class="table-responsive">
              <table class="table table-bordered align-middle" id="assignmentTable">
                <thead><tr><th>Nurse</th><th>Date</th><th>Shift</th><th>Start</th><th>End</th><th>Notes</th></tr></thead>
                <tbody>
                  @for($i = 0; $i < 7; $i++)
                    <tr>
                      <td><select name="assignments[{{ $i }}][nurse_id]" class="form-select" required><option value="">Select</option>@foreach($nurses as $nurse)<option value="{{ $nurse->id }}">{{ $nurse->full_name }}</option>@endforeach</select></td>
                      <td><input type="date" name="assignments[{{ $i }}][duty_date]" class="form-control" required></td>
                      <td><select name="assignments[{{ $i }}][shift_type]" class="form-select" required><option>Morning</option><option>Afternoon</option><option>Night</option><option>Off</option></select></td>
                      <td><input type="time" name="assignments[{{ $i }}][start_time]" class="form-control"></td>
                      <td><input type="time" name="assignments[{{ $i }}][end_time]" class="form-control"></td>
                      <td><input name="assignments[{{ $i }}][notes]" class="form-control"></td>
                    </tr>
                  @endfor
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer text-end"><button class="btn btn-primary">Save Roster</button></div>
        </form>
      </div>
    </div>
  </div>
</div>
@include('parklane.nurses.script')

