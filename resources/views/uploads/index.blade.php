@extends('layouts.auth')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-4xl font-bold text-center text-[#159ed5] mb-8">Uploads</h1>

    <!-- Upload Form (available for all users for demonstration) -->
    <div class="mb-8">
        <form action="{{ route('uploads.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            <div class="mb-4">
                <label for="file" class="block text-lg font-semibold text-gray-700">Select File:</label>
                <input type="file" name="file" id="file" class="mt-2 p-2 border border-gray-300 rounded w-full" required>
            </div>
            <div class="mb-4">
                <label for="category" class="block text-lg font-semibold text-gray-700">Category:</label>
                <input type="text" name="category" id="category" placeholder="Category (e.g., Report, Prescription)" class="mt-2 p-2 border border-gray-300 rounded w-full">
            </div>
            
	   <button type="submit" style="background-color: #159ed5;" class="text-white py-2 px-4 rounded hover:bg-blue-700">
    		Upload File
	   </button>
	
        </form>
    </div>

    <!-- Check if there are any uploads available -->
    @if($uploads->isEmpty())
        <p class="text-center text-gray-600">No files uploaded yet.</p>
    @else
        <!-- Table to display the uploaded files -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-lg rounded-lg">
                <thead>
                    <tr>
                        <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">File Name</th>
                        <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Category</th>
                        <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Uploaded Date</th>
                        <th class="py-2 px-4 bg-gray-200 text-gray-600 font-semibold text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($uploads as $upload)
                    <tr>
                        <td class="border-t py-2 px-4">{{ $upload->filename }}</td>
                        <td class="border-t py-2 px-4">{{ $upload->category ?? 'N/A' }}</td>
			<td class="border-t py-2 px-4">{{ \Carbon\Carbon::parse($upload->created_at)->format('Y-m-d') }}</td>
                        <td class="border-t py-2 px-4">
    @if($upload->filepath)
        <a href="{{ asset($upload->filepath) }}" target="_blank">View File</a>
    @else
        N/A
    @endif
</td>

                       
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
