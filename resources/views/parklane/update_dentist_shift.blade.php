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
            <div class="app-body">

                <!-- Row starts -->
                <div class="row gx-3">
                  <div class="col-xl-12">
                    <div class="card">
                      <div class="card-body">
    
                        <!-- Custom tabs starts -->
                        <div id="toast-container"></div>
                          <!-- Nav tabs starts -->
                          <ul class="nav nav-tabs" id="customTab2" role="tablist">

                            <li class="nav-item" role="presentation">
                              <a class="nav-link" id="tab-threeA" data-bs-toggle="tab" href="#threeA" role="tab"
                                aria-controls="oneA" aria-selected="false"><i class="ri-calendar-check-line"></i>
                                Update Shift</a>
                            </li>

                          </ul>
                          <br>
                          <!-- Nav tabs ends -->
    
                          <!-- Tab content starts -->
                         
                          <h3>Add Shift for Dr. {{ $shift->doctor?->full_name ?? 'N/A' }}</h3>

                          <form action="{{ route('updateDentistShift', $shift->id) }}" method="POST">
                            @csrf
                            @method('PUT') {{-- This tells Laravel we are using the PUT method --}}
                            <input type="hidden" name="dentist_id" value="{{ $shift->dentist_id}}">
                            <input type="hidden" name="hospital_id" value="{{ auth()->user()->hospital_id ?? $hospital->id ?? '' }}">
                        
                            <div class="row gx-3">
                                @php
                                    $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                                    $times = ['6AM', '7AM', '8AM', '9AM'];
                                    $endTimes = ['2PM', '3PM', '4PM', '5PM'];
                                @endphp
                        
                                @foreach ($days as $day)
                                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">{{ $day }}</label>
                                            <div class="input-group">
                                                <select class="form-select" name="{{ strtolower($day) }}_start">
                                                    <option value="">From</option>
                                                    @foreach ($times as $time)
                                                        <option value="{{ $time }}" {{ $shift[strtolower($day).'_start'] == $time ? 'selected' : '' }}>{{ $time }}</option>
                                                    @endforeach
                                                </select>
                                                <select class="form-select" name="{{ strtolower($day) }}_end">
                                                    <option value="">To</option>
                                                    @foreach ($endTimes as $time)
                                                        <option value="{{ $time }}" {{ $shift[strtolower($day).'_end'] == $time ? 'selected' : '' }}>{{ $time }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        
                            <button type="submit" class="btn btn-primary">Update Shift</button>
                        </form>
                        
                        
                        
                          <!-- Row ends -->
                          <!-- Tab content ends -->
    
                        <!-- Custom tabs ends -->
   
    
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Row ends -->
    
              </div>
            <!-- Row ends -->

            <!-- Row starts -->
          
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