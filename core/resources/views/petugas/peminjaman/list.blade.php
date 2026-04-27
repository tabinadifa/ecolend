@extends('layouts.layout')

@section('title', 'Daftar Peminjaman - EcoLend')

@push('styles')
<style>
    .status-badge {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 999px;
        padding: 0.5rem 0.8rem;
    }

    .status-cell {
        min-width: 170px;
    }

    .status-stack {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 0.65rem;
    }

    .status-actions {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 0.65rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    .btn-icon i {
        font-size: 0.92rem;
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

    @media (max-width: 767.98px) {
        .table {
            min-width: 760px;
        }

        .status-stack {
            flex-direction: column;
            align-items: flex-start;
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

    <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
        <h2 class="fw-bold mb-0">Daftar Peminjaman</h2>
    </div>

    @foreach (['success', 'error', 'info'] as $msg)
        @if (session($msg))
            <div class="alert alert-{{ $msg === 'error' ? 'danger' : $msg }} alert-dismissible fade show">
                {{ session($msg) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
            <form method="GET" class="row g-2 mb-3 align-items-center mt-2">
                <div class="col-md-2">
                    <select id="per_page" name="per_page" class="form-select" onchange="this.form.submit()">
                        @foreach ($perPageOptions as $option)
                            <option value="{{ $option }}" @selected((int) request('per_page', 10) === $option)>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 ms-auto">
                    <input
                        type="text"
                        id="search"
                        name="search"
                        class="form-control"
                        placeholder="Cari nama peminjam / alat..."
                        value="{{ request('search') }}"
                        onkeydown="if(event.key==='Enter'){this.form.submit()}">
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-light">
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
                                    <div class="status-stack">
                                        <span class="badge status-badge {{ $statusBadges[$item->status] ?? 'bg-secondary' }}">
                                            {{ $statusLabels[$item->status] ?? ucfirst($item->status) }}
                                        </span>

                                        <div class="status-actions">
                                            @if ($item->status !== 'returned')
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-eco btn-sm btn-icon"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#statusModal-{{ $item->id }}"
                                                    title="Ubah Status"
                                                    aria-label="Ubah Status">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                            @endif

                                            @if ($item->status === 'approve')
                                                <a href="{{ route('petugas.pengembalian.create', ['peminjaman_id' => $item->id]) }}"
                                                    class="btn btn-sm btn-outline-success btn-icon"
                                                    title="Proses Pengembalian"
                                                    aria-label="Proses Pengembalian">
                                                    <i class="bi bi-arrow-return-left"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="text-muted py-4">
                                        <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                        Data peminjaman tidak ditemukan
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">
                @if ($peminjaman->total())
                    Menampilkan {{ $peminjaman->firstItem() }} – {{ $peminjaman->lastItem() }} dari
                    {{ $peminjaman->total() }} data
                @else
                    Tidak ada data untuk ditampilkan
                @endif
            </small>

            {{ $peminjaman->onEachSide(1)->links('pagination::bootstrap-5') }}
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