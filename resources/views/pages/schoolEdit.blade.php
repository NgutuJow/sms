@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h3 class="fw-extrabold text-dark mb-1">Edit Institution</h3>
            <p class="text-secondary mb-0 small fw-medium">Update profile details for {{ $school->name }}.</p>
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

    <!-- Edit Form -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <form action="{{ route('school.update', $school->id) }}" method="POST">
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
                                <label class="form-label x-small fw-bold text-uppercase tracking-wider text-muted">School Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="fas fa-university text-muted x-small"></i></span>
                                    <input type="text" name="name" class="form-control bg-light border-start-0 rounded-end-3 small" placeholder="e.g. St. Peters High School" value="{{ old('name', $school->name) }}" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label x-small fw-bold text-uppercase tracking-wider text-muted">School Code</label>
                                <input type="text" name="code" class="form-control bg-light rounded-3 small" placeholder="e.g. SCH-001" value="{{ old('code', $school->code) }}" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label x-small fw-bold text-uppercase tracking-wider text-muted">Level / Type</label>
                                <select name="school_type" class="form-select bg-light rounded-3 small" required>
                                    <option value="">Select Type</option>
                                    <option value="primary" {{ old('school_type', $school->school_type) == 'primary' ? 'selected' : '' }}>Primary School</option>
                                    <option value="secondary" {{ old('school_type', $school->school_type) == 'secondary' ? 'selected' : '' }}>Secondary School</option>
                                    <option value="high_school" {{ old('school_type', $school->school_type) == 'high_school' ? 'selected' : '' }}>High School</option>
                                </select>
                            </div>

                            <!-- Contact & Communication -->
                            <div class="col-12 mt-5">
                                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                    <i class="fas fa-envelope-open-text me-2 text-success"></i>Contact & Communication
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label x-small fw-bold text-uppercase tracking-wider text-muted">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="fas fa-at text-muted x-small"></i></span>
                                    <input type="email" name="email" class="form-control bg-light border-start-0 rounded-end-3 small" placeholder="office@school.com" value="{{ old('email', $school->email) }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label x-small fw-bold text-uppercase tracking-wider text-muted">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="fas fa-phone text-muted x-small"></i></span>
                                    <input type="text" name="phone" class="form-control bg-light border-start-0 rounded-end-3 small" placeholder="+255 000 000 000" value="{{ old('phone', $school->phone) }}" required>
                                </div>
                            </div>

                            <!-- Geographic Location -->
                            <div class="col-12 mt-5">
                                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                    <i class="fas fa-map-marked-alt me-2 text-warning"></i>Geographic Location
                                </h6>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label x-small fw-bold text-uppercase tracking-wider text-muted">Region</label>
                                <select name="region" id="regionSelect" class="form-select bg-light rounded-3 small" required>
                                    <option value="">Select Region</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label x-small fw-bold text-uppercase tracking-wider text-muted">District</label>
                                <select name="district" id="districtSelect" class="form-select bg-light rounded-3 small" required>
                                    <option value="">Select District</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label x-small fw-bold text-uppercase tracking-wider text-muted">Ward</label>
                                <select name="ward" id="wardSelect" class="form-select bg-light rounded-3 small" required>
                                    <option value="">Select Ward</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label x-small fw-bold text-uppercase tracking-wider text-muted">Street / Plot Number</label>
                                <textarea name="address" class="form-control bg-light rounded-3 small" rows="2" placeholder="Describe the physical location details...">{{ old('address', $school->address) }}</textarea>
                            </div>
                        </div>

                        <div class="mt-5 pt-4 border-top d-flex justify-content-end gap-2">
                            <a href="{{ route('school.index') }}" class="btn btn-white border px-4 rounded-pill fw-bold shadow-sm">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold shadow-sm">
                                <i class="fas fa-save me-2 small"></i>Update Profile
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
        "Arusha District": ["Leguruki", "Moshono", "Piyaya"],
        "Meru": ["Usa River", "Akheri", "Leguruki", "King'ori"],
        "Karatu": ["Karatu Town", "Endabash", "Baray"],
        "Monduli": ["Monduli Town", "Mto wa Mbu", "Esilalei"],
        "Ngorongoro": ["Loliondo", "Ngorongoro Town", "Oldeani"]
    },
    "Dar es Salaam": {
        "Ilala MC": ["Kivukoni", "Upanga West", "Upanga East", "Kariakoo", "Gerezani"],
        "Kinondoni MC": ["Magomeni", "Hananasif", "Kijitonyama", "Sinza"],
        "Ubungo MC": ["Ubungo", "Manzese", "Mbezi", "Kimara", "Saranga"],
        "Temeke MC": ["Mbagala", "Chang'ombe", "Kurasini", "Yombo Vituka"]
    },
    "Dodoma": {
        "Dodoma City": ["Kizota", "Madukani", "Majengo", "Hazina"],
        "Chamwino": ["Chamwino Town", "Iringa Road", "Biharimu"],
        "Kondoa": ["Kondoa Town", "Kolo", "Haubi"],
        "Mpwapwa": ["Mpwapwa Town", "Gulwe", "Kibakwe"]
    },
    "Mwanza": {
        "Mwanza City": ["Igogo", "Pamba", "Mirongo", "Mbugani"],
        "Nyamagana": ["Nyamagana Town", "Kahama", "Iramba"],
        "Ilemela": ["Kirumba", "Kitangiri", "Nyamanoro"],
        "Misungwi": ["Misungwi Town", "Bukoba Road"],
        "Kwimba": ["Kwimba Town", "Bukoba"],
        "Ukerewe": ["Ukerewe Town", "Bujumbura"]
    },
    "Kaskazini A": {
        "Nungwi": ["Nungwi Town", "Kendwa", "Tumbatu"],
        "Wete": ["Wete Town", "Konde", "Wingwi"],
        "Chake Chake": ["Chake Chake Town", "Mkoani"]
    },
    "Kaskazini B": {
        "Mkoani": ["Mkoani Town", "Wete", "Fungeni"],
        "Wete": ["Wete Town", "Konde", "Wingwi"]
    },
    "Kusini": {
        "Makunduchi": ["Makunduchi Town", "Kizimkazi", "Uroa"],
        "Munifu": ["Munifu Town", "Kizimkazi"]
    },
    "Mjini Magharibi": {
        "Stone Town": ["Stone Town Center", "Ng'ambo", "Uroa"],
        "Zanzibar City": ["Zanzibar Town", "Chumbe Island"]
    },
    "Unguja South": {
        "Koani": ["Koani Town", "Kizimkazi"],
        "Jambiani": ["Jambiani Town", "Paje", "Kizimkazi"]
    },
    "Pwani": {
        "Pangani": ["Pangani Town", "Kimwani", "Mararifu"],
        "Bagamoyo": ["Bagamoyo Town", "Ushongo", "Chalinze"],
        "Kibaha": ["Kibaha Town", "Chalinze", "Vikindu"]
    },
    "Pemba South": {
        "Mchinga": ["Mchinga Town", "Pemba Town"],
        "Wawi": ["Wawi Town", "Mtambile"]
    },
    "Pemba North": {
        "Micheweni": ["Micheweni Town", "Pemba Town"],
        "Chake Chake": ["Chake Chake Town", "Mkoani"]
    },
    "Ruvuma": {
        "Songea": ["Songea Town", "Mbagamoyo", "Lupiro"],
        "Mbinga": ["Mbinga Town", "Lupiro", "Manda"],
        "Makambako": ["Makambako Town", "Tunduru"]
    },
    "Mbeya": {
        "Mbeya City": ["Mbeya Town", "Mbeya Arusha Road", "Soweto"],
        "Mbeya District": ["Mbeya Arusha Road", "Chuo Kikuu", "Uyole"],
        "Iringa": ["Iringa Town", "Ihumba", "Mgololo"],
        "Makambako": ["Makambako Town", "Tunduru"],
        "Kyela": ["Kyela Town", "Itungi Port"],
        "Rungwe": ["Rungwe Town", "Lupa", "Muoni"]
    },
    "Iringa": {
        "Iringa City": ["Iringa Town", "Ihumba", "Mgololo"],
        "Iringa District": ["Mlandizi", "Ilembula", "Malangu"],
        "Makambako": ["Makambako Town", "Tunduru"],
        "Njombe": ["Njombe Town", "Ukwegila", "Malangali"],
        "Ludewa": ["Ludewa Town", "Wanging'ombe"]
    },
    "Morogoro": {
        "Morogoro City": ["Morogoro Town", "Mlandizi", "Chalinze"],
        "Morogoro District": ["Mlandizi", "Ulanga", "Mvomero"],
        "Mvomero": ["Mvomero Town", "Beledoani", "Kimamba"],
        "Ulanga": ["Ulanga Town", "Ifakara", "Mpiana"]
    },
    "Lindi": {
        "Lindi City": ["Lindi Town", "Mtambaswima", "Chichiri"],
        "Lindi District": ["Mtambaswima", "Kilwa", "Mtwara"],
        "Kilwa": ["Kilwa Town", "Kilindoni", "Masoko"],
        "Mtwara": ["Mtwara Town", "Nangurukuru", "Litipo"]
    },
    "Mtwara": {
        "Mtwara City": ["Mtwara Town", "Nangurukuru", "Litipo"],
        "Mtwara District": ["Nangurukuru", "Mikindani", "Nanjira"],
        "Newala": ["Newala Town", "Meeuweni", "Nandete"],
        "Tandahimba": ["Tandahimba Town", "Mandimba", "Ruvuma"]
    },
    "Tanga": {
        "Tanga City": ["Tanga Town", "Magomeni", "Mwanjelwa"],
        "Tanga District": ["Korogwe", "Mashewa", "Mwanjelwa"],
        "Pangani": ["Pangani Town", "Kimwani", "Mararifu"],
        "Vangindrani": ["Vangindrani Town", "Pangani"],
        "Bumbuli": ["Bumbuli Town", "Lushoto"],
        "Lushoto": ["Lushoto Town", "Soni", "Bumbuli"],
        "Muheza": ["Muheza Town", "Mandera", "Mlandizi"]
    },
    "Singida": {
        "Singida City": ["Singida Town", "Timboroa", "Dodo"],
        "Singida District": ["Timboroa", "Dodo", "Iramba"],
        "Iramba": ["Iramba Town", "Kigosora", "Dakawa"],
        "Ikungi": ["Ikungi Town", "Dodo", "Dakawa"]
    },
    "Tabora": {
        "Tabora City": ["Tabora Town", "Kaliua", "Magugu"],
        "Tabora District": ["Kaliua", "Magugu", "Nzega"],
        "Kaliua": ["Kaliua Town", "Sumbawanga"],
        "Nzega": ["Nzega Town", "Kaliua"],
        "Uyui": ["Uyui Town", "Mpanda"]
    },
    "Kagera": {
        "Bukoba": ["Bukoba Town", "Kagera Town", "Karagwe"],
        "Muleba": ["Muleba Town", "Kagera", "Kyaka"],
        "Karagwe": ["Karagwe Town", "Bukoba"],
        "Biharamulo": ["Biharamulo Town", "Karagwe"]
    },
    "Kigoma": {
        "Kigoma City": ["Kigoma Town", "Ujiji", "Kasulu"],
        "Kigoma District": ["Ujiji", "Kasulu", "Bariadi"],
        "Ujiji": ["Ujiji Town", "Kasulu"],
        "Kasulu": ["Kasulu Town", "Kigoma"],
        "Kibondo": ["Kibondo Town", "Bariadi"]
    },
    "Manyara": {
        "Babati": ["Babati Town", "Katesh", "Magugu"],
        "Hanang": ["Hanang Town", "Katesh"],
        "Kiteto": ["Kiteto Town", "Arusha Road"],
        "Mbulu": ["Mbulu Town", "Iraqw", "Dareda"]
    },
    "Kilimanjaro": {
        "Moshi": ["Moshi Town", "Kilimanjaro", "Kieni"],
        "Moshi District": ["Kieni", "Rombo", "Same"],
        "Rombo": ["Rombo Town", "Bwakira"],
        "Same": ["Same Town", "Rombo"],
        "Hai": ["Hai Town", "Kieni"],
        "Siha": ["Siha Town", "Karatu"]
    },
    "Simiyu": {
        "Bariadi": ["Bariadi Town", "Nzega"],
        "Nzega": ["Nzega Town", "Kaliua"],
        "Ushirombo": ["Ushirombo Town", "Bariadi"]
    },
    "Geita": {
        "Geita": ["Geita Town", "Nzega"],
        "Bukombe": ["Bukombe Town", "Geita"],
        "Chato": ["Chato Town", "Geita"]
    },
    "Katavi": {
        "Sumbawanga": ["Sumbawanga Town", "Mpanda"],
        "Nkansi": ["Nkansi Town", "Mpanda"],
        "Mpanda": ["Mpanda Town", "Sumbawanga"]
    },
    "Njombe": {
        "Njombe": ["Njombe Town", "Ukwegila", "Malangali"],
        "Makambako": ["Makambako Town", "Tunduru"],
        "Ludewa": ["Ludewa Town", "Wanging'ombe"]
    }
};

