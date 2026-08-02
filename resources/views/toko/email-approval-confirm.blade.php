<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Persetujuan Retur</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); max-width: 500px; width: 100%; text-align: center; }
        .card h2 { margin-top: 0; color: #1f2937; }
        .details { margin: 20px 0; padding: 15px; background: #f9fafb; border-radius: 8px; text-align: left; }
        .details p { margin: 8px 0; color: #4b5563; }
        .details strong { color: #111827; }
        .alert-warning { background-color: #fffbeb; color: #b45309; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: left; border: 1px solid #fde68a; }
        .btn { display: inline-block; padding: 12px 24px; font-weight: bold; border-radius: 8px; color: white; text-decoration: none; cursor: pointer; border: none; font-size: 16px; width: 100%; }
        .btn-approve { background-color: #10b981; }
        .btn-approve:hover { background-color: #059669; }
        .btn-reject { background-color: #ef4444; }
        .btn-reject:hover { background-color: #dc2626; }
        .form-group { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Konfirmasi Keputusan Retur</h2>
        
        <div class="alert-warning">
            <strong>Perhatian:</strong> Anda akan memberikan keputusan 
            @if($status == 'Approved') 
                <strong style="color: #059669;">SETUJU</strong> 
            @else 
                <strong style="color: #dc2626;">TOLAK</strong> 
            @endif 
            atas retur berikut.
        </div>

        <div class="details">
            <p><strong>Kode Retur:</strong> {{ $returnOrder->return_code ?? ('#RET-'.$returnOrder->id) }}</p>
            <p><strong>Alasan:</strong> {{ $returnOrder->reason }}</p>
            <p><strong>Status Saat Ini:</strong> {{ $returnOrder->status }}</p>
        </div>

        <form action="{{ url()->full() }}" method="POST">
            @csrf
            <div class="form-group">
                @if($status == 'Approved')
                    <button type="submit" class="btn btn-approve">Konfirmasi Setujui Retur</button>
                @else
                    <button type="submit" class="btn btn-reject">Konfirmasi Tolak Retur</button>
                @endif
            </div>
        </form>
    </div>
</body>
</html>
