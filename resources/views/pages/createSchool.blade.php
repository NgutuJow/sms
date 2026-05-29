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
                                    <option value="pre_primary" {{ old('school_type') == 'pre_primary' ? 'selected' : '' }}>Pre Primary</option>
                                    <option value="primary" {{ old('school_type') == 'primary' ? 'selected' : '' }}>Primary School</option>
                                    <option value="secondary" {{ old('school_type') == 'secondary' ? 'selected' : '' }}>Secondary School</option>
                                    <option value="all" {{ old('school_type') == 'all' ? 'selected' : '' }}>All Levels (Mixed)</option>
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
        "Arusha City": ["Kaloleni", "Central", "Elementeita", "Engutoto", "Sakina", "Themi", "Terrat", "Sekei", "Elerai", "Ungu"],
        "Arusha District": ["Leguruki", "Moshono", "Piyaya", "Oltrumet", "Bang'ata", "Ilkiding'a", "Lejera"],
        "Meru": ["Usa River", "Akheri", "Leguruki", "King'ori", "Nkoaranga", "Poli", "Ormeruny"],
        "Karatu": ["Karatu Town", "Endabash", "Baray", "Buger", "Dareda", "Oldeani", "Mbulumbulu"],
        "Monduli": ["Monduli Town", "Mto wa Mbu", "Esilalei", "Lolkisale", "Meserani", "Arusha Chini"],
        "Ngorongoro": ["Loliondo", "Ngorongoro Town", "Oldeani", "Nanyokie", "Digodigo", "Engaruka"],
        "Longido": ["Longido Town", "Namanga", "Kamwanga", "Engaresero"]
    },
    "Dar es Salaam": {
        "Ilala MC": ["Kivukoni", "Upanga West", "Upanga East", "Kariakoo", "Gerezani", "Jangwani", "Mchafukoge", "Kisutu", "Gongo la Mboto", "Chanika"],
        "Kinondoni MC": ["Magomeni", "Hananasif", "Kijitonyama", "Sinza", "Mwananyamala", "Ndugumbi", "Tandale", "Makumbusho", "Kawawa"],
        "Ubungo MC": ["Ubungo", "Manzese", "Mbezi", "Kimara", "Saranga", "Kibamba", "Goba", "Makuburi", "Chalinze"],
        "Temeke MC": ["Mbagala", "Chang'ombe", "Kurasini", "Yombo Vituka", "Sandali", "Mtoni", "Mbagala Kuu"],
        "Kigamboni MC": ["Kigamboni", "Tungi", "Mji Mwema", "Kibada", "Somangila", "Mjimwema"]
    },
    "Dodoma": {
        "Dodoma City": ["Kizota", "Madukani", "Majengo", "Hazina", "Chamwino", "Kikuyu North", "Kikuyu South", "Tambukareli"],
        "Chamwino": ["Chamwino Town", "Iringa Road", "Biharimu", "Hanoneti", "Msanga", "Mangurisha"],
        "Kondoa": ["Kondoa Town", "Kolo", "Haubi", "Kwadelo", "Masange", "Mambai"],
        "Mpwapwa": ["Mpwapwa Town", "Gulwe", "Kibakwe", "Pwani", "Rudi", "Iringa"],
        "Bahi": ["Bahi Town", "Kigwe", "Lamaiti", "Mwitikira", "Sogea"],
        "Kongwa": ["Kongwa Town", "Kibaigwa", "Mlali", "Pandambili", "Ruaha"]
    },
    "Mwanza": {
        "Ilemela MC": ["Kirumba", "Kitangiri", "Nyamanoro", "Pasiansi", "Bugogwa", "Sangabuye", "Makongolosi"],
        "Nyamagana MC": ["Igogo", "Pamba", "Mirongo", "Mbugani", "Butimba", "Nyamagana", "Mkuyuni", "Mahina"],
        "Misungwi": ["Misungwi Town", "Usagara", "Gulumilo", "Mwaniko", "Makuburi"],
        "Kwimba": ["Ngudu Town", "Hungumalwa", "Ibungilo", "Kwimba"],
        "Ukerewe": ["Nansio", "Bujumbura", "Muriti", "Makongolosi"],
        "Magu": ["Magu Town", "Kisesa", "Kahangala", "Nyamuswa"],
        "Sengerema": ["Sengerema Town", "Nyatukala", "Kafunlo", "Magu"],
        "Buchosa": ["Nyehunge", "Buharanyonga", "Kome"]
    },
    "Geita": {
        "Geita TC": ["Kalangalala", "Mtakuja", "Bung'wang'a"],
        "Geita DC": ["Kasamwa", "Nyang'hwale", "Buselesele", "Geita"],
        "Chato": ["Chato Town", "Buseresere", "Kachwamba", "Chato"],
        "Bukombe": ["Ushirombo", "Bwende", "Igwamanoni", "Bukombe"],
        "Mbogwe": ["Mbogwe Town", "Lulembela", "Mbogwe"]
    },
    "Pwani": {
        "Kibaha TC": ["Maili Moja", "Kibaha", "Visiga", "Tumbi"],
        "Kibaha DC": ["Mlandizi", "Ruvu", "Kwala", "Chalinze"],
        "Bagamoyo": ["Bagamoyo Town", "Magomeni", "Dunda", "Kiwangwa", "Galu"],
        "Chalinze": ["Chalinze Town", "Msata", "Vigwaza", "Tangamano"],
        "Kisarawe": ["Kisarawe Town", "Maneromango", "Msanga", "Rahe"],
        "Mkuranga": ["Mkuranga Town", "Kimanzichana", "Vikindu", "Mlandizi"],
        "Rufiji": ["Utete Town", "Ikwiriri", "Bungu", "Nangurukuru"],
        "Mafia": ["Kilindoni", "Baleni", "Kanga", "Chole"]
    },
    "Tanga": {
        "Tanga City": ["Central", "Magomeni", "Mwanjelwa", "Makorora", "Ngamiani", "Chang'ombe"],
        "Korogwe TC": ["Manundu", "Mtonga", "Kilole"],
        "Korogwe DC": ["Mombo", "Mashewa", "Magoma", "Korogwe"],
        "Lushoto": ["Lushoto Town", "Soni", "Bumbuli", "Mlalo", "Mtae", "Vangaindrani"],
        "Muheza": ["Muheza Town", "Gombero", "Mkuzi", "Muheza"],
        "Pangani": ["Pangani Mashariki", "Pangani Magharibi", "Mwera", "Pangani"],
        "Handeni TC": ["Chanika", "Mdoe", "Kideleko"],
        "Handeni DC": ["Sindeni", "Kabuku", "Kwedizinga", "Handeni"],
        "Mkinga": ["Kasera", "Maramba", "Moa", "Mkinga"]
    },
    "Kilimanjaro": {
        "Moshi MC": ["Kiboriloni", "Majengo", "Mawenzi", "Rau", "Pasua", "Kati"],
        "Moshi DC": ["Himo", "Marangu Mashariki", "Marangu Magharibi", "Kilema", "Uru"],
        "Hai": ["Bomang'ombe", "Machame Kusini", "Machame Kaskazini", "Hai"],
        "Siha": ["Sanya Juu", "Siha Kati", "Siha Kusini", "Siha"],
        "Rombo": ["Mkuu", "Useri", "Tarakea", "Rombo"],
        "Same": ["Same Town", "Hedaru", "Mbaga", "Same"],
        "Mwanga": ["Mwanga Town", "Usangi", "Ugweno", "Mwanga"]
    },
    "Morogoro": {
        "Morogoro MC": ["Kihonda", "Mazimbu", "Mbuyuni", "Sua", "Kilakala", "Kingolwira"],
        "Morogoro DC": ["Mvuha", "Ngerengere", "Mkuyuni", "Morogoro"],
        "Mvomero": ["Dakawa", "Mtibwa", "Mzumbe", "Hembeti", "Mvomero"],
        "Kilosa": ["Kilosa Town", "Mikumi", "Gairo", "Kimamba", "Kilosa"],
        "Kilombero": ["Ifakara", "Mang'ula", "Mlimba", "Kilombero"],
        "Ulanga": ["Mahenge", "Vigoi", "Mwaya", "Ulanga"]
    },
    "Iringa": {
        "Iringa MC": ["Gangilonga", "Mwangata", "Kwakilosa", "Kihesa"],
        "Iringa DC": ["Kalenga", "Pawaga", "Ismani", "Iringa"],
        "Mufindi": ["Mafinga", "Kibowoda", "Kasanga", "Mufindi"],
        "Kilolo": ["Kilolo Town", "Ilula", "Mazombe", "Kilolo"]
    },
    "Mbeya": {
        "Mbeya City": ["Sisimba", "Iyela", "Mwanjelwa", "Uyole", "Soweto"],
        "Mbeya DC": ["Mbalizi", "Inyala", "Santilya", "Mbeya"],
        "Rungwe": ["Tukuyu", "Kiwira", "Kyimo", "Rungwe"],
        "Kyela": ["Kyela Town", "Ipinda", "Matema", "Kyela"],
        "Mbarali": ["Rujewa", "Madibira", "Igurusi", "Mbarali"],
        "Chunya": ["Makongolosi", "Lupa Tingatinga", "Chunya"]
    },
    "Kagera": {
        "Bukoba MC": ["Bakoba", "Kashai", "Hamugembe", "Miembeni"],
        "Bukoba DC": ["Katerero", "Kemondo", "Maruku", "Bukoba"],
        "Muleba": ["Muleba Town", "Kamachumu", "Nshamba", "Muleba"],
        "Karagwe": ["Kayanga", "Omurushaka", "Ihembe", "Karagwe"],
        "Ngara": ["Ngara Town", "Rulenge", "Benaco", "Ngara"],
        "Biharamulo": ["Biharamulo Town", "Nyakahura", "Biharamulo"]
    },
    "Kigoma": {
        "Kigoma Ujiji MC": ["Ujiji", "Mwanga", "Kigoma", "Bangwe"],
        "Kigoma DC": ["Mwandiga", "Mahembe", "Kalinzi", "Kigoma"],
        "Kasulu TC": ["Kasulu Mjini", "Muranze"],
        "Kasulu DC": ["Makere", "Rungwe Mpya", "Kasulu"],
        "Kibondo": ["Kibondo Town", "Mabamba", "Kibondo"],
        "Kakonko": ["Kakonko Town", "Gwaragwara", "Kakonko"]
    },
    "Shinyanga": {
        "Shinyanga MC": ["Ngokolo", "Ibadakuli", "Lubaga", "Kambarage"],
        "Shinyanga DC": ["Tinde", "Iselamagazi", "Samuye", "Shinyanga"],
        "Kahama TC": ["Mhongolo", "Nyasubi", "Majengo"],
        "Kahama DC": ["Msalala", "Isagehe", "Kahama"],
        "Kishapu": ["Kishapu Town", "Mwadui", "Mwakipoya", "Kishapu"]
    },
    "Mara": {
        "Musoma MC": ["Mwigobero", "Kamunyonge", "Nyasho"],
        "Musoma DC": ["Mugango", "Suguti", "Nyambono", "Musoma"],
        "Tarime TC": ["Tarime Mjini", "Bomani"],
        "Tarime DC": ["Sirari", "Nyamwaga", "Tarime"],
        "Bunda TC": ["Bunda Mjini", "Guta"],
        "Serengeti": ["Mugumu", "Natatta", "Issenye", "Serengeti"]
    },
    "Manyara": {
        "Babati TC": ["Babati Mjini", "Bagara", "Sigino"],
        "Babati DC": ["Magugu", "Gallapo", "Dareda", "Babati"],
        "Hanang": ["Katesh", "Endasak", "Gendabi", "Hanang"],
        "Mbulu TC": ["Mbulu Mjini", "Sanu"],
        "Kiteto": ["Kibaya", "Matui", "Dosidosi", "Kiteto"],
        "Simanjiro": ["Orkesumet", "Mirerani", "Emboret", "Simanjiro"]
    },
    "Singida": {
        "Singida MC": ["Majengo", "Mughanga", "Kindai"],
        "Singida DC": ["Ilongero", "Mtinko", "Mudida", "Singida"],
        "Iramba": ["Kiomboi", "Shelui", "Ndago", "Iramba"],
        "Manyoni": ["Manyoni Town", "Itigi", "Kintinku", "Manyoni"]
    },
    "Tabora": {
        "Tabora MC": ["Chemchem", "Isevya", "Ng'ambo", "Ipuli"],
        "Nzega TC": ["Nzega Mjini", "Uchama"],
        "Igunga": ["Igunga Town", "Nkinga", "Simbo", "Igunga"],
        "Urambo": ["Urambo Town", "Muungano", "Urambo"],
        "Sikonge": ["Sikonge Town", "Tutuo", "Sikonge"]
    },
    "Rukwa": {
        "Sumbawanga MC": ["Old Sumbawanga", "Mazwi", "Izia"],
        "Sumbawanga DC": ["Laela", "Mfinga", "Milepa", "Sumbawanga"],
        "Nkansi": ["Namanyere", "Kirando", "Kabwe", "Nkansi"]
    },
    "Ruvuma": {
        "Songea MC": ["Majengo", "Mshangano", "Bombambili", "Nzereka", "Migoli"],
        "Songea DC": ["Mlangali", "Kiberege", "Kilambo"],
        "Mbinga TC": ["Mbinga Mjini", "Bethlehem", "Makurunge"],
        "Mbinga DC": ["Lupiro", "Manda", "Nampiti", "Mbinga"],
        "Tunduru TC": ["Tunduru", "Namasakata", "Masonya"],
        "Tunduru DC": ["Tunduru", "Makambako", "Ruvuma"]
    },
    "Lindi": {
        "Lindi MC": ["Ndumbwe", "Mikindani", "Mitwero", "Likoma", "Chichiri"],
        "Lindi DC": ["Mtama", "Kiwira", "Masasi"],
        "Kilwa TC": ["Kilwa Masoko", "Kilindoni", "Masoko"],
        "Kilwa DC": ["Kivinje", "Pande", "Nangurukuru"],
        "Ruangwa TC": ["Ruangwa", "Nachingwea", "Mwaya"],
        "Mtwara DC": ["Nangurukuru", "Mikindani"]
    },
    "Mtwara": {
        "Mtwara MC": ["Shangani", "Chikongola", "Railway", "Litipi", "Mlambo"],
        "Mtwara DC": ["Mikindani", "Tandahimba", "Nanjira"],
        "Masasi TC": ["Mkomaindo", "Jidulambasa", "Nambinga"],
        "Masasi DC": ["Nachingwea", "Masasi"],
        "Newala TC": ["Newala Mjini", "Luchingu", "Meeuweni"],
        "Newala DC": ["Nandete", "Tandahimba"]
    },
    "Njombe": {
        "Njombe TC": ["Njombe Mjini", "Mjimwema", "Rujewa"],
        "Njombe DC": ["Ukwegila", "Malangali", "Igima"],
        "Makambako TC": ["Makambako Mjini", "Mlowa", "Matangali"],
        "Makambako DC": ["Tunduru", "Mafinga"],
        "Ludewa TC": ["Ludewa Town", "Mlangali", "Wanging'ombe"],
        "Ludewa DC": ["Makambako", "Ludewa"]
    },
    "Simiyu": {
        "Bariadi TC": ["Bariadi Mjini", "Bariadi", "Iramba"],
        "Bariadi DC": ["Nzega", "Ugaya"],
        "Busega DC": ["Nyashimo", "Lamadi", "Misungwi"],
        "Maswa TC": ["Binza", "Maswa Town", "Ngetani"],
        "Maswa DC": ["Mwazye", "Mwakipoya"],
        "Itilima DC": ["Itilima", "Shinyanga"]
    },
    "Songwe": {
        "Vwawa TC": ["Vwawa", "Mlowo", "Mwali"],
        "Vwawa DC": ["Mbeya", "Vwawa"],
        "Tunduma TC": ["Tunduma", "Sogea", "Mbeya"],
        "Tunduma DC": ["Zunguluka", "Malawi"],
        "Mbozi TC": ["Mlowo", "Vwawa", "Mbeya"],
        "Mbozi DC": ["Mbeya", "Songwe"]
    },
    "Katavi": {
        "Mpanda MC": ["Mpanda Mjini", "Shanwe", "Malongano"],
        "Mpanda DC": ["Sumbawanga", "Milepa"],
        "Mlele DC": ["Inyonga", "Utende", "Mporokoso"],
        "Nkansi DC": ["Namanyere", "Kirando", "Kabwe"]
    },
    "Unguja North": {
        "Kaskazini A": ["Nungwi", "Mkokotoni", "Tumbatu", "Kendwa"],
        "Kaskazini B": ["Mahonda", "Donge", "Kiwanja", "Wete"]
    },
    "Unguja South": {
        "Kusini": ["Makunduchi", "Kizimkazi", "Uroa", "Kizimkazi Chini"],
        "Kati": ["Dunga", "Koani", "Tunguu", "Jambiani"]
    },
    "Unguja West": {
        "Mjini": ["Stone Town", "Ng'ambo", "Shangani", "Uroa"],
        "Magharibi A": ["Bububu", "Mtoni", "Mfenesini", "Fuoni"],
        "Magharibi B": ["Tomondo", "Fuoni", "Mwanakwerekwe", "Ubena"]
    },
    "Pemba North": {
        "Wete": ["Wete Town", "Konde", "Gando", "Wingwi"],
        "Micheweni": ["Micheweni Town", "Wingwi", "Mikindani"]
    },
    "Pemba South": {
        "Chake Chake": ["Chake Chake Town", "Wawi", "Vitongoji", "Mkoani"],
        "Mkoani": ["Mkoani Town", "Mtambile", "Kangani", "Wawi"]
    }
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
        "Songea MC": ["Majengo", "Mshangano", "Bombambili", "Nzereka", "Migoli"],
        "Songea DC": ["Mlangali", "Kiberege", "Kilambo"],
        "Mbinga TC": ["Mbinga Mjini", "Bethlehem", "Makurunge"],
        "Mbinga DC": ["Lupiro", "Manda", "Nampiti"],
        "Tunduru TC": ["Tunduru", "Namasakata", "Masonya"],
        "Tunduru DC": ["Tunduru", "Makambako", "Ruvuma"]
    },
    "Lindi": {
        "Lindi MC": ["Ndumbwe", "Mikindani", "Mitwero", "Likoma", "Chichiri"],
        "Lindi DC": ["Mtama", "Kiwira", "Masasi"],
        "Kilwa TC": ["Kilwa Masoko", "Kilindoni", "Masoko"],
        "Kilwa DC": ["Kivinje", "Pande", "Nangurukuru"],
        "Ruangwa TC": ["Ruangwa", "Nachingwea", "Mwaya"],
        "Mtwara DC": ["Nangurukuru", "Mikindani"]
    },
    "Mtwara": {
        "Mtwara MC": ["Shangani", "Chikongola", "Railway", "Litipi", "Mlambo"],
        "Mtwara DC": ["Mikindani", "Tandahimba", "Nanjira"],
        "Masasi TC": ["Mkomaindo", "Jidulambasa", "Nambinga"],
        "Masasi DC": ["Nachingwea", "Masasi"],
        "Newala TC": ["Newala Mjini", "Luchingu", "Meeuweni"],
        "Newala DC": ["Nandete", "Tandahimba"]
    },
    "Njombe": {
        "Njombe TC": ["Njombe Mjini", "Mjimwema", "Rujewa"],
        "Njombe DC": ["Ukwegila", "Malangali", "Igima"],
        "Makambako TC": ["Makambako Mjini", "Mlowa", "Matangali"],
        "Makambako DC": ["Tunduru", "Mafinga"],
        "Ludewa TC": ["Ludewa Town", "Mlangali", "Wanging'ombe"],
        "Ludewa DC": ["Makambako", "Ludewa"]
    },
    "Simiyu": {
        "Bariadi TC": ["Bariadi Mjini", "Bariadi", "Iramba"],
        "Bariadi DC": ["Nzega", "Ugaya"],
        "Busega DC": ["Nyashimo", "Lamadi", "Misungwi"],
        "Maswa TC": ["Binza", "Maswa Town", "Ngetani"],
        "Maswa DC": ["Mwazye", "Mwakipoya"],
        "Itilima DC": ["Itilima", "Shinyanga"]
    },
    "Songwe": {
        "Vwawa TC": ["Vwawa", "Mlowo", "Mwali"],
        "Vwawa DC": ["Mbeya", "Vwawa"],
        "Tunduma TC": ["Tunduma", "Sogea", "Mbeya"],
        "Tunduma DC": ["Zunguluka", "Malawi"],
        "Mbozi TC": ["Mlowo", "Vwawa", "Mbeya"],
        "Mbozi DC": ["Mbeya", "Songwe"]
    },
    "Katavi": {
        "Mpanda MC": ["Mpanda Mjini", "Shanwe", "Malongano"],
        "Mpanda DC": ["Sumbawanga", "Milepa"],
        "Mlele DC": ["Inyonga", "Utende", "Mporokoso"],
        "Nkansi DC": ["Namanyere", "Kirando", "Kabwe"]
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