const regionSelect = document.getElementById('regionSelect');
const districtSelect = document.getElementById('districtSelect');
const wardSelect = document.getElementById('wardSelect');

const currentRegion = "{{ $school->region }}";
const currentDistrict = "{{ $school->district }}";
const currentWard = "{{ $school->ward }}";

// Populate Regions
Object.keys(locationData).sort().forEach(region => {
    const option = new Option(region, region);
    if(region === currentRegion) option.selected = true;
    regionSelect.add(option);
});

function updateDistricts(region, selectedDistrict = null) {
    districtSelect.innerHTML = '<option value="">Select District</option>';
    wardSelect.innerHTML = '<option value="">Select Ward</option>';
    
    if (region && locationData[region]) {
        const districts = locationData[region];
        Object.keys(districts).sort().forEach(dist => {
            const option = new Option(dist, dist);
            if(dist === selectedDistrict) option.selected = true;
            districtSelect.add(option);
        });
        districtSelect.disabled = false;
        if(selectedDistrict) updateWards(region, selectedDistrict, currentWard);
    } else {
        districtSelect.disabled = true;
        wardSelect.disabled = true;
    }
}

function updateWards(region, district, selectedWard = null) {
    wardSelect.innerHTML = '<option value="">Select Ward</option>';
    if (district && locationData[region] && locationData[region][district]) {
        const wards = locationData[region][district];
        wards.sort().forEach(ward => {
            const option = new Option(ward, ward);
            if(ward === selectedWard) option.selected = true;
            wardSelect.add(option);
        });
        wardSelect.disabled = false;
    } else {
        wardSelect.disabled = true;
    }
}

// Initial Population
if(currentRegion) updateDistricts(currentRegion, currentDistrict);

// Events
regionSelect.addEventListener('change', function() {
    updateDistricts(this.value);
});

districtSelect.addEventListener('change', function() {
    updateWards(regionSelect.value, this.value);
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
