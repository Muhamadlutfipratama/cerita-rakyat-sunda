<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cerita Rakyat Sunda</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .main-content {
            margin-top: 3.5rem;
        }
    </style>
</head>

<body>
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
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4 main-content">
        @yield('content')
    </main>

    <footer class="text-center py-4 mt-5 border-top bg-light text-muted" style="font-size: 0.95rem;">
        &copy; 2025 M. Lutfi Pratama. All Rights Reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
