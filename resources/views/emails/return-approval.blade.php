<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Persetujuan Retur Baru</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-w-md;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #0f172a;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
            margin: -20px -20px 20px -20px;
        }
        .header h2 { margin: 0; font-size: 20px; }
        .content { color: #334155; line-height: 1.5; }
        .content p { margin: 10px 0; }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .data-table th, .data-table td {
            padding: 12px;
            border: 1px solid #e2e8f0;
            text-align: left;
        }
        .data-table th {
            background-color: #f8fafc;
            color: #64748b;
            font-size: 13px;
            width: 40%;
        }
        .action-buttons {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            border-radius: 6px;
            margin: 0 10px;
        }
        .btn-approve { background-color: #10b981; }
        .btn-reject { background-color: #ef4444; }
        .footer {
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
            margin-top: 30px;
            border-top: 1px solid #f1f5f9;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h2>Pengajuan Retur Baru</h2>
        </div>
        <div class="content">
            <p>Halo Manajer,</p>
            <p>Terdapat pengajuan retur baru dari mitra toko yang memerlukan persetujuan Anda segera. Berikut adalah rinciannya:</p>
            
            <table class="data-table">
                <tr>
                    <th>Kode Retur</th>
                    <td><strong>{{ $retur->return_code ?? ('#RET-' . $retur->id) }}</strong></td>
                </tr>
                <tr>
                    <th>Toko Pengaju</th>
                    <td>{{ $storeName }}</td>
                </tr>
                <tr>
                    <th>Barcode Barang</th>
                    <td>{{ $retur->barcode }}</td>
                </tr>
                <tr>
                    <th>Jumlah</th>
                    <td>{{ $retur->quantity }} Pack</td>
                </tr>
                <tr>
                    <th>Alasan</th>
                    <td>{{ $retur->reason }}</td>
                </tr>
                <tr>
                    <th>Catatan Toko</th>
                    <td>{{ $retur->notes ?? '-' }}</td>
                </tr>
            </table>

            <p style="text-align: center; font-size: 14px; margin-bottom: 20px;">Silakan pilih salah satu tindakan di bawah ini. Anda akan diarahkan ke halaman web untuk melakukan konfirmasi akhir.</p>
            
            <div class="action-buttons">
                <!-- Gunakan URL Signed bawaan Laravel -->
                <a href="{{ URL::signedRoute('retur.email.approve', ['id' => $retur->id, 'manager_id' => $managerId, 'status' => 'Approved']) }}" class="btn btn-approve">Setujui Retur</a>
                <a href="{{ URL::signedRoute('retur.email.approve', ['id' => $retur->id, 'manager_id' => $managerId, 'status' => 'Rejected']) }}" class="btn btn-reject">Tolak Retur</a>
            </div>
        </div>
        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh Sistem Jual Benih App.</p>
        </div>
    </div>
</body>
</html>
