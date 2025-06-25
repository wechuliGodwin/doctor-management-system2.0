<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>OPD Guidelines</title>
    <style>
	/* Custom styles (if any) */
	.department-files{
		display: flex;
		flex-wrap:wrap;
		margin-top: 20px;
	}
	.file-item{
		margin-bottom:15px;
	}
	.file-column{
		flex-basis: calc(100% / 3);
		padding: 15px;
	}

	.file-link {
		color: #159ed5;
		text-decoration: none;
		diplay: block;
	}
	.file-link:hover {
		text-decoration: underline;
		color:black;
	}
	@media (max-width: 768px) {
    	.file-column {
        	flex-basis: 100%; /* Single column layout for smaller screens */
    		}
	}

    </style>
    <script>
        function showFiles(department) {
            const allFiles = document.querySelectorAll('.department-files');
            allFiles.forEach((element) => {
                element.style.display = 'none'; // Hide all files
            });
            if (department) {
                document.getElementById(department).style.display = 'block'; // Show selected department
            }
        }

        // Automatically show files for the "OPD Guidelines" department on page load
        document.addEventListener('DOMContentLoaded', () => {
            const defaultDepartment = 'OPD Guidelines';
            document.querySelector(`select`).value = defaultDepartment; // Set default department in dropdown
            showFiles(defaultDepartment); // Show files for default department
        });
    </script>
</head>
<body class="bg-gray-100">
    <!-- Parallax Background Section -->
    <div class="parallax-background"></div>
    
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

    <div class="bg-white p-6 shadow-lg rounded-lg mx-5">
        <h3 class="text-xl font-bold text-[#152F56] mb-4">Select Category</h3>

        <!-- Department Dropdown -->
        <select onchange="showFiles(this.value); updateCategory();"  id="selectCat" class="mb-4 p-2 border rounded">
            <option value="">Select a Category</option>
            @foreach ($departments as $department)
                <option value="{{ $department }}" {{ $department === 'OPD Guidelines' ? 'selected' : '' }}>{{ $department }}</option>
            @endforeach
        </select>

        <!-- Files Display Section -->
        <!-- Files Display Section -->
<div>
    @foreach ($filesByDepartment as $department => $files)
        <div id="{{ $department }}" class="department-files" style="display: none;">
            <h2 class="font-extrabold mb-2  text-2xl" style="color:#159ed5;">{{ $department }}</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @php
                    $files = $files->sortBy('name')->values();
                    $totalFiles = $files->count();
                    $filesPerColumn = ceil($totalFiles / 3);
                @endphp

                <!-- Loop for each column -->
                @for ($column = 0; $column < 3; $column++)
                    <div class="file-column">
                        @php
                            $start = $column * $filesPerColumn;
                            $end = min($start + $filesPerColumn, $totalFiles);
                        @endphp

                        <!-- Loop through the files in the current column range -->
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
	</div>
    @endforeach
</div>


    <script>
        // Show files for OPD Guidelines department initially
        document.getElementById('OPD Guidelines').style.display = 'block'; // Change 'OPD Guidelines' to match your department ID

	function updateCategory(){
		var selectCatObj = document.getElementById("selectCat");
		document.getElementById("head-text-1").innerHTML =selectCatObj.value;
	}


</script>
</body>
</html>

