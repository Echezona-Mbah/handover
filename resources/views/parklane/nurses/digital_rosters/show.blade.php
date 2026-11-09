@include('parklane.nurses.head')
<div class="page-wrapper">
  @include('parklane.nurses.header')
  <div class="main-container">
    @include('parklane.nurses.sidebar')
    <div class="app-container">
      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('nurse.rosters.index') }}">Duty Rosters</a></li><li class="breadcrumb-item text-primary">{{ $roster->title }}</li></ol>
        <button type="button" class="btn btn-outline-secondary ms-auto no-print" onclick="window.print()"><i class="ri-printer-line"></i> Print roster</button>
      </div>
      <div class="app-body">
        @if(session('success'))<div class="alert alert-success no-print">{{ session('success') }}</div>@endif
        <div class="card">
          <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div><h5 class="card-title mb-1">{{ $roster->title }}</h5><small>{{ $roster->ward?->hospital?->name ?? 'Hospital' }} · {{ $roster->ward?->name ?? 'Ward' }} · Prepared by {{ $roster->creator?->full_name ?? 'Nurse' }}</small></div>
            <span class="badge bg-primary">{{ ucfirst($roster->status) }}</span>
          </div>
          <div class="card-body">
            @include('parklane.nursing_admin._roster_matrix', ['roster' => $roster])
          </div>
          @if($roster->status === 'draft')
            <div class="card-footer text-end no-print">
              <form method="POST" action="{{ route('nurse.rosters.submit', $roster) }}">@csrf<button class="btn btn-primary">Submit for Approval</button></form>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@include('parklane.nurses.script')
