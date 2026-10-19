<div class="app-header d-flex align-items-center">

    <!-- Toggle buttons starts -->
    <div class="d-flex">
      <button class="toggle-sidebar">
        <i class="ri-menu-line"></i>
      </button>
      <button class="pin-sidebar">
        <i class="ri-menu-line"></i>
      </button>
    </div>
    <!-- Toggle buttons ends -->

    <!-- App brand starts -->
    <div class="app-brand ms-3">
      <a href="{{asset('dashboard')}}" class="d-lg-block d-none">
        <img src="{{asset('img/unthlogo.jpg')}}" class="logo" alt="Medicare Admin Template">
      </a>
      <a href="{{asset('dashboard')}}" class="d-lg-none d-md-block">
        <img src="{{asset('img/unthlogo.jpg')}}" class="logo" alt="Medicare Admin Template">
      </a>
    </div>
    <!-- App brand ends -->

    <!-- App header actions starts -->
    <div class="header-actions">

      <!-- Search container starts -->
      <div class="search-container d-lg-block d-none mx-3">
        <input type="text" class="form-control" id="searchId" placeholder="Search">
        <i class="ri-search-line"></i>
      </div>
      <!-- Search container ends -->

      <!-- Header actions starts -->
      <div class="d-lg-flex d-none gap-2">

        <!-- Select country dropdown starts -->
        {{-- <div class="dropdown">
          <a class="dropdown-toggle header-icon" href="#!" role="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <img src="assets/images/flags/1x1/fr.svg" class="header-country-flag" alt="Bootstrap Dashboards">
          </a>
          <div class="dropdown-menu dropdown-menu-end dropdown-mini">
            <div class="country-container">
              <a href="index.html" class="py-2">
                <img src="assets/images/flags/1x1/us.svg" alt="Admin Panel">
              </a>
              <a href="index.html" class="py-2">
                <img src="assets/images/flags/1x1/in.svg" alt="Admin Panels">
              </a>
              <a href="index.html" class="py-2">
                <img src="assets/images/flags/1x1/br.svg" alt="Admin Dashboards">
              </a>
              <a href="index.html" class="py-2">
                <img src="assets/images/flags/1x1/tr.svg" alt="Admin Templatess">
              </a>
              <a href="index.html" class="py-2">
                <img src="assets/images/flags/1x1/gb.svg" alt="Google Admin">
              </a>
            </div>
          </div>
        </div> --}}
        <!-- Select country dropdown ends -->

        <!-- Notifications dropdown starts -->
        {{-- <div class="dropdown">
          <a class="dropdown-toggle header-icon" href="#!" role="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="ri-list-check-3"></i>
            <span class="count-label warning"></span>
          </a>
          <div class="dropdown-menu dropdown-menu-end dropdown-300">
            <h5 class="fw-semibold px-3 py-2 text-primary">Activity</h5>

            <!-- Scroll starts -->
            <div class="scroll300">

              <!-- Activity List Starts -->
              <div class="p-3">
                <ul class="p-0 activity-list2">
                  <li class="activity-item pb-3 mb-3">
                    <a href="#!">
                      <h5 class="fw-regular">
                        <i class="ri-circle-fill text-danger me-1"></i>
                        Invoices.
                      </h5>
                      <div class="ps-3 ms-2 border-start">
                        <div class="d-flex align-items-center mb-2">
                          <div class="flex-shrink-0">
                            <img src="assets/images/products/1.jpg" class="img-shadow img-3x rounded-1"
                              alt="Hospital Admin Templates">
                          </div>
                          <div class="flex-grow-1 ms-3">
                            23 invoices have been paid to the MediCare Labs.
                          </div>
                        </div>
                        <p class="m-0 small">10:20AM Today</p>
                      </div>
                    </a>
                  </li>
                  <li class="activity-item pb-3 mb-3">
                    <a href="#!">
                      <h5 class="fw-regular">
                        <i class="ri-circle-fill text-info me-1"></i>
                        Purchased.
                      </h5>
                      <div class="ps-3 ms-2 border-start">
                        <div class="d-flex align-items-center mb-2">
                          <div class="flex-shrink-0">
                            <img src="assets/images/products/2.jpg" class="img-shadow img-3x rounded-1"
                              alt="Hospital Admin Templates">
                          </div>
                          <div class="flex-grow-1 ms-3">
                            28 new surgical equipments have been purchased.
                          </div>
                        </div>
                        <p class="m-0 small">04:30PM Today</p>
                      </div>
                    </a>
                  </li>
                  <li class="activity-item pb-3 mb-3">
                    <a href="#!">
                      <h5 class="fw-regular">
                        <i class="ri-circle-fill text-success me-1"></i>
                        Appointed.
                      </h5>
                      <div class="ps-3 ms-2 border-start">
                        <div class="d-flex align-items-center mb-2">
                          <div class="flex-shrink-0">
                            <img src="assets/images/products/8.jpg" class="img-shadow img-3x rounded-1"
                              alt="Hospital Admin Templates">
                          </div>
                          <div class="flex-grow-1 ms-3">
                            36 new doctors and 28 staff members appointed.
                          </div>
                        </div>
                        <p class="m-0 small">06:50PM Today</p>
                      </div>
                    </a>
                  </li>
                  <li class="activity-item">
                    <a href="#!">
                      <h5 class="fw-regular">
                        <i class="ri-circle-fill text-warning me-1"></i>
                        Requested
                      </h5>
                      <div class="ps-3 ms-2 border-start">
                        <div class="d-flex align-items-center mb-2">
                          <div class="flex-shrink-0">
                            <img src="assets/images/products/9.jpg" class="img-shadow img-3x rounded-1"
                              alt="Hospital Admin Templates">
                          </div>
                          <div class="flex-grow-1 ms-3">
                            Requested for 6 new vehicles for medical emergency. .
                          </div>
                        </div>
                        <p class="m-0 small">08:30PM Today</p>
                      </div>
                    </a>
                  </li>
                </ul>
              </div>
              <!-- Activity List Ends -->

            </div>
            <!-- Scroll ends -->

            <!-- View all button starts -->
            <div class="d-grid m-3">
              <a href="javascript:void(0)" class="btn btn-primary">View all</a>
            </div>
            <!-- View all button ends -->

          </div>
        </div> --}}
        <!-- Notifications dropdown ends -->

        <!-- Notifications dropdown starts -->
        {{-- <div class="dropdown">
          <a class="dropdown-toggle header-icon" href="#!" role="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="ri-alarm-warning-line"></i>
            <span class="count-label success"></span>
          </a>
          <div class="dropdown-menu dropdown-menu-end dropdown-300">
            <h5 class="fw-semibold px-3 py-2 text-primary">Alerts</h5>

            <!-- Scroll starts -->
            <div class="scroll300">

              <!-- Alert list starts -->
              <div class="p-3">
                <div class="d-flex py-2">
                  <div class="icon-box md bg-info rounded-circle me-3">
                    <span class="fw-bold fs-6 text-white">DS</span>
                  </div>
                  <div class="m-0">
                    <h6 class="mb-1 fw-semibold">Douglass Shaw</h6>
                    <p class="mb-1">
                      Appointed as a new President 2014-2025
                    </p>
                    <p class="small m-0 opacity-50">Today, 07:30pm</p>
                  </div>
                </div>
                <div class="d-flex py-2">
                  <div class="icon-box md bg-danger rounded-circle me-3">
                    <span class="fw-bold fs-6 text-white">WG</span>
                  </div>
                  <div class="m-0">
                    <h6 class="mb-1 fw-semibold">Willie Garrison</h6>
                    <p class="mb-1">
                      Congratulate, James for new job.
                    </p>
                    <p class="small m-0 opacity-50">Today, 08:00pm</p>
                  </div>
                </div>
                <div class="d-flex py-2">
                  <div class="icon-box md bg-warning rounded-circle me-3">
                    <span class="fw-bold fs-6 text-white">TJ</span>
                  </div>
                  <div class="m-0">
                    <h6 class="mb-1 fw-semibold">Terry Jenkins</h6>
                    <p class="mb-1">
                      Lewis added new doctors training schedule.
                    </p>
                    <p class="small m-0 opacity-50">Today, 09:30pm</p>
                  </div>
                </div>
              </div>
              <!-- Alert list ends -->

            </div>
            <!-- Scroll ends -->

            <!-- View all button starts -->
            <div class="d-grid m-3">
              <a href="javascript:void(0)" class="btn btn-primary">View all</a>
            </div>
            <!-- View all button ends -->

          </div>
        </div> --}}
        <!-- Notifications dropdown ends -->

        <!-- Messages dropdown starts -->
        {{-- <div class="dropdown">
          <a class="dropdown-toggle header-icon" href="#!" role="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="ri-message-3-line"></i>
            <span class="count-label"></span>
          </a>
          <div class="dropdown-menu dropdown-menu-end dropdown-300">
            <h5 class="fw-semibold px-3 py-2 text-primary">Messages</h5>

            <!-- Scroll starts -->
            <div class="scroll300">

              <!-- Messages list starts -->
              <div class="p-3">
                <div class="d-flex py-2">
                  <img src="assets/images/user3.png" class="img-shadow img-3x me-3 rounded-5"
                    alt="Hospital Admin Templates">
                  <div class="m-0">
                    <h6 class="mb-1 fw-semibold">Nick Gonzalez</h6>
                    <p class="mb-1">
                      Appointed as a new President 2014-2025
                    </p>
                    <p class="small m-0 opacity-50">Today, 07:30pm</p>
                  </div>
                </div>
                <div class="d-flex py-2">
                  <img src="assets/images/user1.png" class="img-shadow img-3x me-3 rounded-5"
                    alt="Hospital Admin Templates">
                  <div class="m-0">
                    <h6 class="mb-1 fw-semibold">Clyde Fowler</h6>
                    <p class="mb-1">
                      Congratulate, James for new job.
                    </p>
                    <p class="small m-0 opacity-50">Today, 08:00pm</p>
                  </div>
                </div>
                <div class="d-flex py-2">
                  <img src="assets/images/user4.png" class="img-shadow img-3x me-3 rounded-5"
                    alt="Hospital Admin Templates">
                  <div class="m-0">
                    <h6 class="mb-1 fw-semibold">Sophie Michiels</h6>
                    <p class="mb-1">
                      Lewis added new doctors training schedule.
                    </p>
                    <p class="small m-0 opacity-50">Today, 09:30pm</p>
                  </div>
                </div>
              </div>
              <!-- Messages list ends -->

            </div>
            <!-- Scroll ends -->

            <!-- View all button starts -->
            <div class="d-grid m-3">
              <a href="javascript:void(0)" class="btn btn-primary">View all</a>
            </div>
            <!-- View all button ends -->

          </div>
        </div> --}}
      </div>
      <!-- Header actions ends -->

      <!-- Header user settings starts -->
    <!-- Header user settings starts -->
