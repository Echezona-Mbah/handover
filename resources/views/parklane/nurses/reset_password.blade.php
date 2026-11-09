@include('parklane.nurses.head')
<main class="container py-5" style="max-width: 560px;">
  <div class="card">
    <div class="card-header"><h1 class="h5 mb-0">Set your nurse account password</h1></div>
    <div class="card-body">
      @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
      <form method="POST" action="{{ route('nurse.password.reset.submit') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <label class="form-label" for="email">Login email</label>
        <input class="form-control mb-3" id="email" type="email" name="email" value="{{ old('email', $email) }}" required autocomplete="email">
        <label class="form-label" for="password">New password</label>
        <input class="form-control mb-3" id="password" type="password" name="password" required autocomplete="new-password">
        <label class="form-label" for="password_confirmation">Confirm new password</label>
        <input class="form-control mb-3" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
        <button class="btn btn-primary" type="submit">Save password</button>
      </form>
    </div>
  </div>
</main>
@include('parklane.nurses.script')
