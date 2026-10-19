@include('head')

@include('topbar')

@include('navbar')

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
                                This Fandova system has transformed the way we transfer patient care during shifts. It’s clear, efficient, and keeps everyone informed in real time.
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
                                Communication among nurses and doctors is now much smoother. We no longer miss key patient details during Fandova. Highly recommended!
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