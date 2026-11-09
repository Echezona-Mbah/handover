@include('head')

@include('topbar')

@include('navbar')



    <!-- Hero Start -->
    <div class="container-fluid bg-primary py-5 mb-5 hero-header">
        <div class="container py-5">
            <div class="row justify-content-start">
                <div class="col-lg-8 text-center text-lg-start">
                    <div class="hero-copy">
                        <span class="eyebrow"><i class="fa fa-heartbeat"></i> Better-connected care starts here</span>
                        <h1 class="text-white mb-4">Every handover deserves clarity.</h1>
                        <p class="hero-summary mb-4">Fandova helps healthcare teams share patient updates, coordinate care, and keep every shift moving with confidence.</p>
                        <div class="d-flex flex-wrap gap-3 pt-2">
                            <a href="{{ url('/contact') }}" class="btn btn-light text-primary">Get started with your team <i class="fa fa-arrow-right ms-2"></i></a>
                            <a href="{{ url('/about') }}" class="btn btn-outline-light">Discover Fandova</a>
                        </div>
                        <div class="hero-proof mt-4">
                            <span><i class="fa fa-check-circle me-2"></i>Structured patient handovers</span>
                            <span><i class="fa fa-check-circle me-2"></i>Connected care teams</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero End -->


    <!-- About Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row gx-5">
                <div class="col-lg-5 mb-5 mb-lg-0" style="min-height: 500px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute w-100 h-100 rounded" src="img/african-american-medical-team-working-healing-patient-hospital-ward-bed-man-woman-with-doctors-occupation-examining-young-adult-treatment-using-monitor-stethoscope.jpg" style="object-fit: cover;">
                    </div>
                </div>
               <div class="col-lg-7">
                    <div class="mb-4">
                        <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">About Us</h5>
                        <h1 class="display-4">A clearer way for care teams to work together</h1>
                    </div>
                    <p>
                       Fandova helps hospitals and clinics coordinate care transitions with a shared digital workspace for doctors, nurses, dentists, and other healthcare professionals.
                    </p>
                    <p>
                        Patient safety and continuity of care depend on timely, accurate communication. Structured handover notes, patient updates, and notifications help the next team understand what has happened and what needs attention.
                    
                        Our goal is to make everyday coordination clearer so healthcare professionals can focus on delivering quality care.
                    </p>
                    <div class="row g-3 pt-3">
                        <div class="col-sm-3 col-6">
                            <div class="bg-light text-center rounded-circle py-4">
                                <i class="fa fa-3x fa-user-md text-primary mb-3"></i>
                                <h6 class="mb-0">Qualified<small class="d-block text-primary">Doctors</small></h6>
                            </div>
                        </div>
                        <div class="col-sm-3 col-6">
                            <div class="bg-light text-center rounded-circle py-4">
                                <i class="fa fa-3x fa-procedures text-primary mb-3"></i>
                                <h6 class="mb-0">24/7<small class="d-block text-primary">Support</small></h6>
                            </div>
                        </div>
                        <div class="col-sm-3 col-6">
                            <div class="bg-light text-center rounded-circle py-4">
                                <i class="fa fa-3x fa-microscope text-primary mb-3"></i>
                                <h6 class="mb-0">Secure<small class="d-block text-primary">Data Access</small></h6>
                            </div>
                        </div>
                        <div class="col-sm-3 col-6">
                            <div class="bg-light text-center rounded-circle py-4">
                                <i class="fa fa-3x fa-ambulance text-primary mb-3"></i>
                                <h6 class="mb-0">Fast<small class="d-block text-primary">Communication</small></h6>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- About End -->
    

