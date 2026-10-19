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

                                <div id="toast-container"></div>
                                @if (session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif

                              
                                

                                <form action="{{ route('add_doctor') }}" method="POST">
                                    @csrf

                                    <input type="hidden" name="hospital_id" value="{{ auth()->user()->hospital_id }}">
                                    {{-- <input type="hidden" name="hospital_id" value="{{ auth()->user()->hospital_id }}"> --}}

                                    <div class="row gx-3">
                                        <!-- Full Name -->
                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="fullname">Full Name</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="ri-account-pin-circle-line"></i>
                                                    </span>
                                                    <input type="text"  name="full_name" placeholder="Enter full name" class="form-control" >
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
                                                    <input type="text"  name="email" placeholder="Enter email" class="form-control" >
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Doctor Cadre</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="ri-user-star-line"></i></span>
                                                    <select class="form-select" name="cadre" required>
                                                        <option value="">Select Cadre</option>
                                                        <option value="house_officer">House Officer</option>
                                                        <option value="medical_officer">Medical Officer</option>
                                                        <option value="registrar">Registrar</option>
                                                        <option value="senior_registrar">Senior Registrar</option>
                                                        <option value="consultant">Consultant</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Doctor Department</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="ri-vip-crown-2-line"></i></span>
                                                    <select class="form-select" id="department" name="department_id" required>
                                                        <option value="">Select Department</option>
                                                        @foreach($departments as $department)
                                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Doctor Unit</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="ri-vip-crown-2-line"></i></span>
                                                    <select class="form-select" id="doctor_unit" name="doctor_unit_id" required>
                                                        <option value="">Select Unit</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Sub Unit wrapper with ID -->
                                        <div class="col-xxl-3 col-lg-4 col-sm-6" id="doctor_sub_unit_wrapper" style="display:none;">
                                            <div class="mb-3">
                                                <label class="form-label">Doctor Sub Unit</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="ri-vip-crown-2-line"></i></span>
                                                    <select class="form-select" id="doctor_sub_unit" name="doctor_sub_unit_id">
                                                        <option value="">Select Sub Unit</option>
                                                    </select>
                                                </div>
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
                                                    <input type="password"  name="password" class="form-control" placeholder="Enter password" >
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
                                                    <input type="password"  name="password_confirmation" placeholder="Confirm password" class="form-control" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <!-- Actions -->
                                    <div class="d-flex gap-2 justify-content-end mt-4">
                                        <a href="{{ route('add_doctor') }}" class="btn btn-outline-secondary">Cancel</a>
                                        <button type="submit" class="btn btn-primary">Create Doctor Profile</button>
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

    <script>
   document.addEventListener('DOMContentLoaded', function () {
    let departmentSelect = document.getElementById('department');
    let unitSelect = document.getElementById('doctor_unit');
    let subUnitSelect = document.getElementById('doctor_sub_unit');
    let subUnitWrapper = document.getElementById('doctor_sub_unit_wrapper');

    // When department changes -> load units
    departmentSelect.addEventListener('change', function () {
        let departmentId = this.value;
        unitSelect.innerHTML = '<option value="">Select Unit</option>';
        subUnitSelect.innerHTML = '<option value="">Select Sub Unit</option>';
        subUnitWrapper.style.display = 'none'; // hide subunit initially

        if (departmentId) {
            fetch(`/get-units/${departmentId}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(unit => {
                        let option = document.createElement('option');
                        option.value = unit.id;
                        option.textContent = unit.name;
                        unitSelect.appendChild(option);
                    });
                });
        }
    });

    // When unit changes -> load sub-units
    unitSelect.addEventListener('change', function () {
        let unitId = this.value;
        subUnitSelect.innerHTML = '<option value="">Select Sub Unit</option>';
        subUnitWrapper.style.display = 'none'; // default hide

        if (unitId) {
            fetch(`/get-sub-units/${unitId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length > 0) {
                        subUnitWrapper.style.display = 'block'; // show only if subunits exist
                        data.forEach(sub => {
                            let option = document.createElement('option');
                            option.value = sub.id;
                            option.textContent = sub.name;
                            subUnitSelect.appendChild(option);
                        });
                    }
                });
        }
    });
});

    </script>
    
@include('parklane.script')