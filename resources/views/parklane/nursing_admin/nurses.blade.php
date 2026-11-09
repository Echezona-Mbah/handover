@include('parklane.head')
<div class="page-wrapper">
  @include('parklane.header')
  <div class="main-container">
    @include('parklane.sidebar')
    <div class="app-container">
      <div class="app-hero-header"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('admin.nursing.dashboard') }}">Nursing Dashboard</a></li><li class="breadcrumb-item text-primary">Ward Nurses</li></ol></div>
      <div class="app-body">
        <div class="card">
          <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="card-title mb-0">Nurses in your wards</h5>
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
              <thead><tr><th>Name</th><th>Ward</th><th>Responsibility</th><th>Contact</th><th>Status</th></tr></thead>
              <tbody>
                @forelse($nurses as $nurse)
                  <tr>
                    <td>{{ $nurse->full_name }}</td>
                    <td>{{ $nurse->ward?->name ?? '—' }}</td>
                    <td>{{ \Illuminate\Support\Str::headline(\App\Services\NursingHierarchy::role($nurse) ?? 'ward nurse') }}</td>
                    <td>{{ $nurse->phone_number ?? $nurse->email }}</td>
                    <td>{{ $nurse->status ?? '—' }}</td>
                  </tr>
                @empty
                  <tr><td colspan="5" class="text-center text-muted">No nurses found in your assigned wards.</td></tr>
                @endforelse
              </tbody>
            </table>
            {{ $nurses->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@include('parklane.script')
