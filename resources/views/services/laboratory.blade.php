<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kijabe Hospital - Laboratory Service Charter</title>
    <meta name="description" content="Explore the comprehensive laboratory services at Kijabe Hospital with detailed turnaround times for each test.">
    <meta name="keywords" content="Kijabe Hospital, laboratory services, diagnostics, medical tests, Kenya health services, pathology, biochemistry, hematology, microbiology, immunology">
    <link rel="canonical" href="https://www.kijabehospital.org/laboratory">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"> <!-- Add Font Awesome for icons -->
    <style>
        .header-bg {
            background-image: url('images/lab.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .text-kijabe-blue {
            color: #159ed5;
        }
        .bg-kijabe-blue {
            background-color: #159ed5;
        }
        .border-kijabe-blue {
            border-color: #159ed5;
        }
        .hover-bg-kijabe-blue:hover {
            background-color: #159ed5;
            color: white;
        }
        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: white;
            padding: 1rem 0;
            border-bottom: 1px solid #ddd;
        }
        .search-wrapper {
            position: relative;
        }
        .search-wrapper input {
            padding-right: 30px;
        }
        .search-wrapper i {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #159ed5;
        }
        .test-item {
            transition: all 0.3s ease;
        }
        .test-item:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 6px rgba(21, 158, 213, 0.1);
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">

    @include('layouts.navigation')

    <section class="relative py-16 bg-blue-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold text-kijabe-blue sm:text-5xl md:text-6xl header-bg py-10">
                    Laboratory <span class="text-kijabe-blue">Service Charter</span>
                </h1>
                <p class="mt-5 text-xl text-gray-500 sm:mt-6">
                    Turnaround Times for Accurate Diagnostics
                </p>
            </div>
        </div>
    </section>

    <div class="container mx-auto py-12 px-6">
        <div class="sticky-header">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-kijabe-blue">Search Tests</h2>
                <div class="search-wrapper">
                    <input type="text" id="search" placeholder="Search for a test..." class="border border-kijabe-blue rounded p-2 w-1/3 sm:w-1/2 md:w-1/4 lg:w-1/6">
                    <i class="fas fa-search"></i>
                </div>
            </div>
        </div>

        <!-- Biochemistry -->
        <h2 class="text-3xl font-bold text-kijabe-blue mb-6 mt-10">Biochemistry</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            @foreach([
                'ALBUMIN' => '2HRS',
                'ALKALINE PHOSPHOTASE.' => '2HRS',
                'ALPHA FETO PROTEIN (AFP)' => '2HRS 30MIN',
                'AMYLASE' => '2HRS',
                'BHCG' => '2HRS 30MIN',
                'BLIRUBIN - (Direct)' => '2HRS',
                'BLIRUBIN - (Total)' => '2HRS',
                'Bone profile (APL,Cal,Phos)' => '2HRS',
                'CA 125' => '2HRS 30MIN',
                'CALCIUM' => '2HRS',
                'CARCINOEMBRYONIC ANTIGEN (CEA)' => '2HRS 30MIN',
                'CRP (C- REACTIVE PROTEIN)' => '2HRS',
                'ELECTROLYTES (Sodium, Potassium, Chloride)' => '2HRS',
                'GLUCOSE (RBS, FBS)' => '2HRS',
                'HBA1C' => '2HRS',
                'LDH -SERUM' => '2HRS',
                "LFT's(BIL,ALP,AST,ALT,GGT,PRO,ALB)" => '2HRS',
                'LIPID PROFILE' => '2HRS',
                'OGTT' => '2HRS',
                'PROTEIN (Total)' => '2HRS',
                'PSA (Total)' => '2HRS',
                'SERUM CREATININE' => '2HRS',
                'Serum Urea' => '2HRS',
                'SGOT (AST)' => '2HRS',
                'SGPT (ALT)' => '2HRS',
                'TROPONIN-I' => '2HRS 30MIN',
                'THYROID PROFILE(TSH,FT3,FT4)' => '2HRS 30MIN',
                'U/E/C (UREA,CREAT,LYTES)' => '2HRS',
                'URIC ACID' => '2HRS',
                'Urine- Electrolytes(Sodium, Potassium, Chloride)' => '2HRS',
                'MAGNESIUM' => '2 HOURS'
            ] as $test => $tat)
                <div class="bg-white rounded-lg shadow-md p-4 test-item">
                    <h3 class="text-lg font-semibold text-kijabe-blue">{{ $test }}</h3>
                    <p class="text-gray-700"><i class="fas fa-clock text-kijabe-blue"></i> {{ $tat }}</p>
                </div>
            @endforeach
        </div>

        <!-- Hematology -->
        <h2 class="text-3xl font-bold text-kijabe-blue mb-6 mt-10">Hematology</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            @foreach([
                'CEREBRAL SPINAL FLUID (CSF) Lumbar puncture/shunt' => '2HRS',
                'APTT' => '1HR',
                'FULL HAEMOGRAM (CBC)' => '1HR',
                'ERYTHOCYTES SEDIMENTATION RATE(ESR)' => '1HR',
                'HAEMOGLOBIN (HB)' => '1HR',
                'HAEMO. PARASITES (Malaria, Trypanosomes etc)' => '1HR',
                'INR (PROTHROMBIN TIME)' => '1HR',
                'MALARIA SMEAR' => '1HR',
                'RETICULOCYTE COUNT' => '1HR',
                'SEMEN ANALYSIS' => '2 HRS',
                'SICKLE CELL (CBC+DIFF+ESR)' => '1HR 30MIN'
            ] as $test => $tat)
                <div class="bg-white rounded-lg shadow-md p-4 test-item">
                    <h3 class="text-lg font-semibold text-kijabe-blue">{{ $test }}</h3>
                    <p class="text-gray-700"><i class="fas fa-clock text-kijabe-blue"></i> {{ $tat }}</p>
                </div>
            @endforeach
        </div>

        <!-- Serological Tests -->
        <h2 class="text-3xl font-bold text-kijabe-blue mb-6 mt-10">Serological Tests</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            @foreach([
                'ASOT TEST' => '2HRS',
                'BRUCELLA ANTIGEN' => '2HRS',
                'DCT (DIRECT COOMBS TEST)' => '2HRS',
                'HEPATITIS B ELISA' => '2HRS',
                'HEPATITIS B RAPID' => '2HRS',
                'HEPATITIS C ELISA' => '2HRS',
                'HEPATITIS C RAPID' => '2HRS',
                'HIV ELISA' => '2HRS',
                'HIV SPOT' => '2HRS',
                'ICT (INDIRECT COOMBS TEST)' => '2HRS',
                'RHEUMATOID FACTOR' => '2HRS',
                'SALMONELLA TYPHI ANTIGEN (STA)' => '2HRS',
                'SYPHILIS ELISA' => '2HRS',
                'SYPHILIS RAPID' => '2HRS',
                'CRYPTOCOCCUS NEOFORMANS' => '2 HOURS'
            ] as $test => $tat)
                <div class="bg-white rounded-lg shadow-md p-4 test-item">
                    <h3 class="text-lg font-semibold text-kijabe-blue">{{ $test }}</h3>
                    <p class="text-gray-700"><i class="fas fa-clock text-kijabe-blue"></i> {{ $tat }}</p>
                </div>
            @endforeach
        </div>

        <!-- Download Link -->
        <div class="text-center mt-10">
            <a href="https://kijabehospital.org/LabServiceCharter.pdf" download="LabServiceCharter.pdf" class="inline-block px-6 py-3 text-white bg-kijabe-blue rounded-full hover-bg-kijabe-blue transition duration-300">
                Download Full Service Charter
            </a>
        </div>
    </div>

    <script>
        document.getElementById('search').addEventListener('input', function(e) {
            let value = e.target.value.toLowerCase();
            let items = document.querySelectorAll('.container .grid div');
            items.forEach(item => {
                let isVisible = item.querySelector('h3').textContent.toLowerCase().includes(value);
                item.style.display = isVisible ? '' : 'none';
            });
        });
    </script>

    @include('layouts.footer')

</body>
</html>