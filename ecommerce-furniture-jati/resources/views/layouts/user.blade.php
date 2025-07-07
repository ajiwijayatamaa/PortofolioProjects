<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title') </title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])      
    <!-- Styles / Scripts -->
    <style>
    .product-image {
        width: 100%;
        height: 200px; /* Kamu bisa atur sesuai preferensi */
        object-fit: cover;
        border-top-left-radius: 0.375rem; /* sesuai Bootstrap card */
        border-top-right-radius: 0.375rem;
    }

    .no-image {
        width: 100%;
        height: 200px;
        background-color: #f8f9fa;
        font-size: 1rem;
        border: 1px dashed #ccc;
    }

    </style>
    @yield('css')
</head>
<body class="font-sans antialiased dark:bg-black dark:text-white/50">
    
    <div class="wrapper-homepage">
        
       @include('components.header-user')
       @yield('content')
       @include('components.footer-user')
      


        
        
    </div>
    <!-- DIV KESELURUHAN -->
    
</body>
@yield('js')
</html>
