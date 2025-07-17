@extends('layout')

@section('content')
<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4 text-center">{{ $quiz->title }}</h2>

        <div class="alert alert-info text-center">
            <h4 class="mb-2">🎯 Hasil Quiz Kamu</h4>
            <p><strong>Jawaban Benar:</strong> {{ $score }} / {{ $total }}</p>
            <p><strong>Nilai Akhir:</strong> {{ round($nilai, 2) }}</p>
        </div>

        {{-- Penilaian dan motivasi --}}
        @php
            $presentase = $score / $total;
        @endphp

        @if($presentase < 0.5)
            <div class="alert alert-danger text-center">
                😓 <strong>Butuh lebih giat belajar!</strong><br>
                Jangan menyerah, teruslah mencoba dan belajar lebih rajin 💪
            </div>
        @elseif($presentase < 0.67)
            <div class="alert alert-warning text-center">
                🙂 <strong>Sudah bagus, tapi masih bisa lebih baik!</strong><br>
                Teruskan usahamu dan tingkatkan lagi semangat belajarmu 📚✨
            </div>
        @else
            <div class="alert alert-success text-center">
                🎉 <strong>Keren banget!</strong><br>
                Kamu sudah paham materi dengan baik! Tapi tetap semangat belajar ya 🚀🔥
            </div>
        @endif

        <div class="text-center mt-4">
            <a href="{{ route('quiz.index') }}" class="btn btn-primary btn-lg">
                Kembali ke Daftar Quiz
            </a>
        </div>
    </div>
</div>
@endsection
