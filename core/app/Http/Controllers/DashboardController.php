<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        /** @var User $user */
        $user = Auth::user();

        if ($user->role === 'peminjam') {
            return $this->peminjamDashboard($user);
        }

        return $this->generalDashboard();
    }

    private function generalDashboard()
    {

        $today = Carbon::today();

        $totalAlat = Alat::count();
        $totalStok = (int) Alat::sum('jumlah_stok');
        $alatAddedThisMonth = Alat::where('created_at', '>=', Carbon::now()->startOfMonth())->count();

        $totalPeminjaman = Peminjaman::count();
        $borrowedCount = (int) Peminjaman::where('status', 'approve')->sum('total_alat');
        $activeLoans = Peminjaman::whereIn('status', ['pending', 'approve'])->count();
        $totalPengembalian = Pengembalian::count();

        $returnCompletionPercentage = $totalPeminjaman > 0
            ? (int) min(100, max(0, round(($totalPengembalian / $totalPeminjaman) * 100)))
            : 0;

        $borrowedPercentage = $totalStok > 0
            ? round(($borrowedCount / $totalStok) * 100)
            : 0;

        $weeklyStats = $this->getWeeklyLoanStats();
        $reminders = [
            'dueSoon' => $this->getDueSoonLoans($today),
            'overdue' => $this->getOverdueLoans($today),
        ];

        return view('dashboard', [
            'totalAlat' => $totalAlat,
            'alatAddedThisMonth' => $alatAddedThisMonth,
            'borrowedCount' => $borrowedCount,
            'borrowedPercentage' => $borrowedPercentage,
            'activeLoans' => $activeLoans,
            'totalPeminjaman' => $totalPeminjaman,
            'totalPengembalian' => $totalPengembalian,
            'returnCompletionPercentage' => $returnCompletionPercentage,
            'weeklyStats' => $weeklyStats,
            'reminders' => $reminders,
        ]);
    }

    private function peminjamDashboard(User $user)
    {
        $today = Carbon::today();

        $baseQuery = Peminjaman::query()->where('peminjam_id', $user->id);

        $jumlahDipinjam = (int) (clone $baseQuery)
            ->where('status', 'approve')
            ->whereDoesntHave('pengembalian')
            ->sum('total_alat');

        $totalPernahDipinjam = (int) (clone $baseQuery)
            ->whereIn('status', ['approve', 'returned'])
            ->sum('total_alat');

        $adaTerlambat = (clone $baseQuery)
            ->where('status', 'approve')
            ->whereDoesntHave('pengembalian')
            ->whereDate('tanggal_kembali', '<', $today->toDateString())
            ->exists();

        $peminjamanAktif = (clone $baseQuery)
            ->with('alat:id,nama_alat')
            ->where('status', 'approve')
            ->whereDoesntHave('pengembalian')
            ->orderBy('tanggal_kembali')
            ->limit(5)
            ->get();

        $riwayat = (clone $baseQuery)
            ->with([
                'alat:id,nama_alat',
                'pengembalian:id,peminjaman_id,tanggal_pengembalian',
            ])
            ->whereIn('status', ['returned', 'rejected'])
            ->latest('tanggal_pinjam')
            ->limit(5)
            ->get();

        return view('peminjam.dashboard', [
            'user' => $user,
            'jumlahDipinjam' => $jumlahDipinjam,
            'totalPernahDipinjam' => $totalPernahDipinjam,
            'adaTerlambat' => $adaTerlambat,
            'peminjamanAktif' => $peminjamanAktif,
            'riwayat' => $riwayat,
            'chartMonthly' => $this->getPeminjamMonthlyChart($user->id),
        ]);
    }

    private function getPeminjamMonthlyChart(int $peminjamId): array
    {
        $endMonth = Carbon::now()->startOfMonth();
        $startMonth = $endMonth->copy()->subMonths(5);

        $monthlyCounts = Peminjaman::selectRaw("DATE_FORMAT(tanggal_pinjam, '%Y-%m') as ym, COUNT(*) as total")
            ->where('peminjam_id', $peminjamId)
            ->whereBetween('tanggal_pinjam', [
                $startMonth->toDateString(),
                $endMonth->copy()->endOfMonth()->toDateString(),
            ])
            ->groupBy('ym')
            ->pluck('total', 'ym');

        $result = [];

        for ($date = $startMonth->copy(); $date->lte($endMonth); $date->addMonth()) {
            $key = $date->format('Y-m');

            $result[] = [
                'month' => $date->translatedFormat('M'),
                'val' => (int) ($monthlyCounts[$key] ?? 0),
            ];
        }

        return $result;
    }

    private function getWeeklyLoanStats(): Collection
    {
        $end = Carbon::today();
        $start = $end->copy()->subDays(6);

        $loanCounts = Peminjaman::selectRaw('DATE(tanggal_pinjam) as tanggal, COUNT(*) as total')
            ->whereBetween('tanggal_pinjam', [$start->toDateString(), $end->toDateString()])
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $stats = collect();

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $stats->push([
                'label' => $date->translatedFormat('D'),
                'count' => (int) ($loanCounts[$date->toDateString()] ?? 0),
            ]);
        }

        return $stats;
    }

    private function getDueSoonLoans(Carbon $today): Collection
    {
        $rangeEnd = $today->copy()->addDays(2);

        return Peminjaman::with(['alat:id,nama_alat', 'peminjam:id,name'])
            ->whereNotNull('tanggal_kembali')
            ->whereBetween('tanggal_kembali', [$today->toDateString(), $rangeEnd->toDateString()])
            ->where('status', 'approve')
            ->orderBy('tanggal_kembali')
            ->limit(3)
            ->get();
    }

    private function getOverdueLoans(Carbon $today): Collection
    {
        return Peminjaman::with(['alat:id,nama_alat', 'peminjam:id,name'])
            ->whereNotNull('tanggal_kembali')
            ->whereDate('tanggal_kembali', '<', $today->toDateString())
            ->where('status', 'approve')
            ->orderByDesc('tanggal_kembali')
            ->limit(3)
            ->get()
            ->map(function ($loan) use ($today) {
                $dueDate = $loan->tanggal_kembali ? Carbon::parse($loan->tanggal_kembali) : null;

                $loan->late_days = $dueDate
                    ? max(1, (int) ceil($dueDate->diffInHours($today) / 24))
                    : 0;

                return $loan;
            });
    }
}