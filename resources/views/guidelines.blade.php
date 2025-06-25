<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Access AIC Kijabe Hospital's guidelines across various departments.">
    <meta name="keywords" content="hospital guidelines, OPD guidelines, medical protocols, AIC Kijabe Hospital">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>OPD Guidelines</title>
    <style>
        .department-files { display: flex; flex-wrap: wrap; margin-top: 20px; }
        .file-item { margin-bottom: 15px; }
        .file-column { flex-basis: calc(100% / 3); padding: 15px; }
        .file-link { color: #159ed5; text-decoration: none; display: block; }
        .file-link:hover { text-decoration: underline; color: black; }
        @media (max-width: 768px) { .file-column { flex-basis: 100%; } }
    </style>
</head>
<body class="bg-gray-100">
    <div class="home_container py-6" style="background-color:#4790D9;">
        <div class="container">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-white">AIC Kijabe Hospital Guidelines</h1>
                <div class="breadcrumbs">
                    <ul class="flex justify-center space-x-4">
                        <li><a href="#" class="text-black">Home</a></li>
                        <li><span id="head-text-1" class="text-white">OPD Guidelines</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 shadow-lg rounded-lg max-w-7xl mx-auto">
        <h3 class="text-xl font-bold text-[#152F56] mb-4">Select Category</h3>

        <select onchange="showFiles(this.value); updateCategory();" id="selectCat" class="mb-4 p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white" aria-label="Select a department to view guidelines">
            <option value="">Select a Category</option>
            @foreach ($departments as $department)
                <option value="{{ $department }}" {{ $department === 'OPD Guidelines' ? 'selected' : '' }}>{{ $department }}</option>
            @endforeach
        </select>
        <div id="no-department" class="text-gray-500 mt-2">Please select a department to view files.</div>

        @foreach ($filesByDepartment as $department => $files)
            <div id="{{ $department }}" class="department-files" style="display: none;">
                <h2 class="font-bold text-2xl text-[#159ed5] mb-4">{{ $department }}</h2>
                @if ($files->isEmpty())
                    <p class="text-gray-500">No files found for this department.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @php
                            $files = $files->sortBy('name')->values();
                            $totalFiles = $files->count();
                            $filesPerColumn = ceil($totalFiles / 3);
                        @endphp
                        @for ($column = 0; $column < 3; $column++)
                            <div class="file-column">
                                @php
                                    $start = $column * $filesPerColumn;
                                    $end = min($start + $filesPerColumn, $totalFiles);
                                @endphp
                                @for ($i = $start; $i < $end; $i++)
                                    @php $file = $files[$i]; @endphp
                                    <div class="file-item">
                                        <a href="{{ asset($file->filepath) }}" target="_blank" class="file-link font-normal">
                                            <i class="fas fa-file-pdf"></i> {{ $file->name }}
                                        </a>
                                    </div>
                                @endfor
                            </div>
                        @endfor
                    </div>
                @endif
            </div>
        @endforeach
	<!-- Statistics Section -->
        <div class="stats mt-6 p-4 bg-gray-100 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Guidelines Page Statistics</h3>
            <p class="text-sm text-gray-600">Views Today: <span class="font-bold">{{ $todayViews }}</span></p>
            <p class="text-sm text-gray-600">Views in Last 30 Days: <span class="font-bold">{{ $last30DaysViews }}</span></p>
        </div>
    </div>

    <script>
        function showFiles(department) {
            const allFiles = document.querySelectorAll('.department-files');
            allFiles.forEach((element) => {
                element.style.display = 'none';
            });
            const noDepartment = document.getElementById('no-department');
            if (department) {
                document.getElementById(department).style.display = 'block';
                if (noDepartment) noDepartment.style.display = 'none';
            } else {
                if (noDepartment) noDepartment.style.display = 'block';
            }
        }

        function updateCategory() {
            var selectCatObj = document.getElementById("selectCat");
            document.getElementById("head-text-1").innerHTML = selectCatObj.value || 'OPD Guidelines';
        }

        document.addEventListener('DOMContentLoaded', () => {
            const defaultDepartment = 'OPD Guidelines';
            document.getElementById('selectCat').value = defaultDepartment;
            showFiles(defaultDepartment);
        });
    </script>
</body>
</html>