<!-- Services Start -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width: 600px;">
            <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Our Services</h5>
            <h1 class="display-4">Tools that keep care coordinated</h1>
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
                    <a class="btn btn-lg btn-primary rounded-pill" href="{{ url('/service') }}" aria-label="Learn about patient diagnosis"><i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="service-item bg-light rounded d-flex flex-column align-items-center justify-content-center text-center">
                    <div class="service-icon mb-4">
                        <i class="fa fa-2x fa-procedures text-white"></i>
                    </div>
                    <h4 class="mb-3">Treatment Coordination</h4>
                    <p class="m-0">Easily create, update, and monitor treatment plans for each patient, ensuring consistency of care between doctors and nursing teams.</p>
                    <a class="btn btn-lg btn-primary rounded-pill" href="{{ url('/service') }}" aria-label="Learn about treatment coordination"><i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="service-item bg-light rounded d-flex flex-column align-items-center justify-content-center text-center">
                    <div class="service-icon mb-4">
                        <i class="fa fa-2x fa-hospital text-white"></i>
                    </div>
                    <h4 class="mb-3">Ward & Bed Management</h4>
                    <p class="m-0">Monitor real-time ward assignments and bed occupancy, reducing confusion and improving patient allocation efficiency.</p>
                    <a class="btn btn-lg btn-primary rounded-pill" href="{{ url('/service') }}" aria-label="Learn about ward management"><i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="service-item bg-light rounded d-flex flex-column align-items-center justify-content-center text-center">
                    <div class="service-icon mb-4">
                        <i class="fa fa-2x fa-bell text-white"></i>
                    </div>
                    <h4 class="mb-3">Critical Alerts</h4>
                    <p class="m-0">Set up automated notifications for emergencies, pending procedures, and urgent cases to ensure rapid medical response.</p>
                    <a class="btn btn-lg btn-primary rounded-pill" href="{{ url('/service') }}" aria-label="Learn about critical alerts"><i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="service-item bg-light rounded d-flex flex-column align-items-center justify-content-center text-center">
                    <div class="service-icon mb-4">
                        <i class="fa fa-2x fa-pills text-white"></i>
                    </div>
                    <h4 class="mb-3">Medication Management</h4>
                    <p class="m-0">Log prescriptions, track medication schedules, and minimize errors through accurate record-keeping and real-time updates.</p>
                    <a class="btn btn-lg btn-primary rounded-pill" href="{{ url('/service') }}" aria-label="Learn about medication management"><i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="service-item bg-light rounded d-flex flex-column align-items-center justify-content-center text-center">
                    <div class="service-icon mb-4">
                        <i class="fa fa-2x fa-microscope text-white"></i>
                    </div>
                    <h4 class="mb-3">Lab & Diagnostic Reports</h4>
                    <p class="m-0">Keep important diagnostic updates available to the right care team to support informed decisions across shifts.</p>
                    <a class="btn btn-lg btn-primary rounded-pill" href="{{ url('/service') }}" aria-label="Learn about diagnostic reports"><i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Services End -->


    <!-- Services End -->


    <!-- Appointment Start -->
    {{-- <div class="container-fluid bg-primary my-5 py-5">
        <div class="container py-5">
            <div class="row gx-5">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="mb-4">
                        <h5 class="d-inline-block text-white text-uppercase border-bottom border-5">Appointment</h5>
                        <h1 class="display-4">Make An Appointment For Your Family</h1>
                    </div>
                    <p class="text-white mb-5">Eirmod sed tempor lorem ut dolores. Aliquyam sit sadipscing kasd ipsum. Dolor ea et dolore et at sea ea at dolor, justo ipsum duo rebum sea invidunt voluptua. Eos vero eos vero ea et dolore eirmod et. Dolores diam duo invidunt lorem. Elitr ut dolores magna sit. Sea dolore sanctus sed et. Takimata takimata sanctus sed.</p>
                    <a class="btn btn-dark rounded-pill py-3 px-5 me-3" href="{{ url('/appointment') }}">Plan a visit</a>
                    <a class="btn btn-outline-dark rounded-pill py-3 px-5" href="{{ url('/about') }}">Learn more</a>
                </div>
                <div class="col-lg-6">
                    <div class="bg-white text-center rounded p-5">
                        <h1 class="mb-4">Book An Appointment</h1>
                        <form>
                            <div class="row g-3">
                                <div class="col-12 col-sm-6">
                                    <select class="form-select bg-light border-0" style="height: 55px;">
                                        <option selected>Choose Department</option>
                                        <option value="1">Department 1</option>
                                        <option value="2">Department 2</option>
                                        <option value="3">Department 3</option>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <select class="form-select bg-light border-0" style="height: 55px;">
                                        <option selected>Select Doctor</option>
                                        <option value="1">Doctor 1</option>
                                        <option value="2">Doctor 2</option>
                                        <option value="3">Doctor 3</option>
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
                                    <button class="btn btn-primary w-100 py-3" type="submit">Make An Appointment</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Appointment End -->


    <!-- Pricing Plan Start -->
    {{-- <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5" style="max-width: 500px;">
                <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Medical Packages</h5>
                <h1 class="display-4">Awesome Medical Programs</h1>
            </div>
            <div class="owl-carousel price-carousel position-relative" style="padding: 0 45px 45px 45px;">
                <div class="bg-light rounded text-center">
                    <div class="position-relative">
                        <img class="img-fluid rounded-top" src="img/price-1.jpg" alt="">
                        <div class="position-absolute w-100 h-100 top-50 start-50 translate-middle rounded-top d-flex flex-column align-items-center justify-content-center" style="background: rgba(29, 42, 77, .8);">
                            <h3 class="text-white">Pregnancy Care</h3>
                            <h1 class="display-4 text-white mb-0">
                                <small class="align-top fw-normal" style="font-size: 22px; line-height: 45px;">$</small>49<small class="align-bottom fw-normal" style="font-size: 16px; line-height: 40px;">/ Year</small>
                            </h1>
                        </div>
                    </div>
                    <div class="text-center py-5">
                        <p>Emergency Medical Treatment</p>
                        <p>Highly Experienced Doctors</p>
                        <p>Highest Success Rate</p>
                        <p>Telephone Service</p>
                        <a href="" class="btn btn-primary rounded-pill py-3 px-5 my-2">Apply Now</a>
                    </div>
                </div>
                <div class="bg-light rounded text-center">
                    <div class="position-relative">
                        <img class="img-fluid rounded-top" src="img/price-2.jpg" alt="">
                        <div class="position-absolute w-100 h-100 top-50 start-50 translate-middle rounded-top d-flex flex-column align-items-center justify-content-center" style="background: rgba(29, 42, 77, .8);">
                            <h3 class="text-white">Health Checkup</h3>
                            <h1 class="display-4 text-white mb-0">
                                <small class="align-top fw-normal" style="font-size: 22px; line-height: 45px;">$</small>99<small class="align-bottom fw-normal" style="font-size: 16px; line-height: 40px;">/ Year</small>
                            </h1>
                        </div>
                    </div>
                    <div class="text-center py-5">
                        <p>Emergency Medical Treatment</p>
                        <p>Highly Experienced Doctors</p>
                        <p>Highest Success Rate</p>
                        <p>Telephone Service</p>
                        <a href="" class="btn btn-primary rounded-pill py-3 px-5 my-2">Apply Now</a>
                    </div>
                </div>
                <div class="bg-light rounded text-center">
                    <div class="position-relative">
                        <img class="img-fluid rounded-top" src="img/price-3.jpg" alt="">
                        <div class="position-absolute w-100 h-100 top-50 start-50 translate-middle rounded-top d-flex flex-column align-items-center justify-content-center" style="background: rgba(29, 42, 77, .8);">
                            <h3 class="text-white">Dental Care</h3>
                            <h1 class="display-4 text-white mb-0">
                                <small class="align-top fw-normal" style="font-size: 22px; line-height: 45px;">$</small>149<small class="align-bottom fw-normal" style="font-size: 16px; line-height: 40px;">/ Year</small>
                            </h1>
                        </div>
                    </div>
                    <div class="text-center py-5">
                        <p>Emergency Medical Treatment</p>
                        <p>Highly Experienced Doctors</p>
                        <p>Highest Success Rate</p>
                        <p>Telephone Service</p>
                        <a href="" class="btn btn-primary rounded-pill py-3 px-5 my-2">Apply Now</a>
                    </div>
                </div>
                <div class="bg-light rounded text-center">
                    <div class="position-relative">
                        <img class="img-fluid rounded-top" src="img/price-4.jpg" alt="">
                        <div class="position-absolute w-100 h-100 top-50 start-50 translate-middle rounded-top d-flex flex-column align-items-center justify-content-center" style="background: rgba(29, 42, 77, .8);">
                            <h3 class="text-white">Operation & Surgery</h3>
                            <h1 class="display-4 text-white mb-0">
                                <small class="align-top fw-normal" style="font-size: 22px; line-height: 45px;">$</small>199<small class="align-bottom fw-normal" style="font-size: 16px; line-height: 40px;">/ Year</small>
                            </h1>
                        </div>
                    </div>
                    <div class="text-center py-5">
                        <p>Emergency Medical Treatment</p>
                        <p>Highly Experienced Doctors</p>
                        <p>Highest Success Rate</p>
                        <p>Telephone Service</p>
                        <a href="" class="btn btn-primary rounded-pill py-3 px-5 my-2">Apply Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Pricing Plan End -->


    <!-- Team Start -->
<!-- Doctors Section Start -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width: 600px;">
            <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Our Medical Team</h5>
            <h1 class="display-4">People working together for better care</h1>
            <p class="text-muted mt-3">
                Meet our team of skilled doctors and specialists who work hand in hand to deliver 
                patient care, clear communication, and thoughtful treatment every day.
            </p>
        </div>

        <div class="owl-carousel team-carousel position-relative">
            <!-- Doctor 1 -->
            <div class="team-item">
                <div class="row g-0 bg-light rounded overflow-hidden">
                    <div class="col-12 col-sm-5 h-100">
                        <img class="img-fluid h-100" src="{{asset('img/images (4).jpg')}}" style="object-fit: cover;">
                    </div>
                    <div class="col-12 col-sm-7 h-100 d-flex flex-column">
                        <div class="mt-auto p-4">
                            <h3>Dr. Chinedu Okafor</h3>
                            <h6 class="fw-normal fst-italic text-primary mb-4">Consultant Cardiologist</h6>
                            <p class="m-0">
                                With over 10 years of experience in cardiovascular medicine, Dr. Okafor 
                                specializes in diagnosing and managing complex heart conditions. He is passionate 
                                about preventive cardiology and promoting healthy lifestyles among patients.
                            </p>
                        </div>
                        <div class="d-flex mt-auto border-top p-4">
                            <a class="btn btn-lg btn-primary btn-lg-square rounded-circle me-3" href="#"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-lg btn-primary btn-lg-square rounded-circle me-3" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-lg btn-primary btn-lg-square rounded-circle" href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Doctor 2 -->
            <div class="team-item">
                <div class="row g-0 bg-light rounded overflow-hidden">
                    <div class="col-12 col-sm-5 h-100">
                        <img class="img-fluid h-100" src="{{asset('img/78f00bdb0503d5f6e2.jpg')}}" style="object-fit: cover;">
                    </div>
                    <div class="col-12 col-sm-7 h-100 d-flex flex-column">
                        <div class="mt-auto p-4">
                            <h3>Dr. Ngozi Eze</h3>
                            <h6 class="fw-normal fst-italic text-primary mb-4">Pediatric Consultant</h6>
                            <p class="m-0">
                                Dr. Eze brings warmth and expertise to child healthcare. With a focus on preventive 
                                medicine and early diagnosis, she is dedicated to improving the well-being of children 
                                through compassionate, family-centered care.
                            </p>
                        </div>
                        <div class="d-flex mt-auto border-top p-4">
                            <a class="btn btn-lg btn-primary btn-lg-square rounded-circle me-3" href="#"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-lg btn-primary btn-lg-square rounded-circle me-3" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-lg btn-primary btn-lg-square rounded-circle" href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Doctor 3 -->
            <div class="team-item">
                <div class="row g-0 bg-light rounded overflow-hidden">
                    <div class="col-12 col-sm-5 h-100">
                        <img class="img-fluid h-100" src="{{asset('img/istockphoto-1287696383-612x612.jpg')}}" style="object-fit: cover;">
                    </div>
                    <div class="col-12 col-sm-7 h-100 d-flex flex-column">
                        <div class="mt-auto p-4">
                            <h3>Dr. Tunde Alabi</h3>
                            <h6 class="fw-normal fst-italic text-primary mb-4">Neurology Specialist</h6>
                            <p class="m-0">
                                A leading neurologist with extensive experience in brain and nervous system disorders, 
                                Dr. Alabi combines advanced clinical knowledge with empathy to provide exceptional care 
                                and ensure accurate communication during patient handovers.
                            </p>
                        </div>
                        <div class="d-flex mt-auto border-top p-4">
                            <a class="btn btn-lg btn-primary btn-lg-square rounded-circle me-3" href="#"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-lg btn-primary btn-lg-square rounded-circle me-3" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-lg btn-primary btn-lg-square rounded-circle" href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Doctor 4 -->
            <div class="team-item">
                <div class="row g-0 bg-light rounded overflow-hidden">
                    <div class="col-12 col-sm-5 h-100">
                        <img class="img-fluid h-100" src="{{asset('img/doctor-woman.jpg')}}" style="object-fit: cover;">
                    </div>
                    <div class="col-12 col-sm-7 h-100 d-flex flex-column">
                        <div class="mt-auto p-4">
                            <h3>Dr. Amaka Nwosu</h3>
                            <h6 class="fw-normal fst-italic text-primary mb-4">Emergency Medicine Consultant</h6>
                            <p class="m-0">
                                Known for her quick thinking and calm presence, Dr. Nwosu leads emergency response teams 
                                with precision and care. Her goal is to improve coordination between doctors and nurses 
                                during critical patient handovers.
                            </p>
                        </div>
                        <div class="d-flex mt-auto border-top p-4">
                            <a class="btn btn-lg btn-primary btn-lg-square rounded-circle me-3" href="#"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-lg btn-primary btn-lg-square rounded-circle me-3" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-lg btn-primary btn-lg-square rounded-circle" href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Doctors Section End -->

    <!-- Team End -->


    <!-- Search Start -->
    <div class="container-fluid bg-primary my-5 py-5">
        <div class="container py-5">
            <div class="text-center mx-auto mb-5" style="max-width: 500px;">
                <h5 class="d-inline-block text-white text-uppercase border-bottom border-5">Find A Doctor</h5>
                <h1 class="display-4 mb-4">The right care starts with the right team</h1>
                <h5 class="text-white fw-normal">Explore our services and connect with a healthcare team that can support your needs.</h5>
            </div>
            <div class="mx-auto" style="width: 100%; max-width: 600px;">
                <div class="input-group">
                    <a href="{{ url('/service') }}" class="btn btn-light px-4 py-3">Explore care services</a>
                    <a href="{{ url('/appointment') }}" class="btn btn-dark px-4 py-3">Plan a visit</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Search End -->


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
                                The platform is straightforward for our team to use and helps us keep important care information in one place.
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


    <!-- Blog Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5" style="max-width: 500px;">
                <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Blog Post</h5>
                <h1 class="display-4">Ideas for safer, more connected care</h1>
            </div>
            <div class="row g-5">
                <div class="col-xl-4 col-lg-6">
                    <div class="bg-light rounded overflow-hidden">
                        <img class="img-fluid w-100" src="img/medium.jpg" alt="">
                        <div class="p-4">
                            <a class="h3 d-block mb-3" href="{{ url('/blog-detail') }}">Making shift handovers clearer with digital tools</a>
                            <p class="m-0">Explore practical ways care teams can improve communication between outgoing and incoming staff.</p>
                        </div>
                        <div class="d-flex justify-content-between border-top p-4">
                            <div class="d-flex align-items-center">
                                <img class="rounded-circle me-2" src="img/user.jpg" width="25" height="25" alt="">
                                <small>Dr. Aisha Bello</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <small class="ms-3"><i class="far fa-eye text-primary me-1"></i>3,210</small>
                                <small class="ms-3"><i class="far fa-comment text-primary me-1"></i>45</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
                    <div class="bg-light rounded overflow-hidden">
                        <img class="img-fluid w-100" src="img/african-american-man-woman-talking-girl-hospital-ward-about-healing-treatment-diagnosis-doctors-examining-sick-young-patient-with-cervical-neck-collar-sitting-bed.jpg" alt="">
                        <div class="p-4">
                            <a class="h3 d-block mb-3" href="{{ url('/blog-detail') }}">Five details to include in every handover</a>
                            <p class="m-0">A simple checklist can help keep important patient information from getting lost between shifts.</p>
                        </div>
                        <div class="d-flex justify-content-between border-top p-4">
                            <div class="d-flex align-items-center">
                                <img class="rounded-circle me-2" src="img/user.jpg" width="25" height="25" alt="">
                                <small>Nurse Chinedu Okafor</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <small class="ms-3"><i class="far fa-eye text-primary me-1"></i>2,578</small>
                                <small class="ms-3"><i class="far fa-comment text-primary me-1"></i>32</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
                    <div class="bg-light rounded overflow-hidden">
                        <img class="img-fluid w-100" src="img/smiling.jpg" alt="">
                        <div class="p-4">
                            <a class="h3 d-block mb-3" href="{{ url('/blog-detail') }}">How connected teams support continuity of care</a>
                            <p class="m-0">See how shared updates help clinicians stay informed, organised, and ready to act.</p>
                        </div>
                        <div class="d-flex justify-content-between border-top p-4">
                            <div class="d-flex align-items-center">
                                <img class="rounded-circle me-2" src="img/user.jpg" width="25" height="25" alt="">
                                <small>Dr. Emeka Umeh</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <small class="ms-3"><i class="far fa-eye text-primary me-1"></i>4,110</small>
                                <small class="ms-3"><i class="far fa-comment text-primary me-1"></i>68</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    <div class="container text-center mt-n4 mb-5">
        <a href="{{ url('/blog') }}" class="btn btn-primary px-5 py-3">Explore health insights <i class="fa fa-arrow-right ms-2"></i></a>
    </div>
    <!-- Blog End -->
    

    @include('footer')