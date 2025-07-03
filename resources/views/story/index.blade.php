@extends('layout')
@section('content')
    <style>
        .hero-section {
            position: relative;
            background: linear-gradient(rgba(30, 30, 30, 0.5), rgba(30, 30, 30, 0.5)), url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=900&q=80') center/cover no-repeat;
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            border-radius: 12px;
        }

        .hero-content {
            color: white;
            text-align: center;
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }

        .hero-title {
            font-size: 2.2rem;
            font-weight: bold;
            margin-bottom: 1.2rem;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
        }

        .hero-search .input-group {
            max-width: 400px;
            margin: 0 auto;
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .card {
            transition: transform 0.2s cubic-bezier(.4, 2, .6, 1), box-shadow 0.2s;
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-2px) scale(1.01);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }
    </style>
    <div class="container">
        <div class="hero-section mt-3 mb-4">
            <div class="hero-content">
                <h2 class="hero-title">Cerita Rakyat Sunda</h2>
                <form method="GET" action="/" class="hero-search mb-0">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari cerita..."
                            value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Notifikasi Flash Message --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <h2 class="mb-4">Daftar Cerita</h2>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            @forelse ($stories as $story)
                <div class="col">
                    <div class="card h-100 shadow-sm" onclick="window.location.href='{{ url('/story/' . $story->id) }}'">
                        @if ($story->image)
                            <img src="{{ asset('storage/' . $story->image) }}" class="card-img-top"
                                alt="{{ $story->title }}">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $story->title }}</h5>
                            <p class="card-text">{{ Str::limit(strip_tags($story->content), 100) }}</p>
                            <a href="/story/{{ $story->id }}" class="btn btn-primary mt-auto">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center w-100 h-dvh">Tidak ada cerita ditemukan.</p>
            @endforelse
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $stories->appends(['search' => request('search')])->links() }}
        </div>
    </div>
@endsection
