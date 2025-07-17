// filepath: resources/views/quiz/index.blade.php
@extends('layout')
@section('content')
    <div class="container mt-4 h-80">
        <h2 class="mb-4">Daftar Quiz</h2>
        <div class="row">
            @forelse ($quizzes as $quiz)
                <div class="col-md-6 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5>{{ $quiz->title }}</h5>
                            <p>{{ $quiz->description }}</p>
                            <a href="{{ route('quiz.show', $quiz) }}" class="btn btn-primary">Mulai Quiz</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        Belum ada kuis tersedia.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
