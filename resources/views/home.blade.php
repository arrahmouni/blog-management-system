<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @auth
            <meta name="user-id" content="{{ request()->user()->id }}">
        @endauth
        
        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
        <link rel="shortcut icon" href="{{ asset('images/default/favicon.png') }}">

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f9fafb;
            }

            .hero-gradient {
                background: linear-gradient(120deg, #4f46e5, #7c3aed);
            }

            .card-hover {
                transition: all 0.3s ease;
            }

            .card-hover:hover {
                transform: translateY(-5px);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }

            .category-item {
                transition: all 0.2s ease;
            }

            .category-item:hover {
                background-color: #9794ca;
                color: white;
            }

            .author-badge {
                position: absolute;
                top: -12px;
                right: 20px;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            .post-card {
                border-radius: 12px;
                overflow: hidden;
                transition: all 0.3s ease;
            }

            .post-card:hover {
                transform: translateY(-5px);
            }

            .post-image {
                height: 200px;
                transition: transform 0.5s ease;
            }

            .post-card:hover .post-image {
                transform: scale(1.05);
            }

            .footer-links a {
                position: relative;
                padding-bottom: 2px;
            }

            .footer-links a:after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                width: 0;
                height: 2px;
                background-color: white;
                transition: width 0.3s ease;
            }

            .footer-links a:hover:after {
                width: 100%;
            }

            .navbar {
                backdrop-filter: blur(10px);
                background-color: rgba(255, 255, 255, 0.85);
            }

            .post-tag {
                transition: all 0.2s ease;
            }

            .post-tag:hover {
                background-color: #4f46e5;
                color: white;
            }
        </style>
        @vite('resources/css/app.css')
    </head>
    <body id="app">
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        @vite('resources/js/frontend/app.js')
    </body>
</html>
