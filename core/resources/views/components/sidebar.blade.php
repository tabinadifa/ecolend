@php
	$currentRoute = request()->route()?->getName();
	$role = auth()->user()->role ?? null;
	$isPeminjamanMenu = str_starts_with($currentRoute ?? '', 'peminjaman.');
	$isPengembalianMenu = str_starts_with($currentRoute ?? '', 'pengembalian.');
	$isPetugasPengembalianMenu = str_starts_with($currentRoute ?? '', 'petugas.pengembalian.');
	$isPeminjamPengembalianMenu = str_starts_with($currentRoute ?? '', 'peminjam.pengembalian.');
@endphp

<div class="sidebar p-3">
	<div class="sidebar-brand d-flex align-items-center gap-2 mb-4 px-2">
		<div class="brand-logo d-flex align-items-center justify-content-center">
			<img src="{{ asset('storage/uploads/logo/EcoLend.png') }}" alt="EcoLend">
		</div>
		<h5 class="mb-0 fw-bold flex-grow-1 brand-name">EcoLend</h5>
		<button type="button" class="btn btn-sm collapse-btn d-none d-lg-inline-flex" aria-label="Kecilkan sidebar"
			onclick="toggleSidebarCollapse()">
			<i class="bi bi-chevron-left"></i>
		</button>
		<button type="button" class="btn btn-sm close-btn d-lg-none" aria-label="Tutup menu"
			onclick="toggleSidebar(false)">
			<i class="bi bi-x-lg"></i>
		</button>
	</div>

	<p class="section-label px-2 mb-2">MENU</p>
	<nav class="nav flex-column gap-1 mb-4">
		<a class="nav-link {{ $currentRoute === 'dashboard' ? 'active' : '' }}" href="{{ route('dashboard') }}" data-nav-link>
			<span class="nav-icon"><i class="bi bi-speedometer2"></i></span>
			<span>Dashboard</span>
		</a>

		@if($role === 'admin')
			<a class="nav-link {{ $currentRoute === 'user.list' ? 'active' : '' }}" href="{{ route('user.list') }}" data-nav-link>
				<span class="nav-icon"><i class="bi bi-people"></i></span>
				<span>Kelola Pengguna</span>
			</a>
			<a class="nav-link {{ $currentRoute === 'kategori.list' ? 'active' : '' }}" href="{{ route('kategori.list') }}" data-nav-link>
				<span class="nav-icon"><i class="bi bi-tags"></i></span>
				<span>Kategori</span>
			</a>
			<a class="nav-link {{ $currentRoute === 'alat.list' ? 'active' : '' }}" href="{{ route('alat.list') }}" data-nav-link>
				<span class="nav-icon"><i class="bi bi-box-seam"></i></span>
				<span>Alat</span>
			</a>
			<a class="nav-link {{ $isPeminjamanMenu ? 'active' : '' }}" href="{{ route('peminjaman.list') }}" data-nav-link>
				<span class="nav-icon"><i class="bi bi-clipboard-check"></i></span>
				<span>Peminjaman</span>
			</a>
			<a class="nav-link {{ $isPengembalianMenu ? 'active' : '' }}" href="{{ route('pengembalian.list') }}" data-nav-link>
				<span class="nav-icon"><i class="bi bi-arrow-return-left"></i></span>
				<span>Pengembalian</span>
			</a>
			<a class="nav-link {{ $currentRoute === 'admin.log.index' ? 'active' : '' }}" href="{{ route('admin.log.index') }}" data-nav-link>
				<span class="nav-icon"><i class="bi bi-clock-history"></i></span>
				<span>Log Aktivitas</span>
			</a>
		@elseif($role === 'petugas')
			<a class="nav-link {{ $currentRoute === 'petugas.peminjaman.list' ? 'active' : '' }}" href="{{ route('petugas.peminjaman.list') }}" data-nav-link>
				<span class="nav-icon"><i class="bi bi-check-circle"></i></span>
				<span>Peminjaman</span>
			</a>
			<a class="nav-link {{ $isPetugasPengembalianMenu ? 'active' : '' }}" href="{{ route('petugas.pengembalian.list') }}" data-nav-link>
				<span class="nav-icon"><i class="bi bi-eye"></i></span>
				<span>Pengembalian</span>
			</a>
			<a class="nav-link {{ $currentRoute === 'petugas.laporan.index' ? 'active' : '' }}" href="{{ route('petugas.laporan.index') }}" data-nav-link>
				<span class="nav-icon"><i class="bi bi-printer"></i></span>
				<span>Laporan</span>
			</a>
		@elseif($role === 'peminjam')
			<a class="nav-link {{ $currentRoute === 'peminjam.peminjaman.list' ? 'active' : '' }}" href="{{ route('peminjam.peminjaman.list') }}" data-nav-link>
				<span class="nav-icon"><i class="bi bi-plus-circle"></i></span>
				<span>Ajukan Peminjaman</span>
			</a>
			<a class="nav-link {{ $isPeminjamPengembalianMenu ? 'active' : '' }}" href="{{ route('peminjam.pengembalian.list') }}" data-nav-link>
				<span class="nav-icon"><i class="bi bi-arrow-return-right"></i></span>
				<span>Pengembalian Saya</span>
			</a>
			<a class="nav-link {{ $currentRoute === 'peminjam.riwayat.list' ? 'active' : '' }}" href="{{ route('peminjam.riwayat.list') }}" data-nav-link>
				<span class="nav-icon"><i class="bi bi-clock-history"></i></span>
				<span>Riwayat Peminjaman</span>
			</a>
		@endif
	</nav>

	<p class="section-label px-2 mb-2">GENERAL</p>
	<nav class="nav flex-column gap-1">
		<a class="nav-link {{ $currentRoute === 'profile' ? 'active' : '' }}" href="{{ route('profile') }}" data-nav-link>
			<span class="nav-icon"><i class="bi bi-person-circle"></i></span>
			<span>Profil</span>
		</a>

		<form id="logoutForm" method="POST" action="{{ route('auth.logout') }}" class="d-none">
			@csrf
		</form>
		<a class="nav-link nav-link-danger" href="#" onclick="confirmLogout(event)" data-ignore-active>
			<span class="nav-icon"><i class="bi bi-box-arrow-right"></i></span>
			<span>Logout</span>
		</a>
	</nav>
