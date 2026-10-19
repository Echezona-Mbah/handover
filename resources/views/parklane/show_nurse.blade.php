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
               Nurse
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

                    <!-- Row starts -->
                    <div class="row gx-3">
                      <div class="col-sm-3">
                        <img src="{{ $nurse->profile_image ? asset('storage/' . $nurse->profile_image) : asset('assets/images/user.png') }}" class="img-fluid rounded-3" alt="Medical Dashboard">
                      </div>
                      <div class="col-sm-9">
                        <div class="text-white mt-3">
                          <h6>Hello I am,</h6>
                          <h3>Nurse. {{ $nurse->full_name }}</h3>
                          <h6>{{ $nurse->ward?->name ?? 'Unassigned' }}
                            @if($nurse->ward?->linen_color)<br>Linen: {{ $nurse->ward->linen_color }}@endif
                            <br>{{ $nurse->NurseDepartment?->name ?? 'No department' }}
                            <br>Nurse cadre: {{ $nurse->NursingRole?->name ?? 'Not assigned' }} </h6>
                          {{-- <h6>{{ $nurse->years_of_experience }} Years Experience Overall</h6> --}}
                          <div class="rating-stars">
                            <div class="readonly5"></div>
                          </div>
                          <div class="mt-1">2896 Reviews</div>
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
                        <h1 class="text-primary">{{$totalPatients}}</h1>
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

                  </div>
                </div>
              </div> --}}
              {{-- <div class="col-xxl-2 col-sm-4">
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
            <!-- Row ends -->

            <!-- Row starts -->
            <div class="row gx-3">

              <div class="col-xl-4 col-sm-12">

                <!-- Row starts -->
                <div class="row gx-3">
                  <div class="col-xl-12 col-sm-6">
                    <div class="card mb-3">
                      <div class="card-header">
                        <h5 class="card-title">Avalibility</h5>
                      </div>
                      <div class="card-body">

                        <div class="d-flex flex-wrap gap-1 mb-3">
                          <span class="p-2 lh-1 bg-light rounded-2 box-shadow">Mon - {{ $nurse->mon_start }} - {{ $nurse->mon_end }}</span>
                          <span class="p-2 lh-1 bg-light rounded-2 box-shadow">Tue -{{ $nurse->tue_start }} - {{ $nurse->tue_end }}</span>
                          <span class="p-2 lh-1 bg-light rounded-2 box-shadow">Wed - {{ $nurse->wed_start }} - {{ $nurse->wed_end }}</span>
                          <span class="p-2 lh-1 bg-light rounded-2 box-shadow">Thu - {{ $nurse->thu_start }} - {{ $nurse->thu_end }}</span>
                          <span class="p-2 lh-1 bg-light rounded-2 box-shadow">Fri - {{ $nurse->fri_start }} - {{ $nurse->fri_end }}</span>
                          <span class="p-2 lh-1 bg-light rounded-2 box-shadow">Sat -{{ $nurse->sat_start }} - {{ $nurse->sat_end }}</span>
                          <span class="p-2 lh-1 bg-light rounded-2 box-shadow">Sun - NA</span>
                        </div>

                        {{-- <a href="book-appointment.html" class="btn btn-primary">
                          Book Appointment
                        </a> --}}
                      </div>
                    </div>
                  </div>
                  {{-- <div class="col-xl-12 col-sm-6">
                    <div class="card mb-3">
                      <div class="card-header">
                        <h5 class="card-title">Awards</h5>
                      </div>
                      <div class="card-body">

                        <div>
                          <div id="carouselAwards" class="carousel carousel-dark slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                              <button type="button" data-bs-target="#carouselAwards" data-bs-slide-to="0" class="active"
                                aria-current="true" aria-label="Slide 1"></button>
                              <button type="button" data-bs-target="#carouselAwards" data-bs-slide-to="1"
                                aria-label="Slide 2"></button>
                              <button type="button" data-bs-target="#carouselAwards" data-bs-slide-to="2"
                                aria-label="Slide 3"></button>
                            </div>
                            <div class="carousel-inner">
                              <div class="carousel-item active" data-bs-interval="10000">
                                <img src="assets/images/award/award.svg" class="d-block w-100" alt="Medical Templates">
                              </div>
                              <div class="carousel-item" data-bs-interval="5000">
                                <img src="assets/images/award/award1.svg" class="d-block w-100" alt="Medical Templates">
                              </div>
                              <div class="carousel-item" data-bs-interval="2500">
                                <img src="assets/images/award/award2.svg" class="d-block w-100" alt="Medical Templates">
                              </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselAwards"
                              data-bs-slide="prev">
                              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselAwards"
                              data-bs-slide="next">
                              <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            </button>
                          </div>

                          <div class="text-center">
                            <h1 class="text-primary">4</h1>
                            Awards received in 2024.
                          </div>

                        </div>

                      </div>
                    </div>
                  </div> --}}
                </div>
                <!-- Row ends -->

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

    
    
@include('parklane.script')
