@include('parklane.head')
<div class="page-wrapper">
  @include('parklane.header')
  <div class="main-container">
    @include('parklane.sidebar')
    <div class="app-container">
      <div class="app-hero-header"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('admin.nursing.dashboard') }}">Nursing Dashboard</a></li><li class="breadcrumb-item text-primary">Ward Patients</li></ol></div>
      <div class="app-body">
        <div class="card">
          <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="card-title mb-0">Patients in your wards</h5>
            <form method="GET" class="d-flex gap-2">
              <select name="ward_id" class="form-select" aria-label="Filter by ward">
                <option value="">All assigned wards</option>
                @foreach($wards as $ward)
                  <option value="{{ $ward->id }}" @selected(request('ward_id') == $ward->id)>{{ $ward->name }}</option>
                @endforeach
              </select>
              <button class="btn btn-outline-primary">Filter</button>
            </form>
          </div>
          <div class="card-body table-responsive">
            <table class="table table-striped align-middle">
              <thead><tr><th>Patient</th><th>Patient number</th><th>Ward</th><th>Condition</th><th>Urgency</th><th>Nursing reports</th></tr></thead>
              <tbody>
                @forelse($patients as $patient)
                  <tr>
                    <td>{{ $patient->full_name }}</td>
                    <td>{{ $patient->pt_number ?? $patient->patient_number ?? '—' }}</td>
                    <td>{{ $patient->ward?->name ?? '—' }}</td>
                    <td>{{ $patient->current_condition ?: '—' }}</td>
                    <td>{{ ucfirst($patient->urgency_status ?? 'routine') }}</td>
                    <td><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.nursing.patients.show', $patient) }}">View patient &amp; reports</a></td>
                  </tr>
                @empty
                  <tr><td colspan="6" class="text-center text-muted">No patients found in your assigned wards.</td></tr>
                @endforelse
              </tbody>
            </table>
            {{ $patients->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@include('parklane.script')
