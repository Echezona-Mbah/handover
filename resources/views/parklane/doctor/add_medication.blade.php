@include('parklane.doctor.head')
<style>
  #dropzone {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    border: 2px dashed #007bff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease-in-out;
  }

  #dropzone:hover {
    border-color: #0056b3;
    background-color: #e9ecef;
  }

  .dz-message {
    color: #495057;
  }

  .dz-button {
    font-size: 16px;
    padding: 12px 30px;
    background-color: #007bff;
    color: white;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .dz-button:hover {
    background-color: #0056b3;
  }

  h5 {
    font-size: 16px;
    font-weight: normal;
    margin-top: 20px;
  }

  @media (max-width: 576px) {
    #dropzone {
      padding: 20px;
    }

    .dz-button {
      font-size: 14px;
      padding: 10px 25px;
    }

    h5 {
      font-size: 14px;
    }
  }

  .form-select, .form-control {
    font-size: 1rem;
    padding: 0.75rem;
    border-radius: 0.375rem;
    border: 1px solid #ced4da;
    transition: all 0.3s ease-in-out;
  }

  .form-select:focus, .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
  }

  .form-label {
    font-weight: 600;
    font-size: 1rem;
    color: #495057;
  }

  .mb-3 {
    margin-bottom: 1rem;
  }

  .medication-block {
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 18px;
    margin-bottom: 18px;
    background: #fbfcfd;
  }

  .suggestions-box {
    position: relative;
    z-index: 5;
  }
</style>

