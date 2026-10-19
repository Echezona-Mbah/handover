<nav id="sidebar" class="sidebar-wrapper">

    <div class="sidebar-profile">
      <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('assets/images/user2.png') }}" class="img-shadow img-3x me-3 rounded-5" alt="{{ Auth::user()->name }}">
      <div class="m-0">
        <h5 class="mb-1 profile-name text-nowrap text-truncate">{{ Auth::user()->name }}</h5>
        <p class="m-0 small profile-name text-nowrap text-truncate">{{ Auth::user()->name }}</p>
      </div>
    </div>

    {{-- <p>DEBUG: {{ asset('storage/' . Auth::user()->profile_picture) }}</p> --}}

    <div class="sidebarMenuScroll">
      <ul class="sidebar-menu">

        <li class="active current-page">
          <a href="{{ route('dashboard') }}">
            <i class="ri-home-6-line"></i>
            <span class="menu-text">Dashboard</span>
          </a>
        </li>

        {{-- Doctors: visible to everyone (super admin sees all, department manager sees only their own via controller scoping) --}}
        <li class="treeview">
          <a href="#!">
            <i class="ri-stethoscope-line"></i>
            <span class="menu-text">Doctors</span>
          </a>
          <ul class="treeview-menu">
            <li>
              <a href="{{ route('doctor_list') }}">Doctors List</a>
            </li>
            <li>
              <a href="{{ route('add_doctor') }}">Add Doctor</a>
            </li>
            <li>
              <a href="{{ route('doctorShift') }}">Doctors Shift</a>
            </li>
          </ul>
        </li>

      @if(auth()->user()->is_super_admin)
        <li class="treeview">
          <a href="#!">
            <i class="ri-building-2-line"></i>
            <span class="menu-text">Departments</span>
          </a>
          <ul class="treeview-menu">
            <li>
              <a href="{{ route('departments.index') }}">Manage Departments</a>
            </li>
          </ul>
        </li>

        @endif

        @if(auth()->user()->is_super_admin)

          <li class="treeview">
            <a href="#!">
              <i class="ri-notification-badge-line"></i>
              <span class="menu-text">Nurse</span>
            </a>
            <ul class="treeview-menu">
              <li>
                <a href="{{ route('nurse_list') }}">Nurse List</a>
              </li>
              <li>
                <a href="{{ route('add_nurse') }}">Add Nurse</a>
              </li>
              <li>
                <a href="{{ route('admin.nurse-notifications.create') }}">Notify Nurses</a>
              </li>
              <li>
                <a href="{{ route('admin.nurse-notifications.acknowledgements') }}">Announcement Acknowledgements</a>
              </li>
            </ul>
          </li>

          <li class="treeview">
        <a href="#!">
          <i class="ri-nurse-line"></i>
          <span class="menu-text">Nursing Digital</span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ route('admin.nursing.reports') }}">Reports Review</a></li>
          <li><a href="{{ route('admin.nursing.statistics') }}">Statistics</a></li>
          <li><a href="{{ route('admin.nursing.rosters') }}">Roster Approval</a></li>
        </ul>
      </li>


          <li class="treeview">
            <a href="#!">
              <i class="ri-group-2-line"></i>
              <span class="menu-text">Dentists</span>
            </a>
            <ul class="treeview-menu">
              <li>
                <a href="{{ route('dentist_list') }}">Dentists List</a>
              </li>
              <li>
                <a href="{{ route('add_dentist') }}">Add Dentists</a>
              </li>
              <li>
                <a href="{{ route('dentistShift') }}">Dentists shifts</a>
              </li>
            </ul>
          </li>

          {{-- <li class="treeview">
            <a href="#!">
              <i class="ri-heart-pulse-line"></i>
              <span class="menu-text">Patients</span>
            </a>
            <ul class="treeview-menu">
              <li>
                <a href="{{ route('patient_list') }}">Patients List</a>
              </li>
              <li>
                <a href="{{ route('add_patient') }}">Add Patient</a>
              </li>
            </ul>
          </li> --}}

          <li class="treeview">
            <a href="#!">
              <i class="ri-shield-user-line"></i>
              <span class="menu-text">Admins</span>
            </a>
            <ul class="treeview-menu">
              <li>
                <a href="{{ route('add_admin_user') }}">Add Admin User</a>
              </li>
            </ul>
          </li>

          <li class="treeview">
            <a href="#!">
              <i class="ri-money-dollar-circle-line"></i>
              <span class="menu-text">News</span>
            </a>
            <ul class="treeview-menu">
              <li>
                <a href="{{ route('add_news') }}">Upload News</a>
              </li>
              <li>
                <a href="{{ route('newss') }}">All News</a>
              </li>
            </ul>
          </li>

        @endif

      </ul>
    </div>

    <div class="sidebar-contact">
      <p class="fw-light mb-1 text-nowrap text-truncate">Emergency Contact</p>
      <h5 class="m-0 lh-1 text-nowrap text-truncate">0987654321</h5>
      <i class="ri-phone-line"></i>
    </div>

</nav>