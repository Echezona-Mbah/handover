@include('parklane.dentist.head')
  <style>
    #dropzone {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    border: 2px dashed #007bff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease-in-out;
}

#dropzone:hover {
    border-color: #0056b3;
    background-color: #e9ecef;
}

.dz-message {
    color: #495057;
}

.dz-button {
    font-size: 16px;
    padding: 12px 30px;
    background-color: #007bff;
    color: white;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.dz-button:hover {
    background-color: #0056b3;
}

h5 {
    font-size: 16px;
    font-weight: normal;
    margin-top: 20px;
}

@media (max-width: 576px) {
    #dropzone {
        padding: 20px;
    }

    .dz-button {
        font-size: 14px;
        padding: 10px 25px;
    }

    h5 {
        font-size: 14px;
    }
}

.form-select, .form-control {
    font-size: 1rem;
    padding: 0.75rem;
    border-radius: 0.375rem;
    border: 1px solid #ced4da;
    transition: all 0.3s ease-in-out;
}

.form-select:focus, .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
}

.form-label {
    font-weight: 600;
    font-size: 1rem;
    color: #495057;
}

.mb-3 {
    margin-bottom: 1rem;
}


  </style>
  
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
                                      <div id="toast-container"></div>

                                      <div class="card-body">
                    
                                        <form action="{{ route('dentist.patientUpdate', $patient->id) }}" method="POST">
                                            @csrf
                                            @method('PUT') <!-- Specifies that this is a PUT request -->
                                        <!-- Row starts -->
                                        <div class="row gx-3">
                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="a1">Full Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="a1" name="full_name" placeholder="Enter Full Name"  value="{{ old('full_name', $patient->full_name) }}" >
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
                                                <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob', $patient->dob) }}" >
                                            </div>
                                          </div>
                                        
                                          

                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                                <label for="gender" class="form-label">Gender</label>
                                                <select name="gender" id="gender" class="form-select">
                                                    <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                                    <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                                </select>
                                            </div>
                                        </div>
                            


                                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                                          <div class="mb-3">
                                              <label class="form-label" for="a5">Email <span class="text-danger">*</span></label>
                                              <input type="email" class="form-control" id="a5" name="email" placeholder="Enter Email ID" value="{{ old('email', $patient->email) }}" >
                                          </div>
                                        </div>

                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                              <label class="form-label" for="a6">Mobile Number <span class="text-danger">*</span></label>
                                              <input type="text" class="form-control" id="a6" name="mobile_number"  value="{{ old('mobile_number', $patient->mobile_number) }}" placeholder="Enter Mobile Number">
                                            </div>
                                          </div>

                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                              <label class="form-label" for="a7">Marital Status</label>
                                              <select class="form-select" id="a7"  name="marital_status" value="{{ old('marital_status', $patient->marital_status) }}">
                                                <option value="{{ old('marital_status', $patient->marital_status) }}">{{ old('marital_status', $patient->marital_status) }}</option>
                                                <option value="Married">Married</option>
                                                <option value="Un Married">Un Married</option>
                                              </select>
                                            </div>
                                          </div>

                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                              <label class="form-label" for="a6">Current Condition <span class="text-danger">*</span></label>
                                              <input type="text" class="form-control" id="a6" name="current_condition"  value="{{ old('current_condition', $patient->current_condition) }}" placeholder="Enter Mobile Number">
                                            </div>
                                          </div>

                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                              <label class="form-label" for="a6">Ward <span class="text-danger">*</span></label>
                                              <input type="text" class="form-control" id="a6" name="ward"  value="{{ old('ward', $patient->ward) }}" placeholder="Enter ward">
                                            </div>
                                          </div>

                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                              <label class="form-label" for="a6">Current Medications<span class="text-danger">*</span></label>
                                              <input type="text" class="form-control" id="a6" name="current_medications"  value="{{ old('current_medications', $patient->current_medications) }}" placeholder="Enter Current Medications">
                                            </div>
                                          </div>

                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                              <label class="form-label" for="a6">Allergies<span class="text-danger">*</span></label>
                                              <input type="text" class="form-control" id="a6" name="allergies"  value="{{ old('allergies', $patient->allergies) }}" placeholder="Enter Allergies">
                                            </div>
                                          </div>

                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                              <label class="form-label" for="a6">Urgency status <span class="text-danger">*</span></label>
                                              <input type="text" class="form-control" id="a6" name="urgency_status"  value="{{ old('urgency_status', $patient->urgency_status) }}" placeholder="Enter Urgency status">
                                            </div>
                                          </div>

                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                              <label class="form-label" for="a8">Occupation</label>
                                              <select class="form-select" id="a8" name="occupation" value=" {{ old('occupation', $patient->occupation) }}">
                                                <option value="0">{{ old('occupation', $patient->occupation) }}</option>
                                                <option value="Teacher">Teacher</option>
                                                <option value="Engineer">Engineer</option>
                                                <option value="Business">Business</option>
                                                <option value="Bank">Bank</option>
                                                <option value="Army">Army</option>
                                              </select>
                                            </div>
                                          </div>

                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                              <label class="form-label" for="a9">Blood Group <span class="text-danger">*</span></label>
                                              <select class="form-select" id="a9" name="blood_group" value="{{ old('blood_group', $patient->blood_group) }}">
                                                <option value="{{ old('blood_group', $patient->blood_group) }}">{{ old('blood_group', $patient->blood_group) }}</option>
                                                <option value="A+">A+</option>
                                                <option value="A-">A-</option>
                                                <option value="B+">B+</option>
                                                <option value="B-">B-</option>
                                                <option value="O+">O+</option>
                                                <option value="O-">O-</option>
                                                <option value="AB+">AB+</option>
                                                <option value="AB-">AB-</option>
                                              </select>
                                            </div>
                                          </div>

                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                              <label class="form-label" for="a10">Blood Presure</label>
                                              <input type="text" class="form-control" name="blood_pressure" value="{{ old('blood_pressure', $patient->blood_pressure) }}" id="a10" placeholder="Enter Blood Presure">
                                            </div>
                                          </div>

                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                              <label class="form-label" for="a11">Sugar</label>
                                              <input type="text" class="form-control" name="sugar_level"  value=" {{ old('sugar_level', $patient->sugar_level) }}" id="a11" placeholder="Enter Sugar Levels">
                                            </div>
                                          </div>

                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                              <label class="form-label" for="a12">Address</label>
                                              <input type="text" class="form-control" name="address" value=" {{ old('address', $patient->address) }}" id="a12" placeholder="Enter Address">
                                            </div>
                                          </div>

                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                              <label class="form-label" for="a13">City</label>
                                              <input type="text" class="form-control" name="city" value=" {{ old('city', $patient->city) }}" id="a13" placeholder="Enter City">
                                            </div>
                                          </div>

                                          <div class="col-xxl-3 col-lg-4 col-sm-6">
                                            <div class="mb-3">
                                              <label class="form-label" for="a14">State</label>
                                              <input type="text" class="form-control" name="state" value="{{ old('state', $patient->state) }}" id="a13" placeholder="Enter City">
                                            </div>
                                          </div>

                                          {{-- <div class="col-sm-12">
                                            <div id="dropzone" class="mb-4">
                                              <form action="https://bootstrapget.com/upload" class="dropzone dz-clickable" id="demo-upload">
                                                <div class="dz-message text-center">
                                                  <button type="button" class="dz-button btn btn-outline-primary">
                                                    Click here to upload or Drag & Drop your reports here.
                                                  </button>
                                                  <h5 class="mt-3 text-muted">Upload your health reports securely</h5>
                                                </div>
                                              </form>
                                            </div>
                                          </div> --}}
                                          

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