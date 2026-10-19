@include('parklane.head')
    <div class="page-wrapper">
      @include('parklane.header')
      <div class="main-container">
        @include('parklane.sidebar')

        <div class="app-container">
          <div class="app-hero-header d-flex align-items-center">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
                <a href="{{ route('dashboard') }}">Home</a>
              </li>
              <li class="breadcrumb-item text-primary" aria-current="page">My Profile</li>
            </ol>
          </div>

          <div class="app-body">
            <div class="row gx-3">
              <div class="col-xl-12">
                <div class="card">
                  <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="row gx-3">

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Profile Picture</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <img
                                            id="profile_picture_preview"
                                            src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('assets/images/user2.png') }}"
                                            class="rounded-5"
                                            width="64" height="64"
                                            style="object-fit: cover;"
                                            alt="Profile picture"
                                        >
                                        <div>
                                            <input type="file" name="profile_picture" id="profile_picture" accept="image/png,image/jpeg,image/webp" class="form-control form-control-sm">
                                            @if ($user->profile_picture)
                                                <div class="form-check mt-1">
                                                    <input class="form-check-input" type="checkbox" name="remove_picture" value="1" id="remove_picture">
                                                    <label class="form-check-label small" for="remove_picture">Remove current picture</label>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @error('profile_picture')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label" for="name">Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-account-pin-circle-line"></i></span>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}">
                                    </div>
                                    @error('name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label" for="email">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-mail-line"></i></span>
                                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" readonly>
                                    </div>
                                    @error('email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>

                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="app-footer bg-white">
            <span>© 2026</span>
          </div>
        </div>
      </div>
    </div>

    <script>
      document.getElementById('profile_picture').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
          document.getElementById('profile_picture_preview').src = URL.createObjectURL(file);
        }
      });
    </script>
@include('parklane.script')