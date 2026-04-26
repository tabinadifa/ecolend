@extends('layouts.layout')

@section('title', 'Dashboard Peminjam - EcoLend')

@push('styles')
<style>
	.dashboard-wrapper { font-family: 'Poppins', sans-serif; }
	.page-header { margin-bottom: 2rem; margin-top: 0.5rem; }
	.page-header h2 { font-family: 'Poppins', serif; font-size: 1.75rem; font-weight: 700; color: #7a3f00; }
	.page-header p { color: #a06c3d; font-size: 0.875rem; }
	.stat-card { border: none; border-radius: 1.125rem; padding: 1.5rem; transition: transform 0.25s ease; animation: fadeSlideUp 0.5s ease both; }
	.stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(0,0,0,0.1); }
	.stat-card.primary { background: linear-gradient(135deg, #ff8c00 0%, #f87300 100%); color: white; }
	.stat-card.soft { background: #ffffff; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
	.stat-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; }
	.stat-icon.green { background: #FFF1DC; color: #D96A00; }
	.stat-icon.amber { background: #FEF3C7; color: #D97706; }
	.stat-icon.rose { background: #FEE2E2; color: #DC2626; }
	.stat-value { font-family: 'Poppins', serif; font-size: 2rem; font-weight: 700; line-height: 1; margin-bottom: 0.35rem; }
	.stat-label { font-size: 0.8rem; font-weight: 500; color: #a06c3d; }
	.stat-card.primary .stat-label { color: rgba(255,255,255,0.75); }
	.stat-badge { display: inline-flex; align-items: center; gap: 0.3rem; font-size: 0.75rem; padding: 0.25rem 0.6rem; border-radius: 20px; background: #FFF1DC; color: #D96A00; }
	.chart-card { background: white; border-radius: 1.125rem; box-shadow: 0 2px 12px rgba(0,0,0,0.06); padding: 1.5rem; height: 100%; animation: fadeSlideUp 0.5s ease; }
	.card-title-sm { font-weight: 600; color: #7a3f00; margin-bottom: 1rem; }
	.loan-row { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid #F3F4F6; }
	.loan-avatar { width: 40px; height: 40px; border-radius: 12px; background: #FF8C00; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; }
	.loan-title { font-size: 0.9rem; font-weight: 600; color: #7a3f00; }
	.loan-meta { font-size: 0.75rem; color: #9CA3AF; }
	.status-badge { font-size: 0.7rem; padding: 0.2rem 0.6rem; border-radius: 20px; }
	.status-active { background: #FFF1DC; color: #D96A00; }
	.status-due { background: #FEF3C7; color: #B45309; }
	.status-late { background: #FEE2E2; color: #DC2626; }
	@keyframes fadeSlideUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
	.bar-chart { display: flex; align-items: flex-end; gap: 12px; height: 180px; margin-top: 1rem; }
	.bar-item { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 5px; }
	.bar { width: 100%; background: linear-gradient(180deg, #FFB347 0%, #FF8C00 100%); border-radius: 6px 6px 0 0; transition: height 0.5s; }
	.bar-label { font-size: 0.7rem; color: #a06c3d; }
	.bar-value { font-size: 0.7rem; font-weight: 600; color: #b35c00; }
</style>
@endpush

@section('content')
<div class="dashboard-wrapper">
	<div class="page-header">
		<h2>Halo, {{ $user->name }}!</h2>
		<p>Selamat datang di EcoLend. Yuk, cek aktivitas peminjaman alat kamu.</p>
	</div>

	<div class="row g-3 mb-4">
		<div class="col-6 col-md-4">
			<div class="stat-card primary">
				<div class="stat-icon"><i class="bi bi-tools fs-4"></i></div>
				<div class="stat-value">{{ $jumlahDipinjam }}</div>
				<div class="stat-label">Alat Sedang Dipinjam</div>
				<span class="stat-badge mt-2"><i class="bi bi-clock"></i> Sedang aktif</span>
			</div>
		</div>
		<div class="col-6 col-md-4">
			<div class="stat-card soft">
				<div class="stat-icon green"><i class="bi bi-archive"></i></div>
				<div class="stat-value">{{ $totalPernahDipinjam }}</div>
				<div class="stat-label">Total Pernah Dipinjam</div>
				<span class="stat-badge"><i class="bi bi-check-circle"></i> Sejak bergabung</span>
			</div>
		</div>
		<div class="col-6 col-md-4">
			<div class="stat-card soft">
				<div class="stat-icon {{ $adaTerlambat ? 'rose' : 'amber' }}">
					<i class="bi bi-exclamation-triangle-fill"></i>
				</div>
				<div class="stat-value">{{ $adaTerlambat ? 'Ada' : 'Aman' }}</div>
				<div class="stat-label">Status Peminjaman</div>
				<span class="stat-badge {{ $adaTerlambat ? 'text-danger bg-light' : '' }}">{{ $adaTerlambat ? 'Segera kembalikan' : 'Tidak ada terlambat' }}</span>
			</div>
		</div>
	</div>

	<div class="row g-3">
		<div class="col-md-6">
			<div class="chart-card">
				<div class="d-flex justify-content-between align-items-center">
					<span class="card-title-sm">Peminjaman Aktif</span>
					@if($peminjamanAktif->count() > 0)
					<span class="status-badge status-active">{{ $peminjamanAktif->count() }} data</span>
					@endif
				</div>
				@forelse($peminjamanAktif as $pinjam)
				@php
					$dueDate = $pinjam->tanggal_kembali ? \Carbon\Carbon::parse($pinjam->tanggal_kembali) : null;
					$isLate = $dueDate?->isPast() && !$dueDate?->isToday();
					$isDue = $dueDate?->isToday();
				@endphp
				<div class="loan-row">
					<div class="loan-avatar">{{ strtoupper(substr($pinjam->alat->nama_alat ?? 'A', 0, 1)) }}</div>
					<div class="flex-grow-1">
						<div class="loan-title">{{ $pinjam->alat->nama_alat ?? 'Alat tidak tersedia' }}</div>
						<div class="loan-meta">Kembali: {{ $dueDate ? $dueDate->translatedFormat('d M Y') : '-' }} · {{ $pinjam->total_alat }} unit</div>
					</div>
					<span class="status-badge {{ $isLate ? 'status-late' : ($isDue ? 'status-due' : 'status-active') }}">
						{{ $isLate ? 'Terlambat' : ($isDue ? 'Jatuh tempo' : 'Aktif') }}
					</span>
				</div>
				@empty
				<div class="text-center text-muted py-4">Kamu sedang tidak meminjam alat apapun.</div>
				@endforelse
			</div>
		</div>

		<div class="col-md-6">
			<div class="chart-card">
				<div class="d-flex justify-content-between align-items-center">
					<span class="card-title-sm">Riwayat Peminjaman</span>
					<a href="{{ route('peminjam.riwayat.list') }}" class="text-decoration-none small text-warning">Lihat semua</a>
				</div>
				@forelse($riwayat as $riw)
				<div class="loan-row">
					<div class="loan-avatar" style="background: #6B7280;">{{ strtoupper(substr($riw->alat->nama_alat ?? 'A', 0, 1)) }}</div>
					<div class="flex-grow-1">
						<div class="loan-title">{{ $riw->alat->nama_alat ?? 'Alat' }}</div>
						<div class="loan-meta">
							@if($riw->status === 'rejected')
								Ditolak · {{ \Carbon\Carbon::parse($riw->tanggal_pinjam)->translatedFormat('d M Y') }}
							@elseif($riw->pengembalian)
								Dikembalikan {{ \Carbon\Carbon::parse($riw->pengembalian->tanggal_pengembalian)->translatedFormat('d M Y') }}
							@else
								{{ \Carbon\Carbon::parse($riw->tanggal_pinjam)->translatedFormat('d M Y') }}
							@endif
						</div>
					</div>
					<span class="status-badge {{ $riw->status === 'rejected' ? 'status-late' : 'status-active' }}">
						{{ $riw->status === 'rejected' ? 'Ditolak' : 'Selesai' }}
					</span>
				</div>
				@empty
				<div class="text-center text-muted py-4">Belum ada riwayat peminjaman.</div>
				@endforelse
			</div>
		</div>

		<div class="col-12">
			<div class="chart-card">
				<span class="card-title-sm">Tren Peminjaman (6 bulan terakhir)</span>
				<div class="bar-chart">
					@foreach($chartMonthly as $monthData)
					<div class="bar-item">
						<div class="bar-value">{{ $monthData['val'] }}</div>
						<div class="bar" style="height: {{ $monthData['val'] > 0 ? ($monthData['val'] / max(collect($chartMonthly)->max('val'), 1) * 100) : 4 }}%; min-height: 8px;"></div>
						<div class="bar-label">{{ $monthData['month'] }}</div>
					</div>
					@endforeach
				</div>
				@if(collect($chartMonthly)->sum('val') == 0)
				<div class="text-center text-muted py-3 mt-2">Belum ada aktivitas peminjaman dalam 6 bulan terakhir.</div>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection
