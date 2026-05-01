@extends('layouts.layout')

@section('title', 'Detail Peminjaman - EcoLend')

@section('content')
@php
    $statusLabels = [
        'pending' => 'Menunggu Persetujuan',
        'approve' => 'Disetujui',
        'rejected' => 'Ditolak',
        'returned' => 'Dikembalikan',
    ];
@endphp

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Detail Peminjaman</h2>
    </div>

    <div class="row">
        {{-- Informasi Peminjaman --}}
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Informasi Peminjaman</h5>

                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="35%" class="text-muted">Status</th>
                            <td>
                                @php
                                    $badge = match ($peminjaman->status) {
                                        'approve' => 'success',
                                        'rejected' => 'danger',
                                        'pending' => 'warning',
                                        'returned' => 'primary',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badge }}">
                                    {{ $statusLabels[$peminjaman->status] ?? ucfirst($peminjaman->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Tanggal Pinjam</th>
                            <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Tanggal Kembali</th>
                            <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Tujuan</th>
                            <td>{{ $peminjaman->tujuan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Total Alat</th>
                            <td>{{ $peminjaman->total_alat }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Alasan Ditolak --}}
            @if ($peminjaman->status === 'rejected')
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <h5 class="fw-semibold text-danger mb-2">Alasan Penolakan</h5>
                        <p class="mb-0">
                            {{ $peminjaman->alasan_ditolak ?? '-' }}
                        </p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Informasi Peminjam & Alat --}}
        <div class="col-md-5">
            {{-- Peminjam --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Peminjam</h5>

                    <p class="mb-1">
                        <strong>{{ optional($peminjaman->peminjam)->name ?? '-' }}</strong>
                    </p>
                    <p class="mb-1 text-muted">
                        Username: {{ optional($peminjaman->peminjam)->username ?? '-' }}
                    </p>
                    <p class="mb-0 text-muted">
                        Email: {{ optional($peminjaman->peminjam)->email ?? '-' }}
                    </p>
                    <p class="mb-0 text-muted">
                        NPM: {{ optional($peminjaman->peminjam)->npm ?? '-' }}
                    </p>
                    <p class="mb-0 text-muted">
                        Program Studi: {{ optional($peminjaman->peminjam)->program_studi ?? '-' }}
                    </p>
                    <p class="mb-0 text-muted">
                        No. Telepon: {{ optional($peminjaman->peminjam)->no_telp ?? '-' }}
                    </p>
                </div>
            </div>

            {{-- Alat --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Alat Dipinjam</h5>

                    <p class="mb-1">
                        <strong>{{ optional($peminjaman->alat)->nama_alat ?? '-' }}</strong>
                    </p>
                    <p class="mb-1 text-muted">
                        Deskripsi: {{ optional($peminjaman->alat)->deskripsi ?? '-' }}
                    </p>
                    <p class="mb-1 text-muted">
                        Stok Saat Ini: {{ optional($peminjaman->alat)->jumlah_stok ?? '-' }}
                    </p>

                    <p class="mb-0 text-muted">
                        Kategori: {{ optional(optional($peminjaman->alat)->kategori)->nama_kategori ?? '-' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Informasi Pengembalian --}}
    <div class="card border-0 shadow-sm rounded-4 mt-4">
        <div class="card-body">
            <h5 class="fw-semibold mb-3">Informasi Pengembalian</h5>

            @if ($peminjaman->pengembalian)
                <table class="table table-borderless mb-0">
                    <tr>
                        <th width="30%" class="text-muted">Tanggal Pengembalian</th>
                        <td>
                            {{ $peminjaman->pengembalian->tanggal_pengembalian
                                ? \Carbon\Carbon::parse($peminjaman->pengembalian->tanggal_pengembalian)->format('d M Y')
                                : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Kondisi Alat</th>
                        <td>
                            {{ $peminjaman->pengembalian->kondisi_alat
                                ? ucwords(str_replace('_', ' ', $peminjaman->pengembalian->kondisi_alat))
                                : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Status</th>
                        <td>
                            {{ $peminjaman->pengembalian->status
                                ? ucwords(str_replace('_', ' ', $peminjaman->pengembalian->status))
                                : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Denda</th>
                        <td>
                            {{ $peminjaman->pengembalian->denda !== null ? 'Rp ' . number_format($peminjaman->pengembalian->denda) : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Metode Pembayaran</th>
                        <td>
                            {{ $peminjaman->pengembalian->metode_pembayaran
                                ? ucwords(str_replace('_', ' ', $peminjaman->pengembalian->metode_pembayaran))
                                : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Catatan</th>
                        <td>{{ $peminjaman->pengembalian->catatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Bukti</th>
                        <td>{{ $peminjaman->pengembalian->fileBuktiPengembalian->file_name ?? '-' }}</td>
                    </tr>
                </table>
            @else
                <p class="mb-0 text-muted">Belum ada data pengembalian.</p>
            @endif
        </div>
    </div>

    {{-- Action --}}
    <div class="d-flex justify-content-end gap-2 mt-4">

        <a href="{{ route('peminjaman.list') }}" class="btn btn-outline-secondary">
            Kembali
        </a>
    </div>
@endsection