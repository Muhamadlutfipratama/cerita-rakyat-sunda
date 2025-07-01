@extends('layout')
@section('content')
    <style>
        .story-image {
            width: 100%;
            max-height: 350px;
            object-fit: cover;
            border-radius: 12px;
            display: block;
            margin-bottom: 1.5rem;
        }

        .story-content img {
            max-width: 75%;
            max-height: 350px;
            height: auto;
            border-radius: 10px;
            display: block;
            margin: 1rem auto;
        }
    </style>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-3">{{ $story->title }}</h2>
            @if ($canEdit)
                <div class="mb-3">
                    <a href="/story/{{ $story->id }}/edit" class="btn btn-sm btn-secondary">Edit Cerita</a>
                    <form action="/story/{{ $story->id }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Yakin ingin menghapus cerita ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm     btn-danger">Hapus Cerita</button>
                    </form>
                </div>
            @endif
        </div>
        <p class="text-muted">Ditulis oleh <strong>{{ $story->user->name }}</strong></p>
        @if ($story->image)
            <img src="{{ asset('storage/' . $story->image) }}" alt="{{ $story->title }}" class="story-image mx-auto">
        @endif
        <div class="story-content">{!! $story->content !!}</div>

        {{-- Notifikasi Flash Message --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Komentar --}}
        <hr>
        <h4>Komentar</h4>
        @foreach ($story->comments as $comment)
            <div class="mb-2">
                <strong>{{ $comment->user->name }}</strong>:
                {{ $comment->comment }}
                @if (auth()->check() && (auth()->id() == $comment->user_id || auth()->user()->is_admin))
                    <form action="/comment/{{ $comment->id }}" method="POST" class="d-inline ms-2">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus komentar ini?')">Hapus</button>
                    </form>
                @endif
            </div>
        @endforeach

        @auth
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="/comment">
                @csrf
                <input type="hidden" name="story_id" value="{{ $story->id }}">
                <div class="mb-3">
                    <textarea name="comment" class="form-control" placeholder="Tulis komentar..." required></textarea>
                </div>
                <button type="submit" class="btn btn-secondary">Kirim Komentar</button>
            </form>
        @else
            <p><a href="/login">Login</a> untuk menulis komentar.</p>
        @endauth

    </div>
@endsection
