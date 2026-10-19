<style>
    /* Custom background for modal */
.modal-content {
    background-color: #f8f9fa; /* Light grey background */
    border-radius: 10px; /* Rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Soft shadow */
}

/* Darken the modal backdrop */
.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5) !important; /* Darker backdrop */
}
/* Custom background for the modal body */
.modal-body {
    background-color: #ffffff; /* White background for form */
    padding: 30px; /* Extra padding around the content */
}

/* Customize form input background */
input, select, textarea {
    background-color: #f1f1f1; /* Light grey background for inputs */
    border-radius: 8px; /* Rounded corners for inputs */
    border: 1px solid #ddd; /* Light border */
}
/* Custom modal header background */
.modal-header {
    background-color: #007bff; /* Blue background */
    color: #fff; /* White text */
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

</style>

<div class="modal fade" id="requestTestModal{{ $patient->id }}" tabindex="-1" aria-labelledby="requestTestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content bg-light">
        <form action="{{ route('lab-orders.store') }}" method="POST" class="modal-content">
          @csrf
          <input type="hidden" name="patient_id" value="{{ $patient->id }}">
  
          <div class="modal-header" style="background-color: #007bff; color: white;">
            <h5 class="modal-title" id="requestTestModalLabel">Request Lab or Diagnostic Test</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
  
          <div class="modal-body row g-3" style="background-color: #ffffff;">
            <div class="col-md-6">
              <label class="form-label">Test Type</label>
              <input type="text" name="test_type" class="form-control" required placeholder="e.g., Blood Test, X-Ray">
            </div>
  
            <div class="col-md-6">
              <label class="form-label">Ward</label>
              <select name="ward_id" class="form-control" required>
                <option value="">Select Ward</option>
                {{-- Loop to populate wards here --}}
              </select>
            </div>
  
            <div class="col-md-6">
              <label class="form-label">Assign Nurse</label>
              <select name="nurse_id" class="form-control" required>
                <option value="">Select Nurse</option>
                {{-- Loop to populate nurses here --}}
              </select>
            </div>
  
            <div class="col-md-12">
              <label class="form-label">Instructions / Notes</label>
              <textarea name="notes" class="form-control" rows="4" placeholder="Special instructions or context for the test"></textarea>
            </div>
          </div>
  
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Submit Request</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
