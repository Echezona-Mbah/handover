@include('parklane.nurses.head')
<div class="page-wrapper">
  @include('parklane.nurses.header')
  <div class="main-container">
    @include('parklane.nurses.sidebar')
    <div class="app-container">
      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('nurse.show_patient', $patient) }}">{{ $patient->full_name }}</a></li><li class="breadcrumb-item text-primary">Nursing process</li></ol>
        <a href="{{ route('nurse.show_patient', $patient) }}" class="btn btn-light ms-auto">Back to patient</a>
      </div>
      <div class="app-body">
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <div class="card">
          <div class="card-header"><h5 class="card-title mb-1">Nursing process · {{ $patient->full_name }}</h5><small class="text-muted">Complete each of the five steps and save this dated patient-care record.</small></div>
          <form method="POST" action="{{ route('nurse.nursing-process.store', $patient) }}">
            @csrf
            <div class="card-body">
              <div class="mb-3"><label class="form-label" for="assessment">1. Assessment</label><textarea id="assessment" name="assessment" class="form-control" rows="4" required maxlength="10000" placeholder="Record relevant subjective and objective patient information.">{{ old('assessment') }}</textarea></div>
              <div class="mb-3"><label class="form-label" for="diagnosis">2. Nursing diagnosis</label><textarea id="diagnosis" name="diagnosis" class="form-control" rows="4" required maxlength="10000" placeholder="Document the nursing clinical judgment about the patient's needs or response.">{{ old('diagnosis') }}</textarea></div>
              <div class="mb-3"><label class="form-label" for="planning">3. Outcomes and planning</label><textarea id="planning" name="planning" class="form-control" rows="4" required maxlength="10000" placeholder="Record measurable expected outcomes and the planned nursing interventions.">{{ old('planning') }}</textarea></div>
              <div class="mb-3"><label class="form-label" for="implementation">4. Implementation</label><textarea id="implementation" name="implementation" class="form-control" rows="4" required maxlength="10000" placeholder="Record the care and interventions carried out.">{{ old('implementation') }}</textarea></div>
              <div class="mb-3"><label class="form-label" for="evaluation">5. Evaluation</label><textarea id="evaluation" name="evaluation" class="form-control" rows="4" required maxlength="10000" placeholder="Record the patient's response, progress toward outcomes, and any follow-up needed.">{{ old('evaluation') }}</textarea></div>
            </div>
            <div class="card-footer d-flex justify-content-end gap-2"><a href="{{ route('nurse.show_patient', $patient) }}" class="btn btn-light">Cancel</a><button class="btn btn-primary">Save nursing process</button></div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@include('parklane.nurses.script')
