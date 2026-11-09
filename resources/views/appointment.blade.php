@include('head')

@include('topbar')

@include('navbar')

<section class="public-page-hero">
    <div class="container position-relative">
        <span class="eyebrow">Appointments</span>
        <h1>Take the next step toward the care you need.</h1>
        <p>Reach out to your care provider to arrange a visit, ask a question, or find out what to bring.</p>
    </div>
</section>

    <!-- Appointment Start -->
  <div class="container-fluid py-5">
    <div class="container">
        <div class="row gx-5">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="mb-4">
                    <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Appointment</h5>
                    <h1 class="display-4">Let’s plan your visit</h1>
                </div>
                <p class="mb-5">
                    For appointment availability and scheduling, contact your hospital or care team directly. They can help you choose the right service and arrange a suitable time.
                </p>
                <a class="btn btn-primary rounded-pill py-3 px-5 me-3" href="{{ url('/contact') }}">Contact us</a>
                <a class="btn btn-outline-primary rounded-pill py-3 px-5" href="{{ url('/service') }}">Explore services</a>
            </div>
            <div class="col-lg-6">
                <div class="appointment-card">
                    <span class="section-kicker">Get started</span>
                    <h2 class="mb-3">Talk with your care provider</h2>
                    <p class="mb-4">Fandova helps care teams coordinate patient information. Appointment booking is arranged by the hospital or clinic providing your care.</p>
                    <div class="d-grid gap-3">
                        <a class="btn btn-primary py-3" href="{{ url('/contact') }}"><i class="fa fa-envelope me-2"></i>Contact the team</a>
                        <a class="btn btn-outline-primary py-3" href="{{ url('/service') }}"><i class="fa fa-list-alt me-2"></i>View care services</a>
                    </div>
                    <div class="border-top mt-4 pt-4 small text-muted">
                        <i class="fa fa-info-circle text-primary me-2"></i>For urgent or emergency symptoms, contact local emergency services or go to the nearest emergency department.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Appointment End -->



@include('footer')