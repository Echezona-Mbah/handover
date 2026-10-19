@include('parklane.head')

<div class="page-wrapper">
  @include('parklane.header')
  <div class="main-container">
    @include('parklane.sidebar')

    <div class="app-container">
      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item text-primary" aria-current="page">Notify Nurses</li>
        </ol>
      </div>

      <div class="app-body">
        <div class="row gx-3">
          <div class="col-lg-8 col-md-10">
            <div class="card">
              <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                  <h5 class="card-title mb-0">Send a nurse notification</h5>
                  <a href="{{ route('admin.nurse-notifications.acknowledgements') }}" class="btn btn-outline-primary btn-sm">
                    View acknowledgements
                  </a>
                </div>
              </div>
              <div class="card-body">
                @if (session('success'))
                  <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('admin.nurse-notifications.store') }}">
                  @csrf
                  <div class="mb-3">
                    <label for="title" class="form-label">Subject</label>
                    <input id="title" name="title" type="text" maxlength="150" required
                      class="form-control @error('title') is-invalid @enderror"
                      value="{{ old('title') }}">
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>

                  <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea id="message" name="message" rows="6" maxlength="5000" required
                      class="form-control @error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                    @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>

                  <div class="form-check mb-3">
                    <input id="requires_acknowledgement" name="requires_acknowledgement" type="checkbox" value="1"
                      class="form-check-input" @checked(old('requires_acknowledgement'))>
                    <label for="requires_acknowledgement" class="form-check-label">
                      Important announcement — require nurses to acknowledge receipt
                    </label>
                  </div>

                  <div class="mb-3">
                    <div class="form-check mb-2">
                      <input id="send_to_all" name="send_to_all" type="checkbox" value="1"
                        class="form-check-input" @checked(old('send_to_all'))>
                      <label for="send_to_all" class="form-check-label">Send to all nurses in my hospital</label>
                    </div>
                    @error('nurse_ids') <div class="text-danger small mb-2">{{ $message }}</div> @enderror

                    <fieldset id="nurse-recipients" @disabled(old('send_to_all'))>
                      <legend class="form-label fs-6">Or select nurses</legend>
                      @forelse ($nurses as $nurse)
                        <div class="form-check">
                          <input id="nurse-{{ $nurse->id }}" name="nurse_ids[]" type="checkbox"
                            value="{{ $nurse->id }}" class="form-check-input"
                            @checked(in_array($nurse->id, (array) old('nurse_ids', [])))>
                          <label for="nurse-{{ $nurse->id }}" class="form-check-label">
                            {{ $nurse->full_name }} <span class="text-muted">({{ $nurse->email }})</span>
                          </label>
                        </div>
                      @empty
                        <p class="text-muted mb-0">No nurses are registered at your hospital.</p>
                      @endforelse
                    </fieldset>
                  </div>

                  <button type="submit" class="btn btn-primary"
                    @disabled($nurses->isEmpty())>Send notification and email</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

      @include('parklane.script')
    </div>
  </div>
</div>

<script>
  document.getElementById('send_to_all').addEventListener('change', function () {
    document.getElementById('nurse-recipients').disabled = this.checked;
  });
</script>
