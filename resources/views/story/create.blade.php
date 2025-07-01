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
                <input type="file" name="image" class="form-control mb-3" id="imageInput" accept="image/*" required>
                <img id="imagePreview" class="img-fluid-mt-2 rounded-2" width="200" style="display;none;">
            </div>
            <div class="mb-3">
                <label class="form-label">Isi Cerita</label>
                <textarea name="content" id="editor" class="form-control" rows="10">{{ old('content', $story->content ?? '') }}</textarea>
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
                const preview = document.getElementById('imagePreview');
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            }
        });
    </script>
@endsection
