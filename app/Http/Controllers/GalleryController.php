<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;

class GalleryController extends Controller
{
    public function index()
    {
        // Fetch images from the public/images/gallery directory
        $images = File::files(public_path('images/gallery'));

        // Extract the filenames
        $imageNames = array_map(function ($image) {
            return $image->getFilename();
        }, $images);

        return view('gallery', ['images' => $imageNames]);
    }
}
