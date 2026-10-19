@include('parklane.head')

<div class="page-wrapper">

  @include('parklane.header')

  <div class="main-container">

    @include('parklane.sidebar')

    <div class="app-container">

      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="index.html">Home</a>
          </li>
          <li class="breadcrumb-item text-primary" aria-current="page">
            Doctor Profile
          </li>
        </ol>
      </div>

      <div class="app-body">

        <div class="row gx-3">
          <div class="col-xxl-8 col-lg-12">
            <div class="card mb-3 bg-1">
              <div class="card-body">
                <div class="row gx-3 align-items-center">
                  <div class="col-md-3 text-center">
                    <img
                      src="{{ $doctor->profile_image ? asset('storage/' . $doctor->profile_image) : asset('assets/images/user.png') }}"
                      class="img-fluid rounded-4 shadow-sm"
                      alt="Doctor"
                      style="max-height: 180px;"
                    >
                  </div>

                  <div class="col-md-9">
                    <div class="text-white">
                      <p class="mb-1">Doctor Profile</p>
                      <h2 class="mb-2">Dr. {{ $doctor->full_name }}</h2>
                      <h6 class="mb-3">{{ $doctor->qualification ?? 'No qualification added' }}</h6>

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
                        <span class="badge bg-light text-dark">
                          Status: {{ ucfirst($doctor->status ?? 'n/a') }}
                        </span>
                      </div>

                      <div class="row g-2">
                        <div class="col-md-6">
                          <div class="p-2 rounded bg-white bg-opacity-10">
                            <small class="d-block text-white-50">Email</small>
                            <strong>{{ $doctor->email ?? 'N/A' }}</strong>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="p-2 rounded bg-white bg-opacity-10">
                            <small class="d-block text-white-50">Phone Number</small>
                            <strong>{{ $doctor->phone_number ?? 'N/A' }}</strong>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="p-2 rounded bg-white bg-opacity-10">
                            <small class="d-block text-white-50">Hospital</small>
                            <strong>{{ $doctor->hospitals?->name ?? 'N/A' }}</strong>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="p-2 rounded bg-white bg-opacity-10">
                            <small class="d-block text-white-50">Years of Experience</small>
                            <strong>{{ $doctor->years_of_experience ?? 'N/A' }}</strong>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="p-2 rounded bg-white bg-opacity-10">
                            <small class="d-block text-white-50">Created At</small>
                            <strong>{{ $doctor->created_at ? $doctor->created_at->format('d M Y, h:i A') : 'N/A' }}</strong>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="p-2 rounded bg-white bg-opacity-10">
                            <small class="d-block text-white-50">Last Updated</small>
                            <strong>{{ $doctor->updated_at ? $doctor->updated_at->format('d M Y, h:i A') : 'N/A' }}</strong>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xxl-4 col-lg-12">
            <div class="row gx-3">
              <div class="col-md-4 col-lg-4 col-12">
                <div class="card mb-3">
                  <div class="card-body text-center">
                    <div class="icon-box xl bg-primary-subtle rounded-5 mb-2 no-shadow mx-auto">
                      <i class="ri-empathize-line fs-1 text-primary"></i>
                    </div>
                    <h1 class="text-primary">{{ $stats['patients'] ?? 0 }}</h1>
                    <h6>Patients</h6>
                  </div>
                </div>
              </div>

              <div class="col-md-4 col-lg-4 col-12">
                <div class="card mb-3">
                  <div class="card-body text-center">
                    <div class="icon-box xl bg-warning-subtle rounded-5 mb-2 no-shadow mx-auto">
                      <i class="ri-mail-send-line fs-1 text-warning"></i>
                    </div>
                    <h1 class="text-warning">{{ $stats['invites'] ?? 0 }}</h1>
                    <h6>Invites</h6>
                  </div>
                </div>
              </div>

              <div class="col-md-4 col-lg-4 col-12">
                <div class="card mb-3">
                  <div class="card-body text-center">
                    <div class="icon-box xl bg-danger-subtle rounded-5 mb-2 no-shadow mx-auto">
                      <i class="ri-hospital-line fs-1 text-danger"></i>
                    </div>
                    <h1 class="text-danger">{{ $stats['discharged'] ?? 0 }}</h1>
                    <h6>Discharged</h6>
                  </div>
                </div>
              </div>
            </div>

            <div class="card mb-3">
              <div class="card-header">
                <h5 class="card-title mb-0">Doctor Details</h5>
              </div>
              <div class="card-body">
                <div class="row g-3">
                  <div class="col-12">
                    <div class="border rounded p-3">
                      <small class="text-muted d-block">Full Name</small>
                      <strong>{{ $doctor->full_name ?? 'N/A' }}</strong>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="border rounded p-3">
                      <small class="text-muted d-block">Cadre</small>
                      <strong>{{ $doctor->cadre ? ucwords(str_replace('_', ' ', $doctor->cadre)) : 'N/A' }}</strong>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="border rounded p-3">
                      <small class="text-muted d-block">Department</small>
                      <strong>{{ $doctor->doctorDepartment?->name ?? 'N/A' }}</strong>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="border rounded p-3">
                      <small class="text-muted d-block">Unit</small>
                      <strong>{{ $doctor->doctorUnit?->name ?? 'N/A' }}</strong>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="border rounded p-3">
                      <small class="text-muted d-block">Sub Unit</small>
                      <strong>{{ $doctor->subUnit?->name ?? 'N/A' }}</strong>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="border rounded p-3">
                      <small class="text-muted d-block">Email Address</small>
                      <strong>{{ $doctor->email ?? 'N/A' }}</strong>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="border rounded p-3">
                      <small class="text-muted d-block">Phone Number</small>
                      <strong>{{ $doctor->phone_number ?? 'N/A' }}</strong>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="border rounded p-3">
                      <small class="text-muted d-block">Specialization</small>
                      <strong>{{ $doctor->specialization ?? 'N/A' }}</strong>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="border rounded p-3">
                      <small class="text-muted d-block">Hospital</small>
                      <strong>{{ $doctor->hospitals?->name ?? 'N/A' }}</strong>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="border rounded p-3">
                      <small class="text-muted d-block">Years of Experience</small>
                      <strong>{{ $doctor->years_of_experience ?? 'N/A' }}</strong>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="border rounded p-3">
                      <small class="text-muted d-block">Gender</small>
                      <strong>{{ ucfirst($doctor->gender ?? 'n/a') }}</strong>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="border rounded p-3">
                      <small class="text-muted d-block">Age</small>
                      <strong>{{ $doctor->ages ?? 'N/A' }}</strong>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="border rounded p-3">
                      <small class="text-muted d-block">Marital Status</small>
                      <strong>{{ ucfirst($doctor->marital_status ?? 'n/a') }}</strong>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="border rounded p-3">
                      <small class="text-muted d-block">Blood Group</small>
                      <strong>{{ $doctor->blood_group ?? 'N/A' }}</strong>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="border rounded p-3">
                      <small class="text-muted d-block">Address</small>
                      <strong>{{ $doctor->address ?? 'N/A' }}</strong>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="border rounded p-3">
                      <small class="text-muted d-block">Country / State / City</small>
                      <strong>
                        {{ $doctor->country ?? 'N/A' }}
                        /
                        {{ $doctor->state ?? 'N/A' }}
                        /
                        {{ $doctor->city ?? 'N/A' }}
                      </strong>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row gx-3">
          <div class="col-lg-12">
            <div class="card mb-3">
              <div class="card-header">
                <h5 class="card-title mb-0">Availability</h5>
              </div>
              <div class="card-body">
                <div class="row g-2">
                  <div class="col-md-3"><div class="border rounded p-2">Sun: {{ $doctor->sun_start && $doctor->sun_end ? $doctor->sun_start . ' - ' . $doctor->sun_end : 'N/A' }}</div></div>
                  <div class="col-md-3"><div class="border rounded p-2">Mon: {{ $doctor->mon_start && $doctor->mon_end ? $doctor->mon_start . ' - ' . $doctor->mon_end : 'N/A' }}</div></div>
                  <div class="col-md-3"><div class="border rounded p-2">Tue: {{ $doctor->tue_start && $doctor->tue_end ? $doctor->tue_start . ' - ' . $doctor->tue_end : 'N/A' }}</div></div>
                  <div class="col-md-3"><div class="border rounded p-2">Wed: {{ $doctor->wed_start && $doctor->wed_end ? $doctor->wed_start . ' - ' . $doctor->wed_end : 'N/A' }}</div></div>
                  <div class="col-md-3"><div class="border rounded p-2">Thu: {{ $doctor->thu_start && $doctor->thu_end ? $doctor->thu_start . ' - ' . $doctor->thu_end : 'N/A' }}</div></div>
                  <div class="col-md-3"><div class="border rounded p-2">Fri: {{ $doctor->fri_start && $doctor->fri_end ? $doctor->fri_start . ' - ' . $doctor->fri_end : 'N/A' }}</div></div>
                  <div class="col-md-3"><div class="border rounded p-2">Sat: {{ $doctor->sat_start && $doctor->sat_end ? $doctor->sat_start . ' - ' . $doctor->sat_end : 'N/A' }}</div></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row gx-3 mt-3">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Doctor Activity Log</h5>
              </div>
              <div class="card-body">
                @if($activities->count())
                  <div class="table-responsive">
                    <table class="table table-striped align-middle">
                      <thead>
                        <tr>
                          <th>Action</th>
                          <th>Route</th>
                          <th>Method</th>
                          <th>IP</th>
                          <th>User Agent</th>
                          <th>Date</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($activities as $log)
                          <tr>
                            <td>{{ $log->action }}</td>
                            <td>{{ $log->route }}</td>
                            <td>{{ $log->method }}</td>
                            <td>{{ $log->ip }}</td>
                            <td style="max-width:280px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                              {{ $log->user_agent }}
                            </td>
                            <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>

                  <div class="mt-3">
                    {{ $activities->links('pagination::bootstrap-5') }}
                  </div>
                @else
                  <p class="text-muted mb-0">No activity recorded yet.</p>
                @endif
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

@include('parklane.script')
