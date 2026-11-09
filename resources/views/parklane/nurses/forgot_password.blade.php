@include('parklane.nurses.head')
<main class="container py-5" style="max-width: 560px;">
  <div class="card">
    <div class="card-header"><h1 class="h5 mb-0">Set up or reset your password</h1></div>
    <div class="card-body">
      @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
      @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
      <p>Enter your nurse account email and we will send a secure password link.</p>
      <form method="POST" action="{{ route('nurse.password.email') }}">
        @csrf
        <label class="form-label" for="email">Email address</label>
        <input class="form-control mb-3" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
        <button class="btn btn-primary" type="submit">Send password link</button>
        <a class="btn btn-link" href="{{ route('nurse.login') }}">Back to login</a>
      </form>
    </div>
  </div>
</main>
@include('parklane.nurses.script')
