@include('parklane.nurses.head')
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
      @include('parklane.nurses.header')
      <!-- App header ends -->

      <!-- Main container starts -->
      <div class="main-container">

        <!-- Sidebar wrapper starts -->
        @include('parklane.nurses.sidebar')

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
                              <!-- App body starts -->
          <div class="app-body">

            <!-- Row starts -->
                      <!-- App body starts -->
          <div class="app-body">

            <!-- Row starts -->
            <div class="row gx-3">
              <div class="col-xl-12">
                <div class="card">
                  <div class="card-body">

                    <!-- Custom tabs starts -->
                    <div class="custom-tabs-container">

                      <!-- Nav tabs starts -->
                      <ul class="nav nav-tabs" id="customTab2" role="tablist">
                        <li class="nav-item" role="presentation">
                          <a class="nav-link active" id="tab-oneA" data-bs-toggle="tab" href="#oneA" role="tab"
                            aria-controls="oneA" aria-selected="true"><i class="ri-briefcase-4-line"></i> Personal
                            Details</a>
                        </li>
                      </ul>
                      <!-- Nav tabs ends -->

                      <!-- Tab content starts -->
                      <div class="tab-content h-350">
                        <div class="tab-pane fade show active" id="oneA" role="tabpanel">

                          <div id="toast-container"></div>

                         

                          <!-- Row starts -->
                          <form action="{{ route('nurse.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                        
                            <div class="row gx-3">
                                <!-- Full Name -->
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="full_name">Full Name <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-account-circle-line"></i></span>
                                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                                   placeholder="Enter Full Name" value="{{ Auth::guard('nurse')->user()->full_name }}">
                                        </div>
                                    </div>
                                </div>
                        
                                <!-- Email -->
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-mail-line"></i></span>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   placeholder="Enter Email" value="{{ Auth::guard('nurse')->user()->email }}" readonly>
                                        </div>
                                    </div>
                                </div>
                        
                                <!-- Date of Birth -->
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-calendar-line"></i></span>
                                            <input type="date" class="form-control" name="ages" 
                                                   value="{{ Auth::guard('nurse')->user()->ages }}">
                                        </div>
                                    </div>
                                </div>
                        
                                <!-- Gender -->
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                                        <div class="m-0">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender" value="male" 
                                                       {{ Auth::guard('nurse')->user()->gender == 'male' ? 'checked' : '' }}>
                                                <label class="form-check-label">Male</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender" value="female" 
                                                       {{ Auth::guard('nurse')->user()->gender == 'female' ? 'checked' : '' }}>
                                                <label class="form-check-label">Female</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        
     
                        
                                <!-- Specialization -->
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label">Department <span class="text-danger">*</span></label>
                                        <select class="form-select" name="nurse_department_id" disabled>
                                            <option value="">Select Department</option>
                                            @foreach($departments as $dept)
                                                <option value="{{ $dept->id }}"
                                                    {{ Auth::guard('nurse')->user()->nurse_department_id == $dept->id ? 'selected' : '' }}>
                                                    {{ $dept->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label">Ward <span class="text-danger">*</span></label>
                                        <select class="form-select" name="ward_id" disabled>
                                            <option value="">Select Ward</option>
                                            @foreach($wards as $ward)
                                                <option value="{{ $ward->id }}"
                                                    {{ Auth::guard('nurse')->user()->ward_id == $ward->id ? 'selected' : '' }}>
                                                    {{ $ward->name }} 
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                

                                                                <!-- Specialization -->
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label">License Number  <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-secure-payment-line"></i></span>
                                            <input type="text" class="form-control" name="license_number" 
                                                   placeholder="License Number " value="{{ Auth::guard('nurse')->user()->license_number }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="col-xxl-3 col-lg-4 col-sm-6">
                                  <div class="mb-3">
                                      <label class="form-label">Years of Experience <span class="text-danger">*</span></label>
                                      <div class="input-group">
                                          <span class="input-group-text"><i class="ri-secure-payment-line"></i></span>
                                          <input type="text" class="form-control" name="years_of_experience" 
                                                 placeholder="Years of Experience" value="{{ Auth::guard('nurse')->user()->years_of_experience }}">
                                      </div>
                                  </div>
                              </div> --}}
                        
                                <!-- Mobile Number -->
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-phone-line"></i></span>
                                            <input type="text" class="form-control" name="phone_number" 
                                                   placeholder="Enter Mobile Number" value="{{ Auth::guard('nurse')->user()->phone_number }}">
                                        </div>
                                    </div>
                                </div>

                              <!-- Qualification -->
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                  <div class="mb-3">
                                      <label class="form-label" for="qualification">Qualification</label>
                                      <div class="input-group">
                                          <span class="input-group-text"><i class="ri-copper-diamond-line"></i></span>
                                          <select class="form-select" name="qualification" id="qualification">
                                              <option value="">Select</option>
                                              <option value="MBBS, MD" {{ Auth::guard('nurse')->user()->qualification == 'MBBS, MD' ? 'selected' : '' }}>MBBS, MD</option>
                                              <option value="MBBS, MS" {{ Auth::guard('nurse')->user()->qualification == 'MBBS, MS' ? 'selected' : '' }}>MBBS, MS</option>
                                              <option value="MBBS" {{ Auth::guard('nurse')->user()->qualification == 'MBBS' ? 'selected' : '' }}>MBBS</option>
                                          </select>
                                      </div>
                                  </div>
                                </div>

                                <!-- Blood Group -->
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                  <div class="mb-3">
                                      <label class="form-label" for="blood_group">Blood Group <span class="text-danger">*</span></label>
                                      <div class="input-group">
                                          <span class="input-group-text"><i class="ri-drop-line"></i></span>
                                          <select class="form-select" name="blood_group" id="blood_group">
                                              <option value="">Select</option>
                                              <option value="A+" {{ Auth::guard('nurse')->user()->blood_group == 'A+' ? 'selected' : '' }}>A+</option>
                                              <option value="A-" {{ Auth::guard('nurse')->user()->blood_group == 'A-' ? 'selected' : '' }}>A-</option>
                                              <option value="B+" {{ Auth::guard('nurse')->user()->blood_group == 'B+' ? 'selected' : '' }}>B+</option>
                                              <option value="B-" {{ Auth::guard('nurse')->user()->blood_group == 'B-' ? 'selected' : '' }}>B-</option>
                                              <option value="O+" {{ Auth::guard('nurse')->user()->blood_group == 'O+' ? 'selected' : '' }}>O+</option>
                                              <option value="O-" {{ Auth::guard('nurse')->user()->blood_group == 'O-' ? 'selected' : '' }}>O-</option>
                                              <option value="AB+" {{ Auth::guard('nurse')->user()->blood_group == 'AB+' ? 'selected' : '' }}>AB+</option>
                                              <option value="AB-" {{Auth::guard('nurse')->user()->blood_group == 'AB-' ? 'selected' : '' }}>AB-</option>
                                          </select>
                                      </div>
                                  </div>
                                </div>
                                    
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                  <div class="mb-3">
                                    <label class="form-label" for="a11">Address</label>
                                    <div class="input-group">
                                      <span class="input-group-text">
                                        <i class="ri-projector-line"></i>
                                      </span>
                                      <input type="text" class="form-control" name="address" value="{{ Auth::guard('nurse')->user()->address }}" id="a11" placeholder="Enter Address">
                                    </div>
                                  </div>
                                </div>
    

    
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                  <div class="mb-3">
                                    <label class="form-label" for="a14">State</label>
                                    <div class="input-group">
                                      <span class="input-group-text">
                                        <i class="ri-scan-line"></i>
                                      </span>
                                      <input type="text" class="form-control" name="state" value="{{ Auth::guard('nurse')->user()->state }}" id="a14" placeholder="Enter State">
                                    </div>
                                  </div>
                                </div>
    
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                  <div class="mb-3">
                                    <label class="form-label" for="a14">City</label>
                                    <div class="input-group">
                                      <span class="input-group-text">
                                        <i class="ri-scan-line"></i>
                                      </span>
                                      <input type="text" class="form-control" name="city" value="{{ Auth::guard('nurse')->user()->city }}" id="a14" placeholder="Enter City">
                                    </div>
                                  </div>
                                </div>
                                
                        
                                <!-- Profile Picture Upload -->
                                <div class="col-sm-3">
                                    <div class="mb-3">
                                        <label class="form-label">Upload Profile</label>
                                        <input type="file" class="form-control" name="profile_picture">
                                        @if(Auth::guard('nurse')->user()->profile_image)
                                            <img src="{{ asset('storage/' . Auth::guard('nurse')->user()->profile_image) }}" 
                                                 class="img-thumbnail mt-2" width="100">
                                        @endif
                                    </div>
                                </div>
                        
                                <!-- Submit Button -->
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Update Profile</button>
                                </div>
                            </div>
                        </form>
                        

       
                          <!-- Row ends -->

                        </div>
 
                      </div>
                      <!-- Tab content ends -->

                    </div>
                    <!-- Custom tabs ends -->

                    <!-- Card acrions starts -->
                    {{-- <div class="d-flex gap-2 justify-content-end mt-4">
                      <a href="doctors-list.html" class="btn btn-outline-secondary">
                        Cancel
                      </a>
                      <a href="doctors-list.html" class="btn btn-primary">
                        Create Doctor Profile
                      </a>
                    </div> --}}
                    <!-- Card acrions ends -->

                  </div>
                </div>
              </div>
            </div>
            <!-- Row ends -->

          </div>
          <!-- App body ends -->
            <!-- Row ends -->

            <!-- Row starts -->
          
            <!-- Row ends -->

          </div>
          <!-- App body ends -->
                      <!-- App body ends -->


          
          <!-- App footer starts -->
          <div class="app-footer bg-white">
            <span>©  2024</span>
          </div>
          <!-- App footer ends -->

        </div>
        <!-- App container ends -->

      </div>
      <!-- Main container ends -->

    </div>
    <!-- Page wrapper ends -->

    
    
@include('parklane.nurses.script')