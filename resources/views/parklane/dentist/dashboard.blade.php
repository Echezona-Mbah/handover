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
              <div class="col-xxl-6 col-sm-12">
                <div class="card mb-3 bg-1">
                  <div class="card-body mh-230">
                    @php
                    $dentist = Auth::guard('dentist')->user();
                @endphp
                    <!-- Row starts -->
                    <div class="row gx-3">
                      <div class="col-sm-3">
                        @if($dentist && $dentist->profile_image)
                            <img src="{{ asset('storage/' . $dentist->profile_image) }}" 
                                 class="img-fluid rounded-3" alt="Doctor Profile">
                        @else
                            <img src="{{ asset('assets/images/user.png') }}" class="img-fluid rounded-3" alt="Default Profile">
                        @endif
                    </div>
                    
                    
                      <div class="col-sm-9">
                        <div class="text-white mt-3">
                          <h6>Hello I am,</h6>
                          <h3>Dr. {{ Auth::guard('dentist')->user()->full_name }}</h3>
                          <h6>{{ Auth::guard('dentist')->user()->qualification }} - {{ Auth::guard('dentist')->user()->load('DentistDepartment')->DentistDepartment?->name }}</h6>
                          {{-- <h6>{{ Auth::guard('dentist')->user()->years_of_experience }} Years Experience Overall</h6> --}}
                          <div class="rating-stars">
                            <div class="readonly5"></div>
                          </div>
                          {{-- <div class="mt-1">2896 Reviews</div> --}}
                        </div>
                      </div>
                    </div>
                    <!-- Row ends -->

                  </div>
                </div>
              </div>
              <div class="col-xxl-2 col-sm-4">
                <div class="card mb-3">
                  <div class="card-body mh-230">

                    <!-- Card details start -->
                    <div>
                      <div class="d-flex flex-column align-items-center">
                        <div class="icon-box xl bg-primary-subtle rounded-5 mb-2 no-shadow">
                          <i class="ri-empathize-line fs-1 text-primary"></i>
                        </div>
                        <h1 class="text-primary">3605</h1>
                        <h6>Patients</h6>
                        <span class="badge bg-primary">68% High</span>
                      </div>
                    </div>
                    <!-- Card details end -->

                  </div>
                </div>
              </div>
              {{-- <div class="col-xxl-2 col-sm-4">
                <div class="card mb-3">
                  <div class="card-body mh-230">

                    <!-- Card details start -->
                    <div>
                      <div class="d-flex flex-column align-items-center">
                        <div class="icon-box xl bg-danger-subtle rounded-5 mb-2 no-shadow">
                          <i class="ri-lungs-line fs-1 text-danger"></i>
                        </div>
                        <h1 class="text-danger">507</h1>
                        <h6>Surgeries</h6>
                        <span class="badge bg-danger">26% High</span>
                      </div>
                    </div>
                    <!-- Card details end -->

                  </div>
                </div>
              </div>
              <div class="col-xxl-2 col-sm-4">
                <div class="card mb-3">
                  <div class="card-body mh-230">

                    <!-- Card details start -->
                    <div>
                      <div class="d-flex flex-column align-items-center">
                        <div class="icon-box xl bg-success-subtle rounded-5 mb-2 no-shadow">
                          <i class="ri-star-line fs-1 text-success"></i>
                        </div>
                        <h1 class="text-success">2896</h1>
                        <h6>Reviews</h6>
                        <span class="badge bg-success">30% High</span>
                      </div>
                    </div>
                    <!-- Card details end -->

                  </div>
                </div>
              </div> --}}
            </div>

            {{-- @php
            $shift = $dentist->shiftSchedules;
        @endphp --}}
        
        <div class="card mb-3">
          <div class="card-header">
            <h5 class="card-title">Shift</h5>
          </div>
          <div class="card-body">
            @foreach ($dentist->shiftSchedules as $shift)
            <div class="d-flex flex-wrap gap-1 mb-3">
              @foreach (['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] as $day)
                @php
                    $start = $shift->{$day . '_start'};
                    $end = $shift->{$day . '_end'};
                @endphp
          
                <span class="p-2 lh-1 bg-light rounded-2 box-shadow">
                  {{ ucfirst($day) }} -
                  @if ($start && $end)
                    {{ \Carbon\Carbon::parse($start)->format('g:i A') }} -
                    {{ \Carbon\Carbon::parse($end)->format('g:i A') }}
                  @else
                    NA
                  @endif
                </span>
              @endforeach
            </div>
          @endforeach
          
          </div>
        </div>
        

            
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