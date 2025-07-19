@extends('layout')
@section('content')
    <style>
        .story-image {
            width: 100%;
            max-height: 350px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border: 1px solid #ddd;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        }

        .story-content img {
            max-width: 75%;
            max-height: 350px;
            height: auto;
            border-radius: 10px;
            display: block;
            margin: 1rem auto;
        }

        .story-meta {
            font-size: 0.95rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }

        .comment-card {
            border-left: 4px solid #dee2e6;
            background-color: #f8f9fa;
            padding: 10px 15px;
            border-radius: 0.4rem;
            margin-bottom: 0.5rem;
        }

        .comment-meta {
            font-weight: 600;
            margin-right: 5px;
        }

        .comment-form {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-top: 1rem;
        }

        .comment-form textarea {
            resize: vertical;
        }

        .pdf-alert {
            font-size: 0.92rem;
        }

        .edit-controls .btn {
            margin-left: 5px;
        }
    </style>
    <div class="container">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <h2 class="fw-bold">{{ $story->title }}</h2>
            @if ($canEdit)
                <div class="edit-controls">
                    <a href="/story/{{ $story->id }}/edit" class="btn btn-sm btn-secondary">Edit</a>
                    <form action="/story/{{ $story->id }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Yakin ingin menghapus cerita ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </div>
            @endif
        </div>
        <p class="story-meta">Ditulis oleh <strong>{{ $story->user->name }}</strong></p>
        @if ($story->image)
            <div class="text-center mb-4">
                <img src="{{ asset('storage/' . $story->image) }}" alt="{{ $story->title }}" class="story-image">
            </div>
        @endif
        <div class="story-content mb-4">{!! $story->content !!}</div>
        @if ($story->pdf)
            <div class="alert alert-warning pdf-alert d-none" id="pdf-warning">
                <strong>Perhatian:</strong> Jika PDF tidak tampil, matikan <b>AdBlock</b> atau <b>IDM</b> di system tray,
                lalu refresh halaman ini.
            </div>
            <div class="mb-4">
                <div id="pdf-viewer"
                    class="border border-2 border-dark rounded position-relative d-flex align-items-center justify-content-center"
                    style="height:800px;">
                    <!-- Floating Prev/Next -->
                    <button id="prev-page" class="btn btn-outline-dark position-absolute top-50 translate-middle-y"
                        style="left: 20px; z-index: 10;">←</button>
                    <button id="next-page" class="btn btn-outline-dark position-absolute top-50 translate-middle-y"
                        style="right: 20px; z-index: 10;">→</button>
                    <!-- Canvas akan di-inject di sini -->
                    <div id="pdf-canvas-container" class="w-100 d-flex align-items-center justify-content-center"></div>
                    <!-- Page info -->
                    <div class="position-absolute bottom-0 start-50 translate-middle-x bg-white bg-opacity-75 px-3 py-1 rounded mb-2 border border-1"
                        style="font-size: 0.95rem;">
                        Halaman <span id="page-num">1</span> / <span id="page-count">1</span>
                    </div>
                </div>
                <a href="{{ url('/pdf/' . basename($story->pdf)) }}" target="_blank"
                    class="btn btn-outline-primary mt-2">Download PDF</a>
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
            <script>
                const url = "{{ url('/pdf/' . basename($story->pdf)) }}";
                const pdfjsLib = window['pdfjsLib'];
                pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js";

                let pdfDoc = null,
                    pageNum = 1,
                    pageRendering = false,
                    pageNumPending = null;

                const container = document.getElementById('pdf-canvas-container');
                const pageNumSpan = document.getElementById('page-num');
                const pageCountSpan = document.getElementById('page-count');
                const prevBtn = document.getElementById('prev-page');
                const nextBtn = document.getElementById('next-page');

                function renderPage(num) {
                    pageRendering = true;
                    pdfDoc.getPage(num).then(function(page) {
                        const viewport = page.getViewport({
                            scale: 0.93
                        });
                        container.innerHTML = '';
                        const canvas = document.createElement('canvas');
                        const context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                        container.appendChild(canvas);

                        const renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        const renderTask = page.render(renderContext);

                        renderTask.promise.then(function() {
                            pageRendering = false;
                            pageNumSpan.textContent = num;
                            if (pageNumPending !== null) {
                                renderPage(pageNumPending);
                                pageNumPending = null;
                            }
                        });
                    });
                }

                function queueRenderPage(num) {
                    if (pageRendering) {
                        pageNumPending = num;
                    } else {
                        renderPage(num);
                    }
                }

                function onPrevPage() {
                    if (pageNum <= 1) return;
                    pageNum--;
                    queueRenderPage(pageNum);
                }

                function onNextPage() {
                    if (pageNum >= pdfDoc.numPages) return;
                    pageNum++;
                    queueRenderPage(pageNum);
                }

                prevBtn.addEventListener('click', onPrevPage);
                nextBtn.addEventListener('click', onNextPage);

                pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
                    pdfDoc = pdfDoc_;
                    pageCountSpan.textContent = pdfDoc.numPages;
                    renderPage(pageNum);
                });

                // Tampilkan alert jika PDF gagal dimuat (canvas tidak muncul)
                setTimeout(function() {
                    var canvas = document.querySelector('#pdf-canvas-container canvas');
                    if (!canvas) {
                        document.getElementById('pdf-warning').style.display = 'block';
                    }
                }, 3000);
            </script>
        @endif

        {{-- Komentar --}}
        <hr>
        <h4 class="mt-5 mb-3">Komentar</h4>
        @if ($story->comments->count())
            @foreach ($story->comments as $comment)
                <div class="comment-card">
                    <span class="comment-meta">{{ $comment->user->name }}</span>:
                    {{ $comment->comment }}
                    @if (auth()->check() && (auth()->id() == $comment->user_id || auth()->user()->is_admin))
                        <form action="/comment/{{ $comment->id }}" method="POST" class="d-inline ms-2">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Hapus komentar ini?')">Hapus</button>
                        </form>
                    @endif
                </div>
            @endforeach
        @else
            <div class="text-center text-muted">Belum ada komentar.</div>
        @endif

        @auth
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="/comment" class="comment-form">
                @csrf
                <input type="hidden" name="story_id" value="{{ $story->id }}">
                <div class="mb-3">
                    <textarea name="comment" class="form-control" placeholder="Tulis komentar..." rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-dark">Kirim Komentar</button>
            </form>
        @else
            <p><a href="/login">Login</a> untuk menulis komentar.</p>
        @endauth

    </div>
@endsection
