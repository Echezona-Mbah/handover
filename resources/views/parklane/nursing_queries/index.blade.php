@if(auth('nurse')->check())
  @include('parklane.nurses.head')
@else
  @include('parklane.head')
@endif
<div class="page-wrapper">
  @if(auth('nurse')->check())
    @include('parklane.nurses.header')
  @else
    @include('parklane.header')
  @endif
  <div class="main-container">
    @if(auth('nurse')->check())
      @include('parklane.nurses.sidebar')
    @else
      @include('parklane.sidebar')
    @endif
    <div class="app-container">
      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ auth('nurse')->check() ? route('nurse.dashboard') : route('admin.nursing.dashboard') }}">Dashboard</a></li><li class="breadcrumb-item text-primary">Nursing Queries</li></ol>
        @unless(auth('nurse')->check())
          <a class="btn btn-primary ms-auto" href="{{ route($routePrefix.'.create') }}">New query</a>
        @endunless
      </div>
      <div class="app-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @forelse($queries as $query)
          <div class="card mb-3">
            <div class="card-body">
              @php($transferDetails = json_decode($query->message, true))
              @php($isWardTransfer = ($transferDetails['workflow'] ?? null) === 'ward_patient_transfer')
              <div class="d-flex justify-content-between gap-2">
                <strong>{{ ucfirst($query->status) }}</strong>
                <small class="text-muted">{{ $query->created_at?->format('d M Y, g:i A') }}</small>
              </div>
              @if($isWardTransfer)
                <p class="mt-2 mb-1"><strong>Ward transfer:</strong> {{ $transferDetails['patient_name'] ?? 'Patient' }} from {{ $transferDetails['source_ward_name'] ?? 'another ward' }} to {{ $query->ward?->name ?? 'receiving ward' }}.</p>
                @if(!empty($transferDetails['reason']))<p class="mb-1">{{ $transferDetails['reason'] }}</p>@endif
                @if(auth('nurse')->check() && $query->status === 'pending')<a class="btn btn-sm btn-outline-primary mt-2" href="{{ route('nurse.patient') }}">Open ward transfer inbox</a>@endif
              @else
                <p class="mt-2 mb-1">{{ $query->message }}</p>
              @endif
              <small class="text-muted">Ward: {{ $query->ward?->name ?? 'Not specified' }}{{ $query->nursing_report_id ? ' · Report #'.$query->nursing_report_id : '' }}</small>
              @foreach($query->responses as $response)
                <div class="border-start ps-3 mt-3">{{ $response->message }}<br><small class="text-muted">{{ $response->created_at?->format('d M Y, g:i A') }}</small></div>
              @endforeach
              @if(!$isWardTransfer && $query->recipient_type === \App\Services\NursingHierarchy::type($actor) && $query->recipient_id == $actor->id && $query->status !== 'closed')
                <form class="mt-3" method="POST" action="{{ route($routePrefix.'.respond', $query) }}">
                  @csrf
                  <label class="form-label" for="response-{{ $query->id }}">Response</label>
                  <textarea class="form-control" id="response-{{ $query->id }}" name="message" required></textarea>
                  <button class="btn btn-sm btn-primary mt-2">Respond</button>
                </form>
              @endif
              @if(!$isWardTransfer && $query->sender_type === \App\Services\NursingHierarchy::type($actor) && $query->sender_id == $actor->id && $query->status !== 'closed')
                <form class="mt-2" method="POST" action="{{ route($routePrefix.'.close', $query) }}">
                  @csrf
                  <button class="btn btn-sm btn-outline-secondary">Close query</button>
                </form>
              @endif
            </div>
          </div>
        @empty
          <div class="card"><div class="card-body text-center text-muted">No nursing queries yet.</div></div>
        @endforelse
        {{ $queries->links() }}
      </div>
    </div>
  </div>
</div>
@if(auth('nurse')->check())
  @include('parklane.nurses.script')
@else
  @include('parklane.script')
@endif
