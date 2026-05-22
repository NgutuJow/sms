@extends('layouts.app')

@section('content')
<style>
    /* Custom Styles for Modern Look */
    .form-section-title {
        border-left: 4px solid #0d6efd;
        padding-left: 15px;
        margin-bottom: 25px;
        font-size: 1.1rem;
        background: rgba(13, 110, 253, 0.05);
        padding-top: 8px;
        padding-bottom: 8px;
    }
    .card {
        border-radius: 15px;
        border: none;
    }
    .form-label {
        color: #555;
        margin-bottom: 5px;
    }
    .form-control, .form-select {
        border-radius: 8px;
        padding: 10px 15px;
        border: 1px solid #dee2e6;
        background-color: #f8f9fa;
    }
    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        border-color: #0d6efd;
        background-color: #fff;
    }
    .input-group-text {
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    .btn-primary {
        border-radius: 8px;
        padding: 12px 30px;
    }
</style>

<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-xl-11">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <h3 class="fw-bold m-0 text-dark">
                            <i class="bi bi-person-plus-fill me-3 text-primary"></i>Student Registration Portal
                        </h3>
                        <span class="badge bg-primary-soft text-primary px-3 py-2">Academic Year: 2026</span>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('students.store') }}" method="POST">
                        @csrf

                        <!-- Section 1: Basic Details -->
                        <h5 class="form-section-title fw-bold text-uppercase">
                            <i class="bi bi-info-circle me-2"></i>Basic Details
                        </h5>
                        <div class="row g-4 mb-5">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small">Admission No</label>
                                <input type="text" name="admission_no" class="form-control" value="{{ old('admission_no') }}" placeholder="TH/2026/001" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small">First Name</label>
                                <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" placeholder="Enter first name" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small">Middle Name</label>
                                <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name') }}" placeholder="Enter middle name">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small">Last Name</label>
                                <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" placeholder="Enter last name" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Date of Birth</label>
                                <input type="date" name="dob" class="form-control" value="{{ old('dob') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Gender</label>
                                <select name="gender" class="form-select" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                        </div>

                        <!-- Section 2: Location & Address -->
                        <h5 class="form-section-title fw-bold text-uppercase">
                            <i class="bi bi-geo-alt me-2"></i>Location & Address
                        </h5>
                        <div class="row g-4 mb-5">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-uppercase">Region (Mkoa)</label>
                                <select id="region-select" class="form-select" name="region" onchange="updateDistricts()" required>
                                    <option value="">Chagua Mkoa</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-uppercase">District (Wilaya)</label>
                                <select id="district-select" class="form-select" name="district" required>
                                    <option value="">Chagua Wilaya</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-uppercase">Street/Ward</label>
                                <input type="text" name="street" class="form-control" value="{{ old('street') }}" placeholder="Mtaa au Kata">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-uppercase">Address</label>
                                <input type="text" name="address" class="form-control" value="{{ old('address') }}" placeholder="Anuani kamili">
                            </div>
                        </div>

                        <!-- Section 3: Guardian Information -->
                        <h5 class="form-section-title fw-bold text-uppercase">
                            <i class="bi bi-shield-check me-2"></i>Guardian Information
                        </h5>
                        <div class="row g-4 mb-5">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-uppercase">Guardian Name</label>
                                <input type="text" name="guardian_name" class="form-control" value="{{ old('guardian_name') }}" placeholder="Full Name" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-uppercase">Guardian Email</label>
                                <input type="email" name="guardian_email" class="form-control" value="{{ old('guardian_email') }}" placeholder="email@example.com" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-uppercase">Guardian Phone</label>
                                <input type="text" name="guardian_phone" class="form-control" value="{{ old('guardian_phone') }}" placeholder="07XXXXXXXX">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-uppercase">Guardian Relation</label>
                                <input type="text" name="guardian_type" class="form-control" value="{{ old('guardian_type') }}" placeholder="e.g. Father, Mother">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-uppercase">Occupation</label>
                                <input type="text" name="guardian_occupation" class="form-control" value="{{ old('guardian_occupation') }}" placeholder="Guardian's Job">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-uppercase">Guardian Region</label>
                                <input type="text" name="guardian_region" class="form-control" value="{{ old('guardian_region') }}" placeholder="Kilimanjaro">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-uppercase">Guardian District</label>
                                <input type="text" name="guardian_district" class="form-control" value="{{ old('guardian_district') }}" placeholder="Moshi">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-uppercase">Street/Ward</label>
                                <input type="text" name="guardian_street" class="form-control" value="{{ old('guardian_street') }}" placeholder="Guardian's Street">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-uppercase">Address</label>
                                <input type="text" name="guardian_address" class="form-control" value="{{ old('guardian_address') }}" placeholder="Guardian's Address">
                            </div>
                        </div>

                        <!-- Section 4: Academic Information -->
                        <h5 class="form-section-title fw-bold text-uppercase">
                            <i class="bi bi-book me-2"></i>Academic Information
                        </h5>
                        <div class="row g-4 mb-5">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-uppercase">Education Level <small class="text-muted">(Secondary Only)</small></label>
                                <select name="education_level" class="form-select">
                                    <option value="">Select Level</option>
                                    <option value="Form 1" {{ old('education_level') == 'Form 1' ? 'selected' : '' }}>O-LEVEL</option>
                                    <option value="Form 2" {{ old('education_level') == 'Form 2' ? 'selected' : '' }}>A-LEVEL</option>
                                </select>
                            </div>
                           <div class="col-md-4">
    <label class="form-label fw-semibold small text-uppercase">Grade Entered</label>
    <select name="classes" class="form-select" required>
        <option value="">Select Grade</option>
        @foreach ($classes as $class)
            <option value="{{ $class->id }}" {{ old('classes') == $class->id ? 'selected' : '' }}>
                {{ $class->class_name }}
            </option>
        @endforeach
    </select>
