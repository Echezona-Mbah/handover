@include('parklane.nurses.head')
<div class="page-wrapper">
  @include('parklane.nurses.header')
  <div class="main-container">
    @include('parklane.nurses.sidebar')
    <div class="app-container">
      <div class="app-hero-header"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('nurse.statistics.index') }}">Statistics</a></li><li class="breadcrumb-item text-primary">Details</li></ol></div>
      <div class="app-body">
        <div class="card">
          <div class="card-header"><h5 class="card-title">Statistics for {{ $statistic->stats_date?->format('d M Y') }}</h5></div>
          <div class="card-body table-responsive">
            <table class="table table-bordered">
              <tbody>
                @foreach(['admissions','discharges','transfers','deaths','bed_occupied','bed_vacant','clinic_attendance','specialized_service_count'] as $field)
                  <tr><th>{{ ucwords(str_replace('_', ' ', $field)) }}</th><td>{{ $statistic->{$field} }}</td></tr>
                @endforeach
                <tr><th>Verified</th><td>{{ $statistic->verified_at ? $statistic->verified_at->format('d M Y g:i A') : 'Pending verification' }}</td></tr>
                <tr><th>Notes</th><td>{{ $statistic->notes ?? 'None' }}</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@include('parklane.nurses.script')

