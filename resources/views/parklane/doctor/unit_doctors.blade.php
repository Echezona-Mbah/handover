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
            <a href="{{ route('doctor.dashboard') }}">Home</a>
          </li>
          <li class="breadcrumb-item text-primary" aria-current="page">
            My Unit Doctors
          </li>
        </ol>
      </div>

      <div class="app-body">
        <div class="row gx-3">
          <div class="col-sm-12">

            <div class="card">
              <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                  <h5 class="card-title mb-0">
                    <i class="ri-team-line me-2 text-primary"></i>
                    Doctors in {{ $unitLabel }}
                  </h5>
                  <small class="text-muted">
                    {{ $doctors->count() }} doctor{{ $doctors->count() !== 1 ? 's' : '' }} in your
                    {{ $doctor->doctor_sub_unit_id ? 'sub-unit' : 'unit' }}
                  </small>
                </div>
              </div>

              <div class="card-body">

                @if($doctors->isEmpty())
                  <div class="text-center py-5">
                    <i class="ri-user-unfollow-line text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3 mb-0">No other doctors found in your
                      {{ $doctor->doctor_sub_unit_id ? 'sub-unit' : 'unit' }}.
                    </p>
                  </div>
                @else
                  <div class="row gx-3 gy-3">
                    @foreach($doctors as $doc)
                      @php
                        $initials = collect(explode(' ', $doc->full_name ?? 'D O'))
                          ->filter()
                          ->map(fn($p) => strtoupper(substr($p, 0, 1)))
                          ->take(2)
                          ->implode('');

                        $cadre = $doc->cadre
                          ? ucwords(str_replace('_', ' ', $doc->cadre))
                          : 'Doctor';
                      @endphp

                      <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                        <div class="card border shadow-sm h-100 mb-0">
                          <div class="card-body text-center p-4">

                            {{-- Avatar --}}
                            @if($doc->profile_image)
                              <img src="{{ asset('storage/' . $doc->profile_image) }}"
                                class="rounded-circle mb-3 border"
                                style="width: 72px; height: 72px; object-fit: cover;"
                                alt="{{ $doc->full_name }}">
                            @else
                              <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3"
                                style="width: 72px; height: 72px; font-size: 24px; font-weight: 700;">
                                {{ $initials }}
                              </div>
                            @endif

                            <h6 class="fw-bold mb-1">Dr. {{ $doc->full_name }}</h6>
                            <p class="text-muted small mb-2">{{ $cadre }}</p>

                            {{-- Sub-unit badge --}}
                            @if($doc->doctor_sub_unit_id)
                              <span class="badge bg-info-subtle text-info mb-2">
                                <i class="ri-git-branch-line me-1"></i>
                                {{ optional(\App\Models\DoctorSubUnit::find($doc->doctor_sub_unit_id))->name ?? 'Sub-Unit' }}
                              </span>
                            @else
                              <span class="badge bg-primary-subtle text-primary mb-2">
                                <i class="ri-building-line me-1"></i>
                                {{ optional(\App\Models\DoctorUnit::find($doc->doctor_unit_id))->name ?? 'Unit' }}
                              </span>
                            @endif

                            <div class="mt-2 pt-2 border-top text-start">
                              @if($doc->email)
                                <p class="small text-muted mb-1">
                                  <i class="ri-mail-line me-1"></i>
                                  {{ $doc->email }}
                                </p>
                              @endif
                              @if($doc->phone)
                                <p class="small text-muted mb-0">
                                  <i class="ri-phone-line me-1"></i>
                                  {{ $doc->phone }}
                                </p>
                              @endif
                            </div>

                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @endif

              </div>
            </div>

          </div>
        </div>
      </div>

      <div class="app-footer bg-white">
        {{-- <span>© 2024</span> --}}
      </div>

    </div>
  </div>
</div>

@include('parklane.script')