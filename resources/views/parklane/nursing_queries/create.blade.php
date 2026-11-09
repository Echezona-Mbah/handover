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
      <div class="app-hero-header">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ auth('nurse')->check() ? route('nurse.dashboard') : route('admin.nursing.dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route(auth('nurse')->check() ? 'nurse.queries.index' : 'nursing.queries.index') }}">Nursing Queries</a></li>
          <li class="breadcrumb-item text-primary">New Query</li>
        </ol>
      </div>
      <div class="app-body">
        <div class="row justify-content-center">
          <div class="col-xl-8">
            <div class="card">
              <div class="card-header"><h5 class="card-title mb-0">Send a nursing query</h5></div>
              <div class="card-body">
                <p class="text-muted">Recipients are limited to the people your nursing role is allowed to query.</p>
                @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
                <form method="POST" action="{{ auth('nurse')->check() ? route('nurse.queries.store') : route('nursing.queries.store') }}">
                  @csrf
                  <div class="mb-3">
                    <label class="form-label" for="recipient">Recipient</label>
                    <select name="recipient" id="recipient" class="form-select" required>
                      <option value="">Select a recipient</option>
                      @foreach($recipients as $recipient)
                        <option value="{{ $recipient['type'] }}:{{ $recipient['id'] }}" @selected(old('recipient') === $recipient['type'].':'.$recipient['id'])>
                          {{ $recipient['name'] }} — {{ str_replace('_', ' ', $recipient['role']) }}{{ !empty($recipient['ward']) ? ' · '.$recipient['ward'] : '' }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3">
                    <label class="form-label" for="nursing_report_id">Related report ID (optional)</label>
                    <input name="nursing_report_id" id="nursing_report_id" type="number" class="form-control" value="{{ old('nursing_report_id') }}">
                  </div>
                  <div class="mb-3">
                    <label class="form-label" for="message">Query</label>
                    <textarea name="message" id="message" rows="5" class="form-control" required>{{ old('message') }}</textarea>
                  </div>
                  <button class="btn btn-primary" @disabled(count($recipients) === 0)>Send query</button>
                  <a class="btn btn-light" href="{{ route(auth('nurse')->check() ? 'nurse.queries.index' : 'nursing.queries.index') }}">Cancel</a>
                  @if(count($recipients) === 0)<p class="text-muted mt-2">There are no recipients available for your role.</p>@endif
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@if(auth('nurse')->check())
  @include('parklane.nurses.script')
@else
  @include('parklane.script')
@endif
