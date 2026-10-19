    


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


     

          <!-- App body starts -->
          <div class="app-body">

            <!-- Row starts -->
           <div class="row gx-3">
                @foreach ($news as $item)
                <div class="col-sm-4 col-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="card-img">
                                <img src="{{ asset('storage/' . $item->image) }}" class="img-fluid rounded-3 mb-3"
                                    alt="{{ $item->title }}">
                            </div>
                            <h5>{{ $item->title }}</h5>
                            <p class="small text-muted">Posted on - {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</p>
                            <p class="mb-4">
                                {{ Str::limit($item->body, 100) }}
                            </p>
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('news.show', $item->id) }}" class="btn btn-info">Read More</a>
                                <div class="d-flex gap-3">
                                    <a href="#" class="d-flex align-items-center">
                                        <div class="icon-box sm bg-danger rounded-5 me-1">
                                            <i class="ri-heart-fill text-white"></i>
                                        </div>
                                        <span class="fw-bold">300</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Row ends -->

          </div>
          <!-- App body ends -->



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