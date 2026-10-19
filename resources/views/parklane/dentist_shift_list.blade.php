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
                Doctor Shift List
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
                <h5 class="card-title">Dentist Shift List</h5>
                <a href="add-doctors.html" class="btn btn-primary ms-auto">Add Dentist</a>
              </div>
              <div class="card-body">

                <!-- Table starts -->
                <div class="table-responsive">
                  <table id="basicExample" class="table truncate m-0 align-middle">
                      <thead>
                          <tr>
                              <th>Dentist Name</th>
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
                          @foreach ($shifts as $shift)
                              <tr>
                             {{-- {{   dd($shift)}} --}}
                                  <td>
                                    <img src="{{ $shift->profile_image ? asset('storage/' . $shift->profile_image) : asset('assets/images/user.png') }}"  class="img-shadow img-2x rounded-5 me-1" alt="Doctor">
                                      {{ $shift->dentist?->full_name }}
                                  </td>
                                  <td>{{ $shift->dentist?->DentistDepartment?->name ?? 'N/A' }}</td>
                                  <td class="text-center">{{ $shift->sun_start ? $shift->sun_start . ' - ' . $shift->sun_end : 'NA' }}</td>
                                  <td class="text-center">{{ $shift->mon_start ? $shift->mon_start . ' - ' . $shift->mon_end : 'NA' }}</td>
                                  <td class="text-center">{{ $shift->tue_start ? $shift->tue_start . ' - ' . $shift->tue_end : 'NA' }}</td>
                                  <td class="text-center">{{ $shift->wed_start ? $shift->wed_start . ' - ' . $shift->wed_end : 'NA' }}</td>
                                  <td class="text-center">{{ $shift->thu_start ? $shift->thu_start . ' - ' . $shift->thu_end : 'NA' }}</td>
                                  <td class="text-center">{{ $shift->fri_start ? $shift->fri_start . ' - ' . $shift->fri_end : 'NA' }}</td>
                                  <td class="text-center">{{ $shift->sat_start ? $shift->sat_start . ' - ' . $shift->sat_end : 'NA' }}</td>
                                  <td>
                                      <div class="d-inline-flex gap-1">
                                        <form id="delete-doctor-form-{{ $shift->id }}" action="{{ route('destroydentistShift.shift.destroy', $shift->id) }}" method="POST" class="d-inline delete-doctor-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-outline-danger btn-sm delete-btn" data-id="{{ $shift->id }}">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                        
                                        <a href="{{ route('editDentistShift', $shift->id) }}" class="btn btn-outline-success btn-sm">
                                          <i class="ri-add-line"></i> Update Shift
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
                        Are you sure you want to delete the doctor from list?
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
$('.delete-btn').click(function(event) {
    event.preventDefault();  // Prevent default form submission behavior

    var form = $(this).closest('form');
    var shiftId = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();  // Submit the form after confirmation
        }
    });
});

  </script>
  
    
@include('parklane.script')