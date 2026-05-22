@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Generate Monthly Payroll</h2>
                    <p class="text-muted mb-0">Disburse salaries for teachers and staff members.</p>
                </div>
                <a href="{{ route('finance.payroll.index') }}" class="btn btn-light border shadow-sm px-4 rounded-pill">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-5">
                    <form action="{{ route('finance.payroll.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-4">
                            <div class="col-md-12">
                                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">Employee Selection</h5>
                            </div>
                            
                            <div class="col-md-8">
                                <label class="form-label fw-bold text-muted small uppercase">Staff Member</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-user-tie text-muted"></i></span>
                                    <select name="teacher_id" id="teacher_id" class="form-control border-0 bg-light py-2 px-3" required onchange="updateSalaryInfo()">
                                        <option value="">Select staff member</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" data-salary="{{ $teacher->base_salary ?? 0 }}">
                                                {{ $teacher->name }} ({{ $teacher->employee_id ?? 'No ID' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted small uppercase">Payroll Period</label>
                                <div class="row g-2">
                                    <div class="col-7">
                                        <select name="month" class="form-control border-0 bg-light py-2 px-3" required>
                                            @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $m)
                                                <option value="{{ $m }}" {{ $m == date('F') ? 'selected' : '' }}>{{ $m }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-5">
                                        <input type="text" name="year" class="form-control border-0 bg-light py-2 px-3" value="{{ date('Y') }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mt-5">
                                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">Salary Breakdown</h5>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted small uppercase">Basic Salary</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 text-muted">TZS</span>
                                    <input type="number" name="basic_salary" id="basic_salary" class="form-control border-0 bg-light py-2 px-3" placeholder="0.00" required oninput="calculateNet()">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted small uppercase">Allowances</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 text-muted">TZS</span>
                                    <input type="number" name="allowances" id="allowances" class="form-control border-0 bg-light py-2 px-3" placeholder="0.00" value="0" oninput="calculateNet()">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted small uppercase">Deductions (Tax, etc.)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0 text-muted">TZS</span>
                                    <input type="number" name="deductions" id="deductions" class="form-control border-0 bg-light py-2 px-3" placeholder="0.00" value="0" oninput="calculateNet()">
                                </div>
                            </div>

                            <div class="col-md-12 mt-4">
                                <div class="card bg-primary bg-opacity-10 border-0 rounded-4 p-4 text-center">
                                    <h6 class="text-primary small fw-bold text-uppercase mb-2">Net Payable Amount</h6>
                                    <h2 class="fw-bold text-primary mb-0" id="net_pay_display">TZS 0.00</h2>
                                </div>
                            </div>

                            <div class="col-md-12 mt-5">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('finance.payroll.index') }}" class="btn btn-light px-4 rounded-pill">Cancel</a>
                                    <button type="submit" class="btn btn-primary px-5 rounded-pill shadow-sm fw-bold">
                                        Confirm & Process Payment
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
function updateSalaryInfo() {
    const select = document.getElementById('teacher_id');
    const selectedOption = select.options[select.selectedIndex];
    const baseSalary = selectedOption.getAttribute('data-salary') || 0;
    document.getElementById('basic_salary').value = baseSalary;
    calculateNet();
}

function calculateNet() {
    const basic = parseFloat(document.getElementById('basic_salary').value) || 0;
    const allowances = parseFloat(document.getElementById('allowances').value) || 0;
    const deductions = parseFloat(document.getElementById('deductions').value) || 0;
    
    const net = basic + allowances - deductions;
    document.getElementById('net_pay_display').innerText = 'TZS ' + net.toLocaleString(undefined, {minimumFractionDigits: 2});
}
</script>

<style>
    .rounded-4 { border-radius: 1.25rem !important; }
    .input-group-text { border: none; }
    .x-small { font-size: 0.75rem; }
</style>
@endsection
