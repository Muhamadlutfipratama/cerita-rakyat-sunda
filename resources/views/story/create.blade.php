@extends('layout')
@section('content')
    <style>
        .ck-editor__editable {
            min-height: 250px;
        }
    </style>
    <div class="container">
        <h2 class="mb-4">Tulis Cerita Baru</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="/story" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Judul Cerita</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Gambar Utama</label>
                <input type="file" name="image" class="form-control" id="imageInput" accept="image/*" required>
                <img id="imagePreview" class="img-fluid-mt-2 mt-3 rounded-2 " width="200" style="display:none;">
            </div>
            <div class="mb-3">
                <label class="form-label">Isi Cerita</label>
                <textarea name="content" id="editor" class="form-control" rows="10">{{ old('content', $story->content ?? '') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="mb-3">Upload PDF (opsional)</label>
                <input type="file" name="pdf" class="form-control" accept="application/pdf">
            </div>
            <button type="submit" class="btn btn-success">Simpan Cerita</button>
        </form>
    </div>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'), {
                ckfinder: {
                    uploadUrl: '/upload-image?_token={{ csrf_token() }}'
                }
            })
            .catch(error => {
                console.error(error);
            });

        document.getElementById('imageInput').addEventListener('change', function(e) {
            const [file] = this.files;
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran gambar tidak boleh lebih dari 5 MB.');
                    this.value = '';
                    document.getElementById('imagePreview').style.display = 'none';
                    return;
                }
                const preview = document.getElementById('imagePreview');
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            }
        });
    </script>
@endsection
