<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Upload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048', // Adjust file types and size as needed
            'category' => 'nullable|string|max:255',
        ]);

        // Store the file in storage/app/uploads and get the path
        $path = $request->file('file')->store('uploads');

        // Create a new upload record in the database
        Upload::create([
            'filename' => $request->file('file')->getClientOriginalName(),  // Store original file name
            'filepath' => $path,                                            // Store the file path
            'category' => $request->input('category'),
            'user_id' => Auth::id(),                                        // Link the upload to the authenticated user
        ]);

        return redirect()->route('services.uploads')->with('success', 'File uploaded successfully.');
    }
}