</div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-uppercase">Stream</label>
                                <select name="stream" class="form-select">
                                    <option value="">Select Stream</option>
                                    @foreach ($streams as $stream)
                                        <option value="{{ $stream->id }}" {{ old('stream') == $stream->id ? 'selected' : '' }}>{{ $stream->stream_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                           <div class="col-md-4">
    <label class="form-label fw-semibold small text-uppercase">Academic Session</label>
    <!-- Hakikisha name ni academic_session -->
    <select name="academic_session" class="form-select" required>
        <option value="">Select Year</option>
        @foreach ($academicSessions as $session)
            <option value="{{ $session->id }}" {{ old('academic_session') == $session->id ? 'selected' : '' }}>
                {{ $session->name }}
            </option>
        @endforeach
    </select>
</div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-uppercase">Semester</label>
                                <select name="semester" class="form-select">
                                    <option value="">Select Semester</option>
                                    @foreach ($semesters as $semester)
                                        <option value="{{ $semester->id }}" {{ old('semester') == $semester->id ? 'selected' : '' }}>{{ $semester->semester_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-uppercase">Branch</label>
                                <select name="branches" class="form-select" required>
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branches') == $branch->id ? 'selected' : '' }}>{{ $branch->branch_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-uppercase">School Previously Attended</label>
                                <input type="text" name="school_attended" class="form-control" value="{{ old('school_attended') }}" placeholder="Previous School Name">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-uppercase">Grade Completed</label>
                                <input type="text" name="grade_completed" class="form-control" value="{{ old('grade_completed') }}" placeholder="e.g. Standard 7 / Form 4">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-uppercase">Suspended Before?</label>
                                <select class="form-select" name="suspended_before">
                                    <option value="">Select Option</option>
                                    <option value="Yes" {{ old('suspended_before') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('suspended_before') == 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-uppercase">Suspension Reason</label>
                                <textarea class="form-control" name="suspension_reason" rows="3" placeholder="Provide details if the student was previously suspended...">{{ old('suspension_reason') }}</textarea>
                            </div>
                        </div>

                        <div class="mt-5 pt-4 border-top d-flex justify-content-end">
                            <button type="reset" class="btn btn-light px-4 me-3 fw-bold">
                                <i class="bi bi-x-circle me-2"></i>Clear Form
                            </button>
                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow">
                                <i class="bi bi-check-circle me-2"></i>Complete Registration
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const tzData = {
        "Arusha": ["Arusha Jiji", "Arumeru", "Karatu", "Longido", "Monduli", "Ngorongoro"],
        "Dar es Salaam": ["Ilala", "Kinondoni", "Temeke", "Kigamboni", "Ubungo"],
        "Dodoma": ["Dodoma Jiji", "Bahi", "Chamwino", "Chemba", "Kondoa", "Kongwa", "Mpwapwa"],
        "Geita": ["Bukombe", "Chato", "Geita Mji", "Mbogwe", "Nyang'hwale"],
        "Iringa": ["Iringa Mjini", "Iringa Vijijini", "Kilolo", "Mufindi"],
        "Kagera": ["Bukoba Mjini", "Bukoba Vijijini", "Biharamulo", "Karagwe", "Kyerwa", "Missenyi", "Muleba", "Ngara"],
        "Katavi": ["Mpanda Mjini", "Mpanda Vijijini", "Mlele", "Tanganyika"],
        "Kigoma": ["Kigoma Ujiji", "Kigoma Vijijini", "Kasulu Mjini", "Kasulu Vijijini", "Kibondo", "Kakonko", "Uvinza"],
        "Kilimanjaro": ["Moshi Mjini", "Moshi Vijijini", "Hai", "Mwanga", "Rombo", "Same", "Siha"],
        "Lindi": ["Lindi Mjini", "Lindi Vijijini", "Kilwa", "Liwale", "Nachingwea", "Ruangwa"],
        "Manyara": ["Babati Mjini", "Babati Vijijini", "Hanang", "Kiteto", "Mbulu", "Simanjiro"],
        "Mara": ["Musoma Mjini", "Musoma Vijijini", "Bunda", "Butiama", "Serengeti", "Rorya", "Tarime"],
        "Mbeya": ["Mbeya Jiji", "Chunya", "Kyela", "Mbarali", "Rungwe", "Busokelo"],
        "Mjini Magharibi": ["Mjini", "Magharibi A", "Magharibi B"],
        "Morogoro": ["Morogoro Mjini", "Morogoro Vijijini", "Gairo", "Kilombero", "Kilosa", "Mvomero", "Ulanga", "Malinyi"],
        "Mtwara": ["Mtwara Mjini", "Mtwara Vijijini", "Masasi Mjini", "Masasi Vijijini", "Nanyamba", "Nanyumbu", "Newala", "Tandahimba"],
        "Mwanza": ["Ilemela", "Nyamagana", "Buchosa", "Kwimba", "Magu", "Misungwi", "Sengerema", "Ukerewe"],
        "Njombe": ["Njombe Mjini", "Njombe Vijijini", "Ludewa", "Makete", "Makambako", "Wanging'ombe"],
        "Pemba Kaskazini": ["Micheweni", "Wete"],
        "Pemba Kusini": ["Chake Chake", "Mkoani"],
        "Pwani": ["Bagamoyo", "Kibaha Mjini", "Kibaha Vijijini", "Kisarawe", "Mafia", "Mkuranga", "Rufiji", "Kibiti"],
        "Rukwa": ["Sumbawanga Mjini", "Sumbawanga Vijijini", "Kalambo", "Nkasi"],
        "Ruvuma": ["Songea Mjini", "Songea Vijijini", "Mbinga", "Namtumbo", "Nyasa", "Tunduru"],
        "Shinyanga": ["Shinyanga Mjini", "Shinyanga Vijijini", "Kahama Mjini", "Kishapu", "Msalala", "Ushetu"],
        "Simiyu": ["Bariadi Mjini", "Bariadi Vijijini", "Busega", "Itilima", "Maswa", "Meatu"],
        "Singida": ["Singida Mjini", "Singida Vijijini", "Iramba", "Ikungi", "Manyoni", "Mkalama", "Itigi"],
        "Songwe": ["Mbozi", "Ileje", "Momba", "Songwe", "Tunduma"],
        "Tabora": ["Tabora Mjini", "Uyui", "Igunga", "Kaliua", "Nzega", "Sikonge", "Urambo"],
        "Tanga": ["Tanga Mjini", "Handeni Mjini", "Handeni Vijijini", "Kilindi", "Korogwe Mjini", "Korogwe Vijijini", "Lushoto", "Mkinga", "Muheza", "Pangani", "Bumbuli"],
        "Unguja Kaskazini": ["Kaskazini A", "Kaskazini B"],
        "Unguja Kusini": ["Kati", "Kusini"]
    };

    const regionSelect = document.getElementById('region-select');
    const districtSelect = document.getElementById('district-select');
    const oldRegion = '{{ old('region') }}';
    const oldDistrict = '{{ old('district') }}';

    Object.keys(tzData).sort().forEach(region => {
        let opt = document.createElement('option');
        opt.value = region;
        opt.innerHTML = region;
        if (region === oldRegion) {
            opt.selected = true;
        }
        regionSelect.appendChild(opt);
    });

    function updateDistricts() {
        const selectedRegion = regionSelect.value;
        districtSelect.innerHTML = '<option value="">Chagua Wilaya</option>';
        if (selectedRegion && tzData[selectedRegion]) {
            tzData[selectedRegion].sort().forEach(district => {
                let opt = document.createElement('option');
                opt.value = district;
                opt.innerHTML = district;
                if (district === oldDistrict) {
                    opt.selected = true;
                }
                districtSelect.appendChild(opt);
            });
        }
    }

    if (oldRegion) {
        updateDistricts();
    }
</script>
@endsection