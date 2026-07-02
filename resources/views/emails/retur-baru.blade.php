<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9fafb; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; border-top: 5px solid #16a34a; }
        .header { font-size: 18px; font-weight: bold; color: #1f2937; border-bottom: 1px solid #e5e7eb; padding-bottom: 10px; margin-bottom: 20px; }
        .content { font-size: 14px; color: #4b5563; line-height: 1.6; }
        .data-table { w-full; border-collapse: collapse; margin-top: 15px; margin-bottom: 20px; }
        .data-table td { padding: 8px; border-bottom: 1px solid #f3f4f6; }
        .label { font-weight: bold; color: #374151; width: 150px; }
        .btn { display: inline-block; background-color: #16a34a; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 5px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Pengajuan Retur Benih Baru</div>
        <div class="content">
            <p>Halo Manajer,</p>
            <p>Terdapat satu pengajuan retur benih baru dari mitra toko yang menunggu untuk ditinjau. Berikut adalah rincian pengajuannya:</p>
            
            <table class="data-table">
                <tr><td class="label">Kode Retur</td><td>: <strong>{{ $returData['return_code'] }}</strong></td></tr>
                <tr><td class="label">Nama Toko</td><td>: {{ $returData['store_name'] }}</td></tr>
                <tr><td class="label">Produk Benih</td><td>: {{ $returData['product_name'] }}</td></tr>
                <tr><td class="label">Jumlah</td><td>: {{ $returData['quantity'] }} Pack</td></tr>
                <tr><td class="label">Alasan Utama</td><td>: <span style="color: #dc2626; font-weight: bold;">{{ $returData['reason'] }}</span></td></tr>
                <tr><td class="label">Catatan Toko</td><td>: <em>{{ $returData['notes'] ?? '-' }}</em></td></tr>
            </table>

            <p>Mohon segera lakukan pengecekan foto bukti dan berikan keputusan (Disetujui/Ditolak) melalui panel dasbor Anda.</p>
            
            <a href="{{ url('/manajer/dashboard') }}" class="btn">Buka Panel Manajer</a>
        </div>
    </div>
</body>
</html>