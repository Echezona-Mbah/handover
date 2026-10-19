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
                                <h3>Register Dentist</h3>

                                <div id="toast-container"></div>

                              
                                

                                <form action="{{ route('add_dentist') }}" method="POST">
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
                                                <label class="form-label">Dentist Role</label>
                                                <select name="dentist_role_id" class="form-control">
                                                    @foreach($DentistROles as $DentistROle)
                                                        <option value="{{ $DentistROle->id }}">{{ $DentistROle->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                          </div>

                                          
                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Dentist Department</label>
                                                <select name="dentist_department_id" class="form-control">
                                                    @foreach($dentistDepartments as $dentistDepartment)
                                                        <option value="{{ $dentistDepartment->id }}">{{ $dentistDepartment->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                          </div>


                                                                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Dentist Unit</label>
                                                <select name="dentist_unit_id" class="form-control">
                                                    @foreach($Dentistunits as $Dentistunit)
                                                        <option value="{{ $Dentistunit->id }}">{{ $Dentistunit->name }}</option>
                                                    @endforeach
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
                                        <a href="{{ route('add_dentist') }}" class="btn btn-outline-secondary">Cancel</a>
                                        <button type="submit" class="btn btn-primary">Create Dentist</button>
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