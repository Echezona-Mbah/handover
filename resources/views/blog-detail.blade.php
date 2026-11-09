@include('head')
@include('topbar')
@include('navbar')

<section class="public-page-hero">
    <div class="container position-relative">
        <span class="eyebrow">Care team journal</span>
        <h1>Communication that carries care forward.</h1>
        <p>Thoughts on structured handovers and clear information-sharing between healthcare teams.</p>
    </div>
</section>

<main class="container py-5">
    <div class="row g-5">
        <article class="col-lg-8">
            <img class="img-fluid w-100 rounded-4 mb-4" src="{{ asset('img/cxcxc.jpg') }}" alt="Healthcare team discussing patient care">
            <span class="section-kicker">Clinical teamwork · 6 minute read</span>
            <h2 class="display-5 mb-4">Improving shift handovers to support patient safety</h2>
            <p class="lead">A clear handover gives the next care team the context they need to continue treatment with confidence.</p>
            <p>At a shift change, essential details can be easy to miss: a change in a patient’s condition, an outstanding test, a medication update, or a task that still needs attention. A consistent handover makes these priorities easier to communicate and follow up.</p>
            <h3 class="h4 mt-4">Keep the essentials together</h3>
            <p>Use a shared structure to record the patient’s current status, recent changes, alerts, and pending tasks. Keep the information concise, factual, and relevant to the next stage of care.</p>
            <h3 class="h4 mt-4">Make follow-up clear</h3>
            <p>Identify who is responsible for the next action and when it should happen. When teams can review updates and handover notes in one place, it is easier to understand what has already been done and what still needs attention.</p>
            <div class="appointment-card mt-4">
                <span class="section-kicker">Support better handovers</span>
                <h3 class="h4">Bring your care team onto the same page.</h3>
                <p class="mb-3">Learn how Fandova can support communication and continuity across your hospital or clinic.</p>
                <a href="{{ url('/contact') }}" class="btn btn-primary px-4 py-3">Talk with our team</a>
            </div>
        </article>
        <aside class="col-lg-4">
            <div class="appointment-card mb-4">
                <span class="section-kicker">Explore Fandova</span>
                <h3 class="h4">Care coordination, made clearer</h3>
                <p>Explore tools for patient updates, clinical handovers, and connected care teams.</p>
                <a href="{{ url('/service') }}" class="btn btn-outline-primary px-4 py-3">View services</a>
            </div>
            <div class="appointment-card">
                <h3 class="h5">More to explore</h3>
                <div class="d-grid gap-2">
                    <a href="{{ url('/blog') }}" class="text-decoration-none"><i class="fa fa-angle-right text-primary me-2"></i>Health insights</a>
                    <a href="{{ url('/team') }}" class="text-decoration-none"><i class="fa fa-angle-right text-primary me-2"></i>Meet the team</a>
                    <a href="{{ url('/appointment') }}" class="text-decoration-none"><i class="fa fa-angle-right text-primary me-2"></i>Plan a visit</a>
                </div>
            </div>
        </aside>
    </div>
</main>

@include('footer')
