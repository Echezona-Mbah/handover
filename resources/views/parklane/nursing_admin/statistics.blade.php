@include('parklane.head')
<div class="page-wrapper">@include('parklane.header')<div class="main-container">@include('parklane.sidebar')<div class="app-container">
  <div class="app-hero-header"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li><li class="breadcrumb-item text-primary">Nursing Statistics</li></ol></div>
  <div class="app-body">
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    <div class="card"><div class="card-body table-responsive">
      <table class="table table-striped"><thead><tr><th>Date</th><th>Ward</th><th>Admissions</th><th>Discharges</th><th>Deaths</th><th>Bed Use</th><th>Verified</th><th></th></tr></thead><tbody>
        @foreach($statistics as $statistic)
          <tr><td>{{ $statistic->stats_date?->format('d M Y') }}</td><td>{{ $statistic->ward->name ?? 'N/A' }}</td><td>{{ $statistic->admissions }}</td><td>{{ $statistic->discharges }}</td><td>{{ $statistic->deaths }}</td><td>{{ $statistic->bed_occupied }} / {{ $statistic->bed_occupied + $statistic->bed_vacant }}</td><td>{{ $statistic->verified_at ? 'Yes' : 'No' }}</td><td>@if(!$statistic->verified_at)<form method="POST" action="{{ route('admin.nursing.statistics.verify', $statistic) }}">@csrf<button class="btn btn-sm btn-primary">Verify</button></form>@endif</td></tr>
        @endforeach
      </tbody></table>{{ $statistics->links() }}
    </div></div>
  </div>
</div></div></div>
@include('parklane.script')

