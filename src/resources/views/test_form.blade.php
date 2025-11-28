<!-- resources/views/test_form.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Form Test</title>
</head>
<body>
<form action="/test-form" method="post">
    @csrf
    <input type="text" name="post_code" value="123-4567">
    <input type="text" name="address" value="東京都港区">
    <input type="text" name="building" value="サンプルマンション">
    <button type="submit">更新</button>
</form>
</body>
</html>
