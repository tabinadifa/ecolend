@extends('layouts.layout')

@section('title', 'Daftar Alat - Peminjam')

@push('styles')
<style>
    :root {
        --forest: #f59e0b;
        --leaf: #fb923c;
        --lime: #ffd166;
        --ink: #2b2416;
        --paper: #fff8ef;
        --mist: #f7efe5;
        --edge: rgba(120, 80, 20, 0.18);
        --glow: 0 18px 35px rgba(245, 158, 11, 0.2);
        --radius-lg: 1.25rem;
        --radius-md: 0.85rem;
    }

    body {
        background: #ffffff;
        color: var(--ink);
        font-family: 'Poppins', sans-serif;
    }

    .peminjam-page {
        position: relative;
    }

    .page-hero {
        position: relative;
        padding: 1.5rem 0 1.25rem;
        margin-bottom: 1.5rem;
    }

    .page-hero::after {
        content: '';
        position: absolute;
        inset: auto 0 0 0;
        height: 2px;
        background: linear-gradient(90deg, var(--forest), rgba(245, 158, 11, 0.15));
        opacity: 0.35;
    }

    .hero-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.35rem 0.85rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        background: rgba(245, 158, 11, 0.12);
        color: var(--forest);
        border: 1px solid rgba(245, 158, 11, 0.35);
    }

    .page-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: clamp(1.8rem, 3.2vw, 2.6rem);
        color: var(--ink);
        margin: 0.35rem 0 0;
        line-height: 1.1;
    }

    .page-subtitle {
        color: rgba(43, 36, 22, 0.7);
        font-size: 0.92rem;
        margin-top: 0.35rem;
    }

    .hero-counter {
        background: #fff;
        border: 1px solid var(--edge);
        border-radius: 999px;
        padding: 0.45rem 0.9rem;
        font-size: 0.78rem;
        font-weight: 600;
        color: rgba(43, 36, 22, 0.7);
        box-shadow: var(--glow);
    }

    .filter-card,
    .catalog-card {
        border: 1px solid var(--edge);
        border-radius: var(--radius-lg);
        background: #fff;
        box-shadow: var(--glow);
    }

    .filter-card {
        position: relative;
        overflow: hidden;
    }

    .filter-card::before {
        content: '';
        position: absolute;
        inset: 0 70% 0 0;
        background: linear-gradient(135deg, rgba(251, 146, 60, 0.18), transparent 70%);
        pointer-events: none;
    }

    .filter-title {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: rgba(43, 36, 22, 0.55);
        font-weight: 700;
    }

    .form-select,
    .form-control {
        border-radius: var(--radius-md);
        border-color: rgba(120, 80, 20, 0.22);
    }

    .form-select:focus,
    .form-control:focus {
        border-color: var(--leaf);
        box-shadow: 0 0 0 0.2rem rgba(251, 146, 60, 0.18);
    }

    .alat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1.5rem;
    }

    .alat-card {
        border: 1px solid rgba(245, 158, 11, 0.16);
        border-radius: var(--radius-lg);
        overflow: hidden;
        height: 100%;
        transition: border-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        box-shadow: 0 15px 35px rgba(245, 158, 11, 0.16), 0 6px 14px rgba(0,0,0,0.08);
        background: #fff;
        display: flex;
        flex-direction: column;
    }

    .alat-card:hover {
        border-color: var(--leaf);
        transform: translateY(-4px);
        box-shadow: 0 24px 45px rgba(245, 158, 11, 0.22), 0 12px 24px rgba(0,0,0,0.1);
    }

    .alat-card-img,
    .alat-card-img-placeholder {
        width: 100%;
        height: 190px;
    }

    .alat-card-img {
        object-fit: cover;
        display: block;
    }

    .alat-card-img-placeholder {
        background: linear-gradient(135deg, #fff3df, #fff8ef);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(245, 158, 11, 0.35);
        font-size: 2.3rem;
    }

    .alat-card-body {
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        flex: 1;
        gap: 0.55rem;
    }

    .alat-title {
        font-weight: 700;
        margin: 0;
    }

    .alat-meta {
        font-size: 0.8rem;
        color: rgba(43, 36, 22, 0.55);
    }

    .stock-chip {
        font-size: 0.75rem;
        font-weight: 700;
        border-radius: 999px;
        padding: 0.35rem 0.7rem;
    }

    .stock-chip.in {
        background: rgba(255, 209, 102, 0.6);
        color: #7a4a10;
    }

    .stock-chip.out {
        background: rgba(255, 122, 122, 0.18);
        color: #7b2f2f;
    }

    .btn-pinjam {
        background: linear-gradient(135deg, var(--forest), var(--leaf));
        color: #fff;
        font-weight: 700;
        border: none;
        border-radius: 0.9rem;
        padding: 0.65rem 1rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn-pinjam:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 24px rgba(245, 158, 11, 0.22);
        color: #fff;
    }

    .btn-pinjam.disabled {
        background: #efe7dc;
        color: rgba(43, 36, 22, 0.45);
        box-shadow: none;
    }

    .catalog-card .card-footer {
        background: #fff;
        border-top: 1px solid var(--edge);
    }

    .catalog-card .pagination {
        --bs-pagination-active-bg: var(--forest);
        --bs-pagination-active-border-color: var(--forest);
        --bs-pagination-color: rgba(43, 36, 22, 0.55);
        --bs-pagination-hover-color: var(--forest);
        --bs-pagination-hover-bg: rgba(251, 146, 60, 0.12);
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(14px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .page-hero,
    .filter-card,
    .catalog-card {
        animation: fadeUp 0.45s ease both;
    }

    .alat-card {
        animation: fadeUp 0.45s ease both;
    }

    @media (max-width: 768px) {
        .page-hero {
            padding-top: 0.5rem;
        }

        .hero-counter {
            align-self: flex-start;
        }
    }
</style>
@endpush

@section('content')
<div class="peminjam-page">
    <div class="page-hero d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <span class="hero-tag"><i class="bi bi-tools"></i> Peminjaman Alat</span>
            <h1 class="page-title">Daftar Alat</h1>
        </div>
        <div class="hero-counter">
            <i class="bi bi-box-seam me-1"></i>
            {{ number_format($alats->total()) }} alat
        </div>
    </div>

    <div class="card filter-card mb-4">
        <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label for="per_page" class="form-label filter-title">Per Halaman</label>
                <select id="per_page" name="per_page" class="form-select" onchange="this.form.submit()">
                    @foreach ([5, 10, 25, 50] as $option)
                        <option value="{{ $option }}" @selected((int) request('per_page', 10) === $option)>
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="kategori" class="form-label filter-title">Kategori</label>
                <select id="kategori" name="kategori" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoriAlats as $kategori)
                        <option value="{{ $kategori->id }}" @selected((int) request('kategori') === $kategori->id)>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="search" class="form-label filter-title">Cari Alat</label>
                <input type="text" id="search" name="search" class="form-control"
                    placeholder="Masukkan nama alat yang ingin dipinjam"
                    value="{{ request('search') }}"
                    onkeydown="if(event.key==='Enter'){this.form.submit()}">
            </div>
        </form>
        </div>
    </div>

    <div class="card catalog-card">
        <div class="card-body">
        @if ($alats->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox mb-2" style="font-size: 2rem;"></i>
                <p class="mb-0">Belum ada alat yang tersedia saat ini.</p>
            </div>
        @else
            <div class="alat-grid">
                @foreach ($alats as $alat)
                    @php
                        $isOutOfStock = (int) $alat->jumlah_stok <= 0;
                        $gambar       = $alat->gambarAlat;
                        $gambarUrl    = $gambar ? asset($gambar->file_path) : null;
                    @endphp
                    <div class="alat-card">
                            {{-- Gambar --}}
                            @if ($gambarUrl)
                                <img src="{{ $gambarUrl }}"
                                     alt="{{ $alat->nama_alat }}"
                                     class="alat-card-img">
                            @else
                                <div class="alat-card-img-placeholder">
                                    <i class="bi bi-tools"></i>
                                </div>
                            @endif

                            {{-- Body --}}
                            <div class="alat-card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h5 class="alat-title">{{ $alat->nama_alat }}</h5>
                                        <p class="alat-meta mb-0">
                                            {{ $alat->kategori?->nama_kategori ?? 'Tanpa kategori' }}
                                        </p>
                                    </div>
                                    <span class="stock-chip {{ $isOutOfStock ? 'out' : 'in' }} ms-2 flex-shrink-0">
                                        {{ $isOutOfStock ? 'Stok Habis' : 'Stok: ' . $alat->jumlah_stok }}
                                    </span>
                                </div>

                                @if ($alat->deskripsi)
                                    <p class="text-muted small mb-3" style="max-height:3.75rem;overflow:hidden;">
                                        {{ $alat->deskripsi }}
                                    </p>
                                @endif

                                <div class="mt-auto">
                                    <a href="{{ $isOutOfStock ? '#' : route('peminjam.peminjaman.create', $alat) }}"
                                        class="btn btn-pinjam w-100 {{ $isOutOfStock ? 'disabled' : '' }}">
                                        Ajukan Pinjam
                                    </a>
                                </div>
                            </div>
                    </div>
                @endforeach
            </div>
        @endif
        </div>

    @if ($alats->hasPages())
        <div class="card-footer bg-white d-flex flex-column flex-md-row justify-content-between align-items-center">
            <small class="text-muted mb-2 mb-md-0">
                Menampilkan {{ $alats->firstItem() }} - {{ $alats->lastItem() }} dari {{ $alats->total() }} alat
            </small>
            {{ $alats->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
</div>
@endsection