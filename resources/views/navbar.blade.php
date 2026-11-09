
    <!-- Navbar Start -->
    <div class="container-fluid sticky-top bg-white shadow-sm">
        <div class="container">
            <nav class="navbar navbar-expand-lg bg-white navbar-light py-3 py-lg-0">
                <a href="/" class="navbar-brand">
                    <h1 class="m-0 text-uppercase text-primary"><i class="fa fa-clinic-medical me-2"></i>Fandova</h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0">
                        <a href="{{ url('/') }}" class="nav-item nav-link {{ request()->is('/') ? 'active' : '' }}">Home</a>
                        <a href="{{ url('/about') }}" class="nav-item nav-link {{ request()->is('about') ? 'active' : '' }}">About</a>
                        <a href="{{ url('/service') }}" class="nav-item nav-link {{ request()->is('service') ? 'active' : '' }}">Services</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle {{ request()->is('blog*', 'team', 'testimonial', 'appointment') ? 'active' : '' }}" data-bs-toggle="dropdown">Explore</a>
                            <div class="dropdown-menu m-0">
                                <a href="{{ url('/blog') }}" class="dropdown-item">Health insights</a>
                                <a href="{{ url('/team') }}" class="dropdown-item">Our team</a>
                                <a href="{{ url('/testimonial') }}" class="dropdown-item">Testimonials</a>
                                <a href="{{ url('/appointment') }}" class="dropdown-item">Appointment</a>
                                {{-- <a href="search.html" class="dropdown-item">Search</a> --}}
                            </div>
                        </div>
                        <a href="{{ url('/contact') }}" class="nav-item nav-link {{ request()->is('contact') ? 'active' : '' }}">Contact</a>
                        <a href="{{ url('/login') }}" class="btn btn-primary ms-lg-3 px-4 py-2">Staff login</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->