@include('parklane.dentist.head')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
          <div id="toast-container"></div>

                    <!-- App body starts -->
                              <!-- App body starts -->
                              <div class="app-body">

                                <!-- Row starts -->
                                <div class="row gx-3">
                                    <div class="col-sm-12">
                                      <div class="card">
                                        <div class="card-header d-flex align-items-center justify-content-between">
                                          <h5 class="card-title">Patients Assign List</h5>
                                          <a href="add-patient.html" class="btn btn-primary ms-auto">Add Patient</a>
                                        </div>
                                        <div class="card-body">
                      
                                          <!-- Table starts -->
                                          <div class="table-responsive">
                                            <table id="basicExample" class="table truncate m-0 align-middle">
                                              <thead>
                                                <tr>
                                                  <th>No.</th>
                                                  <th>Patient Name</th>
                                                  <th>Gender</th>
                                                  {{-- <th>Age</th>
                                                  <th>Blood Group</th> --}}
                                                  <th>Treatment</th>
                                                  <th>Current Condition</th>
                                                  <th>Ward</th>
                                                  <th>Current Medications</th>
                                                  <th>Allergies</th>
                                                  <th>Urgency status</th>
                                                  <th>Plan</th>
                                                  <th>HandoverNote</th>
                                                  {{-- <th>HandoverNote</th> --}}

                                                  <th>Actions</th>

                                                </tr>
                                              </thead>
                                              <tbody>

                                                @if(isset($patients) && $patients->count() > 0)
                                                @foreach ($patients as $patient)
                                                    <tr>
                                                        <td>#{{ $patient->patient_number }}</td>
                                                        <td>{{ $patient->full_name }}</td>
                                                        <td>
                                                            @if($patient->gender == 'male')
                                                                <span class="badge bg-danger text-white">{{ ucfirst($patient->gender) }}</span>
                                                            @elseif($patient->gender == 'female')
                                                                <span class="badge bg-success text-white">{{ ucfirst($patient->gender) }}</span>
                                                            @else
                                                                <span class="badge bg-secondary text-white">{{ ucfirst($patient->gender) }}</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $patient->sugar_level ?? 'N/A' }}</td>
                                                        <td>{{ $patient->current_condition }}</td>
                                                        <td>{{ $patient->ward }}</td>
                                                        <td>{{ $patient->current_medications }}</td>
                                                        <td>{{ $patient->allergies }}</td>
                                                        <td>{{ $patient->urgency_status }}</td>
                                                        <td>
                                                            <a href="{{ route('dentist.patients.plan', $patient->id) }}" class="btn btn-outline-primary btn-sm">Medication</a>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('handover.show', $patient->id) }}" class="btn btn-outline-primary btn-sm">HandoverNote</a>
                                                        </td>
                                                        {{-- <td>
                                                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#requestTestModal{{ $patient->id }}">
                                                                Request Tests
                                                            </button>
                                                        </td> --}}
                                                        <td>
                                                            <div class="d-inline-flex gap-1">
                                                                <a href="{{ route('dentist.edit_patients', $patient->id) }}" class="btn btn-outline-success btn-sm"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Patient Details">
                                                                    <i class="ri-edit-box-line"></i>
                                                                </a>
                                                                <a href="{{ route('dentist.show_patient', $patient->id) }}" class="btn btn-outline-info btn-sm"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="View Dashboard">
                                                                    <i class="ri-eye-line"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                            
                                                    {{-- Include modal here to avoid $patient undefined --}}
                                                    @include('partials.request-test-modal', ['patient' => $patient])
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="12" class="text-center text-danger">No patients assigned to you at the moment.</td>
                                                </tr>
                                            @endif
                                            

                                              <div class="mt-4">
                                                {{ $patients->links('pagination::bootstrap-5') }}
                                              </div>
                                              
                                        
                                              </tbody>
                                            </table>
                                          </div>
                                          <!-- Table ends -->
                      
                                          <!-- Modal Delete Row -->
                                          <!-- Modal: Request Test -->
                                          @foreach ($patients as $patient)
                                          <!-- other patient information here -->
                                          @include('partials.request-test-modal', ['patient' => $patient])
                                        @endforeach
                                      
                                          

                                          {{-- <div class="modal fade" id="delRow" tabindex="-1" aria-labelledby="delRowLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-sm">
                                              <div class="modal-content">
                                                <div class="modal-header">
                                                  <h5 class="modal-title" id="delRowLabel">
                                                    Confirm
                                                  </h5>
                                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                  Are you sure you want to delete the patient?
                                                </div>
                                                <div class="modal-footer">
                                                  <div class="d-flex justify-content-end gap-2">
                                                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                                      aria-label="Close">No</button>
                                                    <button class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Yes</button>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          </div> --}}
                      
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
    <!-- Include SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- Include SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
      const deleteButtons = document.querySelectorAll('.delete-btn');

      deleteButtons.forEach(button => {
          button.addEventListener('click', function () {
              const doctorId = this.getAttribute('data-id');

              Swal.fire({
                  title: 'Are you sure?',
                  text: "This action cannot be undone!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Yes, delete it!'
              }).then((result) => {
                  if (result.isConfirmed) {
                      document.getElementById('delete-doctor-form-' + doctorId).submit();
                  }
              });
          });
      });
  });
</script>

    
    
@include('parklane.doctor.script')