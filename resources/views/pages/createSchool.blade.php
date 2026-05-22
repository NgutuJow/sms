@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h3 class="fw-extrabold text-dark mb-1">Register Institution</h3>
            <p class="text-secondary mb-0 small fw-medium">Onboard a new school into the management system.</p>
        </div>
        <a href="{{ route('school.index') }}" class="btn btn-light border btn-sm px-3 rounded-pill fw-bold shadow-sm">
            <i class="fas fa-arrow-left me-2 x-small"></i>Back to Registry
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

    <!-- Registration Form -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <form action="{{ route('school.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-4">
                            <!-- Basic Information -->
                            <div class="col-12">
                                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                    <i class="fas fa-info-circle me-2 text-primary"></i>Basic Information
                                </h6>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">School Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="fas fa-university text-muted x-small"></i></span>
                                    <input type="text" name="name" class="form-control bg-light rounded-3" placeholder="e.g. St. Peters High School" value="{{ old('name') }}" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold text-dark">School Code</label>
                                <input type="text" name="code" class="form-control bg-light rounded-3 small" placeholder="e.g. SCH-001" value="{{ old('code') }}" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold text-dark">Level / Type</label>
                                <select name="school_type" class="form-select bg-light rounded-3 small" required>
                                    <option value="">Select Type</option>
                                    <option value="primary" {{ old('school_type') == 'primary' ? 'selected' : '' }}>Primary School</option>
                                    <option value="secondary" {{ old('school_type') == 'secondary' ? 'selected' : '' }}>Secondary School</option>
                                    <option value="high_school" {{ old('school_type') == 'high_school' ? 'selected' : '' }}>High School</option>
                                </select>
                            </div>

                            <!-- Contact & Communication -->
                            <div class="col-12 mt-5">
                                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                    <i class="fas fa-envelope-open-text me-2 text-success"></i>Contact & Communication
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="fas fa-at text-muted x-small"></i></span>
                                    <input type="email" name="email" class="form-control bg-light rounded-3" placeholder="office@school.com" value="{{ old('email') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="fas fa-phone text-muted x-small"></i></span>
                                    <input type="text" name="phone" class="form-control bg-light rounded-3" placeholder="+255 000 000 000" value="{{ old('phone') }}" required>
                                </div>
                            </div>

                            <!-- Geographic Location -->
                            <div class="col-12 mt-5">
                                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                    <i class="fas fa-map-marked-alt me-2 text-warning"></i>Geographic Location
                                </h6>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Region</label>
                                <select name="region" id="regionSelect" class="form-select bg-light rounded-3 small" required>
                                    <option value="">Select Region</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">District</label>
                                <select name="district" id="districtSelect" class="form-select bg-light rounded-3 small" disabled required>
                                    <option value="">Select District</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Ward</label>
                                <select name="ward" id="wardSelect" class="form-select bg-light rounded-3 small" disabled required>
                                    <option value="">Select Ward</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-dark">Street / Plot Number</label>
                                <textarea name="address" class="form-control bg-light rounded-3 small" rows="2" placeholder="Describe the physical location details...">{{ old('address') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-5 pt-4 border-top d-flex justify-content-end gap-2">
                            <a href="{{ route('school.index') }}" class="btn btn-white border px-4 rounded-pill fw-bold shadow-sm">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold shadow-sm">
                                <i class="fas fa-save me-2 small"></i>Complete Registration
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
        "Arusha City": ["Central", "Sekei", "Themi", "Kaloleni", "Elerai"],
        "Meru": ["Usa River", "Akheri", "Leguruki", "King'ori"],
        "Karatu": ["Karatu Town", "Endabash", "Baray"],
        "Monduli": ["Monduli Town", "Mto wa Mbu", "Esilalei"]
    },
    "Dar es Salaam": {
        "Ilala MC": ["Kivukoni", "Upanga West", "Upanga East", "Kariakoo", "Gerezani"],
        "Kinondoni MC": ["Magomeni", "Hananasif", "Kijitonyama", "Sinza"],
        "Ubungo MC": ["Ubungo", "Manzese", "Mbezi", "Kimara", "Saranga"],
        "Temeke MC": ["Mbagala", "Chang'ombe", "Kurasini", "Yombo Vituka"]
    },
    "Dodoma": {
        "Dodoma City": ["Kizota", "Madukani", "Majengo", "Hazina"],
        "Kondoa": ["Kondoa Town", "Kolo", "Haubi"],
        "Mpwapwa": ["Mpwapwa Town", "Gulwe", "Kibakwe"]
    },
    "Mwanza": {
        "Nyamagana MC": ["Igogo", "Pamba", "Mirongo", "Mbugani"],
        "Ilemela MC": ["Kirumba", "Kitangiri", "Nyamanoro"]
    }
};

const regionSelect = document.getElementById('regionSelect');
const districtSelect = document.getElementById('districtSelect');
const wardSelect = document.getElementById('wardSelect');

// Populate Regions
Object.keys(locationData).sort().forEach(region => {
    regionSelect.add(new Option(region, region));
});

// Region -> District Logic
regionSelect.addEventListener('change', function() {
    districtSelect.innerHTML = '<option value="">Select District</option>';
    wardSelect.innerHTML = '<option value="">Select Ward</option>';
    wardSelect.disabled = true;

    if (this.value) {
        const districts = locationData[this.value];
        Object.keys(districts).sort().forEach(dist => {
            districtSelect.add(new Option(dist, dist));
        });
        districtSelect.disabled = false;
    } else {
        districtSelect.disabled = true;
    }
});

// District -> Ward Logic
districtSelect.addEventListener('change', function() {
    wardSelect.innerHTML = '<option value="">Select Ward</option>';
    const region = regionSelect.value;
    const district = this.value;

    if (district && locationData[region][district]) {
        const wards = locationData[region][district];
        wards.sort().forEach(ward => {
            wardSelect.add(new Option(ward, ward));
        });
        wardSelect.disabled = false;
    } else {
        wardSelect.disabled = true;
    }
});
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
