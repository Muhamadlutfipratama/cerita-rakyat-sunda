@extends('layout')
@section('content')
    <div class="container mt-5">
        <h2 class="text-center fw-bold mb-4">📝 Daftar Quiz Menarik</h2>

        <div class="row">
            @forelse ($quizzes as $quiz)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 border-1 shadow-sm quiz-card">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-primary">{{ $quiz->title }}</h5>
                            <p class="card-text flex-grow-1">{{ $quiz->description }}</p>
                            <a href="{{ route('quiz.show', $quiz) }}" class="btn btn-primary mt-3">
                                <i class="bi bi-play-circle-fill"></i> Mulai Quiz
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle-fill"></i> Belum ada quiz tersedia saat ini.
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <style>
        .quiz-card:hover {
            transform: translateY(-4px);
            transition: 0.3s ease-in-out;
        }
    </style>
@endsection
