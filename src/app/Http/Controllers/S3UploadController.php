<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class S3UploadController extends Controller
{
    public function create()
    {
        return view('s3-upload');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        $path = Storage::disk('s3')->put('images', $request->file('image'));
        $url = Storage::disk('s3')->url($path);

        return back()->with('url', $url);
    }
}
