@include('parklane.dentist.head')
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
      @include('parklane.dentist.header')
      <!-- App header ends -->

      <!-- Main container starts -->
      <div class="main-container">

        <!-- Sidebar wrapper starts -->
        @include('parklane.dentist.sidebar')

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
                          <form action="{{ route('dentist.profile') }}" method="POST" enctype="multipart/form-data">
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
                                                   placeholder="Enter Full Name" value="{{ Auth::guard('dentist')->user()->full_name }}">
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
                                                   placeholder="Enter Email" value="{{ Auth::guard('dentist')->user()->email }}" readonly>
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
                                                   value="{{ Auth::guard('dentist')->user()->ages }}">
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
                                                       {{ Auth::guard('dentist')->user()->gender == 'male' ? 'checked' : '' }}>
                                                <label class="form-check-label">Male</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender" value="female" 
                                                       {{ Auth::guard('dentist')->user()->gender == 'female' ? 'checked' : '' }}>
                                                <label class="form-check-label">Female</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        
                                <!-- Doctor Department -->
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label">Dentist Departments</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-vip-crown-2-line"></i></span>
                                            <select class="form-select" name="dentist_department_id">
                                                <option value="">Select</option>
                                                @foreach($departments as $department)
                                                    <option value="{{ $department->id }}" 
                                                            {{ Auth::guard('dentist')->user()->dentist_department_id == $department->id ? 'selected' : '' }}>
                                                        {{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                                                <!-- Doctor Department -->
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label">Dentist Role</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-vip-crown-2-line"></i></span>
                                            <select class="form-select" name="dentist_role_id">
                                                <option value="">Select</option>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}" 
                                                            {{ Auth::guard('dentist')->user()->dentist_role_id == $role->id ? 'selected' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                 <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label">Dentist Units</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-vip-crown-2-line"></i></span>
                                            <select class="form-select" name="dentist_unit_id">
                                                <option value="">Select</option>
                                                @foreach($DentistUnits as $DentistUnit)
                                                    <option value="{{ $DentistUnit->id }}" 
                                                            {{ Auth::guard('dentist')->user()->dentist_unit_id == $DentistUnit->id ? 'selected' : '' }}>
                                                        {{ $DentistUnit->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                        




                        
                                <!-- Mobile Number -->
                                <div class="col-xxl-3 col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ri-phone-line"></i></span>
                                            <input type="text" class="form-control" name="phone_number" 
                                                   placeholder="Enter Mobile Number" value="{{ Auth::guard('dentist')->user()->phone_number }}">
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
                                      <input type="text" class="form-control" name="address" value="{{ Auth::guard('dentist')->user()->address }}" id="a11" placeholder="Enter Address">
                                    </div>
                                  </div>
                                </div>
    

 
    

                                
                        
                                <!-- Profile Picture Upload -->
                                <div class="col-sm-3">
                                    <div class="mb-3">
                                        <label class="form-label">Upload Profile</label>
                                        <input type="file" class="form-control" name="profile_picture">
                                        @if(Auth::guard('dentist')->user()->profile_image)
                                            <img src="{{ asset('storage/' . Auth::guard('dentist')->user()->profile_image) }}" 
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

    
    
@include('parklane.dentist.script')