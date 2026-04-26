@extends('layouts.layout')

@section('title', 'Daftar Peminjaman - EcoLend')

@push('styles')
<style>
    .page-title {
        color: var(--primary-green);
    }

    .page-subtitle {
        color: #6B7280;
        letter-spacing: 0.06em;
    }

    .filter-card,
    .table-card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .filter-card .card-body,
    .table-card .card-body,
    .table-card .card-footer {
        background: #fff;
    }

    .info-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.9rem;
        color: #6B7280;
        background: #fff;
        border-radius: 999px;
        padding: 0.55rem 0.9rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .form-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #6B7280;
        letter-spacing: 0.04em;
        margin-bottom: 0.45rem;
    }

    .form-control,
    .form-select {
        border-radius: 0.85rem;
        border: 1px solid #E5E7EB;
        min-height: 44px;
        box-shadow: none;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--light-green);
        box-shadow: 0 0 0 0.18rem rgba(255, 140, 0, 0.15);
    }

    .table thead th {
        background-color: #FFF7ED;
        border-top: none;
        border-bottom: 1px solid #F3E8D8;
        color: #9A6B1F;
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        white-space: nowrap;
    }

    .table > :not(caption) > * > * {
        vertical-align: middle;
        padding: 1rem 0.9rem;
    }

    .table tbody tr:hover {
        background: #FFFDF9;
    }

    .borrower-name,
    .tool-name {
        color: #1F2937;
    }

    .borrower-email,
    .table-muted {
        color: #6B7280;
        font-size: 0.875rem;
    }

    .detail-link {
        color: var(--primary-green);
        font-weight: 600;
        text-decoration: none;
    }

    .detail-link:hover {
        color: var(--light-green);
        text-decoration: underline;
    }

    .status-badge {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 999px;
        padding: 0.55rem 0.8rem;
    }

    .status-cell {
        min-width: 220px;
    }

    .btn-status {
        border-radius: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .btn-outline-eco {
        border: 1px solid var(--primary-green);
        color: var(--primary-green);
        background: transparent;
    }

    .btn-outline-eco:hover {
        background: var(--primary-green);
        color: #fff;
        border-color: var(--primary-green);
    }

    .btn-eco {
        background: linear-gradient(135deg, var(--light-green), var(--primary-green));
        color: #fff;
        border: none;
        border-radius: 0.8rem;
        font-weight: 600;
    }

    .btn-eco:hover {
        color: #fff;
        opacity: 0.95;
    }

    .empty-state {
        padding: 3rem 1rem;
        color: #6B7280;
    }

    .modal-content {
        border: none;
        border-radius: 1rem;
        overflow: hidden;
    }

    .modal-header {
        background: #FFF7ED;
    }

    .modal-title {
        color: #7C5211;
        font-weight: 700;
    }

    .alert-info {
        background-color: #FFF7ED;
        border: 1px solid #FED7AA;
        color: #9A3412;
    }

    .pagination {
        margin-bottom: 0;
    }

    .page-item.active .page-link {
        background-color: var(--primary-green);
        border-color: var(--primary-green);
    }

    .page-link {
        color: var(--primary-green);
        border-radius: 0.5rem !important;
        margin: 0 2px;
    }

    .page-link:focus {
        box-shadow: 0 0 0 0.18rem rgba(255, 140, 0, 0.15);
    }

    .page-header-responsive {
        gap: 1rem;
    }

    @media (max-width: 991.98px) {
        .page-title {
            font-size: 1.7rem;
        }

        .table > :not(caption) > * > * {
            padding: 0.9rem 0.75rem;
        }
    }

    @media (max-width: 767.98px) {
        .page-header-responsive {
            flex-direction: column;
            align-items: flex-start !important;
        }

        .page-header-responsive .text-end {
            width: 100%;
            text-align: left !important;
        }

        .content-wrapper {
            padding: 1rem !important;
        }

        .filter-card .card-body,
        .table-card .card-footer {
            padding: 1rem;
        }

        .table {
            min-width: 760px;
        }

        .status-badge {
            display: inline-block;
            margin-bottom: 0.5rem;
        }

        .btn-status {
            display: block;
            width: 100%;
            margin-left: 0 !important;
        }
    }

    @media (max-width: 575.98px) {
        .page-title {
            font-size: 1.35rem;
        }

        .page-subtitle {
            font-size: 0.72rem;
        }

        .info-chip {
            font-size: 0.82rem;
            width: 100%;
            justify-content: center;
        }

        .modal-dialog {
            margin: 0.75rem;
        }

        .modal-footer {
            flex-direction: column;
            gap: 0.5rem;
        }

        .modal-footer .btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
    @php
        $statusLabels = [
            'pending' => 'Menunggu Persetujuan',
            'approve' => 'Disetujui',
            'rejected' => 'Ditolak',
            'returned' => 'Dikembalikan',
        ];

        $statusBadges = [
            'pending' => 'bg-warning text-dark',
            'approve' => 'bg-success',
            'rejected' => 'bg-danger',
            'returned' => 'bg-secondary',
        ];

        $perPageOptions = [5, 10, 25, 50];

        $formatDate = static function ($date) {
            return $date
                ? \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('d M Y')
                : '—';
        };
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-4 page-header-responsive">
        <div>
            <p class="page-subtitle text-uppercase mb-1 small fw-semibold">Modul Peminjaman</p>
            <h1 class="page-title fw-bold mb-0">Daftar Peminjaman</h1>
        </div>

        <div class="text-end">
            <span class="info-chip">
                <i class="bi bi-database-fill"></i>
                Total Data: <strong>{{ number_format($peminjaman->total()) }}</strong>
            </span>
        </div>
    </div>

    @foreach (['success', 'error', 'info'] as $msg)
        @if (session($msg))
            <div class="alert alert-{{ $msg === 'error' ? 'danger' : $msg }} alert-dismissible fade show border-0 shadow-sm mb-4">
                <div class="d-flex align-items-center">
                    <i class="bi bi-{{ $msg === 'success' ? 'check-circle-fill' : ($msg === 'error' ? 'exclamation-triangle-fill' : 'info-circle-fill') }} me-2"></i>
                    <div>{{ session($msg) }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    <div class="card filter-card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-12 col-sm-6 col-md-3 col-lg-2">
                    <label for="per_page" class="form-label">Per Halaman</label>
                    <select id="per_page" name="per_page" class="form-select" onchange="this.form.submit()">
                        @foreach ($perPageOptions as $option)
                            <option value="{{ $option }}" @selected((int) request('per_page', 10) === $option)>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-9 col-lg-6">
                    <label for="search" class="form-label">Kata Kunci</label>
                    <input
                        type="text"
                        id="search"
                        name="search"
                        class="form-control"
                        placeholder="Cari nama peminjam atau nama alat"
                        value="{{ request('search') }}"
                        onkeydown="if(event.key==='Enter'){this.form.submit()}">
                </div>
            </form>
        </div>
    </div>

    <div class="card table-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Data Peminjam</th>
                            <th>Nama Alat</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Tujuan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjaman as $item)
                            <tr>
                                <td>
                                    <div class="borrower-name fw-semibold">{{ $item->peminjam->name }}</div>
                                    <div class="borrower-email">{{ $item->peminjam->email }}</div>
                                    <a href="{{ route('petugas.peminjaman.show', $item) }}" class="detail-link small">
                                        Lihat detail
                                    </a>
                                </td>

                                <td class="tool-name fw-semibold">{{ $item->alat->nama_alat }}</td>
                                <td>{{ $formatDate($item->tanggal_pinjam) }}</td>
                                <td>{{ $formatDate($item->tanggal_kembali) }}</td>
                                <td>{{ ucfirst($item->tujuan) }}</td>

                                <td class="status-cell">
                                    <span class="badge status-badge {{ $statusBadges[$item->status] ?? 'bg-secondary' }}">
                                        {{ $statusLabels[$item->status] ?? ucfirst($item->status) }}
                                    </span>

                                    @if ($item->status !== 'returned')
                                        <button
                                            type="button"
                                            class="btn btn-outline-eco btn-sm ms-2 btn-status"
                                            data-bs-toggle="modal"
                                            data-bs-target="#statusModal-{{ $item->id }}">
                                            Ubah Status
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                        Data peminjaman tidak ditemukan
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div class="small text-muted mb-0">
                @if ($peminjaman->total())
                    Menampilkan {{ $peminjaman->firstItem() }} - {{ $peminjaman->lastItem() }} dari
                    {{ $peminjaman->total() }} data
                @else
                    Tidak ada data untuk ditampilkan
                @endif
            </div>

            <div>
                {{ $peminjaman->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    @foreach ($peminjaman as $item)
        @php
            $isModalReopened = old('peminjaman_id') && (int) old('peminjaman_id') === $item->id;
            $selectedStatus = $isModalReopened ? old('status') : $item->status;
            $reasonValue = $isModalReopened ? old('alasan_ditolak') : $item->alasan_ditolak;
            $shouldShowReason = $selectedStatus === 'rejected';
        @endphp

        @continue($item->status === 'returned')

        <div class="modal fade" id="statusModal-{{ $item->id }}" tabindex="-1"
            aria-labelledby="statusModalLabel-{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('petugas.peminjaman.update-status', $item) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="peminjaman_id" value="{{ $item->id }}">

                        <div class="modal-header border-0">
                            <h5 class="modal-title" id="statusModalLabel-{{ $item->id }}">
                                Ubah Status Peminjaman
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="statusSelect-{{ $item->id }}" class="form-label">Status Peminjaman</label>
                                <select id="statusSelect-{{ $item->id }}" name="status" class="form-select"
                                    data-reason-toggle="reasonField-{{ $item->id }}">
                                    @foreach ($allowedStatuses as $status)
                                        <option value="{{ $status }}" @selected($selectedStatus === $status)>
                                            {{ $statusLabels[$status] ?? ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 {{ $shouldShowReason ? '' : 'd-none' }}" id="reasonField-{{ $item->id }}">
                                <label for="reasonTextarea-{{ $item->id }}" class="form-label">Alasan Penolakan</label>
                                <textarea
                                    class="form-control"
                                    name="alasan_ditolak"
                                    id="reasonTextarea-{{ $item->id }}"
                                    rows="3"
                                    maxlength="255"
                                    placeholder="Tuliskan alasan penolakan secara singkat">{{ $reasonValue }}</textarea>
                                <div class="form-text">Wajib diisi ketika memilih status ditolak (maks. 255 karakter).</div>
                            </div>

                            <div class="alert alert-info small mb-0">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                Menyetujui peminjaman akan langsung mengurangi stok alat. Menolak peminjaman akan mengembalikan stok yang telah dialokasikan.
                            </div>
                        </div>

                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-eco">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleReasonField = (select) => {
            const targetId = select.getAttribute('data-reason-toggle');
            if (!targetId) return;

            const wrapper = document.getElementById(targetId);
            if (!wrapper) return;

            const textarea = wrapper.querySelector('textarea');
            const shouldShow = select.value === 'rejected';

            wrapper.classList.toggle('d-none', !shouldShow);

            if (textarea) {
                textarea.required = shouldShow;
            }
        };

        document.querySelectorAll('[data-reason-toggle]').forEach((select) => {
            const modal = select.closest('.modal');

            select.addEventListener('change', () => toggleReasonField(select));

            if (modal) {
                modal.addEventListener('shown.bs.modal', () => toggleReasonField(select));
            }

            toggleReasonField(select);
        });

        const failedModalId = @json(old('peminjaman_id'));
        if (failedModalId) {
            const modalEl = document.getElementById(`statusModal-${failedModalId}`);
            if (modalEl) {
                const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
                modalInstance.show();
            }
        }
    });
</script>
@endpush