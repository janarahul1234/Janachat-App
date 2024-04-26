<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ======== Tailwindcss ======== -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- ======== REMIXICONS ======== -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />

    <!-- ======== CSS ======== -->
    {{ $css }}
    <link rel="stylesheet" href="{{ @asset('assets/css/style.css') }}">

    <!-- ======== TITLE ======== -->
    <title>{{ $title }}</title>
</head>

<body>
    {{ $slot }}

    <!-- ======== JQUERY ======== -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- ======== JS ======== -->
    {{ $js }}
</body>

</html>