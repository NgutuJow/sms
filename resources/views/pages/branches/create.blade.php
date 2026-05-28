@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h3 class="fw-extrabold text-dark mb-1">Add New Branch</h3>
            <p class="text-secondary mb-0 small fw-medium">Establishing a new campus for <strong>{{ $school->name }}</strong>.</p>
        </div>
        <a href="{{ route('school.branches', $school->id) }}" class="btn btn-light border btn-sm px-3 rounded-pill fw-bold shadow-sm">
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

    <!-- Registration Form -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <form action="{{ route('branches.store', $school->id) }}" method="POST">
                        @csrf
                        
                        <div class="row g-4">
                            <!-- Basic Information -->
                            <div class="col-12">
                                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                    <i class="fas fa-info-circle me-2 text-primary"></i>Basic Information
                                </h6>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Branch Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="fas fa-building text-muted x-small"></i></span>
                                    <input type="text" name="branch_name" class="form-control bg-light border-start-0 rounded-end-3 small" placeholder="e.g. Main Campus" value="{{ old('branch_name') }}" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold text-dark">Branch Code</label>
                                <input type="text" name="branch_code" class="form-control bg-light rounded-3" placeholder="e.g. BR-001" value="{{ old('branch_code') }}" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold text-dark">Education Level</label>
                                <select name="education_level" class="form-select bg-light rounded-3 small" required>
                                    <option value="">Select Level</option>
                                    <option value="Pre Primary" {{ old('education_level') == 'Pre Primary' ? 'selected' : '' }}>Pre Primary</option>
                                    <option value="Primary" {{ old('education_level') == 'Primary' ? 'selected' : '' }}>Primary</option>
                                    <option value="Secondary" {{ old('education_level') == 'Secondary' ? 'selected' : '' }}>Secondary</option>
                                    <option value="Mixed" {{ old('education_level') == 'Mixed' ? 'selected' : '' }}>Mixed Level</option>
                                </select>
                            </div>

                            <!-- Contact & Administration -->
                            <div class="col-12 mt-5">
                                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                    <i class="fas fa-id-card-alt me-2 text-success"></i>Contact & Administration
                                </h6>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="fas fa-phone text-muted x-small"></i></span>
                                    <input type="text" name="phone" class="form-control bg-light border-start-0 rounded-end-3 small" placeholder="+255 000 000 000" value="{{ old('phone') }}" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="fas fa-at text-muted x-small"></i></span>
                                    <input type="email" name="email" class="form-control bg-light border-start-0 rounded-end-3 small" placeholder="branch@school.com" value="{{ old('email') }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Head Teacher / Manager</label>
                                <input type="text" name="head_teacher" class="form-control bg-light rounded-3" placeholder="Name of administrator" value="{{ old('head_teacher') }}">
                            </div>

                            <!-- Geographic Location -->
                            <div class="col-12 mt-5">
                                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                    <i class="fas fa-map-marked-alt me-2 text-warning"></i>Geographic Location
                                </h6>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold text-dark">Region</label>
                                <select name="region" id="regionSelect" class="form-select bg-light rounded-3 small" required>
                                    <option value="">Select Region</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold text-dark">District</label>
                                <select name="district" id="districtSelect" class="form-select bg-light rounded-3 small" disabled required>
                                    <option value="">Select District</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold text-dark">Ward</label>
                                <select name="ward" id="wardSelect" class="form-select bg-light rounded-3 small" disabled required>
                                    <option value="">Select Ward</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold text-dark">Street</label>
                                <select name="street" id="streetSelect" class="form-select bg-light rounded-3 small" disabled required>
                                    <option value="">Select Street</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-5 pt-4 border-top d-flex justify-content-end gap-2">
                            <a href="{{ route('school.branches', $school->id) }}" class="btn btn-white border px-4 rounded-pill fw-bold shadow-sm">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold shadow-sm">
                                <i class="fas fa-save me-2 small"></i>Save Branch Details
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
        "Arusha City": ["Central", "Sekei", "Themi", "Kaloleni", "Elerai", "Sakina", "Ungu", "Engutoto", "Terrat"],
        "Arusha District": ["Leguruki", "Moshono", "Piyaya", "Oltrumet", "Bang'ata", "Ilkiding'a"],
        "Meru": ["Usa River", "Akheri", "Leguruki", "King'ori", "Nkoaranga", "Poli"],
        "Karatu": ["Karatu Town", "Endabash", "Baray", "Buger", "Dareda", "Oldeani"],
        "Monduli": ["Monduli Town", "Mto wa Mbu", "Esilalei", "Lolkisale", "Meserani"],
        "Ngorongoro": ["Loliondo", "Ngorongoro Town", "Oldeani", "Nanyokie", "Digodigo"],
        "Longido": ["Longido Town", "Namanga", "Kamwanga"]
    },
    "Dar es Salaam": {
        "Ilala MC": ["Kivukoni", "Upanga West", "Upanga East", "Kariakoo", "Gerezani", "Jangwani", "Mchafukoge", "Kisutu", "Gongo la Mboto", "Chanika"],
        "Kinondoni MC": ["Magomeni", "Hananasif", "Kijitonyama", "Sinza", "Mwananyamala", "Ndugumbi", "Tandale", "Makumbusho"],
        "Ubungo MC": ["Ubungo", "Manzese", "Mbezi", "Kimara", "Saranga", "Kibamba", "Goba", "Makuburi"],
        "Temeke MC": ["Mbagala", "Chang'ombe", "Kurasini", "Yombo Vituka", "Sandali", "Mtoni", "Mbagala Kuu"],
        "Kigamboni MC": ["Kigamboni", "Tungi", "Mji Mwema", "Kibada", "Somangila"]
    },
    "Dodoma": {
        "Dodoma City": ["Kizota", "Madukani", "Majengo", "Hazina", "Chamwino", "Kikuyu North", "Kikuyu South", "Tambukareli"],
        "Chamwino": ["Chamwino Town", "Iringa Road", "Biharimu", "Hanoneti", "Msanga"],
        "Kondoa": ["Kondoa Town", "Kolo", "Haubi", "Kwadelo", "Masange"],
        "Mpwapwa": ["Mpwapwa Town", "Gulwe", "Kibakwe", "Pwani", "Rudi"],
        "Bahi": ["Bahi Town", "Kigwe", "Lamaiti", "Mwitikira"],
        "Kongwa": ["Kongwa Town", "Kibaigwa", "Mlali", "Pandambili"]
    },
    "Mwanza": {
        "Ilemela MC": ["Kirumba", "Kitangiri", "Nyamanoro", "Pasiansi", "Bugogwa", "Sangabuye"],
        "Nyamagana MC": ["Igogo", "Pamba", "Mirongo", "Mbugani", "Butimba", "Nyamagana", "Mkuyuni", "Mahina"],
        "Misungwi": ["Misungwi Town", "Usagara", "Gulumilo", "Mwaniko"],
        "Kwimba": ["Ngudu Town", "Hungumalwa", "Ibungilo"],
        "Ukerewe": ["Nansio", "Bujumbura", "Muriti"],
        "Magu": ["Magu Town", "Kisesa", "Kahangala"],
        "Sengerema": ["Sengerema Town", "Nyatukala", "Kafunlo"],
        "Buchosa": ["Nyehunge", "Buharanyonga"]
    },
    "Geita": {
        "Geita TC": ["Kalangalala", "Mtakuja", "Bung'wang'a"],
        "Geita DC": ["Kasamwa", "Nyang'hwale", "Buselesele"],
        "Chato": ["Chato Town", "Buseresere", "Kachwamba"],
        "Bukombe": ["Ushirombo", "Bwende", "Igwamanoni"],
        "Mbogwe": ["Mbogwe Town", "Lulembela"]
    },
    "Pwani": {
        "Kibaha TC": ["Maili Moja", "Kibaha", "Visiga", "Tumbi"],
        "Kibaha DC": ["Mlandizi", "Ruvu", "Kwala"],
        "Bagamoyo": ["Bagamoyo Town", "Magomeni", "Dunda", "Kiwangwa"],
        "Chalinze": ["Chalinze Town", "Msata", "Vigwaza"],
        "Kisarawe": ["Kisarawe Town", "Maneromango", "Msanga"],
        "Mkuranga": ["Mkuranga Town", "Kimanzichana", "Vikindu"],
        "Rufiji": ["Utete Town", "Ikwiriri", "Bungu"],
        "Mafia": ["Kilindoni", "Baleni", "Kanga"]
    },
    "Tanga": {
        "Tanga City": ["Central", "Magomeni", "Mwanjelwa", "Makorora", "Ngamiani"],
        "Korogwe TC": ["Manundu", "Mtonga", "Kilole"],
        "Korogwe DC": ["Mombo", "Mashewa", "Magoma"],
        "Lushoto": ["Lushoto Town", "Soni", "Bumbuli", "Mlalo", "Mtae"],
        "Muheza": ["Muheza Town", "Gombero", "Mkuzi"],
        "Pangani": ["Pangani Mashariki", "Pangani Magharibi", "Mwera"],
        "Handeni TC": ["Chanika", "Mdoe", "Kideleko"],
        "Handeni DC": ["Sindeni", "Kabuku", "Kwedizinga"],
        "Mkinga": ["Kasera", "Maramba", "Moa"]
    },
    "Kilimanjaro": {
        "Moshi MC": ["Kiboriloni", "Majengo", "Mawenzi", "Rau", "Pasua"],
        "Moshi DC": ["Himo", "Marangu Mashariki", "Marangu Magharibi", "Kilema"],
        "Hai": ["Bomang'ombe", "Machame Kusini", "Machame Kaskazini"],
        "Siha": ["Sanya Juu", "Siha Kati", "Siha Kusini"],
        "Rombo": ["Mkuu", "Useri", "Tarakea"],
        "Same": ["Same Town", "Hedaru", "Mbaga"],
        "Mwanga": ["Mwanga Town", "Usangi", "Ugweno"]
    },
    "Morogoro": {
        "Morogoro MC": ["Kihonda", "Mazimbu", "Mbuyuni", "Sua", "Kilakala"],
        "Morogoro DC": ["Mvuha", "Ngerengere", "Mkuyuni"],
        "Mvomero": ["Dakawa", "Mtibwa", "Mzumbe", "Hembeti"],
        "Kilosa": ["Kilosa Town", "Mikumi", "Gairo", "Kimamba"],
        "Kilombero": ["Ifakara", "Mang'ula", "Mlimba"],
        "Ulanga": ["Mahenge", "Vigoi", "Mwaya"]
    },
    "Iringa": {
        "Iringa MC": ["Gangilonga", "Mwangata", "Kwakilosa", "Kihesa"],
        "Iringa DC": ["Kalenga", "Pawaga", "Ismani"],
        "Mufindi": ["Mafinga", "Kibowoda", "Kasanga"],
        "Kilolo": ["Kilolo Town", "Ilula", "Mazombe"]
    },
    "Mbeya": {
        "Mbeya City": ["Sisimba", "Iyela", "Mwanjelwa", "Uyole", "Soweto"],
        "Mbeya DC": ["Mbalizi", "Inyala", "Santilya"],
        "Rungwe": ["Tukuyu", "Kiwira", "Kyimo"],
        "Kyela": ["Kyela Town", "Ipinda", "Matema"],
        "Mbarali": ["Rujewa", "Madibira", "Igurusi"],
        "Chunya": ["Makongolosi", "Lupa Tingatinga"]
    },
    "Kagera": {
        "Bukoba MC": ["Bakoba", "Kashai", "Hamugembe", "Miembeni"],
        "Bukoba DC": ["Katerero", "Kemondo", "Maruku"],
        "Muleba": ["Muleba Town", "Kamachumu", "Nshamba"],
        "Karagwe": ["Kayanga", "Omurushaka", "Ihembe"],
        "Ngara": ["Ngara Town", "Rulenge", "Benaco"],
        "Biharamulo": ["Biharamulo Town", "Nyakahura"]
    },
    "Kigoma": {
        "Kigoma Ujiji MC": ["Ujiji", "Mwanga", "Kigoma", "Bangwe"],
        "Kigoma DC": ["Mwandiga", "Mahembe", "Kalinzi"],
        "Kasulu TC": ["Kasulu Mjini", "Muranze"],
        "Kasulu DC": ["Makere", "Rungwe Mpya"],
        "Kibondo": ["Kibondo Town", "Mabamba"],
        "Kakonko": ["Kakonko Town", "Gwaragwara"]
    },
    "Shinyanga": {
        "Shinyanga MC": ["Ngokolo", "Ibadakuli", "Lubaga", "Kambarage"],
        "Shinyanga DC": ["Tinde", "Iselamagazi", "Samuye"],
        "Kahama TC": ["Mhongolo", "Nyasubi", "Majengo"],
        "Kahama DC": ["Msalala", "Isagehe"],
        "Kishapu": ["Kishapu Town", "Mwadui", "Mwakipoya"]
    },
    "Mara": {
        "Musoma MC": ["Mwigobero", "Kamunyonge", "Nyasho"],
        "Musoma DC": ["Mugango", "Suguti", "Nyambono"],
        "Tarime TC": ["Tarime Mjini", "Bomani"],
        "Tarime DC": ["Sirari", "Nyamwaga"],
        "Bunda TC": ["Bunda Mjini", "Guta"],
        "Serengeti": ["Mugumu", "Natatta", "Issenye"]
    },
    "Manyara": {
        "Babati TC": ["Babati Mjini", "Bagara", "Sigino"],
        "Babati DC": ["Magugu", "Gallapo", "Dareda"],
        "Hanang": ["Katesh", "Endasak", "Gendabi"],
        "Mbulu TC": ["Mbulu Mjini", "Sanu"],
        "Kiteto": ["Kibaya", "Matui", "Dosidosi"],
        "Simanjiro": ["Orkesumet", "Mirerani", "Emboret"]
    },
    "Singida": {
        "Singida MC": ["Majengo", "Mughanga", "Kindai"],
        "Singida DC": ["Ilongero", "Mtinko", "Mudida"],
        "Iramba": ["Kiomboi", "Shelui", "Ndago"],
        "Manyoni": ["Manyoni Town", "Itigi", "Kintinku"]
    },
    "Tabora": {
        "Tabora MC": ["Chemchem", "Isevya", "Ng'ambo", "Ipuli"],
        "Nzega TC": ["Nzega Mjini", "Uchama"],
        "Igunga": ["Igunga Town", "Nkinga", "Simbo"],
        "Urambo": ["Urambo Town", "Muungano"],
        "Sikonge": ["Sikonge Town", "Tutuo"]
    },
    "Rukwa": {
        "Sumbawanga MC": ["Old Sumbawanga", "Mazwi", "Izia"],
        "Sumbawanga DC": ["Laela", "Mfinga", "Milepa"],
        "Nkansi": ["Namanyere", "Kirando", "Kabwe"]
    },
    "Ruvuma": {
        "Songea MC": ["Majengo", "Mshangano", "Bombambili"],
        "Mbinga TC": ["Mbinga Mjini", "Bethlehem"],
        "Tunduru": ["Tunduru Town", "Namasakata", "Masonya"]
    },
    "Lindi": {
        "Lindi MC": ["Ndumbwe", "Mikindani", "Mitwero"],
        "Kilwa": ["Kilwa Masoko", "Kivinje", "Pande"],
        "Ruangwa": ["Ruangwa Town", "Nachingwea"]
    },
    "Mtwara": {
        "Mtwara MC": ["Shangani", "Chikongola", "Railway"],
        "Masasi TC": ["Mkomaindo", "Jidulambasa"],
        "Newala TC": ["Newala Mjini", "Luchingu"]
    },
    "Njombe": {
        "Njombe TC": ["Njombe Mjini", "Mjimwema"],
        "Makambako TC": ["Makambako Mjini", "Mlowa"],
        "Ludewa": ["Ludewa Town", "Mlangali"]
    },
    "Simiyu": {
        "Bariadi TC": ["Bariadi Mjini", "Bariadi"],
        "Busega": ["Nyashimo", "Lamadi"],
        "Maswa": ["Binza", "Maswa Town"]
    },
    "Songwe": {
        "Vwawa TC": ["Vwawa", "Mlowo"],
        "Tunduma TC": ["Tunduma", "Sogea"],
        "Mbozi": ["Mlowo", "Vwawa"]
    },
    "Katavi": {
        "Mpanda MC": ["Mpanda Mjini", "Shanwe"],
        "Mlele": ["Inyonga", "Utende"]
    },
    "Unguja North": {
        "Kaskazini A": ["Nungwi", "Mkokotoni", "Tumbatu"],
        "Kaskazini B": ["Mahonda", "Donge", "Kiwanja"]
    },
    "Unguja South": {
        "Kusini": ["Makunduchi", "Kizimkazi", "Uroa"],
        "Kati": ["Dunga", "Koani", "Tunguu"]
    },
    "Unguja West": {
        "Mjini": ["Stone Town", "Ng'ambo", "Shangani"],
        "Magharibi A": ["Bububu", "Mtoni", "Mfenesini"],
        "Magharibi B": ["Tomondo", "Fuoni", "Mwanakwerekwe"]
    },
    "Pemba North": {
        "Wete": ["Wete Town", "Konde", "Gando"],
        "Micheweni": ["Micheweni Town", "Wingwi"]
    },
    "Pemba South": {
        "Chake Chake": ["Chake Chake Town", "Wawi", "Vitongoji"],
        "Mkoani": ["Mkoani Town", "Mtambile", "Kangani"]
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const rSelect = document.getElementById('regionSelect');
    const dSelect = document.getElementById('districtSelect');
    const wSelect = document.getElementById('wardSelect');

    // Populate Regions
    Object.keys(locationData).sort().forEach(r => rSelect.add(new Option(r, r)));

    rSelect.onchange = function() {
        dSelect.innerHTML = '<option value="">Select District</option>';
        wardSelect.innerHTML = '<option value="">Select Ward</option>';
        dSelect.disabled = wSelect.disabled = true;

        if (this.value) {
            Object.keys(locationData[this.value]).sort().forEach(d => dSelect.add(new Option(d, d)));
            dSelect.disabled = false;
        }
    };

    dSelect.onchange = function() {
        wardSelect.innerHTML = '<option value="">Select Ward</option>';
        wardSelect.disabled = true;

        const wards = locationData[rSelect.value][this.value];
        if (wards && wards.length > 0) {
            wards.sort().forEach(w => wardSelect.add(new Option(w, w)));
            wardSelect.disabled = false;
        }
    };
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
