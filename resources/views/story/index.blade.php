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
            max-width: 600px;
            margin: 0 auto;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .hero-search .input-group {
            max-width: 500px;
            margin: 0 auto;
            flex-wrap: nowrap;
        }

        .card {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .card-img-top {
            height: 180px;
            object-fit: cover;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .no-story {
            font-size: 1.2rem;
            font-weight: 500;
            padding: 2rem;
            text-align: center;
            color: #888;
        }

        .btn-reset {
            margin-left: 8px;
        }
    </style>

    <div class="container">
        <div class="hero-section mt-3 mb-4">
            <div class="hero-content">
                <h2 class="hero-title">📚 Cerita Rakyat Sunda</h2>
                <form method="GET" action="/" class="hero-search d-flex justify-content-center">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari cerita..."
                            value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
                        @if(request('search'))
                            <a href="{{ url('/') }}" class="btn btn-danger btn-reset"><i class="bi bi-x-circle"></i></a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <h4 class="mb-4 text-primary fw-semibold">🌟 Daftar Cerita</h4>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            @forelse ($stories as $story)
                <div class="col">
                    <div class="card h-100 shadow-sm border" onclick="window.location.href='{{ url('/story/' . $story->id) }}'">
                        @if ($story->image)
                            <img src="{{ asset('storage/' . $story->image) }}" class="card-img-top" alt="{{ $story->title }}">
                        @else
                            <img src="https://source.unsplash.com/600x400/?book,story" class="card-img-top" alt="Story">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $story->title }}</h5>
                            <p class="card-text">{{ Str::limit(strip_tags($story->content), 100) }}</p>
                            <a href="{{ url('/story/' . $story->id) }}" class="btn btn-primary mt-auto">
                                <i class="bi bi-book"></i> Baca Selengkapnya
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 w-100">
                    <div class="no-story">
                        <i class="bi bi-exclamation-circle"></i> Tidak ada cerita ditemukan.
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $stories->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
        </div>
    </div>

@endsection
