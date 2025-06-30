@extends('layout')
@section('content')
    <div class="container">
        <h2 class="mb-4">Dashboard Admin</h2>

        <h4>Daftar Cerita</h4>
        <table class="table table-bordered">
            <thead>
                <tr>Judul</tr>
                <tr>Penulis</tr>
                <tr>Dibuat</tr>
                <tr>Aksi</tr>
            </thead>
            <tbody>
                @foreach ($stories as $story)
                    <tr>
                        <td>{{ $story->title }}</td>
                        <td>{{ $story->user->name }}</td>
                        <td>{{ $story->created_at->format('d M Y') }}</td>
                    </tr>
                    <a href="/story/{{ $story->id }}" class="btn btn-sm btn-info">Lihat</a>
                    <form action="/story/{{ $story->id }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Hapus cerita ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                @endforeach
            </tbody>
        </table>
        {{ $stories->links }}

        <hr>
        <h4>Komentar Terbaru</h4>
        <ul class="list-group">
            @foreach ($comments as $comment)
                <li class="list-group-item">
                    <strong>{{ $comment->user->name }}</strong> di <a
                        href="/story/{{ $comment->story->id }}">{{ $comment->story->title }}</a>
                    {{ $comment->comment }}
                </li>
            @endforeach
        </ul>
        {{ $comments->links() }}
    </div>
@endsection
