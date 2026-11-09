@include('head')
@include('topbar')
@include('navbar')

<section class="public-page-hero">
    <div class="container position-relative">
        <span class="eyebrow">Contact</span>
        <h1>We’re here to help your team connect.</h1>
        <p>Questions about Fandova, getting started, or supporting your care team? Reach out through the contact details below.</p>
    </div>
</section>

<main class="container py-5">
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="contact-card">
                <span class="contact-icon"><i class="fa fa-map-marker-alt"></i></span>
                <h3 class="h5">Our location</h3>
                <p class="mb-0">Enugu, Nigeria</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="contact-card">
                <span class="contact-icon"><i class="fa fa-phone-alt"></i></span>
                <h3 class="h5">Call us</h3>
                <p class="mb-0"><a href="tel:+23434567890">+234 345 67890</a></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="contact-card">
                <span class="contact-icon"><i class="fa fa-envelope"></i></span>
                <h3 class="h5">Email</h3>
                <p class="mb-0"><a href="mailto:info@fandova.com">info@fandova.com</a></p>
            </div>
        </div>
    </div>

    <div class="row g-4 align-items-stretch">
        <div class="col-lg-7">
            <div class="public-contact-map h-100">
                <iframe
                    src="https://maps.google.com/maps?q=Enugu%2C%20Nigeria&t=&z=12&ie=UTF8&iwloc=&output=embed"
                    title="Map of Enugu, Nigeria"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    allowfullscreen>
                </iframe>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="contact-card d-flex flex-column justify-content-center">
                <span class="section-kicker">Let’s talk</span>
                <h2 class="mb-3">A better handover starts with a conversation.</h2>
                <p>Contact our team by phone or email and tell us how your hospital or clinic works. We’ll help you find the right next step.</p>
                <div class="d-flex flex-wrap gap-3 mt-2">
                    <a class="btn btn-primary px-4 py-3" href="mailto:info@fandova.com">Send an email</a>
                    <a class="btn btn-outline-primary px-4 py-3" href="{{ url('/about') }}">About Fandova</a>
                </div>
            </div>
        </div>
    </div>
</main>

@include('footer')
