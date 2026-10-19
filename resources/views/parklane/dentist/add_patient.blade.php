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
                                <div class="row gx-3">
                                  <div class="col-sm-12">
                                    <div class="card">
                                      <div class="card-header">
                                        <h5 class="card-title">Add Patient Details</h5>
                                      </div>
                                      <div class="card-body">
                    
                                        <form action="{{ route('dentist.store_patient') }}" method="POST">
                                                @csrf
                                        <!-- Row starts -->
                                        <div class="row gx-3">
                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="a1">Full Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="a1" name="full_name" placeholder="Enter Full Name" value="{{ old('full_name') }}" >
                                            </div>
                                          </div>
                                          {{-- <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                              <label class="form-label" for="a2">Last Name <span class="text-danger">*</span></label>
                                              <input type="text" class="form-control" id="a2" placeholder="Enter Last Name">
                                            </div>
                                          </div> --}}

                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="dob">Date of Birth <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob') }}" >
                                            </div>
                                          </div>
                                        
                                          

                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="selectGender1">Gender <span class="text-danger">*</span></label>
                                                <div class="m-0">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="gender" id="selectGender1" value="male" {{ old('gender') == 'male' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="selectGender1">Male</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="gender" id="selectGender2" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="selectGender2">Female</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            


                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                          <div class="mb-3">
                                              <label class="form-label" for="a5">Email <span class="text-danger">*</span></label>
                                              <input type="email" class="form-control" id="a5" name="email" placeholder="Enter Email ID" value="{{ old('email') }}" >
                                          </div>
                                        </div>

                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                              <label class="form-label" for="a6">Mobile Number <span class="text-danger">*</span></label>
                                              <input type="text" class="form-control" id="a6" name="mobile_number"  value="{{ old('mobile_number') }}" placeholder="Enter Mobile Number">
                                            </div>
                                          </div>

                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                              <label class="form-label" for="a7">Marital Status</label>
                                              <select class="form-select" id="a7"  name="marital_status" value="{{ old('marital_status') }}">
                                                <option value="0">Select</option>
                                                <option value="Married">Married</option>
                                                <option value="unmarried">Un Married</option>
                                              </select>
                                            </div>
                                          </div>

                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                              <label class="form-label" for="a6">Current Condition <span class="text-danger">*</span></label>
                                              <input type="text" class="form-control" id="a6" name="current_condition"  value="{{ old('current_condition') }}" placeholder="Enter Current Condition">
                                            </div>
                                          </div>

                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="a6">Ward <span class="text-danger">*</span></label>
                                                <select class="form-select" id="a6" name="ward" required>
                                                    <option value="">-- Select Ward --</option>
                                                    @foreach($wards as $ward)
                                                        <option value="{{ $ward->id }}" {{ old('ward') == $ward->id ? 'selected' : '' }}>
                                                            {{ $ward->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                         <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="a6">Ward <span class="text-danger">*</span></label>
                                                <select class="form-select" id="a6" name="DentistUnit" required>
                                                    <option value="">-- Select Ward --</option>
                                                    @foreach($DentistUnits as $DentistUnit)
                                                        <option value="{{ $DentistUnit->id }}" {{ old('DentistUnit') == $DentistUnit->id ? 'selected' : '' }}>
                                                            {{ $DentistUnit->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>



                                        

                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                              <label class="form-label" for="a6">Current Medications <span class="text-danger">*</span></label>
                                              <input type="text" class="form-control" id="a6" name="current_medications"  value="{{ old('current_medications') }}" placeholder="EnterCurrent Medications">
                                            </div>
                                          </div>

                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                              <label class="form-label" for="a6">Allergies <span class="text-danger">*</span></label>
                                              <input type="text" class="form-control" id="a6" name="allergies"  value="{{ old('allergies') }}" placeholder="Enter Allergies">
                                            </div>
                                          </div>

                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                              <label class="form-label" for="a6">Urgency Status <span class="text-danger">*</span></label>
                                              <input type="text" class="form-control" id="a6" name="urgency_status"  value="{{ old('urgency_status') }}" placeholder="Enter Urgency Status">
                                            </div>
                                          </div>

                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                              <label class="form-label" for="a12">Address</label>
                                              <input type="text" class="form-control" name="address" value="{{ old('address') }}" id="a12" placeholder="Enter Address">
                                            </div>
                                          </div>



                                          

                                          <div class="col-sm-12">
                                            <div class="d-flex gap-2 justify-content-end">
                                              <a href="" type="button" class="btn btn-outline-secondary">
                                                Cancel
                                              </a>
                                              <button type="submit" class="btn btn-primary">Create Patient</button>
                                              {{-- <a href="submit" type="button" class="btn btn-primary">
                                                Create Patient
                                              </a> --}}
                                              
                                            </div>
                                          </div>
                                        </div>
                                        <!-- Row ends -->
                                      </form>
                    
                                      </div>
                                    </div>
                                  </div>
                                </div>
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