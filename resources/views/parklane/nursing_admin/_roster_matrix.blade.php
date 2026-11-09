@php
  $days = \Carbon\CarbonPeriod::create($roster->start_date, $roster->end_date);
  $dayCount = $roster->start_date->diffInDays($roster->end_date) + 1;
  $assignmentCells = $roster->assignments->keyBy(fn ($assignment) => $assignment->nurse_id.'|'.$assignment->duty_date->format('Y-m-d'));
  $staffGroups = $roster->assignments
      ->pluck('nurse')
      ->filter()
      ->unique('id')
      ->sortBy('full_name')
      ->groupBy(fn ($nurse) => $nurse->NursingRole?->name ?? 'Ward Nursing Staff');
@endphp
<style>
  .roster-scroll{overflow:auto;max-height:72vh}.roster-grid{min-width:max-content;border-collapse:separate;border-spacing:0;font-size:.78rem}
  .roster-grid th,.roster-grid td{min-width:48px;padding:.35rem;border:1px solid #d8dee8;text-align:center;vertical-align:middle}
  .roster-grid thead th{position:sticky;top:0;z-index:2;background:#edf2f7}
  .roster-grid .staff-name{position:sticky;left:0;z-index:1;min-width:220px;text-align:left;background:#fff}
  .roster-grid thead .staff-name{z-index:3;background:#edf2f7}.roster-grid .staff-id{min-width:110px}
  .roster-grid .division-row th{position:static;background:#e4edf5;text-align:left}
  .roster-shift{font-weight:700}.roster-shift-D{color:#176b3a}.roster-shift-M{color:#1454a0}.roster-shift-A{color:#7544a0}.roster-shift-N{color:#293247}.roster-shift-AL,.roster-shift-SL{color:#a44920}
  .roster-print-heading{display:none}
  @media print{.no-print,#sidebar,.app-hero-header{display:none!important}.roster-print-heading{display:block;text-align:center;margin-bottom:12px}.roster-scroll{max-height:none;overflow:visible}.roster-grid{font-size:7pt}.roster-grid th,.roster-grid td{min-width:0;padding:2px}.roster-grid .staff-name{min-width:120px}.app-container{width:100%!important}}
</style>
<div class="roster-print-heading">
  <h3>{{ $roster->ward?->hospital?->name ?? 'Hospital' }}</h3>
  <h4>MONTHLY DUTY ROSTER</h4>
  <div>{{ $roster->ward?->name ?? 'Ward' }} · {{ $roster->start_date?->format('d M Y') }} - {{ $roster->end_date?->format('d M Y') }}</div>
</div>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
  <div><strong>{{ $roster->ward?->name ?? 'Ward' }}</strong> · {{ $roster->ward?->hospital?->name ?? 'Hospital' }}</div>
  <div class="text-muted small">D Day · M Morning · A Afternoon · N Night · O Off · AL Annual Leave · SL Sick Leave</div>
</div>
<div class="roster-scroll">
  <table class="table roster-grid mb-0">
    <thead><tr><th class="staff-name">Staff name</th><th class="staff-id">ID No.</th>
      @foreach($days as $day)<th><span>{{ $day->format('D') }}</span><br>{{ $day->format('j') }}</th>@endforeach
    </tr></thead>
    <tbody>
      @forelse($staffGroups as $designation => $staff)
        <tr class="division-row"><th colspan="{{ $dayCount + 2 }}">{{ $designation }}</th></tr>
        @foreach($staff as $nurse)
          <tr>
            <th class="staff-name">{{ $nurse->full_name }}</th><td class="staff-id">{{ $nurse->license_number ?: '—' }}</td>
            @foreach($days as $day)
              @php($cell = $assignmentCells->get($nurse->id.'|'.$day->format('Y-m-d')))
              <td class="roster-shift roster-shift-{{ $cell?->shift_type }}">{{ $cell?->shift_type ?? '—' }}</td>
            @endforeach
          </tr>
        @endforeach
      @empty
        <tr><td colspan="{{ $dayCount + 2 }}" class="text-center text-muted">No shifts have been assigned in this roster.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
