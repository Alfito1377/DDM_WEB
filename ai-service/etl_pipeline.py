import pandas as pd
from sqlalchemy.orm import Session
from database import SeedTransaction

def process_and_save_data(raw_data: list[dict], db: Session):
    """
    Fungsi ini menerima data mentah berupa list of dictionaries,
    membersihkannya dengan Pandas, dan menyimpannya ke MySQL.
    """
    try:
        # 1. Ubah data mentah menjadi Pandas DataFrame agar mudah diolah
        df = pd.DataFrame(raw_data)
        
        # Jika data kosong, hentikan proses
        if df.empty:
            return {"status": "info", "message": "Tidak ada data untuk diproses."}

        # 2. Proses Transformasi (Pembersihan Data)
        # a. Pastikan format tanggal benar (jika format kacau, ubah jadi NaT/Not a Time)
        df['tanggal'] = pd.to_datetime(df['tanggal'], errors='coerce')
        
        # b. Hapus baris yang tanggalnya tidak valid atau jumlah_kg nya kosong (data cacat)
        df = df.dropna(subset=['tanggal', 'jumlah_kg'])
        
        df['jenis_transaksi'] = df['jenis_transaksi'].str.lower().str.strip()
        
        df['alasan_retur'] = df['alasan_retur'].fillna("-")

        cleaned_data = df.to_dict(orient='records')
        
        db_records = []
        for item in cleaned_data:
            record = SeedTransaction(
                tanggal=item['tanggal'].date(), 
                jenis_transaksi=item['jenis_transaksi'],
                varietas_benih=item['varietas_benih'],
                jumlah_kg=float(item['jumlah_kg']),
                alasan_retur=item['alasan_retur']
            )
            db_records.append(record)
        
    
        db.add_all(db_records)
        db.commit() 
        
        return {"status": "success", "message": f"{len(db_records)} baris data berhasil disimpan ke MySQL."}
        
    except Exception as e:
        db.rollback() 
        return {"status": "error", "message": f"Gagal memproses data: {str(e)}"}