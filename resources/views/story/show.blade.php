@extends('layout')
@section('content')
    <style>
        .story-image {
            width: 100%;
            max-height: 350px;
            object-fit: cover;
            border-radius: 12px;
            display: block;
            margin-bottom: 1.5rem;
        }

        .story-content img {
            max-width: 75%;
            max-height: 350px;
            height: auto;
            border-radius: 10px;
            display: block;
            margin: 1rem auto;
        }

        .pdf-float-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            opacity: 0.85;
            background: rgba(33, 37, 41, 0.7);
            color: #fff;
            border: none;
            padding: 8px 14px;
            border-radius: 50%;
            transition: background 0.2s;
        }

        .pdf-float-btn:hover {
            background: rgba(33, 37, 41, 0.95);
            color: #fff;
        }

        #prev-page {
            left: 10px;
        }

        #next-page {
            right: 10px;
        }
    </style>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-3">{{ $story->title }}</h2>
            @if ($canEdit)
                <div class="mb-3">
                    <a href="/story/{{ $story->id }}/edit" class="btn btn-sm btn-secondary">Edit Cerita</a>
                    <form action="/story/{{ $story->id }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Yakin ingin menghapus cerita ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Hapus Cerita</button>
                    </form>
                </div>
            @endif
        </div>
        <p class="text-muted">Ditulis oleh <strong>{{ $story->user->name }}</strong></p>
        @if ($story->image)
            <div class="d-flex justify-content-center">
                <img src="{{ asset('storage/' . $story->image) }}" alt="{{ $story->title }}"
                    class="story-image mx-auto border border-dark rounded">
            </div>
        @endif
        <div class="story-content mb-4">{!! $story->content !!}</div>
        @if ($story->pdf)
            <div class="alert alert-warning mb-3" id="pdf-warning" style="display:none;">
                <strong>Perhatian:</strong> Jika PDF tidak tampil, silakan <b>matikan AdBlock</b> atau ekstensi download
                manager (seperti IDM) dan exit IDM juga di bagian system tray yang berada dalam ikon panah pada menu, setelah itu refresh halaman ini agar cerita dapat dibaca dengan lancar.
            </div>
            <div class="mb-4">
                <div id="pdf-viewer"
                    class="border rounded position-relative d-flex align-items-center justify-content-center"
                    style="height:600px;">
                    <!-- Floating Prev/Next -->
                    <button id="prev-page" class="btn pdf-float-btn" style="left:10px;">&#8592;</button>
                    <button id="next-page" class="btn pdf-float-btn" style="right:10px;">&#8594;</button>
                    <!-- Canvas akan di-inject di sini -->
                    <div id="pdf-canvas-container" class="w-100 d-flex align-items-center justify-content-center"></div>
                    <!-- Page info -->
                    <div class="position-absolute bottom-0 start-50 translate-middle-x bg-white bg-opacity-75 px-3 py-1 rounded mb-2"
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
        <h4>Komentar</h4>
        @foreach ($story->comments as $comment)
            <div class="mb-2">
                <strong>{{ $comment->user->name }}</strong>:
                {{ $comment->comment }}
                @if (auth()->check() && (auth()->id() == $comment->user_id || auth()->user()->is_admin))
                    <form action="/comment/{{ $comment->id }}" method="POST" class="d-inline ms-2">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus komentar ini?')">Hapus</button>
                    </form>
                @endif
            </div>
        @endforeach

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
            <form method="POST" action="/comment">
                @csrf
                <input type="hidden" name="story_id" value="{{ $story->id }}">
                <div class="mb-3">
                    <textarea name="comment" class="form-control" placeholder="Tulis komentar..." required></textarea>
                </div>
                <button type="submit" class="btn btn-secondary">Kirim Komentar</button>
            </form>
        @else
            <p><a href="/login">Login</a> untuk menulis komentar.</p>
        @endauth

    </div>
@endsection
