@include('parklane.head')
<div class="page-wrapper">
  @include('parklane.header')
  <div class="main-container">
    @include('parklane.sidebar')
    <div class="app-container">
      <div class="app-hero-header"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route('admin.nursing.dashboard') }}">Nursing administration</a></li><li class="breadcrumb-item text-primary">My wards</li></ol></div>
      <div class="app-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <div class="card mb-4">
          <div class="card-header"><h5 class="card-title mb-0">Add a ward you manage</h5></div>
          <form method="POST" action="{{ route('wards.store') }}">
            @csrf
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-3"><label class="form-label" for="name">Ward name</label><input id="name" name="name" class="form-control" value="{{ old('name') }}" required maxlength="255"></div>
                <div class="col-md-2"><label class="form-label" for="category">Type</label><select id="category" name="category" class="form-select" required>@foreach(['Ward', 'Clinic', 'Theatre', 'Unit', 'Other'] as $category)<option value="{{ $category }}" @selected(old('category', 'Ward') === $category)>{{ $category }}</option>@endforeach</select></div>
                <div class="col-md-4"><label class="form-label" for="nurse_department_id">Nursing unit</label><select id="nurse_department_id" name="nurse_department_id" class="form-select" required><option value="">Select a unit</option>@foreach($nursingUnits as $nursingUnit)<option value="{{ $nursingUnit->id }}" @selected(old('nurse_department_id') == $nursingUnit->id)>{{ $nursingUnit->name }}</option>@endforeach</select></div>
                <div class="col-md-3"><label class="form-label" for="linen_color">Linen colour</label><input id="linen_color" name="linen_color" class="form-control" value="{{ old('linen_color') }}" maxlength="100"></div>
                <div class="col-12"><label class="form-label" for="description">Description</label><textarea id="description" name="description" class="form-control" rows="2" maxlength="500">{{ old('description') }}</textarea></div>
              </div>
            </div>
            <div class="card-footer text-end"><button class="btn btn-primary">Add ward</button></div>
          </form>
        </div>
        <div class="card">
          <div class="card-header"><h5 class="card-title mb-0">Wards assigned to you ({{ $wards->count() }})</h5></div>
          <div class="card-body">
            @forelse($wards as $ward)
              <form method="POST" action="{{ route('wards.update', $ward) }}" class="border rounded p-3 mb-3">
                @csrf
                @method('PUT')
                <div class="row g-3">
                  <div class="col-md-4"><label class="form-label" for="name-{{ $ward->id }}">Ward name</label><input id="name-{{ $ward->id }}" name="name" class="form-control" value="{{ $ward->name }}" required maxlength="255"></div>
                  <div class="col-md-3"><label class="form-label" for="category-{{ $ward->id }}">Type</label><select id="category-{{ $ward->id }}" name="category" class="form-select" required>@foreach(['Ward', 'Clinic', 'Theatre', 'Unit', 'Other'] as $category)<option value="{{ $category }}" @selected($ward->category === $category)>{{ $category }}</option>@endforeach</select></div>
                  <div class="col-md-5"><label class="form-label" for="unit-{{ $ward->id }}">Nursing unit</label><select id="unit-{{ $ward->id }}" name="nurse_department_id" class="form-select" required>@foreach($nursingUnits as $nursingUnit)<option value="{{ $nursingUnit->id }}" @selected($ward->nurse_department_id == $nursingUnit->id)>{{ $nursingUnit->name }}</option>@endforeach</select></div>
                  <div class="col-md-5"><label class="form-label" for="linen-{{ $ward->id }}">Linen colour</label><input id="linen-{{ $ward->id }}" name="linen_color" class="form-control" value="{{ $ward->linen_color }}" maxlength="100"></div>
                  <div class="col-12"><label class="form-label" for="description-{{ $ward->id }}">Description</label><textarea id="description-{{ $ward->id }}" name="description" class="form-control" rows="2" maxlength="500">{{ $ward->description }}</textarea></div>
                </div>
                <div class="text-end mt-3"><button class="btn btn-outline-primary">Save ward changes</button></div>
              </form>
            @empty
              <p class="text-muted mb-0">You have not been assigned any wards yet.</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@include('parklane.script')
