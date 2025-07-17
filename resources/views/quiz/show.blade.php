// filepath: resources/views/quiz/show.blade.php
@extends('layout')
@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">{{ $quiz->title }}</h2>
        <form method="POST" action="{{ route('quiz.submit', $quiz) }}">
            @csrf
            @foreach ($quiz->questions as $i => $question)
                <div class="mb-3">
                    <strong>{{ $i + 1 }}. {{ $question->question }}</strong>
                    @foreach ($question->choices as $choice)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]"
                                value="{{ $choice->id }}" id="choice{{ $choice->id }}">
                            <label class="form-check-label" for="choice{{ $choice->id }}">
                                {{ $choice->choice }}
                            </label>
                        </div>
                    @endforeach
                </div>
            @endforeach
            <button type="submit" class="btn btn-success">Kirim Jawaban</button>
        </form>
    </div>
@endsection
