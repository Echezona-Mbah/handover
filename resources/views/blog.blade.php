@include('head')

@include('topbar')

@include('navbar')

  <!-- Blog Start -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width: 500px;">
            <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Health Blog</h5>
            <h1 class="display-4">Insights & Updates From Our Medical Team</h1>
        </div>
        <div class="row g-5">

            <div class="col-xl-4 col-lg-6">
                <div class="bg-light rounded overflow-hidden">
                    <img class="img-fluid w-100" src="img/cxcxc.jpg" alt="">
                    <div class="p-4">
                        <a class="h3 d-block mb-3" href="">Managing Hypertension in Nigerian Adults</a>
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
                <div class="bg-light rounded overflow-hidden">
                    <img class="img-fluid w-100" src="img/dede.jpg" alt="">
                    <div class="p-4">
                        <a class="h3 d-block mb-3" href="">Effective Fandova Practices for Nurses</a>
                        <p class="m-0">Clear and organized shift Fandovas improve patient safety. Learn the tools and structure nurses rely on.</p>
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
                <div class="bg-light rounded overflow-hidden">
                    <img class="img-fluid w-100" src="img/ftrtr.jpg" alt="">
                    <div class="p-4">
                        <a class="h3 d-block mb-3" href="">The Role of Family in Patient Recovery</a>
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
            <button class="btn btn-primary py-3 px-5">View More Articles</button>
        </div>
    </div>
</div>

<!-- Blog End -->



@include('footer')