<div class="page-wrapper">

  @include('parklane.doctor.header')

  <div class="main-container">

    @include('parklane.doctor.sidebar')

    <div class="app-container">

      <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="index.html">Home</a>
          </li>
          <li class="breadcrumb-item text-primary" aria-current="page">
            Dashboard
          </li>
        </ol>
      </div>

      <div class="app-body">
        <div class="row gx-3">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Medication Plan</h5>
              </div>
              <div id="toast-container"></div>

              <div class="card-body">
                <form action="{{ route('doctor.medications.store') }}" method="POST" id="medicationForm">
                  @csrf
                  <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                  <div id="medication-wrapper">
                    <div class="medication-block medication-item" data-index="0">
                      <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Medication 1</h6>
                        <button type="button" class="btn btn-outline-danger btn-sm remove-medication" style="display:none;">Remove</button>
                      </div>

                      <div class="row gx-3">
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                          <div class="mb-3">
                            <label class="form-label">Medication Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control medication-input" name="medication[]" placeholder="Enter medication name" autocomplete="off">
                            <div class="list-group suggestions-box"></div>
                          </div>
                        </div>

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                          <div class="mb-3">
                            <label class="form-label">Start Date<span class="text-danger">*</span></label>
                            <input type="date" class="form-control start-date" name="start_date[]">
                          </div>
                        </div>

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                          <div class="mb-3">
                            <label class="form-label">End Date<span class="text-danger">*</span></label>
                            <input type="date" class="form-control end-date" name="end_date[]">
                          </div>
                        </div>

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                          <div class="mb-3">
                            <label class="form-label">Frequency<span class="text-danger">*</span></label>
                            <select class="form-select frequency" name="frequency[]" required>
                              <option value="">Select frequency</option>
                              <option value="STAT">STAT</option>
                              <option value="DAILY">DAILY</option>
                              <option value="BD">BD</option>
                              <option value="TDS">TDS</option>
                              <option value="QDS">QDS</option>
                              <option value="PRN">PRN</option>
                              <option value="NOCTE">NOCTE</option>
                            </select>
                          </div>
                        </div>

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                          <div class="mb-3">
                            <label class="form-label">No of Times</label>
                            <input type="number" class="form-control total-times" name="total_times[]" placeholder="Auto-calculated" readonly>
                          </div>
                        </div>

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                          <div class="mb-3">
                            <label class="form-label">Dosage Amount<span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control dosage-amount" name="dosage_amount[]" placeholder="Enter dosage amount">
                          </div>
                        </div>

                        <div class="col-xxl-3 col-lg-4 col-sm-6 dosage-unit-wrapper" style="display:none;">
                          <div class="mb-3">
                            <label class="form-label">Dosage Unit<span class="text-danger">*</span></label>
                            <select class="form-select dosage-unit" name="dosage_unit[]">
                              <option value="">Select unit</option>
                              <option value="mg">Mg</option>
                              <option value="g">G</option>
                              <option value="ml">Mls</option>
                            </select>
                          </div>
                        </div>

                        <div class="col-sm-12">
                          <div class="mb-3">
                            <label class="form-label">Instructions</label>
                            <textarea class="form-control" name="instructions[]" rows="3" placeholder="Medical instructions"></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="d-flex justify-content-start mb-3">
                    <button type="button" id="addMedicationBtn" class="btn btn-outline-primary">
                      Add Another Medication
                    </button>
                  </div>

                  <div class="d-flex gap-2 justify-content-end mt-4">
                    <a href="{{ route('doctor.patients.plan', $patient->id) }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Medication</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="app-footer bg-white">
        <span>© 2024</span>
      </div>

    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('medication-wrapper');
    const addBtn = document.getElementById('addMedicationBtn');

    function updateTitles() {
      document.querySelectorAll('.medication-item').forEach((item, index) => {
        item.setAttribute('data-index', index);
        item.querySelector('h6').textContent = `Medication ${index + 1}`;
        const removeBtn = item.querySelector('.remove-medication');
        removeBtn.style.display = document.querySelectorAll('.medication-item').length > 1 ? 'inline-block' : 'none';
      });
    }

    function calculateTimes(block) {
      const startDate = block.querySelector('.start-date');
      const endDate = block.querySelector('.end-date');
      const frequency = block.querySelector('.frequency');
      const totalTimes = block.querySelector('.total-times');

      if (!startDate.value || !endDate.value || !frequency.value) {
        totalTimes.value = '';
        return;
      }

      const start = new Date(startDate.value);
      const end = new Date(endDate.value);

      if (end < start) {
        totalTimes.value = '';
        return;
      }

      const diffDays = Math.floor((end - start) / (1000 * 60 * 60 * 24)) + 1;

      let multiplier = 1;
      switch (frequency.value) {
        case 'STAT': multiplier = 1; break;
        case 'DAILY': multiplier = 1; break;
        case 'BD': multiplier = 2; break;
        case 'TDS': multiplier = 3; break;
        case 'QDS': multiplier = 4; break;
        case 'NOCTE': multiplier = 1; break;
        case 'PRN': multiplier = 1; break;
        default: multiplier = 1;
      }

      totalTimes.value = diffDays * multiplier;
    }

    function bindDosageUnitToggle(block) {
      const dosageAmount = block.querySelector('.dosage-amount');
      const dosageUnitWrapper = block.querySelector('.dosage-unit-wrapper');
      const dosageUnit = block.querySelector('.dosage-unit');

      dosageAmount.addEventListener('input', function () {
        if (this.value !== '') {
          dosageUnitWrapper.style.display = 'block';
        } else {
          dosageUnitWrapper.style.display = 'none';
          dosageUnit.value = '';
        }
      });
    }

    function bindCalculation(block) {
      block.querySelector('.start-date').addEventListener('change', function () {
        calculateTimes(block);
      });

      block.querySelector('.end-date').addEventListener('change', function () {
        calculateTimes(block);
      });

      block.querySelector('.frequency').addEventListener('change', function () {
        calculateTimes(block);
      });
    }

    function bindSuggestions(block) {
      const input = block.querySelector('.medication-input');
      const suggestionsBox = block.querySelector('.suggestions-box');
      let timeout = null;

      input.addEventListener('input', function () {
        const query = this.value.trim();

        clearTimeout(timeout);

        if (query.length < 2) {
          suggestionsBox.innerHTML = '';
          return;
        }

        timeout = setTimeout(() => {
          fetch(`https://api.datamuse.com/sug?s=${encodeURIComponent(query)}`)
            .then(response => {
              if (!response.ok) {
                throw new Error('Suggestion request failed');
              }
              return response.json();
            })
            .then(data => {
              let list = '';

              if (!data.length) {
                suggestionsBox.innerHTML = '';
                return;
              }

              data.forEach(item => {
                list += `<button type="button" class="list-group-item list-group-item-action suggestion-item">${item.word}</button>`;
              });

              suggestionsBox.innerHTML = list;

              suggestionsBox.querySelectorAll('.suggestion-item').forEach(button => {
                button.addEventListener('click', function () {
                  input.value = this.textContent;
                  suggestionsBox.innerHTML = '';
                });
              });
            })
            .catch(error => {
              console.error('Medication suggestion error:', error);
              suggestionsBox.innerHTML = '';
            });
        }, 300);
      });

      input.addEventListener('blur', function () {
        setTimeout(() => {
          suggestionsBox.innerHTML = '';
        }, 200);
      });
    }

    function bindRemove(block) {
      block.querySelector('.remove-medication').addEventListener('click', function () {
        block.remove();
        updateTitles();
      });
    }

    function bindBlock(block) {
      bindCalculation(block);
      bindDosageUnitToggle(block);
      bindSuggestions(block);
      bindRemove(block);
    }

    addBtn.addEventListener('click', function () {
      const firstBlock = document.querySelector('.medication-item');
      const clone = firstBlock.cloneNode(true);

      clone.querySelectorAll('input').forEach(input => {
        input.value = '';
      });

      clone.querySelectorAll('textarea').forEach(textarea => {
        textarea.value = '';
      });

      clone.querySelectorAll('select').forEach(select => {
        select.selectedIndex = 0;
      });

      clone.querySelector('.suggestions-box').innerHTML = '';
      clone.querySelector('.dosage-unit-wrapper').style.display = 'none';

      wrapper.appendChild(clone);
      bindBlock(clone);
      updateTitles();
    });

    document.querySelectorAll('.medication-item').forEach(block => {
      bindBlock(block);
    });

    updateTitles();
  });
</script>


@include('parklane.script')
