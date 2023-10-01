<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.8.2/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com" defer></script>
    <script src="https://cdn.tailwindcss.com" defer></script>
</head>
<body>
    {{-- Navbar --}}
    @include("components.nav")
    @yield("contents")
</body>
</html>