<div class="dropdown ms-2">
  <a id="userSettings" class="dropdown-toggle d-flex align-items-center" href="#!"
     role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <div class="avatar-box">
      @if (auth()->user()->profile_picture)
        <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}"
             class="rounded-circle"
             width="36" height="36"
             style="object-fit: cover;"
             alt="{{ auth()->user()->name }}">
      @else
        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
      @endif
      <span class="status busy"></span>
    </div>
  </a>

  <div class="dropdown-menu dropdown-menu-end shadow-lg">
    <div class="px-3 py-2 d-flex align-items-center gap-2">
      @if (auth()->user()->profile_picture)
        <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}"
             class="rounded-circle"
             width="40" height="40"
             style="object-fit: cover;"
             alt="{{ auth()->user()->name }}">
      @else
        <div class="avatar-box">
          {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
      @endif
      <div>
        <span class="small d-block">{{ auth()->user()->role->name ?? 'Admin' }}</span>
        <h6 class="m-0">{{ auth()->user()->name }}</h6>
      </div>
    </div>

    <div class="dropdown-divider"></div>

    <a href="{{ route('profile.edit') }}" class="dropdown-item">
      <i class="ri-user-settings-line me-2"></i> View / Edit Profile
    </a>

    <div class="mx-3 my-2 d-grid">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); this.closest('form').submit();"
           class="btn btn-danger">Logout</a>
      </form>
    </div>
  </div>
</div>
<!-- Header user settings ends -->
      <!-- Header user settings ends -->

    </div>
    <!-- App header actions ends -->

  </div>