<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>S3 画像アップロードテスト</title>
</head>
<body>
    <h1>S3 画像アップロードテスト</h1>

    @if (session('url'))
        <p>アップロード成功しました。</p>
        <img src="{{ session('url') }}" alt="uploaded image" style="max-width: 400px;">
        <p>URL: {{ session('url') }}</p>
    @endif

    @if (isset($errors) && $errors->any())
        <p style="color:red;">{{ $errors->first() }}</p>
    @endif

    <form action="/s3-upload" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="image">
        <button type="submit">アップロード</button>
    </form>
</body>
</html>
