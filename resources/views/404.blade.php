<!-- resources/views/errors/404.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Page Non Trouvée</title>
</head>
<body>
    <h1>404 - Page Non Trouvée</h1>
    <p>{{ $exception->getMessage() }}</p>
</body>
</html>
