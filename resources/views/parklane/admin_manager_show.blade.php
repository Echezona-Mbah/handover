@include('parklane.head')
<div class="page-wrapper">
  @include('parklane.header')
  <div class="main-container">
    @include('parklane.sidebar')
    <div class="app-container">
      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('add_admin_user') }}">Admin Managers</a></li><li class="breadcrumb-item text-primary">{{ $user->name }}</li></ol>
        <a href="{{ route('add_admin_user') }}" class="btn btn-light ms-auto">Back to administrators</a>
      </div>
      <div class="app-body">
        <div class="card mb-4">
          <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
              <span class="badge bg-primary mb-2">{{ $roleLabel }}</span>
              <h3 class="mb-1">{{ $user->name }}</h3>
              <div class="text-muted">{{ $user->email }}</div>
              <div class="text-muted">{{ $user->hospitals?->name ?? 'Hospital' }}</div>
            </div>
            @if($role === 'unit_head')
              <a class="btn btn-outline-primary" href="{{ route('admin.nursing.dashboard') }}">Open nursing dashboard</a>
            @endif
          </div>
        </div>

        @if($role === 'unit_head')
          <div class="alert alert-info">This overview is limited to wards assigned to this unit head. It includes all activity recorded for those wards, regardless of report recipient.</div>
          <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3"><div class="card h-100"><div class="card-body"><h2>{{ $managedWards->count() }}</h2><span>Assigned wards</span></div></div></div>
            <div class="col-sm-6 col-xl-3"><div class="card h-100"><div class="card-body"><h2>{{ $managedWards->sum('patients_count') }}</h2><span>Patients in those wards</span></div></div></div>
            <div class="col-sm-6 col-xl-3"><div class="card h-100"><div class="card-body"><h2>{{ $managedWards->sum('nurses_count') }}</h2><span>Nurses and supervisors</span></div></div></div>
            <div class="col-sm-6 col-xl-3"><div class="card h-100"><div class="card-body"><h2>{{ $managedWards->sum('nursing_reports_count') }}</h2><span>Nursing reports</span></div></div></div>
          </div>

          <section class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center"><h5 class="card-title mb-0">Assigned wards and ward staff</h5><a href="{{ route('admin.nursing.nurses') }}">View ward nurses</a></div>
            <div class="card-body">
              @forelse($managedWards as $ward)
                <div class="border rounded p-3 mb-3">
                  <div class="d-flex flex-wrap justify-content-between gap-2">
                    <h5 class="mb-0">{{ $ward->name }}</h5>
                    <span class="text-muted">{{ $ward->patients_count }} patients · {{ $ward->nurses_count }} nurses · {{ $ward->nursing_reports_count }} reports</span>
                  </div>
                  <div class="mt-2">
                    @forelse($ward->nurses as $nurse)
                      <span class="badge {{ \App\Services\NursingHierarchy::role($nurse) === 'ward_supervision' ? 'bg-warning text-dark' : 'bg-light text-dark' }} me-1 mb-1">
                        {{ $nurse->full_name }} — {{ \Illuminate\Support\Str::headline(\App\Services\NursingHierarchy::role($nurse) ?? 'ward nurse') }}
                      </span>
                    @empty
                      <span class="text-muted">No nurses assigned to this ward.</span>
                    @endforelse
                  </div>
                </div>
              @empty
                <p class="text-muted mb-0">No wards are currently assigned to this unit head.</p>
              @endforelse
            </div>
          </section>

          <section class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center"><h5 class="card-title mb-0">Recent patient and nursing reports</h5><a href="{{ route('admin.nursing.reports') }}">Open reports</a></div>
            <div class="card-body table-responsive">
              <table class="table table-striped align-middle">
                <thead><tr><th>Submitted</th><th>Patient</th><th>Ward</th><th>Nurse</th><th>Report</th><th>Status</th></tr></thead>
                <tbody>
                  @forelse($recentReports as $report)
                    <tr>
                      <td>{{ $report->submitted_at?->format('d M Y, g:i A') ?? $report->report_date?->format('d M Y') }}</td>
                      <td>
                        @if($report->patient)
                          <a href="{{ route('admin.nursing.patients.show', $report->patient) }}">{{ $report->patient->full_name }}</a>
                        @else
                          General ward report
                        @endif
                      </td>
                      <td>{{ $report->ward?->name ?? '—' }}</td>
                      <td>{{ $report->nurse?->full_name ?? '—' }}</td>
                      <td><a href="{{ route('admin.nursing.reports.show', $report) }}">{{ ucwords(str_replace('_', ' ', $report->report_type)) }}</a></td>
                      <td>{{ str_replace('_', ' ', $report->status) }}</td>
                    </tr>
                  @empty
                    <tr><td colspan="6" class="text-center text-muted">No reports have been recorded for the assigned wards.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </section>

          <section class="card">
            <div class="card-header"><h5 class="card-title mb-0">Recent ward queries</h5></div>
            <div class="card-body">
              @forelse($recentQueries as $query)
                <article class="border-bottom pb-3 mb-3">
                  <div class="d-flex justify-content-between gap-2"><strong>{{ $query->ward?->name ?? 'Ward query' }}</strong><span class="badge bg-secondary">{{ ucfirst($query->status) }}</span></div>
                  <p class="mt-2 mb-1">{{ $query->message }}</p>
                  <small class="text-muted">{{ $query->created_at?->format('d M Y, g:i A') }}{{ $query->nursing_report_id ? ' · Related report #'.$query->nursing_report_id : '' }}</small>
                </article>
              @empty
                <p class="text-muted mb-0">No queries have been recorded for the assigned wards.</p>
              @endforelse
            </div>
          </section>
        @else
          <div class="card"><div class="card-body"><h5>Administrator profile</h5><p class="mb-0">Responsibility: {{ $roleLabel }}</p></div></div>
        @endif
      </div>
    </div>
  </div>
</div>
@include('parklane.script')
