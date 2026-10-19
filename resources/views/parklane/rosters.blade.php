@include('parklane.head')
<div class="page-wrapper">@include('parklane.header')<div class="main-container">@include('parklane.sidebar')<div class="app-container">
  <div class="app-hero-header"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li><li class="breadcrumb-item text-primary">Duty Rosters</li></ol></div>
  <div class="app-body">
    <div class="card"><div class="card-body table-responsive">
      <table class="table table-striped"><thead><tr><th>Title</th><th>Ward</th><th>Period</th><th>Status</th><th></th></tr></thead><tbody>
        @foreach($rosters as $roster)
          <tr><td>{{ $roster->title }}</td><td>{{ $roster->ward->name ?? 'N/A' }}</td><td>{{ $roster->start_date?->format('d M Y') }} - {{ $roster->end_date?->format('d M Y') }}</td><td><span class="badge bg-primary">{{ $roster->status }}</span></td><td><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.nursing.rosters.show', $roster) }}">Open</a></td></tr>
        @endforeach
      </tbody></table>{{ $rosters->links() }}
    </div></div>
  </div>
</div></div></div>
@include('parklane.script')

