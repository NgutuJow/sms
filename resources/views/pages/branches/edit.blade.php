@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h3 class="fw-extrabold text-dark mb-1">Edit Branch Details</h3>
            <p class="text-secondary mb-0 small fw-medium">Updating profile and configuration for <strong>{{ $branch->branch_name }}</strong>.</p>
        </div>
        <a href="{{ route('school.branches', $branch->school_id) }}" class="btn btn-light border btn-sm px-3 rounded-pill fw-bold shadow-sm">
            <i class="fas fa-arrow-left me-2 x-small"></i>Back to Branches
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-2"></i>
                <div>
                    <ul class="mb-0 ps-3 x-small fw-bold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Edit Form -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <form action="{{ route('branches.update', $branch->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-4">
                            <!-- Basic Information -->
                            <div class="col-12">
                                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                    <i class="fas fa-info-circle me-2 text-primary"></i>Basic Information
                                </h6>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label x-small fw-bold text-uppercase tracking-wider text-muted">Branch Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="fas fa-building text-muted x-small"></i></span>
                                    <input type="text" name="branch_name" class="form-control bg-light border-start-0 rounded-end-3 small" value="{{ old('branch_name', $branch->branch_name) }}" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label x-small fw-bold text-uppercase tracking-wider text-muted">Branch Code</label>
                                <input type="text" name="branch_code" class="form-control bg-light rounded-3 small" value="{{ old('branch_code', $branch->branch_code) }}" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label x-small fw-bold text-uppercase tracking-wider text-muted">Education Level</label>
                                <select name="education_level" class="form-select bg-light rounded-3 small" required>
                                    <option value="">Select Level</option>
                                    <option value="Pre Primary" {{ old('education_level', $branch->education_level) == 'Pre Primary' ? 'selected' : '' }}>Pre Primary</option>
                                    <option value="Primary" {{ old('education_level', $branch->education_level) == 'Primary' ? 'selected' : '' }}>Primary</option>
                                    <option value="Secondary" {{ old('education_level', $branch->education_level) == 'Secondary' ? 'selected' : '' }}>Secondary</option>
                                    <option value="Mixed" {{ old('education_level', $branch->education_level) == 'Mixed' ? 'selected' : '' }}>Mixed Level</option>
                                </select>
                            </div>

                            <!-- Contact & Administration -->
                            <div class="col-12 mt-5">
                                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                    <i class="fas fa-id-card-alt me-2 text-success"></i>Contact & Administration
                                </h6>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label x-small fw-bold text-uppercase tracking-wider text-muted">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="fas fa-phone text-muted x-small"></i></span>
                                    <input type="text" name="phone" class="form-control bg-light border-start-0 rounded-end-3 small" value="{{ old('phone', $branch->phone) }}" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label x-small fw-bold text-uppercase tracking-wider text-muted">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="fas fa-at text-muted x-small"></i></span>
                                    <input type="email" name="email" class="form-control bg-light border-start-0 rounded-end-3 small" value="{{ old('email', $branch->email) }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label x-small fw-bold text-uppercase tracking-wider text-muted">Head Teacher / Manager</label>
                                <input type="text" name="head_teacher" class="form-control bg-light rounded-3 small" value="{{ old('head_teacher', $branch->head_teacher) }}">
                            </div>

                            <!-- Geographic Location -->
                            <div class="col-12 mt-5">
                                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                    <i class="fas fa-map-marked-alt me-2 text-warning"></i>Geographic Location
                                </h6>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label x-small fw-bold text-uppercase tracking-wider text-muted">Region</label>
                                <select name="region" id="regionSelect" class="form-select bg-light rounded-3 small" required>
                                    <option value="">Select Region</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label x-small fw-bold text-uppercase tracking-wider text-muted">District</label>
                                <select name="district" id="districtSelect" class="form-select bg-light rounded-3 small" required>
                                    <option value="">Select District</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label x-small fw-bold text-uppercase tracking-wider text-muted">Ward</label>
                                <select name="ward" id="wardSelect" class="form-select bg-light rounded-3 small" required>
                                    <option value="">Select Ward</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label x-small fw-bold text-uppercase tracking-wider text-muted">Street</label>
                                <select name="street" id="streetSelect" class="form-select bg-light rounded-3 small" required>
                                    <option value="">Select Street</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-5 pt-4 border-top d-flex justify-content-end gap-2">
                            <a href="{{ route('school.branches', $branch->school_id) }}" class="btn btn-white border px-4 rounded-pill fw-bold shadow-sm">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold shadow-sm">
                                <i class="fas fa-save me-2 small"></i>Update Branch Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const locationData = {
    "Arusha": {
        "Arusha City": { "Central": ["Street A", "Street B"], "Sekei": ["Mianzini", "Kijenge"], "Themi": ["Njiro", "Engutoto"] },
        "Meru": { "Usa River": ["Majengo", "Kitefu"], "Akheri": ["Poli", "Nkoaranga"] },
        "Karatu": { "Karatu Town": ["Bakhita", "Pera"] },
        "Monduli": { "Monduli Town": ["Saba Saba"] }
    },
    "Dar es Salaam": {
        "Ilala MC": { "Kivukoni": ["Posta", "Sea View"], "Kariakoo": ["Msimbazi", "Uhuru"], "Buguruni": ["Rozana", "Malapa"] },
        "Kinondoni MC": { "Magomeni": ["Mapipa", "Kagera"], "Kijitonyama": ["Sayansi", "Mpakani"] },
        "Ubungo MC": { "Mbezi": ["Mbezi Louis", "Mshikamano"], "Kimara": ["Stop Over", "Temboni"] },
        "Temeke MC": { "Mbagala": ["Kizuiani", "Zakiem"], "Chang'ombe": ["Toroli", "Bora"] }
    },
    "Dodoma": {
        "Dodoma City": { "Kizota": ["Relini", "Kizota Kati"], "Hazina": ["Hazina A", "Hazina B"] },
        "Bahi": { "Bahi": ["Bahi Sokoni"] },
        "Chamwino": { "Chamwino": ["Ikulu"] }
    }
};

const rSelect = document.getElementById('regionSelect');
const dSelect = document.getElementById('districtSelect');
const wSelect = document.getElementById('wardSelect');
const sSelect = document.getElementById('streetSelect');

const currentRegion = "{{ $branch->region }}";
const currentDistrict = "{{ $branch->district }}";
const currentWard = "{{ $branch->ward }}";
const currentStreet = "{{ $branch->street }}";

// Populate Regions
Object.keys(locationData).sort().forEach(region => {
    const option = new Option(region, region);
    if(region === currentRegion) option.selected = true;
    rSelect.add(option);
});

function updateDistricts(region, selDist = null) {
    dSelect.innerHTML = '<option value="">Select District</option>';
    wSelect.innerHTML = '<option value="">Select Ward</option>';
    sSelect.innerHTML = '<option value="">Select Street</option>';
    if (region && locationData[region]) {
        Object.keys(locationData[region]).sort().forEach(d => {
            const opt = new Option(d, d);
            if(d === selDist) opt.selected = true;
            dSelect.add(opt);
        });
        dSelect.disabled = false;
        if(selDist) updateWards(region, selDist, currentWard);
    }
}

function updateWards(region, district, selWard = null) {
    wSelect.innerHTML = '<option value="">Select Ward</option>';
    sSelect.innerHTML = '<option value="">Select Street</option>';
    if (district && locationData[region] && locationData[region][district]) {
        Object.keys(locationData[region][district]).sort().forEach(w => {
            const opt = new Option(w, w);
            if(w === selWard) opt.selected = true;
            wSelect.add(opt);
        });
        wSelect.disabled = false;
        if(selWard) updateStreets(region, district, selWard, currentStreet);
    }
}

function updateStreets(region, district, ward, selStreet = null) {
    sSelect.innerHTML = '<option value="">Select Street</option>';
    if (ward && locationData[region] && locationData[region][district] && locationData[region][district][ward]) {
        const streets = locationData[region][district][ward];
        streets.sort().forEach(s => {
            const opt = new Option(s, s);
            if(s === selStreet) opt.selected = true;
            sSelect.add(opt);
        });
        sSelect.disabled = false;
    }
}

// Initial
if(currentRegion) updateDistricts(currentRegion, currentDistrict);

rSelect.onchange = function() { updateDistricts(this.value); };
dSelect.onchange = function() { updateWards(rSelect.value, this.value); };
wSelect.onchange = function() { updateStreets(rSelect.value, dSelect.value, this.value); };
</script>

<style>
    .fw-extrabold { font-weight: 800; }
    .x-small { font-size: 0.7rem; }
    .tracking-wider { letter-spacing: 0.05em; }
    .rounded-4 { border-radius: 1rem !important; }
    .btn-white { background-color: #fff; color: #1e293b; }
    .form-control:focus, .form-select:focus {
        background-color: #fff !important;
        border-color: #2563eb !important;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1) !important;
    }
</style>
@endsection
