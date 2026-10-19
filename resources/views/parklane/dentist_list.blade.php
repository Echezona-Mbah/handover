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
                Dentist List
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
              <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title">Dentist List</h5>
                <a href="add_doctors" class="btn btn-primary ms-auto">Add Dentist</a>
              </div>
              <div class="card-body">
                <div id="toast-container"></div>

                <!-- Table starts -->
                <div class="table-responsive">
                  <table id="basicExample" class="table truncate m-0 align-middle">
                      <thead>
                          <tr>
                              <th>ID</th>
                              <th>Doctor Name</th>
                              <th>Department</th>
                              <th class="text-center">Sun</th>
                              <th class="text-center">Mon</th>
                              <th class="text-center">Tue</th>
                              <th class="text-center">Wed</th>
                              <th class="text-center">Thu</th>
                              <th class="text-center">Fri</th>
                              <th class="text-center">Sat</th>
                              <th>Actions</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($dentists as $dentist)
                              <tr>
                                  <td>#{{ $dentist->id }}</td>
                                  <td>
                                    <img src="{{ $dentist->profile_image ? asset('storage/' . $dentist->profile_image) : asset('assets/images/user.png') }}"  class="img-shadow img-2x rounded-5 me-1" alt="Doctor">
                                      {{ $dentist->full_name }}
                                  </td>
                                  <td>{{ $dentist->load('DentistDepartment')->DentistDepartment?->name }}</td>
                                  <td class="text-center">{{ $dentist->sun_start ? $dentist->sun_start . ' - ' . $dentist->sun_end : 'NA' }}</td>
                                  <td class="text-center">{{ $dentist->mon_start ? $dentist->mon_start . ' - ' . $dentist->mon_end : 'NA' }}</td>
                                  <td class="text-center">{{ $dentist->tue_start ? $dentist->tue_start . ' - ' . $dentist->tue_end : 'NA' }}</td>
                                  <td class="text-center">{{ $dentist->wed_start ? $dentist->wed_start . ' - ' . $dentist->wed_end : 'NA' }}</td>
                                  <td class="text-center">{{ $dentist->thu_start ? $dentist->thu_start . ' - ' . $dentist->thu_end : 'NA' }}</td>
                                  <td class="text-center">{{ $dentist->fri_start ? $dentist->fri_start . ' - ' . $dentist->fri_end : 'NA' }}</td>
                                  <td class="text-center">{{ $dentist->sat_start ? $dentist->sat_start . ' - ' . $dentist->sat_end : 'NA' }}</td>
                                  <td>
                                      <div class="d-inline-flex gap-1">
                                        <form id="delete-doctor-form-{{ $dentist->id }}" action="{{ route('dentists.destroy', $dentist->id) }}" method="POST" class="d-inline delete-doctor-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-outline-danger btn-sm delete-btn" data-id="{{ $dentist->id }}">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('dentists.shifts', $dentist->id) }}" class="btn btn-outline-success btn-sm">
                                          <i class="ri-add-line"></i> Add Shift
                                      </a>
                                      
                    
                                          <a href="{{ route('dentists.show', $dentist->id) }}" class="btn btn-outline-info btn-sm"
                                          data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View Profile">
                                          <i class="ri-eye-line"></i>
                                        </a>
                                      </div>
                                  </td>
                              </tr>
                          @endforeach
                      </tbody>
                  </table>
              </div>
              
                <!-- Table ends -->

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
                        Are you sure you want to delete the dentist from list?
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
                </div>

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
            {{-- <span>©  2024</span> --}}
          </div>
          <!-- App footer ends -->

        </div>
        <!-- App container ends -->

      </div>
      <!-- Main container ends -->

    </div>
    <!-- Page wrapper ends -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
  
    
@include('parklane.script')