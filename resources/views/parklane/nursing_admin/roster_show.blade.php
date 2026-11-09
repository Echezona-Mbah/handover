@include('parklane.head')
<div class="page-wrapper">@include('parklane.header')<div class="main-container">@include('parklane.sidebar')<div class="app-container">
  <div class="app-hero-header d-flex align-items-center"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('admin.nursing.rosters') }}">Rosters</a></li><li class="breadcrumb-item text-primary">Review</li></ol><button type="button" class="btn btn-outline-secondary ms-auto no-print" onclick="window.print()"><i class="ri-printer-line"></i> Print roster</button></div>
  <div class="app-body">
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    <div class="card"><div class="card-header d-flex justify-content-between"><h5 class="card-title">{{ $roster->title }}</h5><span class="badge bg-primary">{{ $roster->status }}</span></div><div class="card-body table-responsive">
      <p><strong>Hospital:</strong> {{ $roster->ward?->hospital?->name ?? 'N/A' }} | <strong>Ward:</strong> {{ $roster->ward->name ?? 'N/A' }} | <strong>Created by:</strong> {{ $roster->creator->full_name ?? 'N/A' }}</p>
      @include('parklane.nursing_admin._roster_matrix', ['roster' => $roster])
      @if(in_array($roster->status, ['submitted', 'approved', 'published'], true))
      <form method="POST" action="{{ route('admin.nursing.rosters.approve', $roster) }}" class="text-end mt-3 no-print">@csrf
        <select name="status" class="form-select d-inline-block w-auto"><option value="approved">Approve</option><option value="published">Publish</option></select>
        <button class="btn btn-primary">Update Roster</button>
      </form>
      @endif
    </div></div>
  </div>
</div></div></div>
@include('parklane.script')
