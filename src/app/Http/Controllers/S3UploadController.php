<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;  // ★追加（ランダムなファイル名を作るため）

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

        $file = $request->file('image');

        // ★① アップロード画像を読み込む（JPEG・PNGなどは自動で判別される）
        $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
        imagepalettetotruecolor($image);  // 色数の少ないPNGも変換できるようにする

        // ★② WebP（画質80）に変換して、結果を変数に受け取る
        ob_start();
        imagewebp($image, null, 80);
        $webp = ob_get_clean();
        imagedestroy($image);

        // ★③ 拡張子を .webp にしてS3に保存
        $path = 'images/' . Str::uuid() . '.webp';
        Storage::disk('s3')->put($path, $webp, ['ContentType' => 'image/webp']);

        // CloudFront経由のURLを取得（AWS_URLを設定したので、自動でCloudFrontの住所になる）
        $url = Storage::disk('s3')->url($path);

        return back()->with('url', $url);
    }
}
