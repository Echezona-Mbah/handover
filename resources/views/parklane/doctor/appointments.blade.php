@include('parklane.doctor.head')

<div class="page-wrapper">
  @include('parklane.doctor.header')

  <div class="main-container">
    @include('parklane.doctor.sidebar')

    <div class="app-container">
      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item text-primary" aria-current="page">Appointments & Follow-ups</li>
        </ol>
      </div>

      <div class="app-body">
        @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row gx-3">
          <div class="col-xl-4">
            <div class="card mb-3">
              <div class="card-header">
                <h5 class="card-title mb-0">Schedule an appointment</h5>
              </div>
              <div class="card-body">
                <form method="POST" action="{{ route('doctor.appointments.store') }}">
                  @csrf
                  <div class="mb-3">
                    <label for="patient_id" class="form-label">Patient</label>
                    <select id="patient_id" name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                      <option value="">Select patient</option>
                      @foreach ($patients as $patient)
                        <option value="{{ $patient->id }}" @selected(old('patient_id', request('patient_id')) == $patient->id)>
                          {{ $patient->full_name }} ({{ $patient->pt_number ?: $patient->patient_number }})
                        </option>
                      @endforeach
                    </select>
                    @error('patient_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @if ($patients->isEmpty())
                      <small class="text-muted">No patients are available for scheduling. Patient access must be assigned or accepted first.</small>
                    @endif
                  </div>

                  <div class="mb-3">
                    <label for="scheduled_at" class="form-label">Date and time</label>
                    <input id="scheduled_at" name="scheduled_at" type="datetime-local"
                      min="{{ now()->format('Y-m-d\TH:i') }}" value="{{ old('scheduled_at') }}"
                      class="form-control @error('scheduled_at') is-invalid @enderror" required>
                    @error('scheduled_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>

                  <div class="mb-3">
                    <label for="appointment_type" class="form-label">Appointment type</label>
                    <select id="appointment_type" name="appointment_type"
                      class="form-select @error('appointment_type') is-invalid @enderror" required>
                      <option value="consultation" @selected(old('appointment_type') === 'consultation')>Consultation</option>
                      <option value="review" @selected(old('appointment_type') === 'review')>Review</option>
                      <option value="follow_up" @selected(old('appointment_type') === 'follow_up')>Follow-up</option>
                      <option value="procedure" @selected(old('appointment_type') === 'procedure')>Procedure</option>
                    </select>
                    @error('appointment_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>

                  <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea id="notes" name="notes" rows="3" maxlength="2000"
                      class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                    @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  </div>

                  <button type="submit" class="btn btn-primary" @disabled($patients->isEmpty())>
                    <i class="ri-calendar-check-line me-1"></i> Schedule
                  </button>
                </form>
              </div>
            </div>
          </div>

          <div class="col-xl-8">
            <div class="card mb-3">
              <div class="card-header">
                <h5 class="card-title mb-0">Your appointments</h5>
              </div>
              <div class="card-body">
                @if ($appointments->isEmpty())
                  <div class="text-center py-5">
                    <i class="ri-calendar-line fs-1 text-muted"></i>
                    <p class="mb-0 mt-2 text-muted">No appointments scheduled yet.</p>
                  </div>
                @else
                  <div class="table-responsive">
                    <table class="table align-middle">
                      <thead>
                        <tr>
                          <th>Patient</th>
                          <th>Date & time</th>
                          <th>Type</th>
                          <th>Status</th>
                          <th>Notes</th>
                          <th>Update</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($appointments as $appointment)
                          <tr>
                            <td>
                              <a href="{{ route('doctor.show_patient', $appointment->patient_id) }}">
                                {{ $appointment->patient?->full_name ?? 'Patient record unavailable' }}
                              </a>
                            </td>
                            <td>{{ $appointment->scheduled_at->format('M j, Y g:i A') }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $appointment->appointment_type)) }}</td>
                            <td>
                              @php
                                $statusClass = match ($appointment->status) {
                                  'completed' => 'success',
                                  'cancelled' => 'secondary',
                                  default => 'primary',
                                };
                              @endphp
                              <span class="badge bg-{{ $statusClass }}">{{ ucfirst($appointment->status) }}</span>
                            </td>
                            <td>{{ $appointment->notes ?: '—' }}</td>
                            <td>
                              <form method="POST" action="{{ route('doctor.appointments.status', $appointment) }}"
                                class="d-flex gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-select form-select-sm" aria-label="Appointment status">
                                  @foreach (['scheduled', 'completed', 'cancelled'] as $status)
                                    <option value="{{ $status }}" @selected($appointment->status === $status)>
                                      {{ ucfirst($status) }}
                                    </option>
                                  @endforeach
                                </select>
                                <button type="submit" class="btn btn-sm btn-outline-primary">Save</button>
                              </form>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  {{ $appointments->links() }}
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>

      @include('parklane.doctor.script')
    </div>
  </div>
</div>
