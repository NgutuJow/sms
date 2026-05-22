@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Create Fee Structure</h2>
                    <p class="text-muted mb-0">Define a new fee category and its payment rules.</p>
                </div>
                <a href="{{ route('finance.fee-structures.index') }}" class="btn btn-light border shadow-sm px-4 rounded-pill">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-5">
                    <form action="{{ route('finance.fee-structures.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-4">
                            <!-- Basic Info -->
                            <div class="col-md-12">
                                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">Basic Configuration</h5>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small uppercase">Target Class</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-school text-muted"></i></span>
                                    <select name="class_id" class="form-control border-0 bg-light py-2 px-3" required>
                                        <option value="">Apply to all classes</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('class_id') <span class="text-danger x-small">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small uppercase">Academic Year</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-calendar-alt text-muted"></i></span>
                                    <input type="text" name="academic_year" class="form-control border-0 bg-light py-2 px-3" placeholder="e.g. 2025/2026" value="{{ old('academic_year') }}" required>
                                </div>
                                @error('academic_year') <span class="text-danger x-small">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small uppercase">Fee Category / Type</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-tag text-muted"></i></span>
                                    <input type="text" name="fee_type" class="form-control border-0 bg-light py-2 px-3" placeholder="e.g. Tuition Fee" value="{{ old('fee_type') }}" required>
                                </div>
                                @error('fee_type') <span class="text-danger x-small">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small uppercase">Total Amount (TZS)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 text-muted fw-bold">TZS</span>
                                    <input type="number" name="amount" class="form-control border-0 bg-light py-2 px-3" placeholder="0.00" value="{{ old('amount') }}" required>
                                </div>
                                @error('amount') <span class="text-danger x-small">{{ $message }}</span> @enderror
                            </div>

                            <!-- Installment Rules -->
                            <div class="col-md-12 mt-5">
                                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">Payment Model</h5>
                                
                                <div class="card bg-light border-0 rounded-4 p-4 mt-3">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input ms-0 me-3" type="checkbox" id="allow_installments" name="allow_installments" value="1" style="width: 3rem; height: 1.5rem;">
                                        <label class="form-check-label fw-bold text-dark" for="allow_installments" style="padding-top: 0.2rem;">
                                            Allow payment in installments
                                        </label>
                                    </div>
                                    <p class="text-muted x-small mt-2 mb-0 ms-5">If enabled, parents can pay the total amount across multiple payments instead of a full single payment.</p>
                                </div>
                            </div>

                            <div id="installment-options" style="display: none;" class="col-md-12 mt-3">
                                <div class="row g-4 ms-2 ps-4 border-start border-3 border-primary border-opacity-25">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted small uppercase">Number of Installments</label>
                                        <select id="number_of_installments" name="number_of_installments" class="form-control border-0 bg-light py-2 px-3">
                                            @foreach([2, 3, 4, 6, 12] as $num)
                                                <option value="{{ $num }}">{{ $num }} Installments</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="alert alert-info border-0 rounded-4 small mb-0">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Installment dates will be automatically scheduled based on the academic session calendar.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mt-5">
                                <hr class="my-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('finance.fee-structures.index') }}" class="btn btn-light px-4 rounded-pill">Cancel</a>
                                    <button type="submit" class="btn btn-primary px-5 rounded-pill shadow-sm fw-bold">
                                        Save Fee Structure
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('allow_installments').addEventListener('change', function() {
    const installmentOptions = document.getElementById('installment-options');
    if (this.checked) {
        installmentOptions.style.display = 'block';
    } else {
        installmentOptions.style.display = 'none';
    }
});
</script>

<style>
    .rounded-4 { border-radius: 1.25rem !important; }
    .input-group-text { border: none; }
    .x-small { font-size: 0.75rem; }
    .form-switch .form-check-input:checked { background-color: #0d6efd; border-color: #0d6efd; }
</style>
@endsection
