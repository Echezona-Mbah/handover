@include('head')

@include('topbar')

@include('navbar')

  <!-- Blog Start -->
  <div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-8">
            <!-- Blog Detail Start -->
         <div class="mb-5">
            <img class="img-fluid w-100 rounded mb-5" src="img/cxcxc.jpg" alt="">
            <h1 class="mb-4">Improving Medical Shift Fandova to Enhance Patient Safety</h1>
            <p>
                Effective communication during medical Fandovas is vital in reducing errors and ensuring continuity of care. 
                Across Nigerian hospitals, adopting structured Fandova templates and protocols helps doctors and nurses 
                accurately transfer patient information at shift changes.
            </p>
            <p>
                Whether transitioning between day and night shifts or across departments, healthcare workers must document 
                critical alerts, pending treatments, patient vitals, and changes in medication plans. Introducing checklists, 
                digital records, and accountability structures can significantly improve patient outcomes.
            </p>
            <p>
                At Flovide, our platform enables seamless communication among medical professionals during shift transitions. 
                The goal is to reduce miscommunication, minimize delays in treatment, and provide every patient with consistent, 
                informed care. Technology now allows hospitals to log Fandovas in real-time, with timestamps, status updates, 
                and detailed logs.
            </p>
            <div class="d-flex justify-content-between bg-light rounded p-4 mt-4 mb-4">
                <div class="d-flex align-items-center">
                    <img class="rounded-circle me-2" src="img/user.jpg" width="40" height="40" alt="">
                    <span>Dr. Ifeoma Okafor</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="ms-3"><i class="far fa-eye text-primary me-1"></i>8,764</span>
                    <span class="ms-3"><i class="far fa-comment text-primary me-1"></i>47</span>
                </div>
            </div>
        </div>

            <!-- Blog Detail End -->

            <!-- Comment List Start -->
            <div class="mb-5">
                <h4 class="d-inline-block text-primary text-uppercase border-bottom border-5 mb-4">3 Comments</h4>

                <div class="d-flex mb-4">
                    <img src="img/user.jpg" class="img-fluid rounded-circle" style="width: 45px; height: 45px;">
                    <div class="ps-3">
                        <h6><a href="#">Dr. Chukwuemeka N.</a> <small><i>18 Jul 2025</i></small></h6>
                        <p>This is a much-needed solution. At my hospital, Fandova errors have caused serious delays before. A structured system will truly help improve patient care.</p>
                        <button class="btn btn-sm btn-light">Reply</button>
                    </div>
                </div>

                <div class="d-flex mb-4">
                    <img src="img/user.jpg" class="img-fluid rounded-circle" style="width: 45px; height: 45px;">
                    <div class="ps-3">
                        <h6><a href="#">Nurse Janet U.</a> <small><i>19 Jul 2025</i></small></h6>
                        <p>I agree with this approach. Nurses need clarity when picking up shifts. Having digital access to patient updates makes our work much smoother.</p>
                        <button class="btn btn-sm btn-light">Reply</button>
                    </div>
                </div>

                <div class="d-flex ms-5 mb-4">
                    <img src="img/user.jpg" class="img-fluid rounded-circle" style="width: 45px; height: 45px;">
                    <div class="ps-3">
                        <h6><a href="#">Dr. Musa A.</a> <small><i>19 Jul 2025</i></small></h6>
                        <p>Thanks for sharing this! We've been planning to implement digital Fandovas at our teaching hospital. This article gave us some clarity.</p>
                        <button class="btn btn-sm btn-light">Reply</button>
                    </div>
                </div>
            </div>

            <!-- Comment List End -->

            <!-- Comment Form Start -->
            <div class="bg-light rounded p-5">
                <h4 class="d-inline-block text-primary text-uppercase border-bottom border-5 border-white mb-4">Post a Comment</h4>
                <form>
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <input type="text" class="form-control bg-white border-0" placeholder="Full Name" style="height: 55px;">
                        </div>
                        <div class="col-12 col-sm-6">
                            <input type="email" class="form-control bg-white border-0" placeholder="Email Address" style="height: 55px;">
                        </div>
                        <div class="col-12">
                            <input type="text" class="form-control bg-white border-0" placeholder="Hospital or Organization (optional)" style="height: 55px;">
                        </div>
                        <div class="col-12">
                            <textarea class="form-control bg-white border-0" rows="5" placeholder="Write your comment or feedback here..."></textarea>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary w-100 py-3" type="submit">Submit Comment</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Comment Form End -->
             </div>

        <!-- Sidebar Start -->
        <div class="col-lg-4">
            <!-- Search Form Start -->
            <div class="mb-5">
                <div class="input-group">
                    <input type="text" class="form-control p-3" placeholder="Keyword">
                    <button class="btn btn-primary px-3"><i class="fa fa-search"></i></button>
                </div>
            </div>
            <!-- Search Form End -->

            <!-- Category Start -->
            <div class="mb-5">
                <h4 class="d-inline-block text-primary text-uppercase border-bottom border-5 mb-4">Categories</h4>
                <div class="d-flex flex-column justify-content-start">
                    <a class="h5 bg-light rounded py-2 px-3 mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Web Design</a>
                    <a class="h5 bg-light rounded py-2 px-3 mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Web Development</a>
                    <a class="h5 bg-light rounded py-2 px-3 mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Web Development</a>
                    <a class="h5 bg-light rounded py-2 px-3 mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Keyword Research</a>
                    <a class="h5 bg-light rounded py-2 px-3 mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Email Marketing</a>
                </div>
            </div>
            <!-- Category End -->

            <!-- Recent Post Start -->
            <div class="mb-5">
                <h4 class="d-inline-block text-primary text-uppercase border-bottom border-5 mb-4">Recent Posts</h4>
                
                <div class="d-flex rounded overflow-hidden mb-3">
                    <img class="img-fluid" src="img/ftrtr.jpg" style="width: 100px; height: 100px; object-fit: cover;" alt="">
                    <a href="" class="h6 d-flex align-items-center bg-light px-3 mb-0">
                        10 Tips for Maintaining Good Mental Health
                    </a>
                </div>

                <div class="d-flex rounded overflow-hidden mb-3">
                    <img class="img-fluid" src="img/hush-naidoo-jade-photography-9ROuHJ25XVc-unsplash.jpg" style="width: 100px; height: 100px; object-fit: cover;" alt="">
                    <a href="" class="h6 d-flex align-items-center bg-light px-3 mb-0">
                        Understanding Hypertension: Causes & Prevention
                    </a>
                </div>

                <div class="d-flex rounded overflow-hidden mb-3">
                    <img class="img-fluid" src="img/awaw.jpg" style="width: 100px; height: 100px; object-fit: cover;" alt="">
                    <a href="" class="h6 d-flex align-items-center bg-light px-3 mb-0">
                        How to Eat Healthy on a Budget: Simple Steps
                    </a>
                </div>

                <div class="d-flex rounded overflow-hidden mb-3">
                    <img class="img-fluid" src="img/awvd.jpg" style="width: 100px; height: 100px; object-fit: cover;" alt="">
                    <a href="" class="h6 d-flex align-items-center bg-light px-3 mb-0">
                        The Importance of Regular Medical Checkups
                    </a>
                </div>

                <div class="d-flex rounded overflow-hidden mb-3">
                    <img class="img-fluid" src="img/portrait-happy-african-american-woman-surgeon-standing-operating-room-ready-work-patient-female-medical-worker-surgical-uniform-operation-theater.jpg" style="width: 100px; height: 100px; object-fit: cover;" alt="">
                    <a href="" class="h6 d-flex align-items-center bg-light px-3 mb-0">
                        Managing Diabetes: Lifestyle Changes That Work
                    </a>
                </div>
            </div>

            <!-- Recent Post End -->

            <!-- Image Start -->
            <div class="mb-5">
                <img src="img/download.jpg" alt="" class="img-fluid rounded">
            </div>
            <!-- Image End -->

            <!-- Tags Start -->
            <div class="mb-5">
                <h4 class="d-inline-block text-primary text-uppercase border-bottom border-5 mb-4">Tag Cloud</h4>
                <div class="d-flex flex-wrap m-n1">
                    <a href="" class="btn btn-primary m-1">Design</a>
                    <a href="" class="btn btn-primary m-1">Development</a>
                    <a href="" class="btn btn-primary m-1">Marketing</a>
                    <a href="" class="btn btn-primary m-1">SEO</a>
                    <a href="" class="btn btn-primary m-1">Writing</a>
                    <a href="" class="btn btn-primary m-1">Consulting</a>
                    <a href="" class="btn btn-primary m-1">Design</a>
                    <a href="" class="btn btn-primary m-1">Development</a>
                    <a href="" class="btn btn-primary m-1">Marketing</a>
                    <a href="" class="btn btn-primary m-1">SEO</a>
                    <a href="" class="btn btn-primary m-1">Writing</a>
                    <a href="" class="btn btn-primary m-1">Consulting</a>
                </div>
            </div>
            <!-- Tags End -->

            <!-- Plain Text Start -->
            <div>
                <h4 class="d-inline-block text-primary text-uppercase border-bottom border-5 mb-4">Plain Text</h4>
                <div class="bg-light rounded text-center" style="padding: 30px;">
                    <p>Vero sea et accusam justo dolor accusam lorem consetetur, dolores sit amet sit dolor clita kasd justo, diam accusam no sea ut tempor magna takimata, amet sit et diam dolor ipsum amet diam</p>
                    <a href="" class="btn btn-primary py-2 px-4">Read More</a>
                </div>
            </div>
            <!-- Plain Text End -->
        </div>
        <!-- Sidebar End -->
    </div>
</div>
<!-- Blog End -->



@include('footer')