@include('parklane.doctor.head')

<div class="page-wrapper">

  @include('parklane.doctor.header')

  <div class="main-container">

    @include('parklane.doctor.sidebar')

    <div class="app-container">

      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="index.html">Home</a>
          </li>
          <li class="breadcrumb-item text-primary" aria-current="page">
            Dashboard
          </li>
        </ol>
      </div>

      <div class="app-body">

        <div class="row gx-3">
          <div class="col-xxl-8 col-sm-12">
            <div class="card mb-3 bg-1">
              <div class="card-body">

                <div class="row gx-3 align-items-center">
                  <div class="col-sm-3">
                    @if($doctor->profile_image)
                      <img src="{{ asset('storage/' . $doctor->profile_image) }}"
                           class="img-fluid rounded-3"
                           alt="Doctor Profile">
                    @else
                      <img src="{{ asset('assets/images/user2.png') }}"
                           class="img-fluid rounded-3"
                           alt="Default Profile">
                    @endif
                  </div>

                  <div class="col-sm-9">
                    <div class="text-white mt-3">
                      <h6>Hello,</h6>
                      <h3>Dr. {{ $doctor->full_name }}</h3>
                      {{-- <h6 class="mb-2">
                        {{ $doctor->qualification ?? 'No qualification added' }}
                      </h6> --}}

                      <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge bg-light text-dark">
                          Cadre: {{ $doctor->cadre ? ucwords(str_replace('_', ' ', $doctor->cadre)) : 'N/A' }}
                        </span>
                        <span class="badge bg-light text-dark">
                          Department: {{ $doctor->doctorDepartment?->name ?? 'N/A' }}
                        </span>
                        <span class="badge bg-light text-dark">
                          Unit: {{ $doctor->doctorUnit?->name ?? 'N/A' }}
                        </span>
                        <span class="badge bg-light text-dark">
                          Sub Unit: {{ $doctor->subUnit?->name ?? 'N/A' }}
                        </span>
                      </div>

                      <div class="row g-2">
                        <div class="col-md-6">
                          {{-- <p class="mb-1"><strong>Specialization:</strong> {{ $doctor->specialization ?? 'N/A' }}</p> --}}
                          {{-- <p class="mb-1"><strong>Experience:</strong> {{ $doctor->years_of_experience ?? 'N/A' }} years</p> --}}
                          <p class="mb-1"><strong>Email:</strong> {{ $doctor->email ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                          <p class="mb-1"><strong>Phone:</strong> {{ $doctor->phone_number ?? 'N/A' }}</p>
                          <p class="mb-1"><strong>Hospital:</strong> {{ $doctor->hospitals?->name ?? 'N/A' }}</p>
                          <p class="mb-1"><strong>Status:</strong> {{ ucfirst($doctor->status ?? 'n/a') }}</p>
                        </div>
                      </div>

                      <div class="rating-stars mt-2">
                        <div class="readonly5"></div>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <div class="col-xxl-2 col-sm-4">
            <div class="card mb-3">
              <div class="card-body mh-230">
                <div class="d-flex flex-column align-items-center">
                  <div class="icon-box xl bg-primary-subtle rounded-5 mb-2 no-shadow">
                    <i class="ri-empathize-line fs-1 text-primary"></i>
                  </div>
                  <h1 class="text-primary">{{ $patientCount }}</h1>
                  <h6>Patients</h6>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xxl-2 col-sm-4">
            <div class="card mb-3">
              <div class="card-body mh-230">
                <div class="d-flex flex-column align-items-center">
                  <div class="icon-box xl bg-success-subtle rounded-5 mb-2 no-shadow">
                    <i class="ri-hospital-line fs-1 text-success"></i>
                  </div>
                  <h1 class="text-success">{{ $invitedUnitsCount }}</h1>
                  <h6>Invited Units</h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        @php
          $shift = $doctor->shiftSchedules;
        @endphp

        <div class="card mb-3">
          <div class="card-header">
            <h5 class="card-title">Shift Schedule</h5>
          </div>
          <div class="card-body">
            <div class="d-flex flex-wrap gap-1 mb-3">
              @foreach (['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] as $day)
                @php
                  $start = $shift?->{$day . '_start'};
                  $end = $shift?->{$day . '_end'};
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
          </div>
        </div>

        <div class="row gx-3">
          <div class="col-lg-6">
            <div class="card mb-3">
              <div class="card-header">
                <h5 class="card-title">Professional Details</h5>
              </div>
              <div class="card-body">
                <p class="mb-2"><strong>Cadre:</strong> {{ $doctor->cadre ? ucwords(str_replace('_', ' ', $doctor->cadre)) : 'N/A' }}</p>
                {{-- <p class="mb-2"><strong>Qualification:</strong> {{ $doctor->qualification ?? 'N/A' }}</p>
                <p class="mb-2"><strong>Specialization:</strong> {{ $doctor->specialization ?? 'N/A' }}</p>
                <p class="mb-2"><strong>Years of Experience:</strong> {{ $doctor->years_of_experience ?? 'N/A' }}</p> --}}
                <p class="mb-0"><strong>Department / Unit:</strong> {{ $doctor->doctorDepartment?->name ?? 'N/A' }} / {{ $doctor->doctorUnit?->name ?? 'N/A' }}</p>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="card mb-3">
              <div class="card-header">
                <h5 class="card-title">Personal Details</h5>
              </div>
              <div class="card-body">
                <p class="mb-2"><strong>Email:</strong> {{ $doctor->email ?? 'N/A' }}</p>
                <p class="mb-2"><strong>Phone:</strong> {{ $doctor->phone_number ?? 'N/A' }}</p>
                <p class="mb-2"><strong>Gender:</strong> {{ ucfirst($doctor->gender ?? 'n/a') }}</p>
                {{-- <p class="mb-2"><strong>Marital Status:</strong> {{ ucfirst($doctor->marital_status ?? 'n/a') }}</p>
                <p class="mb-0"><strong>Address:</strong> {{ $doctor->address ?? 'N/A' }}</p> --}}
              </div>
            </div>
          </div>
        </div>

      </div>

      <div class="app-footer bg-white">
        <span>© 2024</span>
      </div>

    </div>
  </div>
</div>

@include('parklane.doctor.script')
