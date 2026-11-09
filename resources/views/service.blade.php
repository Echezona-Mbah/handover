@include('head')

@include('topbar')

@include('navbar')
    
<section class="public-page-hero">
    <div class="container position-relative">
        <span class="eyebrow">Fandova services</span>
        <h1>Practical tools for connected care.</h1>
        <p>Bring patient updates, handovers, and care-team communication into a clearer daily workflow.</p>
    </div>
</section>

    <!-- Services Start -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width: 600px;">
            <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Our Services</h5>
            <h1 class="display-4">Support every step of the care journey</h1>
            <p class="text-muted mt-3">
                Helping doctors and nurses with practical tools for seamless shift transitions,
                clear communication, and improved patient outcomes.
            </p>
        </div>

        <div class="row g-5">
            <div class="col-lg-4 col-md-6">
                <div class="service-item bg-light rounded d-flex flex-column align-items-center justify-content-center text-center">
                    <div class="service-icon mb-4">
                        <i class="fa fa-2x fa-user-md text-white"></i>
                    </div>
                    <h4 class="mb-3">Patient Diagnosis</h4>
                    <p class="m-0">Record and share diagnostic updates effortlessly across shifts to ensure every healthcare provider is fully informed on each patient’s condition.</p>
                    <a class="btn btn-lg btn-primary rounded-pill" href="{{ url('/appointment') }}" aria-label="Learn about patient diagnosis"><i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="service-item bg-light rounded d-flex flex-column align-items-center justify-content-center text-center">
                    <div class="service-icon mb-4">
                        <i class="fa fa-2x fa-procedures text-white"></i>
                    </div>
                    <h4 class="mb-3">Treatment Coordination</h4>
                    <p class="m-0">Easily create, update, and monitor treatment plans for each patient, ensuring consistency of care between doctors and nursing teams.</p>
                    <a class="btn btn-lg btn-primary rounded-pill" href="{{ url('/appointment') }}" aria-label="Learn about treatment coordination"><i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="service-item bg-light rounded d-flex flex-column align-items-center justify-content-center text-center">
                    <div class="service-icon mb-4">
                        <i class="fa fa-2x fa-hospital text-white"></i>
                    </div>
                    <h4 class="mb-3">Ward & Bed Management</h4>
                    <p class="m-0">Monitor real-time ward assignments and bed occupancy, reducing confusion and improving patient allocation efficiency.</p>
                    <a class="btn btn-lg btn-primary rounded-pill" href="{{ url('/appointment') }}" aria-label="Learn about ward management"><i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="service-item bg-light rounded d-flex flex-column align-items-center justify-content-center text-center">
                    <div class="service-icon mb-4">
                        <i class="fa fa-2x fa-bell text-white"></i>
                    </div>
                    <h4 class="mb-3">Critical Alerts</h4>
                    <p class="m-0">Set up automated notifications for emergencies, pending procedures, and urgent cases to ensure rapid medical response.</p>
                    <a class="btn btn-lg btn-primary rounded-pill" href="{{ url('/appointment') }}" aria-label="Learn about critical alerts"><i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="service-item bg-light rounded d-flex flex-column align-items-center justify-content-center text-center">
                    <div class="service-icon mb-4">
                        <i class="fa fa-2x fa-pills text-white"></i>
                    </div>
                    <h4 class="mb-3">Medication Management</h4>
                    <p class="m-0">Log prescriptions, track medication schedules, and minimize errors through accurate record-keeping and real-time updates.</p>
                    <a class="btn btn-lg btn-primary rounded-pill" href="{{ url('/appointment') }}" aria-label="Learn about medication management"><i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="service-item bg-light rounded d-flex flex-column align-items-center justify-content-center text-center">
                    <div class="service-icon mb-4">
                        <i class="fa fa-2x fa-microscope text-white"></i>
                    </div>
                    <h4 class="mb-3">Lab & Diagnostic Reports</h4>
                    <p class="m-0">Keep important diagnostic updates available to the right care team to support informed decisions across shifts.</p>
                    <a class="btn btn-lg btn-primary rounded-pill" href="{{ url('/appointment') }}" aria-label="Learn about care coordination"><i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- Services End -->


    <!-- Appointment Start -->
   <div class="container-fluid bg-primary my-5 py-5">
        <div class="container py-5">
            <div class="row gx-5">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="mb-4">
                        <h5 class="d-inline-block text-white text-uppercase border-bottom border-5">Appointment</h5>
                        <h1 class="display-4">Book a Medical Appointment for You or a Loved One</h1>
                    </div>
                    <p class="text-white mb-5">
                        Explore how Fandova helps care teams keep patient information organised and transitions clear. For a visit, contact the hospital or clinic providing your care.
                    </p>
                    <a class="btn btn-dark rounded-pill py-3 px-5 me-3" href="{{ url('/appointment') }}">Plan a visit</a>
                    <a class="btn btn-outline-dark rounded-pill py-3 px-5" href="{{ url('/about') }}">Learn more</a>
                </div>
                <div class="col-lg-6">
                    <div class="bg-white text-center rounded p-5">
                        <h1 class="mb-4">Book an Appointment</h1>
                        <form>
                            <div class="row g-3">
                                <div class="col-12 col-sm-6">
                                    <select class="form-select bg-light border-0" style="height: 55px;">
                                        <option selected>Choose Department</option>
                                        <option value="1">General Medicine</option>
                                        <option value="2">Pediatrics</option>
                                        <option value="3">Cardiology</option>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <select class="form-select bg-light border-0" style="height: 55px;">
                                        <option selected>Select Doctor</option>
                                        <option value="1">Dr. Adebayo</option>
                                        <option value="2">Dr. Okonkwo</option>
                                        <option value="3">Dr. Fatima Musa</option>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <input type="text" class="form-control bg-light border-0" placeholder="Your Name" style="height: 55px;">
                                </div>
                                <div class="col-12 col-sm-6">
                                    <input type="email" class="form-control bg-light border-0" placeholder="Your Email" style="height: 55px;">
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="date" id="date" data-target-input="nearest">
                                        <input type="text"
                                            class="form-control bg-light border-0 datetimepicker-input"
                                            placeholder="Date" data-target="#date" data-toggle="datetimepicker" style="height: 55px;">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="time" id="time" data-target-input="nearest">
                                        <input type="text"
                                            class="form-control bg-light border-0 datetimepicker-input"
                                            placeholder="Time" data-target="#time" data-toggle="datetimepicker" style="height: 55px;">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary w-100 py-3" type="submit">Book Appointment</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointment End -->


    <!-- Testimonial Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5" style="max-width: 500px;">
                <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Testimonial</h5>
                <h1 class="display-4">What Our Users Say</h1>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="owl-carousel testimonial-carousel">
                        <!-- Testimonial 1 -->
                        <div class="testimonial-item text-center">
                            <div class="position-relative mb-5">
                                <img class="img-fluid rounded-circle mx-auto" src="img/user.jpg" alt="">
                                <div class="position-absolute top-100 start-50 translate-middle d-flex align-items-center justify-content-center bg-white rounded-circle" style="width: 60px; height: 60px;">
                                    <i class="fa fa-quote-left fa-2x text-primary"></i>
                                </div>
                            </div>
                            <p class="fs-4 fw-normal">
                                Structured handovers make it easier for our team to pass on important updates and stay aligned throughout a shift.
                            </p>
                            <hr class="w-25 mx-auto">
                            <h3>Dr. Aisha Bello</h3>
                            <h6 class="fw-normal text-primary mb-3">Consultant Pediatrician</h6>
                        </div>

                        <!-- Testimonial 2 -->
                        <div class="testimonial-item text-center">
                            <div class="position-relative mb-5">
                                <img class="img-fluid rounded-circle mx-auto" src="{{asset('img/kjh.png')}}" alt="">
                                <div class="position-absolute top-100 start-50 translate-middle d-flex align-items-center justify-content-center bg-white rounded-circle" style="width: 60px; height: 60px;">
                                    <i class="fa fa-quote-left fa-2x text-primary"></i>
                                </div>
                            </div>
                            <p class="fs-4 fw-normal">
                                As a hospital IT officer, I’ve implemented several platforms, but this one stands out. It's user-friendly, secure, and tailored for Nigerian healthcare.
                            </p>
                            <hr class="w-25 mx-auto">
                            <h3>Mr. Chinedu Okeke</h3>
                            <h6 class="fw-normal text-primary mb-3">Hospital IT Administrator</h6>
                        </div>

                        <!-- Testimonial 3 -->
                        <div class="testimonial-item text-center">
                            <div class="position-relative mb-5">
                                <img class="img-fluid rounded-circle mx-auto" src="{{asset('img/ChatGPT Image Jul 25, 2025, 02_22_03 PM.png')}}" alt="">
                                <div class="position-absolute top-100 start-50 translate-middle d-flex align-items-center justify-content-center bg-white rounded-circle" style="width: 60px; height: 60px;">
                                    <i class="fa fa-quote-left fa-2x text-primary"></i>
                                </div>
                            </div>
                            <p class="fs-4 fw-normal">
                                Communication between nurses and doctors is smoother, and the next shift has a clearer view of patient priorities.
                            </p>
                            <hr class="w-25 mx-auto">
                            <h3>Nurse Grace Umeh</h3>
                            <h6 class="fw-normal text-primary mb-3">Senior Ward Nurse</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Testimonial End -->

    @include('footer')