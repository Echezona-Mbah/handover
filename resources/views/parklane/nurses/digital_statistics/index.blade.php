@include('parklane.nurses.head')
<div class="page-wrapper">
  @include('parklane.nurses.header')
  <div class="main-container">
    @include('parklane.nurses.sidebar')
    <div class="app-container">
      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('nurse.dashboard') }}">Home</a></li><li class="breadcrumb-item text-primary">Statistics</li></ol>
        <a href="{{ route('nurse.statistics.create') }}" class="btn btn-primary ms-auto">New Statistics</a>
      </div>
      <div class="app-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-warning">{{ session('error') }}</div> @endif
        <div class="card"><div class="card-body table-responsive">
          <table class="table table-striped">
            <thead><tr><th>Date</th><th>Admissions</th><th>Discharges</th><th>Transfers</th><th>Deaths</th><th>Occupancy</th><th></th></tr></thead>
            <tbody>
              @forelse($statistics as $statistic)
                <tr>
                  <td>{{ $statistic->stats_date?->format('d M Y') }}</td>
                  <td>{{ $statistic->admissions }}</td>
                  <td>{{ $statistic->discharges }}</td>
                  <td>{{ $statistic->transfers }}</td>
                  <td>{{ $statistic->deaths }}</td>
                  <td>{{ $statistic->bed_occupied }} occupied / {{ $statistic->bed_vacant }} vacant</td>
                  <td><a class="btn btn-sm btn-outline-primary" href="{{ route('nurse.statistics.show', $statistic) }}">View</a></td>
                </tr>
              @empty
                <tr><td colspan="7" class="text-center">No statistics submitted yet.</td></tr>
              @endforelse
            </tbody>
          </table>
          {{ $statistics->links() }}
        </div></div>
      </div>
    </div>
  </div>
</div>
@include('parklane.nurses.script')
