@include('parklane.head')
    <!-- Loading starts -->
    <div id="loading-wrapper">
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
    </div>
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
          <!-- App Hero header ends -->

          <!-- App body starts -->
          <div class="app-body">

            <!-- Row starts -->
            <div class="row gx-3">
              <div class="col-xxl-12 col-sm-12">
                <div class="card mb-3 bg-2">
                  <div class="card-body">
                    <div class="py-4 px-3 text-white">
                      @php
                      $hour = now()->format('H');
                      if ($hour < 12) {
                          $greeting = 'Good Morning';
                      } elseif ($hour < 17) {
                          $greeting = 'Good Afternoon';
                      } else {
                          $greeting = 'Good Evening';
                      }
                  @endphp

                  <h6>{{ $greeting }},</h6>
                  <h2>Dr. {{ auth()->user()->name }}</h2>
                      {{-- <div class="mt-4 d-flex gap-3">
                        <div class="d-flex align-items-center">
                          <div class="icon-box lg bg-arctic rounded-3 me-3">
                            <i class="ri-surgical-mask-line fs-4"></i>
                          </div>
                          <div class="d-flex flex-column">
                            <h2 class="m-0 lh-1">9</h2>
                            <p class="m-0">Patients</p>
                          </div>
                        </div>
                        <div class="d-flex align-items-center">
                          <div class="icon-box lg bg-lime rounded-3 me-3">
                            <i class="ri-lungs-line fs-4"></i>
                          </div>
                          <div class="d-flex flex-column">
                            <h2 class="m-0 lh-1">3</h2>
                            <p class="m-0">Surgeries</p>
                          </div>
                        </div>

                      </div> --}}
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Row ends -->

            <!-- Row starts -->
                      <!-- Row starts -->
            <div class="row gx-3">

              @if(auth()->user()->is_super_admin)
              <div class="col-xl-3 col-sm-6 col-12">
                <div class="card mb-3">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="p-2 border border-success rounded-circle me-3">
                        <div class="icon-box md bg-success-subtle rounded-5">
                          <i class="ri-surgical-mask-line fs-4 text-success"></i>
                        </div>
                      </div>
                      <div class="d-flex flex-column">
                        <h2 class="lh-1">{{ $patients }}</h2>
                        <p class="m-0">All Patients</p>
                      </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-1">
                      <a class="text-success" href="{{route('patient_list')}}">
                        <span>View All</span>
                        <i class="ri-arrow-right-line text-success ms-1"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              @endif

              <div class="col-xl-3 col-sm-6 col-12">
                <div class="card mb-3">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="p-2 border border-primary rounded-circle me-3">
                        <div class="icon-box md bg-primary-subtle rounded-5">
                          <i class="ri-lungs-line fs-4 text-primary"></i>
                        </div>
                      </div>
                      <div class="d-flex flex-column">
                        <h2 class="lh-1">{{ $doctors }}</h2>
                        <p class="m-0">All Doctor</p>
                      </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-1">
                      <a class="text-primary" href="{{route('doctor_list')}}">
                        <span>View All</span>
                        <i class="ri-arrow-right-line ms-1"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>

              @if(auth()->user()->is_super_admin)
              <div class="col-xl-3 col-sm-6 col-12">
                <div class="card mb-3">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="p-2 border border-danger rounded-circle me-3">
                        <div class="icon-box md bg-danger-subtle rounded-5">
                          <i class="ri-microscope-line fs-4 text-danger"></i>
                        </div>
                      </div>
                      <div class="d-flex flex-column">
                        <h2 class="lh-1">{{ $nurses }}</h2>
                        <p class="m-0">All Nurses</p>
                      </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-1">
                      <a class="text-danger" href="{{route('nurse_list')}}">
                        <span>View All</span>
                        <i class="ri-arrow-right-line ms-1"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-3 col-sm-6 col-12">
                <div class="card mb-3">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="p-2 border border-warning rounded-circle me-3">
                        <div class="icon-box md bg-warning-subtle rounded-5">
                          <i class="ri-money-dollar-circle-line fs-4 text-warning"></i>
                        </div>
                      </div>
                      <div class="d-flex flex-column">
                        <h2 class="lh-1">{{ $dentists }}</h2>
                        <p class="m-0">All Dentists</p>
                      </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-1">
                      <a class="text-warning" href="{{route('dentist_list')}}">
                        <span>View All</span>
                        <i class="ri-arrow-right-line ms-1"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              @endif

              @if(auth()->user()->is_super_admin)
              <div class="col-xl-3 col-sm-6 col-12">
                <div class="card mb-3">
                  <div class="card-body">
                    <div class="d-flex align-items-center">
                      <div class="p-2 border border-info rounded-circle me-3">
                        <div class="icon-box md bg-info-subtle rounded-5">
                          <i class="ri-shield-user-line fs-4 text-info"></i>
                        </div>
                      </div>
                      <div class="d-flex flex-column">
                        <h2 class="lh-1">{{ $admins }}</h2>
                        <p class="m-0">All Admins</p>
                      </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-1">
                      <a class="text-info" href="{{ route('add_admin_user') }}">
                        <span>View All</span>
                        <i class="ri-arrow-right-line ms-1"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              @endif

            </div>
            <!-- Row ends -->
            <!-- Row ends -->





          </div>
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