<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>決済キャンセル</title>
</head>
<body>
    <h1>お支払いがキャンセルされました</h1>

    @error('payment')
        <p style="color: red;">{{ $message }}</p>
    @enderror
</body>
</html>