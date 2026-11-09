@include('head')

@include('topbar')

@include('navbar')

<section class="public-page-hero">
    <div class="container position-relative">
        <span class="eyebrow">Health insights</span>
        <h1>Ideas for safer, more connected care.</h1>
        <p>Explore practical perspectives on patient communication, continuity, and the everyday work of care teams.</p>
    </div>
</section>

  <!-- Blog Start -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width: 500px;">
            <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Health Blog</h5>
            <h1 class="display-4">Perspectives for care teams</h1>
        </div>
        <div class="row g-5">

            <div class="col-xl-4 col-lg-6">
                <div class="blog-card">
                    <img src="{{ asset('img/cxcxc.jpg') }}" alt="Clinician discussing care with a patient">
                    <div class="blog-card-body">
                        <span class="section-kicker">Patient wellbeing</span>
                        <a class="h3 d-block mb-3 text-decoration-none" href="{{ url('/blog-detail') }}">Managing hypertension in Nigerian adults</a>
                        <p class="m-0">Explore lifestyle tips, diet choices, and early detection practices to control high blood pressure across communities.</p>
                    </div>
                    <div class="d-flex justify-content-between border-top p-4">
                        <div class="d-flex align-items-center">
                            <img class="rounded-circle me-2" src="img/user.jpg" width="25" height="25" alt="">
                            <small>Dr. Tunde Alabi</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <small class="ms-3"><i class="far fa-eye text-primary me-1"></i>5,432</small>
                            <small class="ms-3"><i class="far fa-comment text-primary me-1"></i>21</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6">
                <div class="blog-card">
                    <img src="{{ asset('img/dede.jpg') }}" alt="Nurse reviewing a patient care plan">
                    <div class="blog-card-body">
                        <span class="section-kicker">Clinical teamwork</span>
                        <a class="h3 d-block mb-3 text-decoration-none" href="{{ url('/blog-detail') }}">Effective handover practices for nurses</a>
                        <p class="m-0">Clear and organised shift handovers support patient safety. Explore a practical structure for sharing essential updates.</p>
                    </div>
                    <div class="d-flex justify-content-between border-top p-4">
                        <div class="d-flex align-items-center">
                            <img class="rounded-circle me-2" src="img/user.jpg" width="25" height="25" alt="">
                            <small>Nurse Ngozi Eze</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <small class="ms-3"><i class="far fa-eye text-primary me-1"></i>3,289</small>
                            <small class="ms-3"><i class="far fa-comment text-primary me-1"></i>12</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6">
                <div class="blog-card">
                    <img src="{{ asset('img/ftrtr.jpg') }}" alt="Family supporting a loved one during recovery">
                    <div class="blog-card-body">
                        <span class="section-kicker">Recovery</span>
                        <a class="h3 d-block mb-3 text-decoration-none" href="{{ url('/blog-detail') }}">The role of family in patient recovery</a>
                        <p class="m-0">Strong emotional support from loved ones plays a critical role in mental and physical recovery during treatment.</p>
                    </div>
                    <div class="d-flex justify-content-between border-top p-4">
                        <div class="d-flex align-items-center">
                            <img class="rounded-circle me-2" src="img/user.jpg" width="25" height="25" alt="">
                            <small>Dr. Ibrahim Musa</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <small class="ms-3"><i class="far fa-eye text-primary me-1"></i>4,911</small>
                            <small class="ms-3"><i class="far fa-comment text-primary me-1"></i>34</small>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-12 text-center mt-5">
            <a class="btn btn-primary py-3 px-5" href="{{ url('/blog-detail') }}">Read an insight <i class="fa fa-arrow-right ms-2"></i></a>
        </div>
    </div>
</div>

<!-- Blog End -->



@include('footer')