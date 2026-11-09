@include('parklane.nurses.head')
@php
  $oldSchedule = json_decode(old('schedule_json', '{}'), true) ?: [];
  $nurseRosterData = $nurses->map(fn ($nurse) => [
      'id' => $nurse->id,
      'name' => $nurse->full_name,
      'staffId' => $nurse->license_number,
      'designation' => $nurse->NursingRole?->name ?? 'Ward Nursing Staff',
  ])->values()->all();
@endphp
<style>
  .roster-scroll{overflow:auto;max-height:70vh}.roster-grid{min-width:max-content;border-collapse:separate;border-spacing:0;font-size:.78rem}
  .roster-grid th,.roster-grid td{min-width:48px;padding:.3rem;border:1px solid #d8dee8;text-align:center;vertical-align:middle}
  .roster-grid thead th{position:sticky;top:0;z-index:2;background:#edf2f7}
  .roster-grid .staff-name{position:sticky;left:0;z-index:1;min-width:210px;text-align:left;background:#fff}
  .roster-grid thead .staff-name{z-index:3;background:#edf2f7}.roster-grid .staff-id{min-width:110px}
  .roster-grid .division-row th{position:static;background:#e4edf5;text-align:left}
  .roster-grid select{min-width:48px;padding:.25rem;font-size:.75rem}
  .roster-print-heading{display:none}
  @media print{.no-print,.page-wrapper>.main-container>#sidebar,.app-hero-header{display:none!important}.roster-scroll{max-height:none;overflow:visible}.roster-grid{font-size:7pt}.roster-grid th,.roster-grid td{min-width:0;padding:2px}.roster-grid select{appearance:none;border:0;padding:0;width:25px}.roster-print-heading{display:block;text-align:center;margin-bottom:12px}.app-container{width:100%!important}}
</style>
<div class="page-wrapper">
  @include('parklane.nurses.header')
  <div class="main-container">
    @include('parklane.nurses.sidebar')
    <div class="app-container">
      <div class="app-hero-header"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('nurse.rosters.index') }}">Duty Rosters</a></li><li class="breadcrumb-item text-primary">Create monthly roster</li></ol></div>
      <div class="app-body">
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <form id="roster-form" method="POST" action="{{ route('nurse.rosters.store') }}" class="card">
          @csrf
          <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div><h5 class="card-title mb-1">Monthly ward duty roster</h5><small>{{ $hospital?->name ?? 'Hospital' }} · {{ $ward?->name ?? 'Ward' }}</small></div>
            <button type="button" class="btn btn-outline-secondary no-print" onclick="window.print()"><i class="ri-printer-line"></i> Print</button>
          </div>
          <div class="card-body">
            <div class="row gx-3 no-print">
              <div class="col-lg-4 mb-3"><label class="form-label" for="title">Roster title</label><input id="title" name="title" class="form-control" value="{{ old('title') }}" placeholder="Optional; defaults to ward and period"></div>
              <div class="col-md-4 col-lg-3 mb-3"><label class="form-label" for="start_date">Period starts</label><input type="date" id="start_date" name="start_date" class="form-control" value="{{ old('start_date', $startDate->toDateString()) }}" required></div>
              <div class="col-md-4 col-lg-3 mb-3"><label class="form-label" for="end_date">Period ends (maximum 31 days)</label><input type="date" id="end_date" name="end_date" class="form-control" value="{{ old('end_date', $endDate->toDateString()) }}" required></div>
              <div class="col-md-4 col-lg-2 mb-3 d-flex align-items-end"><button type="button" class="btn btn-outline-primary w-100" id="refresh-period">Update dates</button></div>
            </div>
            <div class="roster-print-heading">
              <h4>{{ $hospital?->name ?? 'Hospital' }}</h4>
              <strong>MONTHLY DUTY ROSTER — {{ $ward?->name ?? 'Ward' }}</strong>
              <div id="print-period">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</div>
            </div>
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
              <div><strong>{{ $ward?->name ?? 'Ward' }}</strong> · {{ $hospital?->name ?? 'Hospital' }}</div>
              <div class="text-muted small">D Day · M Morning · A Afternoon · N Night · O Off · AL Annual Leave · SL Sick Leave</div>
            </div>
            @if($nurses->isEmpty())
              <div class="alert alert-warning">There are no active nurses assigned to this ward.</div>
            @else
              <input type="hidden" name="schedule_json" id="schedule_json">
              <div class="roster-scroll">
                <table class="table roster-grid mb-0" id="roster-grid">
                  <thead><tr id="date-heading"><th class="staff-name">Staff name</th><th class="staff-id">ID No.</th>
                    @foreach($days as $day)<th data-date="{{ $day->format('Y-m-d') }}"><span>{{ $day->format('D') }}</span><br>{{ $day->format('j') }}</th>@endforeach
                  </tr></thead>
                  <tbody id="roster-body">
                    @foreach($nurses->groupBy(fn ($nurse) => $nurse->NursingRole?->name ?? 'Ward Nursing Staff') as $designation => $group)
                      <tr class="division-row"><th colspan="{{ $days->count() + 2 }}">{{ $designation }}</th></tr>
                      @foreach($group as $nurse)
                        <tr data-nurse-row="{{ $nurse->id }}">
                          <th class="staff-name">{{ $nurse->full_name }}</th><td class="staff-id">{{ $nurse->license_number ?: '—' }}</td>
                          @foreach($days as $day)
                            <td><select aria-label="{{ $nurse->full_name }} {{ $day->format('D j M') }}" data-nurse-id="{{ $nurse->id }}" data-duty-date="{{ $day->format('Y-m-d') }}"><option value="">—</option>@foreach(['D' => 'D Day', 'M' => 'M Morning', 'A' => 'A Afternoon', 'N' => 'N Night', 'O' => 'O Off', 'AL' => 'AL Annual leave', 'SL' => 'SL Sick leave'] as $value => $label)<option value="{{ $value }}" @selected(($oldSchedule[$nurse->id][$day->format('Y-m-d')] ?? '') === $value)>{{ $value }}</option>@endforeach</select></td>
                          @endforeach
                        </tr>
                      @endforeach
                    @endforeach
                  </tbody>
                </table>
              </div>
            @endif
          </div>
          <div class="card-footer d-flex justify-content-between align-items-center no-print">
            <a href="{{ route('nurse.rosters.index') }}" class="btn btn-light">Cancel</a>
            <button class="btn btn-primary" @disabled($nurses->isEmpty())>Save draft roster</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
(() => {
  const nurses = @json($nurseRosterData);
  const initialSchedule = @json($oldSchedule);
  const savedSchedule = {};
  Object.entries(initialSchedule).forEach(([nurseId, days]) => {
    Object.entries(days || {}).forEach(([date, shift]) => {
      savedSchedule[`${nurseId}|${date}`] = shift;
    });
  });
  let initialized = false;
  const shiftOptions = [['', '—'], ['D', 'D'], ['M', 'M'], ['A', 'A'], ['N', 'N'], ['O', 'O'], ['AL', 'AL'], ['SL', 'SL']];
  const startInput = document.getElementById('start_date');
  const endInput = document.getElementById('end_date');
  const body = document.getElementById('roster-body');
  const heading = document.getElementById('date-heading');
  const printPeriod = document.getElementById('print-period');
  if (!startInput || !endInput || !body || !heading) return;

  const makeCell = (date, nurse, savedSchedule) => {
    const cell = document.createElement('td');
    const select = document.createElement('select');
    select.dataset.nurseId = nurse.id;
    select.dataset.dutyDate = date.iso;
    select.setAttribute('aria-label', `${nurse.name} ${date.label}`);
    shiftOptions.forEach(([value, label]) => {
      const option = document.createElement('option');
      option.value = value;
      option.textContent = label;
      option.selected = value === (savedSchedule[`${nurse.id}|${date.iso}`] ?? '');
      select.append(option);
    });
    cell.append(select);
    return cell;
  };

  const drawRoster = () => {
    if (!startInput.value || !endInput.value) return;
    if (initialized) {
      body.querySelectorAll('select[data-nurse-id]').forEach(select => {
        savedSchedule[`${select.dataset.nurseId}|${select.dataset.dutyDate}`] = select.value;
      });
    }
    const start = new Date(`${startInput.value}T00:00:00`);
    const end = new Date(`${endInput.value}T00:00:00`);
    const dates = [];
    for (const date = new Date(start); date <= end && dates.length <= 31; date.setDate(date.getDate() + 1)) {
      dates.push({
        iso: date.toISOString().slice(0, 10),
        day: date.getDate(),
        label: date.toLocaleDateString(undefined, { weekday: 'short', day: 'numeric', month: 'short' }),
        weekday: date.toLocaleDateString(undefined, { weekday: 'short' }),
      });
    }
    if (!dates.length || dates.length > 31) {
      window.alert('Choose a roster period between 1 and 31 days.');
      return;
    }

    heading.replaceChildren();
    const staffHeading = document.createElement('th');
    staffHeading.className = 'staff-name';
    staffHeading.textContent = 'Staff name';
    const idHeading = document.createElement('th');
    idHeading.className = 'staff-id';
    idHeading.textContent = 'ID No.';
    heading.append(staffHeading, idHeading);
    dates.forEach(date => {
      const th = document.createElement('th');
      const weekday = document.createElement('span');
      weekday.textContent = date.weekday;
      th.append(weekday, document.createElement('br'), document.createTextNode(date.day));
      heading.append(th);
    });
    body.replaceChildren();
    const groups = new Map();
    nurses.forEach(nurse => {
      if (!groups.has(nurse.designation)) groups.set(nurse.designation, []);
      groups.get(nurse.designation).push(nurse);
    });
    groups.forEach((staff, designation) => {
      const groupRow = document.createElement('tr');
      groupRow.className = 'division-row';
      const groupCell = document.createElement('th');
      groupCell.colSpan = dates.length + 2;
      groupCell.textContent = designation;
      groupRow.append(groupCell);
      body.append(groupRow);
      staff.forEach(nurse => {
        const row = document.createElement('tr');
        const name = document.createElement('th');
        name.className = 'staff-name';
        name.textContent = nurse.name;
        const staffId = document.createElement('td');
        staffId.className = 'staff-id';
        staffId.textContent = nurse.staffId || '—';
        row.append(name, staffId);
        dates.forEach(date => row.append(makeCell(date, nurse, savedSchedule)));
        body.append(row);
      });
    });
    if (printPeriod) printPeriod.textContent = `${startInput.value} - ${endInput.value}`;
    initialized = true;
  };

  document.getElementById('refresh-period')?.addEventListener('click', drawRoster);
  startInput.addEventListener('change', drawRoster);
  endInput.addEventListener('change', drawRoster);
  document.getElementById('roster-form')?.addEventListener('submit', event => {
    const schedule = {};
    body.querySelectorAll('select[data-nurse-id]').forEach(select => {
      const nurseId = select.dataset.nurseId;
      if (!schedule[nurseId]) schedule[nurseId] = {};
      schedule[nurseId][select.dataset.dutyDate] = select.value;
    });
    const scheduled = Object.values(schedule).some(days => Object.values(days).some(Boolean));
    if (!scheduled) {
      event.preventDefault();
      window.alert('Select at least one shift before saving the roster.');
      return;
    }
    document.getElementById('schedule_json').value = JSON.stringify(schedule);
  });
  drawRoster();
})();
</script>
@include('parklane.nurses.script')
