@include('parklane.head')
<div class="page-wrapper">
  @include('parklane.header')
  <div class="main-container">
    @include('parklane.sidebar')
    <div class="app-container">
      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb"><li class="breadcrumb-item text-primary">Nursing Administration Dashboard</li></ol>
      </div>
      <div class="app-body">
        <div class="card mb-3 bg-2"><div class="card-body"><div class="py-3 px-2 text-white">
          <h6>{{ now()->hour < 12 ? 'Good morning' : (now()->hour < 17 ? 'Good afternoon' : 'Good evening') }},</h6>
          <h2>{{ $admin->name }}</h2>
          <p class="mb-0">
            @switch(\App\Services\NursingHierarchy::role($admin))
              @case('hns') Head of Nursing Services @break
              @case('control_162') 162 Control Unit @break
              @case('unit_head') Nursing Unit Head @break
              @case('ward_supervision') Ward Supervisor @break
              @default {{ $admin->nursing_admin_role === 'nursing_691' ? '691 Nurse Administration' : ($admin->nursing_admin_role ? 'Nurse Management Administration' : 'Nursing Administrator') }}
            @endswitch
          </p>
        </div></div></div>

        <div class="row gx-3">
          <div class="col-xl-3 col-sm-6"><div class="card mb-3"><div class="card-body"><h2 class="text-primary">{{ $totalNurses }}</h2><p class="mb-0">Nurses in your scope</p></div></div></div>
          <div class="col-xl-3 col-sm-6"><div class="card mb-3"><div class="card-body"><h2 class="text-warning">{{ $pendingReports }}</h2><p class="mb-0">Reports awaiting action</p></div></div></div>
          <div class="col-xl-3 col-sm-6"><div class="card mb-3"><div class="card-body"><h2 class="text-success">{{ $approvedReports }}</h2><p class="mb-0">Approved reports</p></div></div></div>
          <div class="col-xl-3 col-sm-6"><div class="card mb-3"><div class="card-body"><h2 class="text-danger">{{ $declinedReports }}</h2><p class="mb-0">Declined reports</p></div></div></div>
        </div>

        <div class="card mb-3">
          <div class="card-body d-flex flex-wrap gap-2">
            <a class="btn btn-primary" href="{{ route('nursing.queries.create') }}">Query nursing staff</a>
            <a class="btn btn-outline-primary" href="{{ route('nursing.queries.index') }}">View nursing queries</a>
            @if(\App\Services\NursingHierarchy::canManageNursingDirectory($admin))
              <a class="btn btn-outline-primary" href="{{ route('admin.nursing.patients') }}">Patients</a>
              <a class="btn btn-outline-primary" href="{{ route('departments.index') }}">Manage wards</a>
              <a class="btn btn-outline-primary" href="{{ route('admin.nursing.nurses') }}">Ward nurses</a>
            @endif
          </div>
        </div>

        <div class="row gx-3">
          <div class="col-lg-8"><div class="card mb-3"><div class="card-header d-flex justify-content-between"><h5 class="card-title mb-0">Recent reports for your administration</h5><a class="btn btn-sm btn-primary" href="{{ route('admin.nursing.reports') }}">View all</a></div><div class="card-body table-responsive">
            <table class="table align-middle mb-0"><thead><tr><th>Nurse</th><th>Ward</th><th>Submitted</th><th>Status</th><th></th></tr></thead><tbody>
              @forelse($recentReports as $report)<tr><td>{{ $report->nurse->full_name ?? 'N/A' }}</td><td>{{ $report->ward->name ?? 'N/A' }}</td><td>{{ $report->submitted_at?->format('d M Y, g:i A') }}</td><td><span class="badge bg-primary">{{ str_replace('_', ' ', $report->status) }}</span></td><td><a href="{{ route('admin.nursing.reports.show', $report) }}" class="btn btn-sm btn-outline-primary">Open</a></td></tr>@empty<tr><td colspan="5" class="text-center text-muted">No reports sent to this administration yet.</td></tr>@endforelse
            </tbody></table>
          </div></div></div>
          <div class="col-lg-4"><div class="card mb-3"><div class="card-header"><h5 class="card-title mb-0">Today’s activity</h5></div><div class="card-body"><h2>{{ $todayStatistics }}</h2><p class="mb-0 text-muted">Nursing statistics submitted today</p></div></div></div>
        </div>
      </div>
    </div>
  </div>
</div>
@include('parklane.script')
