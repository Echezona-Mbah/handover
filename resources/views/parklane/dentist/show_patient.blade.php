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
                                    <div class="card mb-3">
                                      <div class="card-body">
                    
                                        <div class="d-flex ">
                                          <!-- Stats starts -->
                                          <div class="d-flex align-items-center flex-wrap gap-4">
                                            <div class="d-flex align-items-center">
                                              <div class="icon-box lg bg-primary rounded-5 me-2">
                                                <i class="ri-account-circle-line fs-3"></i>
                                              </div>
                                              <div>
                                                <h4 class="mb-1">{{ $patient->full_name }}</h4>
                                                <p class="m-0">Patient Name</p>
                                              </div>
                                            </div>
                                            <div class="d-flex align-items-center">
                                              <div class="icon-box lg bg-primary rounded-5 me-2">
                                                <i class="ri-women-line fs-3"></i>
                                              </div>
                                              <div>
                                                <h4 class="mb-1">{{ $patient->full_name }}</h4>
                                                <p class="m-0">Gender</p>
                                              </div>
                                            </div>
                                            <div class="d-flex align-items-center">
                                              <div class="icon-box lg bg-primary rounded-5 me-2">
                                                <i class="ri-arrow-right-up-line fs-3"></i>
                                              </div>
                                              <div>
                                                <h4 class="mb-1">{{ \Carbon\Carbon::parse($patient->dob)->age }}</h4>
                                                <p class="m-0">Patient Age</p>
                                              </div>
                                            </div>
                                            <div class="d-flex align-items-center">
                                              <div class="icon-box lg bg-primary rounded-5 me-2">
                                                <i class="ri-contrast-drop-2-line fs-3"></i>
                                              </div>
                                              <div>
                                                <h4 class="mb-1">{{ $patient->blood_group }}</h4>
                                                <p class="m-0">Blood Type</p>
                                              </div>
                                            </div>
                                            <div class="d-flex align-items-center">
                                              <div class="icon-box lg bg-secondary rounded-5 me-2">
                                                <i class="ri-stethoscope-line fs-3 text-body"></i>
                                              </div>
                                              <div>
                                                <h4 class="mb-1">Dr. {{ $patient->doctorById?->full_name ?? 'Not Assigned' }}</h4>
                                                <p class="m-0">Consulting Doctor</p>
                                              </div>
                                            </div>
                                          </div>
                                          <!-- Stats ends -->
                    
                                          {{-- <img src="assets/images/patient4.png" class="img-7x rounded-circle ms-auto"
                                            alt="Patient Admin Template"> --}}
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <!-- Row ends -->
                    
                                <!-- Row starts -->
                                {{-- <div class="row gx-3">
                                  <div class="col-xxl-3 col-sm-6 col-12">
                                    <div class="card mb-3">
                                      <div class="card-body">
                                        <div class="text-center">
                                          <div class="icon-box md bg-danger rounded-5 m-auto">
                                            <i class="ri-capsule-line fs-3"></i>
                                          </div>
                                          <div class="mt-3">
                                            <h5>BP Levels</h5>
                                            <p class="m-0 opacity-50">Recent five visits</p>
                                          </div>
                                        </div>
                                        <div id="bpLevels"></div>
                                        <ul class="list-group">
                                          <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>24/04/2024</div>
                                            <div>140</div>
                                          </li>
                                          <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>16/04/2024</div>
                                            <div>190</div>
                                          </li>
                                          <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>10/04/2024</div>
                                            <div>230</div>
                                          </li>
                                        </ul>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-xxl-3 col-sm-6 col-12">
                                    <div class="card mb-3">
                                      <div class="card-body">
                                        <div class="text-center">
                                          <div class="icon-box md bg-info rounded-5 m-auto">
                                            <i class="ri-contrast-drop-2-line fs-3"></i>
                                          </div>
                                          <div class="mt-3">
                                            <h5>Sugar Levels</h5>
                                            <p class="m-0 opacity-50">Recent five visits</p>
                                          </div>
                                        </div>
                                        <div id="sugarLevels"></div>
                                        <ul class="list-group">
                                          <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>24/04/2024</div>
                                            <div>140</div>
                                          </li>
                                          <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>16/04/2024</div>
                                            <div>190</div>
                                          </li>
                                          <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>10/04/2024</div>
                                            <div>230</div>
                                          </li>
                                        </ul>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-xxl-3 col-sm-6 col-12">
                                    <div class="card mb-3">
                                      <div class="card-body">
                                        <div class="text-center">
                                          <div class="icon-box md bg-success rounded-5 m-auto">
                                            <i class="ri-heart-pulse-line fs-3"></i>
                                          </div>
                                          <div class="mt-3">
                                            <h5>Heart Rate</h5>
                                            <p class="m-0 opacity-50">Recent five visits</p>
                                          </div>
                                        </div>
                                        <div id="heartRate"></div>
                                        <ul class="list-group">
                                          <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>24/04/2024</div>
                                            <div>110</div>
                                          </li>
                                          <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>16/04/2024</div>
                                            <div>120</div>
                                          </li>
                                          <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>10/04/2024</div>
                                            <div>100</div>
                                          </li>
                                        </ul>
                                      </div>
                                    </div>
                                  </div>
                                  
                                  <div class="col-xxl-3 col-sm-6 col-12">
                                    <div class="card mb-3">
                                      <div class="card-body">
                                        <div class="text-center">
                                          <div class="icon-box md bg-warning rounded-5 m-auto">
                                            <i class="ri-flask-line fs-3"></i>
                                          </div>
                                          <div class="mt-3">
                                            <h5>Clolesterol</h5>
                                            <p class="m-0 opacity-50">Recent five visits</p>
                                          </div>
                                        </div>
                                        <div id="clolesterolLevels"></div>
                                        <ul class="list-group">
                                          <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>24/04/2024</div>
                                            <div>180</div>
                                          </li>
                                          <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>16/04/2024</div>
                                            <div>220</div>
                                          </li>
                                          <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>10/04/2024</div>
                                            <div>230</div>
                                          </li>
                                        </ul>
                                      </div>
                                    </div>
                                  </div>
                                </div> --}}
                                <!-- Row ends -->
                    
                                <!-- Row starts -->
                                <div class="row gx-3">
     
                                  <div class="col-xl-6 col-sm-12">
                                    <div class="card mb-3">
                                      <div class="card-header">
                                        <h5 class="card-title">Dentist Visits</h5>
                                      </div>
                                      <div class="card-body">
                                        <div class="table-outer">
                                          <div class="table-responsive">
                                            <table class="table align-middle truncate m-0">
                                              <thead>
                                                <tr>
                                                  <th>Dentist</th>
                                                  <th>Date</th>
                                                  <th>Department</th>
                                                  {{-- <th>Medication</th> --}}
                                                  <th>View Handover Notes</th>
                                                </tr>
                                              </thead>
                                              <tbody>

                                                @foreach($handoverNotes as $handoverNote)
                                                <tr>
                                                  <td>{{ $handoverNote->dentist?->full_name }}</td>
                                                  <td>{{ $handoverNote->created_at->format('d/m/Y') }}</td>
                                                  <td>{{ $handoverNote->dentist?->DentistDepartment?->name }}</td>
                                                  {{-- <td>{{ $handoverNote->medication }}</td> --}}
                                                  <td>
                                                    <div class="d-inline-flex gap-1">
                                                      <!-- Trigger Modal -->
                                                      <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#viewHandoverNoteModal{{ $handoverNote->id }}">
                                                        View HandoverNotes
                                                      </button>
                                                
                                                      <a href="{{ route('handover.edit', $handoverNote->id) }}" class="btn btn-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Handover Note">
                                                        <i class="ri-edit-2-line"></i> Edit
                                                      </a>
                                                      
                                                    </div>
                                                  </td>
                                                </tr>
                                                
                                                <!-- Modal -->
                                                <div class="modal fade" id="viewHandoverNoteModal{{ $handoverNote->id }}" tabindex="-1" aria-labelledby="handoverModalLabel{{ $handoverNote->id }}" aria-hidden="true">
                                                  <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">
                                                      <div class="modal-header">
                                                        <h5 class="modal-title" id="handoverModalLabel{{ $handoverNote->id }}">Handover Details</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                      </div>
                                                
                                                      <div class="modal-body">
                                                        <p><strong>Dentist:</strong> {{ $handoverNote->dentist?->full_name }}</p>
                                                        <p><strong>Department:</strong> {{ $handoverNote->dentist?->DentistDepartment?->name }}</p>
                                                        <p><strong>Date:</strong> {{ $handoverNote->created_at->format('d/m/Y') }}</p>
                                                        <hr>
                                                        <p><strong>General Summary:</strong><br>{{ $handoverNote->summary }}</p>
                                                        <p><strong>Critical Alerts:</strong><br>{{ $handoverNote->alerts }}</p>
                                                        <p><strong>Pending Tasks:</strong><br>{{ $handoverNote->pending_tasks }}</p>
                                                        {{-- <p><strong>Medication:</strong><br>{{ $handoverNote->medication }}</p> --}}
                                                      </div>
                                                
                                                      <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                                @endforeach
                                                



                                              </tbody>
                                            </table>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>


                                  <div class="col-xl-6 col-sm-12">
                                    <div class="card mb-3">
                                      <div class="card-header">
                                        <h5 class="card-title">Plan</h5>
                                      </div>
                                      <div class="card-body">
                                        <div class="table-outer">
                                          <div class="table-responsive">
                                            <table class="table align-middle truncate m-0">
                                              <thead>
                                                <tr>
                                                  <th>#</th>
                                                  <th>Medication</th>
                                                  <th>Dosage</th>
                                                  {{-- <th>Instructions</th> --}}
                                                  <th>Date</th>
                                                  <th>Actions</th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                                @foreach($medications as $med)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $med->medication }}</td>
                                                    <td>{{ $med->dosage }}</td>
                                                    {{-- <td>{{ $med->instructions }}</td> --}}
                                                    <td>{{ $med->created_at->format('M d, Y') }}</td>
                                                  <td>
                                                    <div class="d-inline-flex gap-1">

                                                      <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                      data-bs-target="#viewHandModal{{ $med->id }}">
                                                      View Medical plan
                                                    </button>

                                                        <form id="delete-medication-form-{{ $med->id }}" action="{{ route('medications.destroy', $med->id) }}" method="POST" class="d-inline delete-medication-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-outline-danger btn-sm delete-btn" data-id="{{ $med->id }}">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        </form>
                                                        

                                                      <a href="{{ route('edit_medication', $med->id) }}" 
                                                        class="btn btn-info btn-sm" 
                                                       >
                                                        <i class="ri-file-download-line"></i>
                                                     </a>
                                                     
                                                    </div>
                                                  </td>
                                                </tr>
                                                @endforeach


                                                @foreach($medications as $med)
                                                  <!-- Modal for viewing medication -->
                                                  <div class="modal fade" id="viewHandModal{{ $med->id }}" tabindex="-1" aria-labelledby="viewHandModalLabel{{ $med->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                                      <div class="modal-content">
                                                        <div class="modal-header bg-primary text-white">
                                                          <h5 class="modal-title" id="viewHandModalLabel{{ $med->id }}">Medication Plan Details</h5>
                                                          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                          <div class="row g-3">
                                                            <div class="col-md-6">
                                                              <strong>Medication:</strong>
                                                              <p>{{ $med->medication }}</p>
                                                            </div>
                                                            <div class="col-md-6">
                                                              <strong>Dosage:</strong>
                                                              <p>{{ $med->dosage }}</p>
                                                            </div>
                                                            <div class="col-md-6">
                                                              <strong>Frequency:</strong>
                                                              <p>{{ $med->frequency }}</p>
                                                            </div>
                                                            <div class="col-md-6">
                                                              <strong>Start Date:</strong>
                                                              <p>{{ \Carbon\Carbon::parse($med->start_date)->format('d/m/Y') }}</p>
                                                            </div>
                                                            <div class="col-md-6">
                                                              <strong>End Date:</strong>
                                                              <p>{{ \Carbon\Carbon::parse($med->end_date)->format('d/m/Y') }}</p>
                                                            </div>
                                                            <div class="col-md-12">
                                                              <strong>Special Instructions:</strong>
                                                              <p>{{ $med->instructions ?: 'None' }}</p>
                                                            </div>
                                                            <div class="col-md-6">
                                                              <strong>Prescribed On:</strong>
                                                              <p>{{ $med->created_at->format('d M, Y') }}</p>
                                                            </div>
                                                          </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  @endforeach





                                              </tbody>
                                            </table>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>

        
                                </div>
                                <!-- Row ends -->
                    
                                <!-- Modal Delete Row -->
                                <div class="modal fade" id="delRow" tabindex="-1" aria-labelledby="delRowLabel" aria-hidden="true">
                                  <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="delRowLabel">
                                          Confirm
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body">
                                        Are you sure you want to delete this report?
                                      </div>
                                      <div class="modal-footer">
                                        <div class="d-flex justify-content-end gap-2">
                                          <button class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">No</button>
                                          <button class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Yes</button>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                    
                                <!-- Modal View All Reports -->
                                <div class="modal fade" id="viewReportsModal1" tabindex="-1" aria-labelledby="viewReportsModalLabel1"
                                  aria-hidden="true">
                                  <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="viewReportsModalLabel1">
                                          View Reports
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body">
                    
                                        <!-- Row starts -->
                                        <div class="row g-3">

                                          <div class="col-sm-2">
                                            <a href="#" class="d-flex flex-column bg-light p-2 rounded-2 text-center"
                                              data-bs-target="#viewReportsModal2" data-bs-toggle="modal">
                                              <img src="assets/images/report.svg" class="img-fluid rounded-2" alt="Medical Dashboards">
                                              <h6 class="mt-3 mb-1 text-truncate">Clinical Report</h6>
                                              <p class="m-0 small">10/05/2024</p>
                                            </a>
                                          </div>

                                        </div>
                                        <!-- Row ends -->
                    
                                      </div>
                                    </div>
                                  </div>
                                </div>
                    
                                <!-- Modal View Single Report -->
                                <div class="modal fade" id="viewReportsModal2" tabindex="-1" aria-labelledby="viewReportsModalLabel2"
                                  aria-hidden="true">
                                  <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="viewReportsModalLabel2">
                                          <div class="d-flex align-items-center">
                                            <a href="#!" class="btn btn-sm btn-outline-primary me-2" data-bs-target="#viewReportsModal1"
                                              data-bs-toggle="modal">
                                              <i class="ri-arrow-left-wide-fill"></i>
                                            </a>
                                            Clinical Report
                                          </div>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body">
                    
                                        <!-- Row starts -->
                                        <div class="row g-3">
                                          <div class="col-sm-12">
                                            <a href="#" class="d-flex flex-column bg-light p-2 rounded-2 text-center">
                                              <img src="assets/images/report.svg" class="img-fluid rounded-2" alt="Medical Dashboards">
                                              <h6 class="mt-3 mb-1 text-truncate">Clinical Report</h6>
                                              <p class="m-0 small">10/05/2024</p>
                                            </a>
                                          </div>
                                        </div>
                                        <!-- Row ends -->
                    
                                      </div>
                                    </div>
                                  </div>
                                </div>
                    
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.delete-btn');
      
            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const medId = this.getAttribute('data-id');
      
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This will permanently delete the medication record.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-medication-form-' + medId).submit();
                        }
                    });
                });
            });
        });
      </script>

<script>
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })
</script>

      
    
@include('parklane.dentist.script')