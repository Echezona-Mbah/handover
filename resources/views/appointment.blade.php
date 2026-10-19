@include('head')

@include('topbar')

@include('navbar')

    <!-- Appointment Start -->
  <div class="container-fluid py-5">
    <div class="container">
        <div class="row gx-5">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="mb-4">
                    <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Appointment</h5>
                    <h1 class="display-4">Schedule a Visit for Your Loved Ones</h1>
                </div>
                <p class="mb-5">
                    Need professional medical care for yourself or your family? Book an appointment with our trusted doctors today. 
                    Whether it's a routine checkup or a specialist consultation, we’re here to provide the best possible care with 
                    compassion and expertise.
                </p>
                <a class="btn btn-primary rounded-pill py-3 px-5 me-3" href="">Find a Doctor</a>
                <a class="btn btn-outline-primary rounded-pill py-3 px-5" href="">Learn More</a>
            </div>
            <div class="col-lg-6">
                <div class="bg-light text-center rounded p-5">
                    <h1 class="mb-4">Book an Appointment</h1>
                    <form>
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <select class="form-select bg-white border-0" style="height: 55px;">
                                    <option selected>Select Department</option>
                                    <option value="1">Pediatrics</option>
                                    <option value="2">Cardiology</option>
                                    <option value="3">General Medicine</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6">
                                <select class="form-select bg-white border-0" style="height: 55px;">
                                    <option selected>Select Doctor</option>
                                    <option value="1">Dr. Grace Okafor</option>
                                    <option value="2">Dr. John Musa</option>
                                    <option value="3">Dr. Tunde Adebayo</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6">
                                <input type="text" class="form-control bg-white border-0" placeholder="Your Full Name" style="height: 55px;">
                            </div>
                            <div class="col-12 col-sm-6">
                                <input type="email" class="form-control bg-white border-0" placeholder="Your Email Address" style="height: 55px;">
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="date" id="date" data-target-input="nearest">
                                    <input type="text"
                                        class="form-control bg-white border-0 datetimepicker-input"
                                        placeholder="Select Date" data-target="#date" data-toggle="datetimepicker" style="height: 55px;">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="time" id="time" data-target-input="nearest">
                                    <input type="text"
                                        class="form-control bg-white border-0 datetimepicker-input"
                                        placeholder="Select Time" data-target="#time" data-toggle="datetimepicker" style="height: 55px;">
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" type="submit">Submit Appointment Request</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Appointment End -->



@include('footer')