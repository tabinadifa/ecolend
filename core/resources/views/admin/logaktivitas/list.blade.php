@extends('layouts.layout')

@section('title', 'Log Aktivitas - Administrator')

@push('styles')
    <style>
        .page-title {
            color: #1e4d35;
        }

        .filter-card,
        .table-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 14px rgba(23, 56, 35, 0.08);
        }

        .table thead th {
            border-bottom: 2px solid #e1e7e4;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.05em;
            color: #789082;
            background-color: #f8f9fa;
        }

        .badge-action {
            font-size: 0.75rem;
            padding: 0.4rem 0.75rem;
            text-transform: uppercase;
        }

        .btn-theme {
            background-color: #f28c28;
            border-color: #f28c28;
            color: #fff;
        }

        .btn-theme:hover,
        .btn-theme:focus {
            background-color: #e07f22;
            border-color: #e07f22;
            color: #fff;
        }

        .btn-outline-theme {
            border-color: #f28c28;
            color: #f28c28;
        }

        .btn-outline-theme:hover,
        .btn-outline-theme:focus {
            background-color: #fef1e5;
            border-color: #f28c28;
            color: #d97117;
        }

        .filter-actions {
            flex-wrap: wrap;
        }

        .filter-actions .btn {
            min-width: 110px;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <p class="text-uppercase text-muted mb-1 small">Sistem</p>
            <h1 class="page-title fw-bold mb-0">Log Aktivitas</h1>
        </div>
        <div class="text-end">
            <span class="text-muted small d-block">Total {{ number_format($logs->total()) }} catatan</span>
            <a class="btn btn-sm btn-theme mt-2" href="{{ route('admin.log.export', request()->query()) }}">
                <i class="bi bi-printer me-1"></i>
                Cetak PDF
            </a>
        </div>
    </div>

    <div class="card filter-card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.log.index') }}" class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label small text-muted" for="search">Cari</label>
                    <input
                        type="text"
                        id="search"
                        name="search"
                        class="form-control"
                        placeholder="Nama user, deskripsi, aksi, model"
                        value="{{ request('search') }}"
                    />
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small text-muted" for="action">Aksi</label>
                    <select id="action" name="action" class="form-select">
                        <option value="">Semua</option>
                        <option value="create" @selected(request('action') === 'create')>Create</option>
                        <option value="update" @selected(request('action') === 'update')>Update</option>
                        <option value="delete" @selected(request('action') === 'delete')>Delete</option>
                        <option value="login" @selected(request('action') === 'login')>Login</option>
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small text-muted" for="start_date">Mulai</label>
                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        class="form-control"
                        value="{{ request('start_date') }}"
                    />
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small text-muted" for="end_date">Sampai</label>
                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        class="form-control"
                        value="{{ request('end_date') }}"
                    />
                </div>
                <div class="col-12 col-md-3 d-flex gap-2 filter-actions">
                    <button type="submit" class="btn btn-theme flex-grow-1">Terapkan</button>
                    <a href="{{ route('admin.log.index') }}" class="btn btn-outline-theme flex-grow-1">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card table-card">
        <div class="card-body p-0">
            @if ($logs->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-clock-history mb-2" style="font-size: 2rem;"></i>
                    <p class="mb-0">Belum ada aktivitas tercatat.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 15%;">User</th>
                                <th style="width: 10%;">Aksi</th>
                                <th style="width: 30%;">Deskripsi</th>
                                <th style="width: 15%;">Model / Subject</th>
                                <th style="width: 15%;">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                                <tr>
                                    <td>{{ $loop->iteration + ($logs->firstItem() - 1) }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $log->user->name ?? 'System/Guest' }}</div>
                                        @if($log->user)
                                        <div class="small text-muted">{{ $log->user->role }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match ($log->action) {
                                                'create' => 'bg-success',
                                                'update' => 'bg-warning text-dark',
                                                'delete' => 'bg-danger',
                                                'login' => 'bg-info text-dark',
                                                default => 'bg-secondary',
                                            };
                                        @endphp
                                        <span class="badge badge-action {{ $badgeClass }}">{{ $log->action }}</span>
                                    </td>
                                    <td>{{ $log->description }}</td>
                                    <td class="small font-monospace">
                                        {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                                    </td>
                                    <td>
                                        <div>{{ $log->created_at->format('d M Y') }}</div>
                                        <div class="small text-muted">{{ $log->created_at->format('H:i:s') }}</div>
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        @if ($logs->hasPages())
            <div class="card-footer bg-white d-flex flex-column flex-md-row justify-content-between align-items-center">
                <small class="text-muted mb-2 mb-md-0">
                    Menampilkan {{ $logs->firstItem() }} - {{ $logs->lastItem() }} dari {{ $logs->total() }} aktivitas
                </small>
                {{ $logs->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
