@include('parklane.head')
<div class="page-wrapper">@include('parklane.header')<div class="main-container">@include('parklane.sidebar')<div class="app-container">
  <div class="app-hero-header"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li><li class="breadcrumb-item text-primary">Nursing Reports</li></ol></div>
  <div class="app-body">
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    <div class="card"><div class="card-body table-responsive">
      <table class="table table-striped"><thead><tr><th>Date</th><th>Nurse</th><th>Ward</th><th>Type</th><th>Status</th><th></th></tr></thead><tbody>
        @forelse($reports as $report)
          <tr><td>{{ $report->report_date?->format('d M Y') }}</td><td>{{ $report->nurse->full_name ?? 'N/A' }}</td><td>{{ $report->ward->name ?? 'N/A' }}</td><td>{{ ucwords(str_replace('_', ' ', $report->report_type)) }}</td><td><span class="badge bg-primary">{{ str_replace('_', ' ', $report->status) }}</span></td><td><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.nursing.reports.show', $report) }}">Review</a></td></tr>
        @empty
          <tr><td colspan="6" class="text-center">No nursing reports found.</td></tr>
        @endforelse
      </tbody></table>{{ $reports->links() }}
    </div></div>
  </div>
</div></div></div>
@include('parklane.script')

