@extends('layout')
@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center">📊 Dashboard Admin</h2>

    {{-- Flash Message --}}
    @if (session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger mb-3">{{ session('error') }}</div>
    @endif

    {{-- === Cerita Section === --}}
    <h4 class="mt-4">📖 Daftar Cerita</h4>
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stories as $story)
                    <tr>
                        <td>{{ $story->title }}</td>
                        <td>{{ $story->user->name }}</td>
                        <td>{{ $story->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="/story/{{ $story->id }}" class="btn btn-sm btn-info me-1">
                                👁️ Lihat
                            </a>
                            <form action="/story/{{ $story->id }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus cerita ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">🗑️ Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">🚫 Belum ada cerita yang ditambahkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $stories->links('pagination::bootstrap-5') }}
    </div>

    <hr>

    {{-- === Quiz Section === --}}
    <h4 class="mt-4">📝 Manajemen Quiz</h4>
    <a href="{{ route('quiz.create') }}" class="btn btn-success mb-3">➕ Tambah Quiz</a>
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Judul Quiz</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($quizzes as $quiz)
                    <tr>
                        <td>{{ $quiz->title }}</td>
                        <td>{{ $quiz->description }}</td>
                        <td>
                            <form action="/admin/quiz/{{ $quiz->id }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus quiz ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">🗑️ Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">📚 Belum ada quiz yang tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $quizzes->links('pagination::bootstrap-5') }}
    </div>

    <hr>

    {{-- === Komentar Section === --}}
    <h4 class="mt-4">💬 Komentar Terbaru</h4>
    <ul class="list-group mb-4">
        @forelse ($comments as $comment)
            <li class="list-group-item">
                <strong>{{ $comment->user->name }}</strong> berkomentar di
                <a href="/story/{{ $comment->story->id }}">{{ $comment->story->title }}</a>:<br>
                {{ $comment->comment }}
            </li>
        @empty
            <li class="list-group-item text-muted text-center">🗨️ Belum ada komentar baru.</li>
        @endforelse
    </ul>
    {{ $comments->links('pagination::bootstrap-5') }}
</div>
@endsection
