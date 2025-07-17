<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cerita Rakyat Sunda</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .main-content {
            margin-top: 3.5rem;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" style="z-index: 100;">
        <div class="container">
            <a href="/" class="navbar-brand fw-bold fs-4">Cerita Rakyat Sunda</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a href="/create" class="nav-link fs-5">Tulis Cerita</a>
                        </li>
                        @if (auth()->user()->is_admin)
                            <li class="nav-item">
                                <a href="/admin/dashboard" class="nav-link fs-5">Dashboard Admin</a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link fs-5" href="{{ route('quiz.index') }}">Quiz</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-5" href="{{ route('about') }}">About</a>
                        </li>
                        <li class="nav-item">
                            <form action="/logout" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-link nav-link fs-5" type="submit">Logout</button>
                            </form>
                        </li>
                    @else
                        <li>
                        <li class="nav-item">
                            <a class="nav-link fs-5" href="/login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-5" href="/register">Daftar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-5" href="{{ route('quiz.index') }}">Quiz</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-5" href="{{ route('about') }}">About</a>
                        </li>
                        </li>
                    @endauth
                    <li class="nav-item align-self-center">
                        <a href="https://wa.me/6285283189510?text=Halo%20Kak%2C%20saya%20ingin%20bertanya%20tentang%20website%20Cerita%20Rakyat%20Sunda%20atau%20fitur%20lainnya."
                            class="btn btn-success ms-2" target="_blank" rel="noopener">
                            <i class="bi bi-whatsapp"></i> Contact Me
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4 main-content flex-grow-1">
        @yield('content')
    </main>

    <footer class="px-5 py-4 mt-5 border-top bg-dark text-white" style="font-size: 0.95rem;">
        <div class="container d-flex justify-content-between align-items-center">
            &copy; 2025 M. Lutfi Pratama. All Rights Reserved.
            <br>
            <a href="https://wa.me/6285283189510?text=Halo%20Kak%2C%20saya%20ingin%20bertanya%20tentang%20website%20Cerita%20Rakyat%20Sunda%20atau%20fitur%20lainnya."
                class="btn btn-success btn-sm mt-2" target="_blank" rel="noopener">
                <i class="bi bi-whatsapp"></i> Contact Me
            </a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
