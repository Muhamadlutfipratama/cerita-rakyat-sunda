@extends('layout')
@section('content')
<div class="container mt-5" style="max-width: 750px;">
    <div class="card shadow-lg border-0">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <img src="{{ asset('profile.jpg') }}"
                    alt="M. Lutfi Pratama" class="rounded-circle mb-2 shadow object-fit-cover" width="80" height="80">
                <h3 class="fw-bold mb-0">M. Lutfi Pratama</h3>
                <span class="text-muted">Web Developer</span>
            </div>
            <hr>
            <h2 class="mb-3 text-center">Tentang Aplikasi</h2>
            <p class="fs-5 text-justify">
                <strong>Cerita Rakyat Sunda</strong> adalah aplikasi web yang bertujuan untuk melestarikan dan membagikan cerita-cerita rakyat Sunda secara digital. Melalui platform ini, masyarakat dapat membaca, menulis, dan berbagi kisah-kisah inspiratif yang menjadi bagian dari kekayaan budaya lokal.
            </p>
            <ul class="list-group list-group-flush mb-4">
                <li class="list-group-item bg-transparent"><i class="bi bi-check-circle-fill text-success me-2"></i>Berbagi cerita rakyat Sunda secara mudah</li>
                <li class="list-group-item bg-transparent"><i class="bi bi-check-circle-fill text-success me-2"></i>Mendukung pelestarian budaya lokal</li>
                <li class="list-group-item bg-transparent"><i class="bi bi-check-circle-fill text-success me-2"></i>Fitur interaktif: komentar, unggah gambar &amp; PDF</li>
                <li class="list-group-item bg-transparent"><i class="bi bi-check-circle-fill text-success me-2"></i>Gratis &amp; terbuka untuk umum</li>
            </ul>
            <div class="mb-4">
                <h5 class="fw-semibold">Tentang Pembuat</h5>
                <p class="mb-2">
                    Saya, <strong>M. Lutfi Pratama</strong>, adalah pengembang aplikasi ini. Saya memiliki minat besar pada teknologi, budaya, dan pendidikan. Aplikasi ini merupakan kontribusi kecil saya untuk memperkenalkan dan melestarikan cerita rakyat Sunda kepada generasi muda.
                </p>
                <p class="mb-0">
                    Jika Anda memiliki pertanyaan, kritik, atau saran, jangan ragu untuk menghubungi saya melalui WhatsApp:
                </p>
            </div>
            <div class="text-center">
                <a href="https://wa.me/6285283189510?text=Halo%20Kak%20Lutfi%2C%20saya%20ingin%20bertanya%20tentang%20website%20Cerita%20Rakyat%20Sunda%20atau%20fitur%20lainnya."
                    class="btn btn-success btn-lg px-4 shadow-sm" target="_blank" rel="noopener">
                    <i class="bi bi-whatsapp"></i> Hubungi via WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
