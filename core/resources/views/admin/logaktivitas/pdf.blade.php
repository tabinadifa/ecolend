<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Log Aktivitas | EcoLend</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 22px 20px;
        }

        body {
            font-family: "DejaVu Sans", "Segoe UI", Arial, sans-serif;
            font-size: 10.5px;
            color: #2b2b2b;
            background: #fff6ed;
        }

        .report-container {
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08), 0 2px 4px rgba(0, 0, 0, 0.02);
            overflow: hidden;
            padding: 22px 24px 24px 24px;
        }

        .top-header {
            width: 100%;
            margin-bottom: 18px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .brand-block {
            vertical-align: middle;
        }

        .logo-box {
            display: inline-block;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, #f28c28 0%, #e07f22 100%);
            box-shadow: 0 6px 12px rgba(242, 140, 40, 0.25);
            margin-right: 10px;
        }

        .brand-name {
            display: inline-block;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.3px;
            color: #d86f12;
        }

        .print-meta {
            text-align: right;
            font-size: 9px;
            color: #b85f10;
            font-weight: 600;
        }

        .print-pill {
            display: inline-block;
            background: #fff2e6;
            padding: 6px 12px;
            border-radius: 30px;
            border: 1px solid #ffd9b8;
        }

        .title-section {
            border-bottom: 2px solid #ffe0c5;
            margin-bottom: 16px;
            padding-bottom: 10px;
        }

        .title-section h1 {
            font-size: 20px;
            font-weight: 700;
            color: #3a2a1a;
        }

        .subhead {
            font-size: 10px;
            color: #9b6a3c;
            margin-top: 6px;
        }

        .summary-card {
            background: linear-gradient(105deg, #fff4ea 0%, #ffe8d6 100%);
            border-radius: 18px;
            padding: 12px 18px;
            margin-bottom: 20px;
            border: 1px solid #ffd9b8;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .period-box {
            font-weight: 600;
            color: #b85f10;
        }

        .period-label {
            display: inline-block;
            background: #ffffff;
            padding: 4px 10px;
            border-radius: 20px;
            color: #b85f10;
            font-size: 9.5px;
            font-weight: 700;
            box-shadow: inset 0 0 0 1px #ffd9b8;
            margin-right: 6px;
        }

        .total-badge {
            text-align: right;
            font-weight: 700;
            color: #ffffff;
        }

        .total-pill {
            display: inline-block;
            background: #f28c28;
            padding: 6px 14px;
            border-radius: 60px;
            font-size: 11px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .total-pill strong {
            font-size: 15px;
            margin-right: 4px;
        }

        .log-table-wrapper {
            border-radius: 16px;
            border: 1px solid #ffe0c5;
            background: #ffffff;
            margin-bottom: 16px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.8px;
        }

        thead th {
            background: #f28c28;
            color: #ffffff;
            padding: 10px 8px;
            font-weight: 700;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            border-bottom: 1px solid #e07f22;
        }

        tbody td {
            padding: 10px 8px;
            border-bottom: 1px solid #ffe9d7;
            vertical-align: top;
            color: #3a2a1a;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 40px;
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.2px;
        }

        .badge-create {
            background: #fff1e3;
            color: #a8540f;
            border-left: 2px solid #f28c28;
        }

        .badge-update {
            background: #ffead6;
            color: #9a5a1d;
            border-left: 2px solid #f2a24a;
        }

        .badge-delete {
            background: #ffe1d9;
            color: #b13b2d;
            border-left: 2px solid #e05a4f;
        }

        .badge-login {
            background: #ffeedd;
            color: #9b5a1a;
            border-left: 2px solid #f2b26f;
        }

        .badge-default {
            background: #fff4ea;
            color: #8a6a4a;
            border-left: 2px solid #f2c9a2;
        }

        .user-info {
            font-weight: 700;
            color: #8f4d16;
        }

        .user-role {
            font-size: 8px;
            color: #b3845d;
            margin-top: 3px;
            letter-spacing: 0.2px;
        }

        .text-mono {
            font-family: "DejaVu Sans Mono", "Courier New", monospace;
            font-size: 8.5px;
            background: #fff2e6;
            padding: 3px 6px;
            border-radius: 12px;
            display: inline-block;
            color: #8a5a2a;
        }

        .time-date {
            font-size: 9px;
            font-weight: 600;
        }

        .time-hour {
            font-size: 8px;
            color: #b3845d;
        }

        .footer-note {
            border-top: 1px solid #ffe0c5;
            padding-top: 12px;
            font-size: 8.5px;
            color: #b3845d;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .empty-state {
            text-align: center;
            padding: 36px 18px;
            background: #fff7ef;
            border-radius: 18px;
            color: #b3845d;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="report-container">
        <div class="top-header">
            <table class="header-table">
                <tr>
                    <td class="brand-block">
                        <span class="logo-box"></span>
                        <span class="brand-name">EcoLend</span>
                    </td>
                    <td class="print-meta">
                        <span class="print-pill">Dicetak: {{ now()->format('d M Y, H:i') }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="title-section">
            <h1>Laporan Aktivitas Pengguna</h1>
            <div class="subhead">Ringkasan log aktivitas dan audit trail sistem</div>
        </div>

        <div class="summary-card">
            <table class="summary-table">
                <tr>
                    <td class="period-box">
                        <span class="period-label">Periode</span>
                        <span>
                            {{ $startDate ?? 'Semua tanggal' }}
                            @if ($startDate || $endDate)
                                sampai {{ $endDate ?? 'Sekarang' }}
                            @endif
                        </span>
                    </td>
                    <td class="total-badge">
                        <span class="total-pill">
                            <strong>{{ $logs->count() }}</strong> total aktivitas
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        @if ($logs->isEmpty())
            <div class="empty-state">
                Tidak ada aktivitas tercatat pada periode ini.
            </div>
        @else
            <div class="log-table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 15%">Pengguna</th>
                            <th style="width: 10%">Aksi</th>
                            <th style="width: 32%">Deskripsi</th>
                            <th style="width: 18%">Target Model</th>
                            <th style="width: 20%">Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $index => $log)
                            @php
                                $badgeClass = match ($log->action) {
                                    'create' => 'badge-create',
                                    'update' => 'badge-update',
                                    'delete' => 'badge-delete',
                                    'login' => 'badge-login',
                                    default => 'badge-default',
                                };
                            @endphp
                            <tr>
                                <td style="font-weight: 600; color: #3c6b58;">
                                    {{ $index + 1 }}
                                </td>
                                <td>
                                    <div class="user-info">{{ $log->user->name ?? 'System/Guest' }}</div>
                                    @if ($log->user && isset($log->user->role))
                                        <div class="user-role">{{ ucfirst($log->user->role) }}</div>
                                    @else
                                        <div class="user-role">System</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $badgeClass }}">{{ $log->action }}</span>
                                </td>
                                <td style="line-height: 1.35;">{{ $log->description }}</td>
                                <td>
                                    <span class="text-mono">
                                        {{ class_basename($log->subject_type) }}
                                        <span style="color: #acbba9;">#{{ $log->subject_id }}</span>
                                    </span>
                                </td>
                                <td>
                                    <div class="time-date">{{ $log->created_at->format('d M Y') }}</div>
                                    <div class="time-hour">{{ $log->created_at->format('H:i:s') }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="footer-note">
            <table class="footer-table">
                <tr>
                    <td>EcoLend - Sistem Manajemen dan Log Aktivitas Terintegrasi</td>
                    <td style="text-align: right;">Laporan audit dibuat pada {{ now()->format('d/m/Y H:i') }}</td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
