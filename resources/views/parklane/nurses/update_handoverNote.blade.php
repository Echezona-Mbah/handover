@include('parklane.nurses.head')
  <style>
    #dropzone {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    border: 2px dashed #007bff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease-in-out;
}

#dropzone:hover {
    border-color: #0056b3;
    background-color: #e9ecef;
}

.dz-message {
    color: #495057;
}

.dz-button {
    font-size: 16px;
    padding: 12px 30px;
    background-color: #007bff;
    color: white;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.dz-button:hover {
    background-color: #0056b3;
}

h5 {
    font-size: 16px;
    font-weight: normal;
    margin-top: 20px;
}

@media (max-width: 576px) {
    #dropzone {
        padding: 20px;
    }

    .dz-button {
        font-size: 14px;
        padding: 10px 25px;
    }

    h5 {
        font-size: 14px;
    }
}

.form-select, .form-control {
    font-size: 1rem;
    padding: 0.75rem;
    border-radius: 0.375rem;
    border: 1px solid #ced4da;
    transition: all 0.3s ease-in-out;
}

.form-select:focus, .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
}

.form-label {
    font-weight: 600;
    font-size: 1rem;
    color: #495057;
}

.mb-3 {
    margin-bottom: 1rem;
}


  </style>
  
    <!-- Page wrapper starts -->
    <div class="page-wrapper">

      <!-- App header starts -->
      @include('parklane.nurses.header')
      <!-- App header ends -->

      <!-- Main container starts -->
      <div class="main-container">

        <!-- Sidebar wrapper starts -->
        @include('parklane.nurses.sidebar')

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
                                  <div class="col-sm-12">
                                    <div class="card">
                                      <div class="card-header">
                                        <h5 class="card-title">Update Handover Notes</h5>
                                      </div>
                                      <div id="toast-container"></div>

                                      <div class="card-body">
                    
                                        <form action="{{ route('nurse.handoverupdate', $handoverNote->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                        <input type="hidden" name="id" value="{{ $handoverNote->id }}">

                                        <!-- Row starts -->
                                        <div class="col-sm-12">

                                            <div class="mb-3">
                                                <label class="form-label" for="a1">General Summary<span class="text-danger">*</span></label>
                                                <textarea class="form-control" id="a9" name="summary" placeholder="Enter the patient's current condition and any important notes" rows="5">{{ old('summary', $handoverNote->summary) }}</textarea>
                                            </div>

                                          <div class="col-sm-12">

                                              <div class="mb-3">
                                                <label class="form-label" for="a1">Critical Alerts<span class="text-danger">*</span></label>
                                                <textarea class="form-control" id="a9" name="alerts" placeholder="Enter any critical alerts that the incoming doctor should be aware of" rows="5">{{ old('alerts', $handoverNote->alerts) }}</textarea>
                                              </div>

                                            <div class="col-sm-12">
                                                <div class="mb-3">
                                                  <label class="form-label" for="a9">Pending Tasks</label>
                                                  <textarea class="form-control" id="a9" name="pending_tasks" placeholder="Pending Tasks" rows="3">{{ old('pending_tasks', $handoverNote->pending_tasks) }}</textarea>

                                                </div>
                                              </div>

                                          

                                          <div class="col-sm-12">
                                            <div class="d-flex gap-2 justify-content-end">
                                              <a href="" type="button" class="btn btn-outline-secondary">
                                                Cancel
                                              </a>
                                              <button type="submit" class="btn btn-primary">Update Handover Notes</button>
                                              {{-- <a href="submit" type="button" class="btn btn-primary">
                                                Create Patient
                                              </a> --}}
                                              
                                            </div>
                                          </div>
                                        </div>
                                        <!-- Row ends -->
                                      </form>
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
            <span>©  2024</span>
          </div>
          <!-- App footer ends -->

        </div>
        <!-- App container ends -->

      </div>
      <!-- Main container ends -->

    </div>
    <!-- Page wrapper ends -->

    
    
@include('parklane.nurses.script')