</div>

<style>
	.sidebar {
		background: linear-gradient(180deg, #ff8c00 0%, #f67a00 100%);
		min-height: 100vh;
		width: 260px;
		flex-shrink: 0;
		border-right: none;
		position: relative;
		z-index: 999;
		transition: transform 0.3s ease, width 0.25s ease, padding 0.25s ease;
		display: flex;
		flex-direction: column;
		box-shadow: 8px 0 24px rgba(122, 54, 0, 0.22);
	}

	.sidebar-brand {
		min-height: 48px;
	}

	.brand-logo {
		width: 38px;
		height: 38px;
		border-radius: 10px;
		background: rgba(255, 255, 255, 0.2);
		display: flex;
		align-items: center;
		justify-content: center;
		box-shadow: 0 2px 10px rgba(122, 54, 0, 0.25);
	}

	.brand-logo img {
		width: 32px;
		height: 32px;
		object-fit: contain;
	}

	.brand-name {
		font-size: 1.05rem;
		letter-spacing: -0.3px;
		color: #ffffff;
	}

	.collapse-btn {
		border: none;
		color: rgba(255, 255, 255, 0.8);
		width: 30px;
		height: 30px;
		border-radius: 8px;
		background: rgba(255, 255, 255, 0.12);
		align-items: center;
		justify-content: center;
		padding: 0;
		transition: background-color 0.2s ease, color 0.2s ease;
	}

	.collapse-btn:hover {
		background: rgba(255, 255, 255, 0.22);
		color: #ffffff;
	}

	.close-btn {
		border: none;
		color: rgba(255, 255, 255, 0.7);
		padding: 0.25rem 0.5rem;
		background: transparent;
	}

	.close-btn:hover {
		color: #ffffff;
		background: rgba(255, 255, 255, 0.12);
	}

	body.sidebar-collapsed .sidebar {
		width: 88px;
		padding-left: 0.5rem !important;
		padding-right: 0.5rem !important;
	}

	body.sidebar-collapsed .brand-name,
	body.sidebar-collapsed .section-label,
	body.sidebar-collapsed .nav-link > span:last-child {
		display: none;
	}

	body.sidebar-collapsed .sidebar-brand {
		justify-content: center;
		gap: 0 !important;
	}

	body.sidebar-collapsed .brand-logo {
		margin-right: 0 !important;
	}

	body.sidebar-collapsed .collapse-btn {
		position: absolute;
		right: 8px;
		transform: rotate(180deg);
	}

	body.sidebar-collapsed .nav-link {
		justify-content: center;
		padding-left: 0.5rem;
		padding-right: 0.5rem;
		gap: 0;
	}

	.section-label {
		font-size: 0.68rem;
		font-weight: 700;
		letter-spacing: 0.08em;
		color: rgba(255, 244, 227, 0.65);
		text-transform: uppercase;
	}

	.nav-link {
		color: rgba(255, 252, 245, 0.92);
		padding: 0.62rem 1rem;
		border-radius: 0.7rem;
		transition: background-color 0.2s ease, color 0.2s ease;
		text-decoration: none;
		display: flex;
		align-items: center;
		gap: 0.75rem;
		font-size: 0.89rem;
		font-weight: 600;
	}

	.nav-icon {
		display: flex;
		align-items: center;
		justify-content: center;
		width: 28px;
		height: 28px;
		border-radius: 7px;
		background: transparent;
		transition: background-color 0.2s ease;
		font-size: 0.95rem;
		flex-shrink: 0;
	}

	.nav-link:hover {
		background-color: rgba(255, 255, 255, 0.17);
		color: #ffffff;
	}

	.nav-link:hover .nav-icon {
		background-color: rgba(255, 255, 255, 0.16);
		color: #ffffff;
	}

	.nav-link.active {
		background-color: #ffffff;
		color: #d96800;
		font-weight: 700;
		box-shadow: 0 8px 20px rgba(139, 62, 0, 0.2);
	}

	.nav-link.active .nav-icon {
		background-color: #fff4e7;
		color: #d96800;
	}

	.nav-link-danger {
		color: rgba(255, 232, 232, 0.85);
	}

	.nav-link-danger:hover {
		background-color: rgba(220, 38, 38, 0.23);
		color: #ffe1e1;
	}

	.nav-link-danger:hover .nav-icon {
		background-color: rgba(220, 38, 38, 0.25);
		color: #ffe1e1;
	}

	@media (max-width: 991.98px) {
		.sidebar {
			position: fixed;
			top: 0;
			left: 0;
			height: 100vh;
			transform: translateX(-100%);
			overflow-y: auto;
			box-shadow: 4px 0 24px rgba(0, 0, 0, 0.2);
		}
	}
</style>

{{-- SCRIPT --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
	const sidebar = document.querySelector('.sidebar');
	if (!sidebar) return;

	const collapseButton = document.querySelector('.collapse-btn');
	const collapseStorageKey = 'sidebar-collapsed';

	const applyCollapsedState = (collapsed) => {
		document.body.classList.toggle('sidebar-collapsed', collapsed);
		if (!collapseButton) return;

		collapseButton.setAttribute('aria-expanded', String(!collapsed));
		collapseButton.setAttribute('aria-label', collapsed ? 'Lebarkan sidebar' : 'Kecilkan sidebar');
	};

	window.toggleSidebarCollapse = function(forceState = null) {
		if (window.innerWidth < 992) return;

		const isCollapsed = document.body.classList.contains('sidebar-collapsed');
		const shouldCollapse = forceState ?? !isCollapsed;
		applyCollapsedState(shouldCollapse);
		localStorage.setItem(collapseStorageKey, shouldCollapse ? '1' : '0');
	};

	const savedCollapsedState = localStorage.getItem(collapseStorageKey) === '1';
	if (window.innerWidth >= 992) {
		applyCollapsedState(savedCollapsedState);
	}

	const navLinks = sidebar.querySelectorAll('[data-nav-link]');

	navLinks.forEach(link => {
		link.addEventListener('click', function (e) {
			if (this.hasAttribute('data-ignore-active')) return;

			navLinks.forEach(item => item.classList.remove('active'));
			this.classList.add('active');

			if (window.innerWidth < 992) {
				toggleSidebar(false);
			}

			if (this.getAttribute('href') === '#') {
				e.preventDefault();
			}
		});
	});

	window.addEventListener('resize', () => {
		const savedState = localStorage.getItem(collapseStorageKey) === '1';
		if (window.innerWidth >= 992) {
			applyCollapsedState(savedState);
		} else {
			applyCollapsedState(false);
		}
	});
});
</script>
@endpush