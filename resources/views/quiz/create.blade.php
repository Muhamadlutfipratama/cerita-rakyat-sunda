@extends('layout')
@section('content')
<div class="container">
    <h2 class="mb-4">Tambah Quiz Baru</h2>
    <form action="{{ route('quiz.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Judul Quiz</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <hr>
        <h5>Pertanyaan & Pilihan</h5>
        <div id="questions-container"></div>
        <button type="button" class="btn btn-secondary mb-3" onclick="addQuestion()">+ Tambah Pertanyaan</button>
        <br>
        <button type="submit" class="btn btn-success">Simpan Quiz</button>
    </form>
</div>
<script>
let qIndex = 0;
function addQuestion() {
    const container = document.getElementById('questions-container');
    const html = `
    <div class="card mb-3 p-3">
        <div class="mb-3">
            <label class="mb-2">Pertanyaan</label>
            <input type="text" name="questions[${qIndex}][question]" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="mb-2">Pilihan</label>
            <div class="mb-2">
                <input type="text" name="questions[${qIndex}][choices][0][choice]" class="form-control d-inline w-75" required placeholder="Pilihan 1">
                <input type="radio" name="questions[${qIndex}][correct]" value="0" required> Benar
            </div>
            <div class="mb-2">
                <input type="text" name="questions[${qIndex}][choices][1][choice]" class="form-control d-inline w-75" required placeholder="Pilihan 2">
                <input type="radio" name="questions[${qIndex}][correct]" value="1"> Benar
            </div>
            <div class="mb-2">
                <input type="text" name="questions[${qIndex}][choices][2][choice]" class="form-control d-inline w-75" placeholder="Pilihan 3">
                <input type="radio" name="questions[${qIndex}][correct]" value="2"> Benar
            </div>
            <div>
                <input type="text" name="questions[${qIndex}][choices][3][choice]" class="form-control d-inline w-75" placeholder="Pilihan 4">
                <input type="radio" name="questions[${qIndex}][correct]" value="3"> Benar
            </div>
        </div>
    </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    qIndex++;
}
</script>
@endsection
