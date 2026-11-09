{{-- !-- Footer Start --> --}}
    <div class="container-fluid text-light mt-5 py-5 public-footer">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="d-inline-block text-primary text-uppercase border-bottom border-5 border-secondary mb-4">Get In Touch</h4>
                    <p class="mb-4">Have questions about Fandova or need help getting your care team started? Get in touch with us.</p>
                    <p class="mb-2"><i class="fa fa-map-marker-alt text-primary me-3"></i>Enugu, Nigeria</p>
                    <p class="mb-2"><i class="fa fa-envelope text-primary me-3"></i><a href="mailto:info@fandova.com">info@fandova.com</a></p>
                    <p class="mb-0"><i class="fa fa-phone-alt text-primary me-3"></i><a href="tel:+23434567890">+234 345 67890</a></p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="d-inline-block text-primary text-uppercase border-bottom border-5 border-secondary mb-4">Quick Links</h4>
                    <div class="d-flex flex-column justify-content-start">
                        <a class="text-light mb-2" href="{{ url('/') }}"><i class="fa fa-angle-right me-2"></i>Home</a>
                        <a class="text-light mb-2" href="{{ url('/about') }}"><i class="fa fa-angle-right me-2"></i>About</a>
                        <a class="text-light mb-2" href="{{ url('/service') }}"><i class="fa fa-angle-right me-2"></i>Services</a>
                        <a class="text-light mb-2" href="{{ url('/team') }}"><i class="fa fa-angle-right me-2"></i>Our team</a>
                        <a class="text-light mb-2" href="{{ url('/blog') }}"><i class="fa fa-angle-right me-2"></i>Health insights</a>
                        <a class="text-light" href="{{ url('/contact') }}"><i class="fa fa-angle-right me-2"></i>Contact</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="d-inline-block text-primary text-uppercase border-bottom border-5 border-secondary mb-4">For care teams</h4>
                    <p class="text-white-50 mb-4">Keep handovers clear, patient updates connected, and teams aligned throughout every shift.</p>
                    <a href="{{ url('/appointment') }}" class="btn btn-primary px-4 py-3">Get in touch</a>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid bg-dark text-light border-top border-secondary py-4">
        <div class="container">
            <div class="row g-5">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-md-0">&copy; {{ now()->year }} <a class="text-primary" href="{{ url('/') }}">Fandova</a>. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">Supporting better-connected care.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#top" class="btn btn-lg btn-primary btn-lg-square back-to-top" aria-label="Back to top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>