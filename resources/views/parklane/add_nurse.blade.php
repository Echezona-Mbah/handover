@include('parklane.head')
  <!-- Loading starts -->
    {{-- <div id="loading-wrapper">
      <div class='spin-wrapper'>
        <div class='spin'>
          <div class='inner'></div>
        </div>
        <div class='spin'>
          <div class='inner'></div>
        </div>
        <div class='spin'>
          <div class='inner'></div>
        </div>
        <div class='spin'>
          <div class='inner'></div>
        </div>
        <div class='spin'>
          <div class='inner'></div>
        </div>
        <div class='spin'>
          <div class='inner'></div>
        </div>
      </div>
    </div> --}}
    <!-- Loading ends -->
  
    <!-- Page wrapper starts -->
    <div class="page-wrapper">

      <!-- App header starts -->
      @include('parklane.header')
      <!-- App header ends -->

      <!-- Main container starts -->
      <div class="main-container">

        <!-- Sidebar wrapper starts -->
        @include('parklane.sidebar')

        <!-- Sidebar wrapper ends -->

        <!-- App container starts -->
        <div class="app-container">

          <!-- App hero header starts -->
          <div class="app-hero-header d-flex align-items-center">

            <!-- Breadcrumb starts -->
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
                <a href="index.html">Home</a>
              </li>
              <li class="breadcrumb-item text-primary" aria-current="page">
                Dashboard
              </li>
            </ol>
            <!-- Breadcrumb ends -->

            <!-- Sales stats starts -->
            {{-- <div class="ms-auto d-lg-flex d-none flex-row">
              <div class="d-flex flex-row gap-1 day-sorting">
                <button class="btn btn-sm btn-primary">Today</button>
                <button class="btn btn-sm">7d</button>
                <button class="btn btn-sm">2w</button>
                <button class="btn btn-sm">1m</button>
                <button class="btn btn-sm">3m</button>
                <button class="btn btn-sm">6m</button>
                <button class="btn btn-sm">1y</button>
              </div>
            </div> --}}
            <!-- Sales stats ends -->

          </div>

                    <!-- App body starts -->
                    <div class="app-body">

                        <!-- Row starts -->
                        <div class="row gx-3">
                          <div class="col-xl-12">
                            <div class="card">
                              <div class="card-body">

                                <h3>Register Nurse</h3>
                            

                                <div id="toast-container"></div>

                                @if(session('success'))
                                  <div class="alert alert-success">{{ session('success') }}</div>
                                @endif

                                @if($errors->any())
                                  <div class="alert alert-danger">
                                    <ul class="mb-0">
                                      @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                                    </ul>
                                  </div>
                                @endif

                              
                                

                                <form action="{{ route('storeNurse.add_nurse') }}" method="POST">
                                    @csrf

                                    <div class="row gx-3">
                                        <!-- Full Name -->
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="fullname">Full Name</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="ri-account-pin-circle-line"></i>
                                                    </span>
                                                    <input type="text" id="fullname" name="full_name" value="{{ old('full_name') }}" placeholder="Enter nurse's full name" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="email">Email</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="ri-account-pin-circle-line"></i>
                                                    </span>
                                                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Enter nurse email" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Nurse Department -->
                                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                              <div class="mb-3">
                                                  <label class="form-label">Nurse Department</label>
                                                  <select name="nurse_department_id" class="form-select">
                                                      <option value="">Select department (optional)</option>
                                                      @foreach($departments as $dept)
                                                          <option value="{{ $dept->id }}" @selected(old('nurse_department_id') == $dept->id)>{{ $dept->name }}</option>
                                                      @endforeach
                                                  </select>
                                              </div>
                                            </div>

                                            <!-- Ward -->
                                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                              <div class="mb-3">
                                                  <label class="form-label">Assigned ward / clinic <span class="text-danger">*</span></label>
                                                  <select name="ward_id" id="ward_id" class="form-select" required>
                                                      <option value="">Select a ward, clinic, or theatre</option>
                                                      @foreach($wards as $ward)
                                                          <option value="{{ $ward->id }}" data-linen-color="{{ $ward->linen_color }}" @selected(old('ward_id') == $ward->id)>
                                                              {{ $ward->category }} — {{ $ward->name }}{{ $ward->linen_color ? ' · Linen: ' . $ward->linen_color : '' }}
                                                          </option>
                                                      @endforeach
                                                  </select>
                                                  <small id="linen-color-hint" class="form-text text-muted">Choose an assignment to view its linen colour.</small>
                                              </div>
                                            </div>

                                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                              <div class="mb-3">
                                                  <label class="form-label">Nurse cadre</label>
                                                  <select name="nursing_role_id" class="form-select">
                                                      <option value="">Select cadre (optional)</option>
                                                      @foreach($nursingRoles as $nursingRole)
                                                          <option value="{{ $nursingRole->id }}" @selected(old('nursing_role_id') == $nursingRole->id)>{{ $nursingRole->name }}</option>
                                                      @endforeach
                                                  </select>
                                              </div>
                                            </div>

                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="phone_number">Phone number</label>
                                                <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" class="form-control" placeholder="e.g. 08012345678">
                                            </div>
                                        </div>

                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="license_number">Nursing licence number</label>
                                                <input type="text" id="license_number" name="license_number" value="{{ old('license_number') }}" class="form-control" placeholder="Licence number">
                                            </div>
                                        </div>

                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="qualification">Qualification</label>
                                                <input type="text" id="qualification" name="qualification" value="{{ old('qualification') }}" class="form-control" placeholder="e.g. RN, B.NSc">
                                            </div>
                                        </div>

                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="status">Account status <span class="text-danger">*</span></label>
                                                <select id="status" name="status" class="form-select" required>
                                                    <option value="Active" @selected(old('status', 'Active') === 'Active')>Active</option>
                                                    <option value="Inactive" @selected(old('status') === 'Inactive')>Inactive</option>
                                                </select>
                                            </div>
                                        </div>

                                
                                        <!-- Password -->
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="password">Password</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="ri-lock-password-line"></i>
                                                    </span>
                                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
                                                </div>
                                            </div>
                                        </div>
                                
                                        <!-- Confirm Password -->
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="confirm_password">Confirm Password</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="ri-lock-password-line"></i>
                                                    </span>
                                            <input type="password" id="confirm_password" name="password_confirmation" placeholder="Confirm password" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <!-- Actions -->
                                    <div class="d-flex gap-2 justify-content-end mt-4">
                                        <a href="{{ route('add_nurse') }}" class="btn btn-outline-secondary">Cancel</a>
                                        <button type="submit" class="btn btn-primary">Register</button>
                                    </div>
                                </form>
                                
                                <!-- Card acrions ends -->
            
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- Row ends -->
            
                      </div>
                      <!-- App body ends -->


          
          <!-- App footer starts -->
          <div class="app-footer bg-white">
            {{-- <span>©  2024</span> --}}
          </div>
          <!-- App footer ends -->

        </div>
        <!-- App container ends -->

      </div>
      <!-- Main container ends -->

    </div>
    <!-- Page wrapper ends -->

    
    
@include('parklane.script')
<script>
  const wardSelect = document.getElementById('ward_id');
  const linenHint = document.getElementById('linen-color-hint');
  const showLinenColor = () => {
    const option = wardSelect.options[wardSelect.selectedIndex];
    const color = option?.dataset.linenColor;
    linenHint.textContent = color ? `Linen colour for this assignment: ${color}` : 'No linen colour has been configured for this assignment.';
  };
  wardSelect?.addEventListener('change', showLinenColor);
  showLinenColor();
</script>
