@extends('layout')
@section('content')
    <div class="container">
        <h2 class="mb-4">Dashboard Admin</h2>

        {{-- Notifikasi Flash Message --}}
        @if (session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger mb-3">{{ session('error') }}</div>
        @endif

        <h4>Daftar Cerita</h4>
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stories as $story)
                    <tr>
                        <td>{{ $story->title }}</td>
                        <td>{{ $story->user->name }}</td>
                        <td>{{ $story->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="/story/{{ $story->id }}" class="btn btn-sm btn-info">Lihat</a>
                            <form action="/story/{{ $story->id }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Hapus cerita ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $stories->links('pagination::bootstrap-5') }}

        <hr>
        <h4>Manajemen Quiz</h4>
        <a href="{{ route('quiz.create') }}" class="btn btn-success mb-3">+ Tambah Quiz</a>
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Judul Quiz</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach (\App\Models\Quiz::all() as $quiz)
                    <tr>
                        <td>{{ $quiz->title }}</td>
                        <td>{{ $quiz->description }}</td>
                        <td>
                            <form action="/admin/quiz/{{ $quiz->id }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Hapus Quiz ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <hr>
        <h4>Komentar Terbaru</h4>
        <ul class="list-group mb-4">
            @foreach ($comments as $comment)
                <li class="list-group-item">
                    <strong>{{ $comment->user->name }}</strong> di
                    <a href="/story/{{ $comment->story->id }}">{{ $comment->story->title }}</a>:
                    {{ $comment->comment }}
                </li>
            @endforeach
        </ul>
        {{ $comments->links() }}
    </div>
@endsection
