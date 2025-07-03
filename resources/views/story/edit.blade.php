@extends('layout')
@section('content')
    <style>
        .ck-editor__editable {
            min-height: 250px;
        }
    </style>
    <div class="container">
        <h2 class="mb-4">Edit Cerita</h2>
        <form action="/story/{{ $story->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Judul Cerita</label>
                <input type="text" name="title" class="form-control" value="{{ $story->title }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Ganti Gambar</label>
                <input type="file" name="image" class="form-control mb-3" id="imageInput" accept="image/*">
                @if ($story->image)
                    <img id="imagePreview" src="{{ asset('storage/' . $story->image) }}" class="img-fluid mt-2 rounded-2"
                        width="200">
                @else
                    <img id="imagePreview" class="img-fluid-mt-2 rounded-2" width="200" style="display:none;">
                @endif
            </div>
            <div class="mb-3">
                <label class="form-label">Isi Cerita</label>
                <textarea name="content" id="editor" class="form-control" rows="10">{{ old('content', $story->content ?? '') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="mb-3">Upload PDF (opsional)</label>
                <input type="file" name="pdf" class="form-control" accept="application/pdf">
            </div>
            <button type="submit" class="btn btn-success">Update Cerita</button>
            <a href="{{ url('/story/' . $story->id) }}" class="btn btn-danger ms-2"
                onclick="return confirm('Batal update cerita?');">Batal</a>
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
