@extends('layout')
@section('content')
    <div class="container mt-5">
        <div class="mb-4 text-center">
            <h2 class="fw-bold">{{ $quiz->title }}</h2>
            <p class="text-muted">{{ $quiz->description }}</p>
        </div>

        <form method="POST" action="{{ route('quiz.submit', $quiz) }}">
            @csrf

            @forelse ($quiz->questions as $i => $question)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <strong>Soal {{ $i + 1 }}</strong>
                    </div>
                    <div class="card-body">
                        <p class="mb-3 fw-semibold">{{ $question->question }}</p>

                        @foreach ($question->choices as $choice)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]"
                                    value="{{ $choice->id }}" id="choice{{ $choice->id }}">
                                <label class="form-check-label" for="choice{{ $choice->id }}">
                                    {{ $choice->choice }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-circle-fill"></i> Belum ada soal untuk quiz ini.
                </div>
            @endforelse

            <div class="text-center">
                <button type="submit" class="btn btn-lg btn-success px-5 mt-3">
                    <i class="bi bi-send-check-fill"></i> Kirim Jawaban
                </button>
            </div>
        </form>
    </div>
@endsection
